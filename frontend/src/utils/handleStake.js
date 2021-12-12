import { ApiPromise, WsProvider } from '@polkadot/api';
import { web3Accounts, web3Enable, web3FromSource, web3FromAddress } from '@polkadot/extension-dapp';

const awsChainAddr = 'ws://ec2-35-77-14-64.ap-northeast-1.compute.amazonaws.com:9944'
const localChainAddr = 'ws://127.0.0.1:9944'
const awsBackend = 'http://ec2-35-77-14-64.ap-northeast-1.compute.amazonaws.com:3000'
const localBackend = 'http://127.0.0.1:3000'
const adminAddr = '5ERa1SRcxziVbBWbxRVts6nzzrR4yCULpQugDhXQuQjreWFC'


const actingAddress = awsChainAddr
const unit = 100000000000000;
const test_addr = "5HCEAUZFvKys98dYaoNDLRWDzqh8WKogzcJ3WES7oGxy7nfT" 

 

function stakeResultHandler(status){
    console.log(status)
    return ""
}

//transfer
async function handleStake(params) {
    let bal = params.wallet_id
    
    let ammount =  params.stake_ammount  //1
    const SENDER = bal; 

    // finds an injector for an address
    const wsProvider = new WsProvider(actingAddress); 
    const api = await ApiPromise.create({ provider: wsProvider });
    
    const extensions = await web3Enable('BACA'); 
    const injector = await web3FromAddress(SENDER); 

     
    // block 
    const txHash = await api.tx.balances
      .transfer(adminAddr,  ammount)
      .signAndSend(SENDER,{ signer: injector.signer });

    // Show the hash
    console.log(`Submitted with hash ${txHash}`); 

     /*
     // unblock 
    api.tx.balances
      .transfer(adminAddr,  ammount)
      .signAndSend(SENDER,{ signer: injector.signer }); 

    // setUnsub(() => unsub);*/
} 

function getReward() {  
    let rb = JSON.stringify({ "addr": test_addr })
    let backendapi = awsBackend
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

//getReward()

 
 
export default handleStake=handleStake;


