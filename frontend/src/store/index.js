import Vue from 'vue'
import Vuex from 'vuex'
import { getToken } from '@/utils/token.js'
Vue.use(Vuex)
export default new Vuex.Store({
  state: { 
    wallet:getToken("walletToken") && getToken("walletToken").token,
    token: getToken("bacaToken") && getToken("bacaToken").token,  
    refresh_token: getToken("bacaToken") && getToken("bacaToken").refresh_token
  },
  mutations: {
    changeWallet(state, wallet) {
      state.wallet = wallet;
    },
    changeToken(state, token) {
      state.token = token;
    },
    changeRefToken(state, token) {
      state.refresh_token = token;
    }
  },
  actions: {
  },
  modules: {
  }
})
