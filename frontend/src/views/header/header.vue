<template>
    <div>
        <!-- search popup area start -->
        <div class="search-popup" id="search-popup">
            <form action="index.html" class="search-form">
                <div class="form-group">
                    <input type="text" class="form-control" placeholder="Search.....">
                </div>
                <button type="submit" class="submit-btn"><i class="fa fa-search"></i></button>
            </form>
        </div>
        <!-- //. search Popup -->
        <div class="body-overlay" id="body-overlay"></div>



          <!-- navbar start -->
    <div class="adbar-area d-none d-lg-block pd-top-10 pd-bottom-10" style="background-color: rgb(133 47 47);;">
        <div class="container">
            <div class="row">
                <div class="col-md-3 align-self-center">
                    <div class="logo text-md-left text-center">
                        <a class="main-logo" href="index.html"><img src="@/assets/img/logo.png" alt="img" style="width: 65%;"></a>
                    </div>
                </div>
                <div class="col-md-7 text-end">
                    <a href="#" class="adbar-right">  
                        <img src="@/assets/img/ad/9.png" alt="img">
                    </a>
                </div>
                <div class="col-md-2 align-self-center"> 
                    <ul class="social-area text-md-right text-center">
                        <li class="user ms-auto"><a href="/#/mine"><i class="fa fa-user"></i></a></li>
                    </ul>
                     
                </div>
            </div>
        </div>
    </div>
    <!-- navbar end --> 

    </div>
</template>
<script>
export default {
    name: 'Header',
    data() {
        return {
            msg: 'header'
        }
    }
}
</script>
<!-- Add "scoped" attribute to limit CSS to this component only -->
<style scoped>
.header-area {
    margin-bottom: 2em;
    height: 8em;
    background-color: #3e99d3;
}
</style>