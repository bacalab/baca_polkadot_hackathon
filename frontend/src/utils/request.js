import axios from 'axios' 
import { getToken } from '@/utils/token.js'
import JSONBig from 'json-bigint'
let request = axios.create({
    baseURL: process.env.VUE_APP_URL,
    timeout: 60000,
    transformResponse: [function (data) {
        try {
            return JSONBig.parse(data);
        } catch(err){
            return data;
        }

    }],
})

// 1，添加请求拦截器
// 请求拦截里面携带token
request.interceptors.request.use(function (config) { 
    var mytoken = getToken("bacaToken");    
    //config.headers['accessToken'] = mytoken
    //config.headers['accessToken'] = mytoken
    //config.headers['token'] = mytoken
    //config.headers['AccessToken'] = mytoken
    //config.headers['Token'] = mytoken
    config.headers.Authorization = mytoken && 'Bearer ' + mytoken;//这句代码就是，每次首页里面的请求在发送后都会被拦截下来，自动加上一个token值。
    //console.log("token", config)
    return config;
}, function (error) {
    //(2) 对请求错误做些什么
    return Promise.reject(error);
});
 
export default request;