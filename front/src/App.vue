<template>
  <v-app>
<!--    Navigaciona traka sa definisanim rutama unutar njenih tagova-->
    <Navbar>
      <router-link to="/home"></router-link>
      <router-link to="/about"></router-link>
      <router-link to="/register"></router-link>
      <router-link to="/login"></router-link>
    </Navbar>
    <div style="min-height: 90%; background-color: #4a5568">
          <router-view/>
    </div>
    <Footer></Footer>
  </v-app>
<!--  <div id="app">-->
<!--    <div id="nav">-->
<!--      <router-link to="/">Home</router-link> |-->
<!--      <router-link to="/about">About</router-link>-->
<!--    </div>-->
<!--    <router-view/>-->
<!--  </div>-->
</template>

<script>
// Ovdje se definisu sve globalne Vue komponente
import Navbar from "@/components/Navbar";
import Footer from "@/components/Footer";

export default {
  components: {Navbar, Footer},
  data() {
    return {};
  },
  methods: {},
};
</script>

<style lang="scss">
#app {
  font-family: Avenir, Helvetica, Arial, sans-serif;
  -webkit-font-smoothing: antialiased;
  -moz-osx-font-smoothing: grayscale;
  text-align: center;
  color: #2c3e50;
}

#nav {
  padding: 30px;

  a {
    font-weight: bold;
    color: #2c3e50;

    &.router-link-exact-active {
      color: #42b983;
    }
  }
}
</style>


