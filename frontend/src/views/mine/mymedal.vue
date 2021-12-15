<template>
    <div>
        <div class="row personbox">
            <div class="col-xs-6 col-xm-6 col-lg-3 col-md-6" v-for="item in medalObjList">
                <div style="padding:10em;"> <img :src="require(`./imgs/medal${item.name}.png`)" alt="img">
                    <h5>{{item.note}}</h5>
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
        // 页面一打开就去列表。
        if (this.$checkLogin()) {
            let stakelistTmp = await stakeList();
            this.stakeObjList = stakelistTmp.data.data; 
        } else { this.$router.push("/login"); }
    },
    data() {
        return {
            stakeObjList: [],
            medalObjList: [{ "name": "1", "note": "Share Income +21.5%" }, { "name": "2", "note": "Read Income +15.8%" }, { "name": "3", "note": "Time Income +3.4%" }]
        }
    }
}
</script>
<style scoped>
.maincontext {
    margin-top: 50px; 
}

.personbox { 
    background-color: #e9e9e9;
    display: flex;
    font-size: 0.2em;
    padding: 3em;
    width: 80%;
    margin: 0 auto;

}



@media only screen and (max-width: 479px) {
    .personbox {
        width: 100%;
    }

    .sub_box {
        font-size: 0.2em;
    }

    .title_box {
        width: 5px;
    }
}
</style>