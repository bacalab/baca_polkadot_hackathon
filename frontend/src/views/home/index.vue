<template>
  <div>   
    <div class="top-story-area pd-top-50 pd-bottom-30">
        <div class="container">
            <div class="section-title">
                <div class="row">
                    <div class="col-sm-8">
                        <h4 class="title">Top Voted Stories</h4>
                        <span class="line"></span>
                    </div>
                    <div class="col-sm-4 text-sm-right align-self-center">
                        <a class="see-all-btn float-sm-end" href="#">View all</a>
                    </div>
                </div>
            </div>
            <div class="row" >
                <div class="col-lg-3 col-md-6" v-for="item in toparticles"> 
                    <div class="single-post-wrap">
                        <div class="thumb">
                            <img :src=item.cover_img alt="img">
                            <a class="tag top-right tag-sky top_title" href="#">{{item.category.title}}</a>
                        </div>
                        <div class="post-details">
                            <div class="meta">
                                <div class="date">
                                    <i class="fa fa-clock-o"></i>
                                    {{item.created_at}}
                                </div>
                            </div>  
                            <h6 class="top_title" ><a :href="'#/detail/' + item.id " target="_blank" >{{item.title}}</a></h6>
                        </div>
                    </div>
                </div> 
            </div>
        </div>
    </div> 

    <!-- news-area Start -->
    <div class="pd-top-50 pd-bottom-30">
        <div class="container">
            <div class="row">
                <div class="col-lg-8">
                    <div class="section-title">
                        <h4 class="title">Stories</h4>
                        <span class="line"></span>
                    </div>
                    <div class="media-post-wrap-2 media" v-for="item in articles">
                        <div class="thumb" style="width: 20em;align-self: center;justify-content: center;">
                            <img :src=item.cover_img alt="img">
                        </div>
                        <div class="media-body" style="margin-top:2px">
                            
                            <div class="meta d-block mt-3">
                                <div class="date">
                                    <i class="fa fa-clock-o"></i>
                                    {{item.created_at}} 
                                    <i class="fa author"></i>
                                    {{item.author}}
                                </div>
                            </div>
                            <a :href="'#/detail/' + item.id " target="_blank" ><h6 class="story_title">{{item.title}}</h6>
                            <p v-html="item.content" class="story_content"></p></a>
                            <a class="tag tag-red" href="#" style="margin-top: 2em;">{{item.category.title}}</a>
                            <img src="@/assets/img/vote.png" alt="img" style="width:1.5em;margin-left: 14em;" /> {{item.vote}}
                        </div>
                    </div> 
                    <!-- <div class="btn-wrap mt-5 mb-5 text-center">
                        <a class="btn btn-main" href="#">Load More</a>
                    </div>-->
                </div>
                <div class="col-lg-4">
                    <div class="section-title">
                        <h4 class="title">Categories</h4>
                        <span class="line"></span>
                    </div>
                    <ul class="widget widget-categories">
                        <li v-for="item in cateObjList">
                            <div class="thumb"><img :src=item.cate_img alt="img"></div>
                            <a :href="'#/category/' + item.id " target="_blank" >{{item.title}}</a>
                        </li> 
                    </ul>
                  
                </div>
            </div>
        </div>
    </div>
    <!-- news-area End --> 

</div> 
</template> 
<script> 
import { cateList,articleList,articleTopVoteList } from "@/api/article.js";   
import Header from "../header/header"
import Footer from "../footer/footer"
export default {
  name: 'Home',
  components: {
    Header,Footer
  },
  async created() {  
     // 页面一打开就去加载列表。
    let cateRes = await cateList(); 
    this.cateObjList = cateRes.data.data; 
    let articleRes = await articleTopVoteList({"page":1});  
    this.toparticles = articleRes.data.data.slice(0,4) 
    let articleRes2 = await articleList({"page":1}); 
    this.articles = articleRes2.data.data
    console.log(this.articles )

   
    /*
    for(var j = 0 ; j < this.articles.length; j++) {
        var tmp = this.articles[j].content.split(" ")
        var newcont = tmp.slice(0,20).join(' ') + "..." 
        //this.articles[j].content = newcont  
    } */
  },
  data() {
    return {
      toparticles:{},
      articles:{},
      cateObjList:[]
    }
     },
  methods: {} 

}

 

</script> 
<!-- Add "scoped" attribute to limit CSS to this component only -->
<style scoped> 
.top_title{ 
    max-height: 3em;
    overflow: hidden;
    display: -webkit-box;
    -webkit-box-orient: vertical;
    -webkit-line-clamp: 2;}
.story_title{ 
    font-size:1em !important;
    max-height: 2em;
    overflow: hidden;
    display: -webkit-box;
    -webkit-box-orient: vertical;
    -webkit-line-clamp: 1;}
 .story_content{ 
    max-height: 5em;
    overflow: hidden;
    display: -webkit-box;
    -webkit-box-orient: vertical;
    -webkit-line-clamp: 3;
 
 }

.meta .date i {
color: #7c7577;
}
 
</style>
