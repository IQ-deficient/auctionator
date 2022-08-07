<template>
  <v-main>
    <div class="staticHero">
      <v-img src="../../src/assets/home/home.jpg" max-height="250">
        <v-row align="end" class="lightbox white--text pa-2 fill-height">
          <v-col>
            <v-container>
              <div class="headline text-lg-h2">Welcome to Auctionator</div>
            </v-container>
          </v-col>
        </v-row>
      </v-img>
    </div>
    <div class="block">
      <v-container>
        <p>An online auction house is a great way to sell your items.
          You can sell anything here, from antiques to old electronics to your old toys.
          All you need is an Internet connection.</p>
      </v-container>
    </div>
    <Gallery/>
<!--    <Hero/>-->
<!--    <LatestPost/>-->
  </v-main>
</template>

<script>
import Gallery from "../components/Gallery";
// import Hero from "../components/Hero";
// import LatestPost from "../components/LatestPost";

export default {
  name: "Home",

  components: {
    Gallery,
    // Hero,
    // LatestPost
  },
  mounted() {
  }
};
</script>