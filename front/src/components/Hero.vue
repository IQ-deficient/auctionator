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
          src: require("../../src/assets/images/hero1.jpg"),
          title: "Auctor eu augue ut lectus arcu bibendum."
        },
        {
          src: require("../../src/assets/images/hero2.jpg"),
          title: "Nunc mattis enim ut tellus elementum sagittis vitae et."
        },
        {
          src: require("../../src/assets/images/hero3.jpg"),
          title: "Rhoncus dolor purus non enim praesent elementum facilisis leo."
        }
      ]
    };
  }
};
</script>