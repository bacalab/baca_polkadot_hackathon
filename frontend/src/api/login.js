import request from '@/utils/request.js'
const host="http://ec2-54-178-107-66.ap-northeast-1.compute.amazonaws.com"
 

//  登录接口  
export function userLogin(params) { 
    var address = params.address 
    var name = params.name 
    var res = request({  
        url: host + "/api/login?address=" + address  + "&name=" + name,
        method: "get"
    }) 
    return res
}