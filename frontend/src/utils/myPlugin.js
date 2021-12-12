
import store from '@/store/index.js'
import router from '@/router/index.js'
import { setToken,getToken} from "@/utils/token.js";
let myPlugin = {
    checkLogin() { 
        if (getToken("bacaToken")) {
            // 用户已登录
            return true;
        } else {
            // 用户未登录
            console.log("请先登录");  
            return false;
        }

    },
    checkWallet() { 
    	if (getToken("bacaWallet")) {  
            return true;
        } else { 
          console.log("请先注册钱包") 
          return false;
        }
    },
    // 一般插件内的对象要提供一个install方法
    install(Vue) {
        Vue.prototype.$checkLogin = myPlugin.checkLogin;
        Vue.prototype.$checkWallet = myPlugin.checkWallet;
    }
}
export default myPlugin;
