<template>
  <div class="heroBlock">
    <v-carousel cycle hide-delimiters>
      <v-carousel-item
          dark
          v-for="(item,i) in items"
          :key="i"
          :src="item.src"
          reverse-transition="fade-transition"
          transition="fade-transition"
      >
        <v-row class="title fill-height hidden-xs-only" align="center" justify="center">{{ item.title }}</v-row>
      </v-carousel-item>
    </v-carousel>
  </div>
</template>

<script>
export default {
  name: "Hero",
  data() {
    return {
      items: [
        {
          src: require("../assets/home/hero/hero1.jpg"),
          title: "An online auction house is a great way to sell your items. You can sell anything here, from antiques to old electronics to your old toys. All you need is an Internet connection."
        },
        {
          src: require("../assets/home/hero/hero2.jpg"),
          title: "Nunc mattis enim ut tellus elementum sagittis vitae et."
        },
        {
          src: require("../assets/home/hero/hero3.jpg"),
          title: "Rhoncus dolor purus non enim praesent elementum facilisis leo."
        }
      ]
    }
  }
}
</script>