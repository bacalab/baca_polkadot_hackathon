import { ApiPromise, WsProvider } from '@polkadot/api';
import { Keyring } from '@polkadot/keyring';
import { cryptoWaitReady, mnemonicGenerate } from '@polkadot/util-crypto';

import express from 'express';
import cors from 'cors';
import bodyParser from 'body-parser';

const app = express()
const port = 3000

app.use('/', express.static('./dist', {
    index: "index.html"
}))

app.use(bodyParser.urlencoded({ extended: true }));
app.use(bodyParser.json());

// TODO remove after testing
const corsOptions = {
    origin: '*',
    credentials: true,            //access-control-allow-credentials:true
    optionSuccessStatus: 200,
}

app.use(cors(corsOptions)) // Use this after the variable declaration

const provider = new WsProvider('ws://ec2-35-77-14-64.ap-northeast-1.compute.amazonaws.com:9944');
// const api = await ApiPromise.create({ provider });

const unit = 1000000000000

async function getBallance(addr) {
    const api = await ApiPromise.create({ provider });
    const { hash, parentHash } = await api.rpc.chain.getHeader();
    console.log(`last header hash ${hash.toHex()}`);
    const account = await api.query.system.account.at(parentHash, addr);
    const rawBallance = account.data.free
    let ballance = rawBallance / unit
    console.log(`ballance: ${ballance}`)
    return ballance
}

async function _transfer(api, sender, to, ammount) {
    // Sign and send the transaction using our account
    const transfer = api.tx.balances.transfer(to, ammount);
    try {
        const hash = await transfer.signAndSend(sender);
        return hash.toHex(), null;
    } catch (error) {
        return '', error.toString();
    }
}

async function handleGetReward(toAddr) {
    const api = await ApiPromise.create({ provider });
    const key = process.env.BACA_KEY;
    // Constuct the keyring after the API (crypto has an async init)
    const keyring = new Keyring({ ss58Format: 42, type: 'sr25519' });
    const sender = keyring.addFromUri(key);
    return _transfer(api, sender, toAddr, unit)
}

app.post('/api/get_reward', (req, res) => {
    const addr = req.body['addr']
    if (addr) {
        handleGetReward(addr)
            .then((hash, e) => res.json({ 'hash': hash, 'msg': e }))
            .catch(e => res.json({ 'msg': e.toString() }))
    } else {
        res.json({ 'msg': "addr is empty" })
    }
});

app.use('/api/ballance', (req, res) => {
    const addr = req.query['addr']

    if (addr.length > 0) {
        getBallance(addr).then(b => res.json({ 'ballance': b }))

    } else {
        res.json({ 'msg': "addr is empty" })
    }
})
await cryptoWaitReady();
app.listen(port, () => console.log(`Example app listening on port ${port}!`))