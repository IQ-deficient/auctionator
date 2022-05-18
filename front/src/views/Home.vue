<template>
  <div class="home">
    <img class="pt-5" alt="Vue logo" src="../assets/Marc.png">
    <p style="color: white"> Welcome </p>
<!--    <HelloWorld msg="Welcome to Your Vue.js App"/>-->
  </div>
</template>

<script>
// @ is an alias to /src
// import HelloWorld from '@/components/HelloWorld.vue'

export default {
  name: 'Home',
  components: {
    // HelloWorld
  },

  mounted() {
    document.title = 'Auction House'
  }
}
</script>
