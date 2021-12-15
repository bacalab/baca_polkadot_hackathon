<template>
    <div>
        <div class="maincontext">
            <div class="personbox" v-for="item in stakeObjList">
                <div class="sub_box vote_box"><img src="./imgs/vote.png" style="width: 3em;"></div>
                <div class="sub_box title_box">
                    <span style="padding:0 2em;">{{item.article.title}}</span></div>
                <div class="sub_box stake_box">
                    <span v-if>staked:{{item.stake}}</span>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
import MyHeader from './myheader'
import { userInfo, stakeList } from "@/api/mine.js";
import { ApiPromise, WsProvider } from '@polkadot/api';

export default {
    name: 'Myvote',
    components: {
        MyHeader
    },
    async created() {
        console.log("my vote page")
        // 页面一打开就去列表。
        if (this.$checkLogin()) {
            let stakelistTmp = await stakeList();
            console.log(stakelistTmp)
            this.stakeObjList = stakelistTmp.data.data;
        } else { this.$router.push("/login"); }
        console.log("stakelistTmp", this.stakelistTmp)
    },
    data() {
        return {
            stakeObjList: []
        }
    }
}
</script>
<style scoped>
.personbox:nth-child(even) {
    background-color: lightgray;
}


.personbox {
    background-color: #e9e9e9;
    display: flex;
    font-size: 0.2em;
    padding: 3em;
    width: 80%;
    margin: 0 auto;
}

.sub_box {
    flex: auto;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 3em;
}

.vote_box {
    width: 10%;
}

.title_box {
    text-align: left;
    width: 60%;
    font-weight: 500;
    font-size: 1rem;
    color: rgba(136, 136, 136, 1);
    justify-content: left;
}

.stake_box {
    width: 30%;
    font-size: 1rem;

}

@media only screen and (max-width: 479px) {
    .personbox {
        width: 100%;
    }

    .title_box {
        width: 75%;
    }

    .stake_box {
        width: 15%;
        font-size: 1em;

    }

}
</style>