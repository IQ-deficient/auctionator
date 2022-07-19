<template>
  <v-main>
    <div class="staticHero">
      <v-img src="../../src/assets/images/aboutus.jpg" max-height="250">
        <v-row align="end" class="lightbox white--text pa-2 fill-height">
          <v-col>
            <v-container>
              <div class="headline">About Us</div>
            </v-container>
          </v-col>
        </v-row>
      </v-img>
    </div>
    <div class="block">
      <v-container>
        <p>Eget velit aliquet sagittis id consectetur purus ut. Quisque sagittis purus sit amet. Odio facilisis mauris
          sit amet. Ultrices gravida dictum fusce ut placerat orci nulla pellentesque. Venenatis lectus magna fringilla
          urna porttitor rhoncus dolor purus. Sed blandit libero volutpat sed cras ornare arcu dui vivamus. Libero
          volutpat sed cras ornare arcu dui vivamus arcu. Suspendisse sed nisi lacus sed viverra tellus. Ultricies
          tristique nulla aliquet enim tortor at auctor urna. Condimentum mattis pellentesque id nibh tortor. Magna ac
          placerat vestibulum lectus. Ultrices eros in cursus turpis massa tincidunt dui ut ornare. Odio ut enim blandit
          volutpat maecenas.</p>
      </v-container>
    </div>
    <div class="block">
      <v-container>
        <h2 class="text-center">Our Team</h2>
        <v-row>
          <v-col v-for="item in items" :key="item.id" class="d-flex child-flex" cols="12" sm="4">
            <v-card flat tile class="mx-auto">
              <v-img :src="item.src" aspect-ratio="1" class="grey lighten-2"></v-img>
              <v-card-text class="text--primary text-center">
                <div class="title">{{ item.name }}</div>
                <p>{{ item.title }}</p>
              </v-card-text>
            </v-card>
          </v-col>
        </v-row>
      </v-container>
    </div>
  </v-main>
</template>

<script>
export default {
  name: "About",
  data() {
    return {
      items: [
        {
          id: 1,
          src: require("../../src/assets/images/team1.jpg"),
          name: 'Peter Smith',
          title: 'Admin'
        },
        {
          id: 2,
          src: require("../assets/about/marc.jpg"),
          name: 'Alec "Marc" Thompson',
          title: 'Don\'t be scared of the truth because we need to restart the human foundation in truth And I love you like Kanye loves Kanye I love Rick Owens’ bed design but the back is...'
        },
        {
          id: 3,
          src: require("../../src/assets/images/team3.jpg"),
          name: 'Lisa White',
          title: 'Manager'
        }
      ]
    };
  },

  mounted() {
  }
};
</script>