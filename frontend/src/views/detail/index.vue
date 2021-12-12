<template>
    <div v-loading="loading" element-loading-text="doing">
        <img src="@/assets/img/bct.gif" alt="img" class="fixed_div">
        <!-- blog area Start -->
        <div class="blog-details-area pd-top-30 pd-bottom-80">
            <div class="container">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="breadcrumb-inner pb-4">
                            <i class="fa fa-home fa-2"></i>
                            <ul class="page-list">
                                <li><a href="/" style="font-size: 1.5em;">Home</a></li>
                                <li style="font-size: 1.5em;">Details</li>
                            </ul>
                        </div>
                        <div class="blog-details-wrap">
                            <h3>{{articleObj.title}}</h3>
                            <p class="subtitle">{{articleObj.sub_title}}</p>
                            <div>
                            </div>
                            <div class="meta">
                                <a href="#" class="author">
                                    <img :src=articleObj.author_head alt="img" style="width:2em">
                                    {{articleObj.author}}
                                </a>
                                <el-button round type="primary" ref="followuser" style="padding: 9px 10px;margin-right:2em" @click="follow">Follow</el-button>
                            </div>
                            <div class="meta float-sm-right">
                                <div class="date">
                                    <i class="fa fa-clock-o" style="color: #7c7577;"></i>
                                    {{articleObj.created_at}}
                                </div>
                            </div>
                            <div class="row">
                                <div v-html="articleObj.content" class="col-lg-12"> </div>
                            </div>
                            <div class="blog-share-area">
                                <ul class="social-area action">
                                    <li>
                                        <i class="fa fa-thumbs-up fa-lg" @click=like></i> {{articleObj.like}}
                                    </li>
                                    <li>
                                        <i class="fa fa-thumbs-down fa-lg" @click=dislike></i> {{articleObj.unlike}}
                                    </li>
                                    <li @click=vote>
                                        <img src="./imgs/vote.png" alt="img" style="width:1.5em" />
                                        <el-button round type="primary" @click="vote" class="votebutton">vote</el-button>
                                    </li>
                                </ul>
                                <ul class="social-area">
                                    <li>
                                        <a class="facebook" href="#"><i class="fa fa-facebook"></i></a>
                                    </li>
                                    <li>
                                        <a class="pinterest" href="#"><i class="fa fa-pinterest"></i></a>
                                    </li>
                                    <li>
                                        <a class="twitter" href="#"><i class="fa fa-twitter"></i></a>
                                    </li>
                                    <li>
                                        <a class="linkedin" href="#"><i class="fa fa-linkedin"></i></a>
                                    </li>
                                </ul>
                            </div>
                            <el-dialog title="VOTE" :visible.sync="centerDialogVisible" width="30%" center :destroy-on-close="true" class="votedialog">
                                <h6 style="text-align: center;">How many BAT to stake?</h6>
                                <div style="margin-top: 3em;">
                                    <vue-slider v-model="votenum" :tooltip="'always'" :max="max" :min="min" />
                                </div>
                                <h6 style="text-align: center; margin-top:1.2em;color: #409eff;">Avaliable: {{balance}}</h6>
                                <span slot="footer" class="dialog-footer">
                                    <el-button @click="centerDialogVisible = false">取 消</el-button>
                                    <el-button type="primary" @click="submitTran">确 定</el-button>
                                </span>
                            </el-dialog>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- blog area End -->
    </div>
</template>
<script>
import { articleDetail, articleLike, articleDisLike } from "@/api/article.js";
import { userBanllance, userTransferToBack } from "@/api/mine.js";
import { setToken, getToken } from "@/utils/token.js";
import { Notification } from 'element-ui';
import userTransfer from "@/utils/handleStake.js";
import VueSlider from 'vue-slider-component'
import 'vue-slider-component/theme/antd.css'
import { Loading } from 'element-ui';


export default {
    name: 'Detail',
    components: {
        VueSlider
    },
    async created() {
        // 页面一打开就去加载文章详情。
        let res = await articleDetail({
            art_id: this.$route.params.art_id
        });
        this.articleObj = res.data.data;

    },
    data() {
        return {
            articleObj: {},
            centerDialogVisible: false,
            balance: 0,
            votenum: 1,
            min: 1,
            max: 100,
            loading: false
        }
    },
    methods: {
        follow() { 
        },
        async like() {
            if (this.$checkLogin()) {
                let res = await articleLike({
                    aid: this.articleObj.id
                });
                if (res.data.code == 0) {
                    //刷新文章喜欢数
                    let res = await articleDetail({
                        art_id: this.$route.params.art_id
                    });
                    this.articleObj = res.data.data;
                }
            } else { this.$router.push("/login"); }
        },
        async dislike() {
            console.log('dislike')
            if (this.$checkLogin()) {
                let res = await articleDisLike({
                    aid: this.articleObj.id
                });
                if (res.data.code == 0) {
                    //刷新文章喜欢数
                    let res = await articleDetail({
                        art_id: this.$route.params.art_id
                    });
                    this.articleObj = res.data.data;
                }
            } else { this.$router.push("/login"); }
        },
        async vote() {
            console.log('vote')
            // 获取余额

            if (this.$checkLogin()) { 
                let ba = getToken("bacaWallet")
                let bal_res = await userBanllance({ wallet_id: ba })
                if (bal_res.data && bal_res.data.code == 0) {
                    this.balance = bal_res.data.data['ballance']
                    this.max = parseInt(this.balance)
                }
                this.centerDialogVisible = true
            } else { this.$router.push("/login"); }

        },
        async submitTran() {
            var that = this

            var mywallet = getToken("bacaWallet")
            var ammount = this.votenum
            console.log("当前值", this.votenum)
            if (this.$checkLogin()) {
                this.centerDialogVisible = false
                let loadingInstance = Loading.service({ fullscreen: true });
                let res = await userTransfer({ "wallet_id": mywallet, "stake_ammount": ammount })
                console.log(res)
                loadingInstance.close();
                //alert("交易提交成功，请稍候查询")
                const h = this.$createElement;
                var mes = "Transfer submit seccuss"
                var options = {
                    title: 'Aha ~',
                    message: h('i', { style: 'color: teal;font-weight:700' }, mes),
                    type: 'success'

                }
                Notification(options)
                var params = { "aid": this.articleObj.id, "stake": ammount }
                let backres = await userTransferToBack(params)
                console.log(backres) 
            }
        }
    }
}
</script>
<style>
.blog-share-area {
    padding-bottom: 0 !important;
    margin-bottom: 0 !important;
    border: none !important;
}

.social-area {
    display: inline-block;
}

.action {
    float: left;
}

.action li {
    margin-right: 2em !important;
}

.el-dialog__header {
    background-color: #409EFF;
    font-weight: bold;
}

.votebutton {
    margin-left: 0.5em !important;
    vertical-align: middle !important;
    padding: 5px 7px !important;
}

.votedialog .el-dialog {
    border: 1px solid #94c1e7;
    -moz-border-radius: 6px;
    -webkit-border-radius: 6px;
    border-radius: 6px;
    box-shadow: darkgrey 1px 1px 2px 2px;
}


.fixed_div {
    position: fixed;
    z-index: 10;
    right: 0;
    bottom: 0;
    width: 4em
}

.meta .date i {
    color: #7c7577;
}
</style>