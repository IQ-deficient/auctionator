<template>
  <div class="block galleryBlock">
    <v-container>
      <h2 class="text-center pb-3">Popular items sold in the past</h2>
      <v-row>
        <v-col v-for="item in items" :key="item.id" class="d-flex child-flex" cols="6" sm="3">
          <v-card flat tile class="d-flex">
            <v-img :src="item.src" aspect-ratio="1" class="grey lighten-2">
              <template v-slot:placeholder>
                <v-row class="fill-height ma-0" align="center" justify="center">
                  <v-progress-circular indeterminate color="grey lighten-1"></v-progress-circular>
                </v-row>
              </template>
            </v-img>
          </v-card>
        </v-col>
      </v-row>
    </v-container>
  </div>
</template>

<script>
export default {
  name: "Gallery",
  data() {
    return {
      items: [
        {
          id: 1,
          src: require("../../src/assets/home/gallery/item_1.jpg")
        },
        {
          id: 2,
          src: require("../../src/assets/home/gallery/item_3.jpg")
        },
        {
          id: 3,
          src: require("../../src/assets/home/gallery/item_4.jpg")
        },
        {
          id: 4,
          src: require("../../src/assets/home/gallery/item_5.jpg")
        },
        {
          id: 5,
          src: require("../../src/assets/home/gallery/item_6.jpg")
        },
        {
          id: 6,
          src: require("../../src/assets/home/gallery/item_7.jpg")
       },
        {
          id: 7,
          src: require("../../src/assets/home/gallery/item_2.jpg")
        },
        {
          id: 8,
          src: require("../../src/assets/home/gallery/item_8.jpg")
        },
      ]
    };
  }
};
</script>