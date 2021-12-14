<template>
    <div class="login">
         <div class="selec_addr">
            <h5>{{lodinginfo}}</h5>
        </div>
        <select v-model="selected" class="selectbox">
            <option disabled value="">please select one account</option>
            <option v-for="item in accList" v-bind:value="item.address" class="selectoption">
                {{item.meta.name}}
            </option>
        </select>
        <div class="selec_addr">
            <h6>your address {{selected}}</h6>
        </div>
        <div>
            <el-button type="primary" @click="saveAcc" class="savebutton">Login In</el-button>
        </div>
    </div>
</template>
<script>
import { userLogin } from "@/api/login.js";
import { setToken, getToken } from "@/utils/token.js";
import setup from '@/utils/polka.js'
import { Notification } from 'element-ui';
import { Loading } from 'element-ui';
export default {
    name: "login",
    data() {
        return {
            isLoading: false,
            accList: [],
            selected: '',
            loading: true,
            lodinginfo: "Loading......"
        }
    },
    async created() {
        try {
            const h = this.$createElement; 
            this.accList = await setup()
            if (this.accList == null || this.accList.length < 1) { 
                var mes = "account list is null"
                var options = {
                    title: 'Aha ~',
                    message: h('i', { style: 'color: teal;font-weight:700' }, mes)
                }
                Notification(options)
            } 
            this.lodinginfo = "Loading account success."
        } catch (error) {
            console.log("connect wallet fail", error)
            this.lodinginfo = "Loading account fail."
        } finally {
            console.log("finally")
            this.isLoading = false
        }
    },

    methods: {
        async saveAcc() { 
            const h = this.$createElement; 

            let obj = this.accList.find((item) => { return item.address === this.selected });
            if (obj) {
                setToken("username", obj.meta.name)
                setToken("bacaWallet", obj.address)
            } else {
                var mes = "please select one account"
                var options = {
                    title: 'Aha ~',
                    message: h('i', { style: 'color: teal;font-weight:700' }, mes)
                }
                Notification(options)
                return
            } 

            let res = await userLogin({ "address": obj.address,"name":obj.meta.name })
            if (res.data && res.data.data.token) {
                setToken("bacaToken", res.data.data.token)
                var mes = "Login in seccuss"
                var options = {
                    title: 'Aha ~',
                    message: h('i', { style: 'color: teal;font-weight:700' }, mes)

                }
                Notification(options) 
                this.$router.back()
            }


        }
    }
}
</script>
<style scoped>
.login {
    width: 50%;
    padding: 3em;
    background-color: #e9e9e9;
    margin:  auto;
    margin-top:2em;
    text-align: center; 
    box-shadow: #d5d4d4 1px 1px 2px 2px;
}

.selectbox {
    text-align: center;

    border: 1px solid #d5d4d4;
    -moz-border-radius: 6px;
    -webkit-border-radius: 6px;
    border-radius: 6px;
    -webkit-appearance: none;
    -moz-appearance: none;
    appearance: none;
    color: RGBA(102, 102, 102, 0.7);
    cursor: pointer;
    outline: none;
    font-weight: 800;
    height: 2.5em;
    width: 80%
}

.selectoption {
    text-align: center;
}

.selec_addr h6 {
    margin: 1em;
    color: RGBA(102, 102, 102, 0.7);
}
 


::v-deep {
    .el-input__inner {
        background: white !important;
        border-color: #DBDBDB;
        color: #767676;
    }
}
</style>