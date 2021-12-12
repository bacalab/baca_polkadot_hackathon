<template>
  <div id="app">
      <Header/> 
      <router-view/>
     <Footer/>
  </div>
</template>

<script>
import Header from "./views/header/header"
import Footer from "./views/footer/footer"
 
export default {
  name: 'App',
  components: {
    Header,Footer
  }
}
</script>

<style>

</style>
