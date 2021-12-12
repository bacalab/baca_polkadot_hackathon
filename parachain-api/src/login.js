import React, { useState, useEffect } from 'react';
import { ApiPromise, WsProvider } from '@polkadot/api';
import { web3Accounts, web3Enable, web3FromSource, web3FromAddress } from '@polkadot/extension-dapp';


const unit = 1000000000000;

function Login() {

    const [api, setApi] = useState(null);
    const [accounts, setAccounts] = useState([]);
    const [actingAddress, setActingAddress] = useState(null);
    const [blockchainUrl, setBlockchainUrl] = useState('ws://ec2-35-77-14-64.ap-northeast-1.compute.amazonaws.com:9944');
    const [backendAPI, setBackendAPI] = useState('http://ec2-35-77-14-64.ap-northeast-1.compute.amazonaws.com:3000')
    const [ballance, setBallance] = useState(0);
    const [stakeAmmount, setStakeAmmount] = useState(0);
    const [status, setStatus] = useState(null);

    const awsChainAddr = 'ws://ec2-35-77-14-64.ap-northeast-1.compute.amazonaws.com:9944';
    const localChainAddr = 'ws://127.0.0.1:9944';
    const awsBackend = 'http://ec2-35-77-14-64.ap-northeast-1.compute.amazonaws.com:3000'
    const localBackend = 'http://127.0.0.1:3000'
    const adminAddr = '5ERa1SRcxziVbBWbxRVts6nzzrR4yCULpQugDhXQuQjreWFC'

    useEffect(() => {
        setup();
    }, []);

    function useAwsChain() {
        setBlockchainUrl(awsChainAddr);
        setup().then(() => { });
    }

    function useLocalChain() {
        setBlockchainUrl(localChainAddr);
        setup().then(() => { });
    }

    function useAwsBackend() {
        setBackendAPI(awsBackend)
    }

    function useLocalBackend() {
        console.log("here")
        setBackendAPI(localBackend)
    }

    async function setup() {
        const wsProvider = new WsProvider(blockchainUrl);
        const api = await ApiPromise.create({ provider: wsProvider });
        // await api.rpc.chain.subscribeNewHeads((lastHeader) => {
        //     setBlock(`${lastHeader.number}`);
        //     setLastBlockHash(`${lastHeader.hash}`);
        // });
        setApi(api);
        await extensionSetup();
    }

    const extensionSetup = async () => {
        const extensions = await web3Enable('BACA');
        if (extensions.length === 0) {
            return;
        }
        const acc = await web3Accounts()
        setAccounts(acc);
        if (actingAddress == null && acc.length > 0) {
            setActingAddress(acc[0].address)
        }
    };

    const checkAmmount = async () => {
        const backendapi = backendAPI
        const url = `${backendapi}/api/ballance?addr=${actingAddress}`
        fetch(url)
            .then(response => response.json())
            .then(data => setBallance(data.ballance));
    }

    const getReward = async () => {
        let rb = JSON.stringify({ "addr": actingAddress })
        let backendapi = backendAPI
        const url = `${backendapi}/api/get_reward`
        console.log(url)
        fetch(url, {
            body: rb,
            headers: {
                'Content-Type': 'application/json',
            },
            method: 'POST',
        })
            .then((response) => response.json())
            .then(a => console.log(a))
    }

    const stakeResultHandler = ({ status }) =>
        status.isFinalized
            ? setStatus(`Finalized. Block hash: ${status.asFinalized.toString()}`)
            : setStatus(`Current transaction status: ${status.type}`);

    const errHandler = err =>
        setStatus(`Transaction Failed: ${err.toString()}`);

    const handleStake = async () => {
        const SENDER = actingAddress;

        // finds an injector for an address
        const injector = await web3FromAddress(SENDER);

        api.tx.balances
            .transfer(adminAddr, unit * stakeAmmount)
            .signAndSend(SENDER, { signer: injector.signer }, stakeResultHandler).catch(errHandler);
    }

    return (
        <div>
            <div>
                chain: {blockchainUrl}
            </div>
            <div>
                backendAPI: {backendAPI}
            </div>
            <select onChange={(event) => {
                setActingAddress(event.target.value)
            }}>
                {accounts.map(a => <option value={a.address}>{a.address} [{a.meta.name}]</option>)}
            </select>
            <div>
                Your Address: {actingAddress}
            </div>
            <div>
                <button onClick={useAwsChain}>Use AWS Chain</button>
            </div>
            <div>
                <button onClick={useLocalChain}>Use Local Chain</button>
            </div>
            <div>
                <button onClick={useAwsBackend}>Use AWS Backend</button>
            </div>
            <div>
                <button onClick={useLocalBackend}>Use Local Backend</button>
            </div>
            <div>
                <button onClick={checkAmmount}>Update Ballance</button>
            </div>
            <div>
                Your Ballance: {ballance}
            </div>
            <div>
                <button onClick={getReward}>Get Reward(1 unit)</button>
            </div>
            <div>
                <input onChange={(event) => setStakeAmmount(event.target.value)} />
                <button onClick={handleStake}> Stake </button>
            </div>
            <div>
                {status}
            </div>
        </div>
    )
}

export default Login;