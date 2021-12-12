# BACA for polkadot hackathon
This is the aggregated repo of three parts in BACA:
- parachain setup and chain related api, as in "parachain-api" directory
- backend service for the website, as in "backend" directory
- Assets and scripts to power the frontend, as in "frontend" directory.

For general information of BACA, please refer to our homepage: http://bacamedium.com and [whitepaper](https://whitepaper.bacamedium.com/).

BACA is in active development, feel free to follow us on twitter: https://twitter.com/BacaWeb3


The features we have implemented so far during this hackathon:

- [Canvas](https://github.com/paritytech/canvas) based parachain deployment in aws
- Token reward distribution API
- User regsitration and login
- Content browsing with sections and ranking
- Badges center
- "Staking to Vote" demo

Features we are acitvely working on:

- Creator access control and invitation program
- Decentralized "Staking to Vote", in smart contracts or pallet to make it transparent
- Decentralized voting reward distribution in smart contracts or pallet

## Parachain setup and chain related API

We deployed a [Canvas](https://github.com/paritytech/canvas) based parachain as our development chain on: ws://ec2-35-77-14-64.ap-northeast-1.compute.amazonaws.com:9944

An admin account is used to gather and distribute reward tokens. A node backend is deployed on ec2-35-77-14-64.ap-northeast-1.compute.amazonaws.com:3000 with the following API.

### To install and run it on yourself

```
yarn install 

node server/app.js # start the server for testing

yarn install pm2 -g # install pm2

pm2 start server/app.js # start the server with PM2
```
You will also need to add the admin secret key in the environment parameter before starting the PM2 daemon.


### Check Ballance
This endpoint will retur the current ballance of a specific address

endpoint: /api/ballance

type: GET

input paramter: addr

response: JSON {'ballance': <ballance value>} or {'msg': "addr is empty"}
  
example: 

```
        const url = `${backendapi}/api/ballance?addr=${actingAddress}`
        fetch(url)
            .then(response => response.json())
  ```
  

### Get Reward

This endpoint will give 1 unit of ballance to a specific address from the admin address.
  
endpoint: /api/get_reward
  
type: POST
  
input parameter: addr (in request body)
  
response: JSON {'hash': hash, 'msg': e }
  
example: 
 ```
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
 ```

A simple react frontend is also included in the repo to show basic functionalities.