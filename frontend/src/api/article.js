import request from '@/utils/request.js'
const host="http://ec2-54-178-107-66.ap-northeast-1.compute.amazonaws.com/"


// 1，获取频道列表
export function cateList() {
    return request({
        url: host + "/api/article/category",
        method: "get" 
    })
}


//
//获取文章列表
export function articleTopVoteList(params) {  
    let page = 1
    if (params.hasOwnProperty("page") &&   (params.page != null)){
        page = params.page
    }  
    var parmstmp = "page=" + page 

    if (params.hasOwnProperty("cid") && (params.cid !== null) && (params.cid !== undefined))
        parmstmp = parmstmp +'&cid=' +  params.cid 
  
    
    return request({
        url: host + "/api/article/topVote?" + parmstmp, 
        method: "get" 
    })
}

//获取文章列表
export function articleList(params) { 
    console.log(params)
    let page = 1
    if (params.hasOwnProperty("page") &&   (params.page != null)){
        page = params.page
    }  
    var parmstmp = "page=" + page 

    if (params.hasOwnProperty("cid") && (params.cid !== null) && (params.cid !== undefined))
        parmstmp = parmstmp +'&cid=' +  params.cid 
    
    return request({
        url: host + "/api/article/list?" + parmstmp, 
        method: "get" 
    })
}




// 获取文章详情
export function articleDetail(params) { 
    return request({  
        url: host + "/api/article/detail?aid=" +`${params.art_id}`,  
        method: "get"
    })
}
 
 

//点赞文章
export function articleLike(params) {
    return request({
        url: host +  "/api/article/like?aid=" + params.aid + "&like=1",
        method: "get" 
    })
}
 

// 对文章不喜欢 
export function articleDisLike(params) {
    return request({
        url: host +  "/api/article/like?aid=" + params.aid + "&type=unlike&add=1",
        method: "get" 
    })
}

 
  