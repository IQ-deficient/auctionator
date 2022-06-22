<template>
  <v-app style="background-color: #1C263E">
    <Loader></Loader>
    <div style="min-height: 100%">
      <Navbar>
        <router-link to="/home"></router-link>
        <router-link to="/about"></router-link>
        <router-link to="/contact"></router-link>
        <router-link to="/register"></router-link>
        <router-link to="/login"></router-link>
      </Navbar>
      <div>
        <router-view/>
      </div>
    </div>
    <Footer></Footer>
  </v-app>
</template>

<script>
import Navbar from "@/components/Navbar";
import Footer from "@/components/Footer";
import Loader from "@/components/Loader";

export default {
  components: {Navbar, Footer, Loader},
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
  color: #e7d8d2;
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


