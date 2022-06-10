<template>
  <div class="home">
    <p style="color: white"> Welcome </p>
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
