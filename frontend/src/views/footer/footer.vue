<template>
  <div> 
    <!-- footer area start -->
    <footer class="footer-area" style="background-image: url(assets/img/footer/bg.png);">
        <div class="container"> 
            <div style="padding: 1em 0;">
                <div class="row">
                    <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 offset-xl-3 offset-lg-3 offset-md-3 offset-sm-3" style="text-align:center;margin:0 auto">
                        <div class="widget widget_about" style="margin-bottom: 0px;">
                            <div class="logo">
                                <img src="@/assets/img/logo-2.png" alt="img" style="width: 30%;">
                            </div>
                            <p class="text-white mt-3 mb-3">To bring the old and classic "reading" back to our life and to distribute high-quality, genuine and valuable thoughts to the world.</p>
                            <ul class="social-area social-area-2">
                                <li><a href="#"><i class="fa fa-facebook" target="_blank"></i></a></li>
                                <li><a href="https://twitter.com/BacaWeb3" target="_blank"><i class="fa fa-twitter"></i></a></li>
                                <li><a href="#"><i class="fa fa-linkedin" target="_blank"></i></a></li>
                                <li><a href="#"><i class="fa fa-pinterest" target="_blank"></i></a></li>
                            </ul>
                        </div>
                    </div>
                 
                   
                   
                </div>
            </div>
        </div>
        <div class="copyright-area" style="padding: 5px 0 5px">
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-lg-12 text-center">
                        <p>Copyright 2021. All Right Reserved</p>
                    </div>
                </div>
            </div>
        </div>
    </footer>
    <!-- footer area end -->

    <!-- back to top area start -->
    <div class="back-to-top">
        <span class="back-top"><i class="fa fa-angle-double-up"></i></span>
    </div>
    <!-- back to top area end --> 

</div>

</template>

<script>
 


export default {
  name: 'Footer',
  data () {
    return {
      msg: 'Welcome to Your Vue.js App'
    }
  }
}

</script>
<!-- Add "scoped" attribute to limit CSS to this component only -->
<style scoped>
.footer-area{
    margin-top:2em
}
 
</style>
