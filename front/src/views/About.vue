<template>
  <v-main>
    <div class="staticHero">
      <v-img src="../../src/assets/about/about_us.jpg" max-height="250">
        <v-row align="end" class="lightbox white--text pa-2 fill-height">
          <v-col>
            <v-container>
              <div class="headline text-lg-h2">About Us</div>
            </v-container>
          </v-col>
        </v-row>
      </v-img>
    </div>
    <div class="block">
      <v-container>
        <p>An online auction company is a company that provides an online platform for sellers to list their products
          for sale. The company manages the transaction process and facilitates the sales between the seller and
          buyers. It offers various services to make a website where the buyers and sellers can meet and do business. It
          also helps the buyers and sellers to sell their products to make a profit.</p>
      </v-container>
    </div>
    <div class="block">
      <v-container>
        <h2 class="text-center pb-3">Company Officials</h2>
        <v-row>
          <v-col v-for="item in items" :key="item.id" class="d-flex child-flex" cols="12" sm="3">
            <v-card flat tile class="mx-auto">
              <v-img :src="item.src" aspect-ratio="1" class="grey lighten-2">
                <template v-slot:placeholder>
                  <v-row class="fill-height ma-0" align="center" justify="center">
                    <v-progress-circular indeterminate color="grey lighten-1"></v-progress-circular>
                  </v-row>
                </template>
              </v-img>
              <v-card-text class="text--primary text-center">
                <div class="title">{{ item.name }}</div>
                <p>{{ item.title }}</p>
              </v-card-text>
            </v-card>
          </v-col>
        </v-row>
      </v-container>
    </div>
    <div style="width: 100%" class="pt-12 pr-15 pl-15">
      <iframe
        width="100%" height="550" frameborder="0" scrolling="no" marginheight="0" marginwidth="0"
        src="https://maps.google.com/maps?width=100%25&amp;height=550&amp;hl=en&amp;q=+(Univerzitet%20Mediteran%20Podgorica)&amp;t=&amp;z=16&amp;ie=UTF8&amp;iwloc=B&amp;output=embed"
      >
      </iframe>
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
          src: require("../assets/about/employee_1.jpg"),
          name: 'Shuaib Velasquez',
          title: 'Jump out of an airplane and reinvent the wheel.'
        },
        {
          id: 2,
          src: require("../assets/about/marc.jpg"),
          name: 'Alec "Marc" Thompson',
          title: 'Don\'t be scared of the truth because we need to restart the human foundation in truth And I love you like Kanye loves Kanye I love Rick Owens’ bed design but the back is...'
        },
        {
          id: 3,
          src: require("../../src/assets/about/employee_2.jpg"),
          name: 'Jakob Lowry',
          title: 'Every time I thought I was being rejected from something good, I was actually being re-directed to something better.'
        },
        {
          id: 4,
          src: require("../../src/assets/about/employee_3.jpg"),
          name: 'Reilly Shelton',
          title: 'Don’t waste your time in anger, regrets, worries, and grudges. Life is too short to be unhappy.'
        }
      ]
    };
  },

  mounted() {
    // if (window.localStorage.user_roles.includes('Manager') || window.localStorage.user_roles.includes('Auctioneer') || window.localStorage.user_roles.includes('Administrator')) {
    //   this.$router.push('/pageNotFound')
    // }
  }
};
</script>