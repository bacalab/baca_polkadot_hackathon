import { ApiPromise, WsProvider } from '@polkadot/api';
import { web3Accounts, web3Enable, web3FromSource, web3FromAddress } from '@polkadot/extension-dapp';

const awsChainAddr = 'ws://ec2-35-77-14-64.ap-northeast-1.compute.amazonaws.com:9944'
const localChainAddr = 'ws://127.0.0.1:9944'
const awsBackend = 'http://ec2-35-77-14-64.ap-northeast-1.compute.amazonaws.com:3000'
const localBackend = 'http://127.0.0.1:3000'
const adminAddr = '5ERa1SRcxziVbBWbxRVts6nzzrR4yCULpQugDhXQuQjreWFC'


const actingAddress = awsChainAddr 
const test_addr = "5F9PQ7K3A2dP6ENfXFv7xuq7r3YGi4EbxkvNBWmstnGcabJb"

 
 
async function setup() {   
        const wsProvider = new WsProvider(actingAddress); 
        const api = await ApiPromise.create({ provider: wsProvider });
        const extensions = await web3Enable('BACA');
        if (extensions.length === 0) {
            return null;
        }
        const acc = await web3Accounts()
        //const acc = [{"address":1,"meta":{"name":1}},{"address":2,"meta":{"name":2}}]  
        return acc 

} 


export default setup=setup; 


