<template>
  <div class="block galleryBlock">
    <v-container>
      <h2 class="text-center">Gallery</h2>
      <v-row>
        <v-col v-for="item in items" :key="item.id" class="d-flex child-flex" cols="6" sm="4">
          <v-card flat tile class="d-flex">
            <v-img :src="item.src" aspect-ratio="1" class="grey lighten-2">
              <template v-slot:placeholder>
                <v-row class="fill-height ma-0" align="center" justify="center">
                  <v-progress-circular indeterminate color="grey lighten-5"></v-progress-circular>
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
          src: require("../../src/assets/images/gallery1.jpg")
        },
        {
          id: 2,
          src: require("../../src/assets/images/gallery2.jpg")
        },
        {
          id: 3,
          src: require("../../src/assets/images/gallery3.jpg")
        },
        {
          id: 4,
          src: require("../../src/assets/images/gallery4.jpg")
        },
        {
          id: 5,
          src: require("../../src/assets/images/gallery5.jpg")
        },
        {
          id: 6,
          src: require("../../src/assets/images/gallery6.jpg")
        },
        {
          id: 7,
          src: require("../../src/assets/images/gallery7.jpg")
        },
        {
          id: 8,
          src: require("../../src/assets/images/gallery8.jpg")
        },
        {
          id: 9,
          src: require("../../src/assets/images/gallery9.jpg")
        }
      ]
    };
  }
};
</script>