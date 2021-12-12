<template>
    <div class="myheadercontain">
        <img :src="require('./imgs/headimg.png')" border=2 class="myheadimg" />
        <div class="myname" style="font-size:2em;margin:0.5em;font-weight:800"> {{userObj.name}} </div>
        <div class="bitinfo">
            <div class="parent_nav" style="color:#ffbb00; font-size:2em;font-weight:600">
                <span class="sub_nav">BCT$</span><span class="sub_nav">{{userObj.total}}</span></div>
            <div class="parent_nav"><span class="sub_nav">Staked</span><span class="sub_nav">{{userObj.stake}}</span></div>
            <div class="parent_nav"><span class="sub_nav">Available</span><span class="sub_nav">{{userObj.money}}</span></div>
        </div>
    </div>
</template>
<script>
import { userInfo, stakeList } from "@/api/mine.js";
import { setToken, getToken } from "@/utils/token.js";
export default {
    name: 'MyHeader',

    async created() {
        // 页面一打开就去列表。 
      if (this.$checkLogin()) { 
          let ba = getToken("bacaToken")
          console.log("bacaToken",ba)
          var mywallet = getToken("bacaWallet")
          console.log("bacaWallet",mywallet)
          let userObjTmp = await userInfo();
          this.userObj = userObjTmp.data.data;
          console.log("***********", this.userObj)
        }else { this.$router.push("/login"); } 

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

.sub_nav {
    flex: auto;
    display: flex;
    align-items: center;
    justify-content: center;
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
</style>