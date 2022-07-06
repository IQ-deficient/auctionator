<template>
  <div class="block latestPostBlock">
    <v-container>
      <h2 class="text-center">Latest Post</h2>
      <v-row>
        <v-col v-for="item in items" :key="item.id" cols="12" md="4">
          <v-card outlined class="mx-auto">
            <v-img
              class="white--text align-end"
              height="200px"
              :src="item.src"
            >
              <v-card-title>{{ item.title }}</v-card-title>
            </v-img>
            <v-card-subtitle class="pb-0">{{ item.subtitle }}</v-card-subtitle>
            <v-card-text class="text--primary">
              <div>{{ item.description }}</div>
            </v-card-text>
          </v-card>
        </v-col>
      </v-row>
    </v-container>
  </div>
</template>

<script>
export default {
  name: "LatestPost",
  data: () => ({
    show: false,
    items: [
      {
        id: 1,
        title: "Nibh mauris cursus mattis molestie a iaculis at erat pellentesque.",
        subtitle: "Viverra aliquet eget sit amet tellus cras.",
        description: "Ultrices gravida dictum fusce ut placerat orci. Rutrum tellus pellentesque eu tincidunt tortor aliquam. Pellentesque elit ullamcorper dignissim cras.",
        src: require("../../src/assets/images/post1.jpg")
      },
      {
        id: 2,
        title: "Nisi quis eleifend quam adipiscing vitae proin sagittis nisl.",
        subtitle: "Venenatis a condimentum vitae sapien pellentesque habitant.",
        description: "Blandit volutpat maecenas volutpat blandit aliquam etiam erat velit. Ullamcorper sit amet risus nullam. Sit amet est placerat in egestas erat.",
        src: require("../../src/assets/images/post2.jpg")
      },
      {
        id: 3,
        title: "Tempor commodo ullamcorper a lacus vestibulum sed.",
        subtitle: "Amet nisl suscipit adipiscing bibendum est.",
        description: "Enim ut tellus elementum sagittis. Diam ut venenatis tellus in metus vulputate eu. Dictum at tempor commodo ullamcorper a lacus vestibulum.",
        src: require("../../src/assets/images/post3.jpg")
      }
    ]
  })
};
</script>