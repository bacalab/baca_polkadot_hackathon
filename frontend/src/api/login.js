import request from '@/utils/request.js'
const host="http://ec2-54-178-107-66.ap-northeast-1.compute.amazonaws.com"
 

//  登录接口  
export function userLogin(params) { 
    var user_id = params.wallet_id
    var res = request({  
        url: host + "/api/index/" + user_id ,  
        method: "get"
    }) 
    return res
}