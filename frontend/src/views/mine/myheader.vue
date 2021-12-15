<template>
    <div class="myheadercontain">
        <img :src="require('./imgs/headimg.png')" border=2 class="myheadimg" />
        <div class="myname" > {{userObj.name}} </div>
        <div class="bitinfo">
            <div class="parent_nav" style="color:#ffbb00; font-size:2em;font-weight:600">
                <span class="sub_nav_left">BCT$</span><span class="sub_nav_right">{{userObj.total.toFixed(4)}}</span></div>
            <div class="parent_nav"><span class="sub_nav_left">Staked</span><span class="sub_nav_right">{{userObj.stake}}</span></div>
            <div class="parent_nav"><span class="sub_nav_left">Available</span><span class="sub_nav_right">{{userObj.money.toFixed(4)}}</span></div>
        </div>
    </div>
</template>
<script>
import { userInfo, stakeList } from "@/api/mine.js";
import { setToken, getToken } from "@/utils/token.js";
export default {
    name: 'MyHeader', 
    async created() {
        console.log(" my MyHeader page")  
        let ba = getToken("bacaToken")
        console.log("bacaToken", ba)
        var mywallet = getToken("bacaWallet")
        console.log("bacaWallet", mywallet)
        let userObjTmp = await userInfo();
        this.userObj = userObjTmp.data.data;


    },
    data() {
        return {
            userObj: {},
            cur: 0 //默认选中第一个tab
        }
    }
}
</script>
<style scoped>
.bitinfo {
    width: 50%;
    margin: 0 auto;
}

.parent_nav {
    display: flex;

}

.sub_nav_left{
    flex: auto;
    display: flex;
    align-items: center;
    justify-content: left;
    font-weight: 600;
}
.sub_nav_right{
    flex: auto;
    display: flex;
    align-items: center;
    justify-content: right;
    font-weight: 600;
}  
.myheadimg {
    width: 7em;
    height: 7em;
    border-radius: 50%;
    background-color: #bebebe;
    padding: 0.5em;
    border: none;
}
.myname {
    font-size:2em;margin:0.5em;font-weight:800
}  


@media only screen and (max-width: 479px) {
       .bitinfo{width: 80%;}
}
</style>