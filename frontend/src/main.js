// The Vue build version to load with the `import` command
// (runtime-only or standalone) has been set in webpack.base.conf with an alias.
import Vue from 'vue'
import App from './App'
import router from './router'
import store from './store'

import "@/assets/css/vendor.css";
import "@/assets/css/magnific-popup.css";
import "@/assets/css/style.css";
import "@/assets/css/responsive.css"; 


import { Button, Dialog,Notification,Loading } from 'element-ui';
import 'element-ui/lib/theme-chalk/index.css';
Vue.use(Button)
Vue.use(Dialog)
//Vue.use(Notification)
Vue.use(Loading)

// 导入判断用户是否登录插件
import myPlugin from '@/utils/myPlugin.js'
Vue.use(myPlugin)
 
Vue.config.productionTip = false

 
 

new Vue({ 
    el: '#app',
    router,
   store,
   components: { App },
  render: h => h(App),
}).$mount('#app')
