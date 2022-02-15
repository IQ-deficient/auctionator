<template>
  <div>
<!--    Globalni tag navigacione trake -->
    <v-toolbar
        light
        color="tertiary"
        height="75"
    >
<!--      Fioka sa elementima kategorija i potkategorija-->
      <v-app-bar-nav-icon @click.stop="drawer = !drawer" style="color: black; margin-left: 10px"></v-app-bar-nav-icon>
      <router-link to="/home">
<!--        Logo aplikacije sa likom na /home-->
        <v-img src="../assets/architecture icon.svg"

               style="margin-left: 28px"
               max-height="60px"
               max-width="60px"
        ></v-img></router-link>

      <v-divider id="divider" vertical style="margin-left: 28px; border-right: 2px solid black"></v-divider>

      <v-toolbar-title style="margin-left: 28px">Auction House</v-toolbar-title>
      <v-spacer></v-spacer>
      <router-link to="/login/" style="text-decoration: none">
        <v-btn value="center"
               color="primary" style="margin: 10px">
          <span class="hidden-sm-and-down">Login</span>
        </v-btn>
      </router-link>
      <router-link to="/register/" style="text-decoration: none">
        <v-btn value="center"
               color="primary">
          <span class="hidden-sm-and-down">Register</span>
        </v-btn>
      </router-link>

<!--      Korisnikov avatar koji otvara funkcije odjavljivanja i azuriranja naloga-->
      <v-speed-dial
          v-model="fab"
          :top="top"
          :bottom="bottom"
          :right="right"
          :left="left"
          :direction="direction"
          :open-on-hover="hover"
          :transition="transition"
      >
        <template v-slot:activator>
          <v-btn
              v-model="fab"
              color="accent"
              dark
              fab
          >
            <v-icon v-if="fab">
              mdi-close
            </v-icon>
            <v-icon v-else>
              mdi-account-circle
            </v-icon>
          </v-btn>
        </template>
        <v-btn
            fab
            dark
            small
            color="primary"
        >
          <v-icon>mdi-logout-variant</v-icon>
        </v-btn>
        <v-btn
            fab
            dark
            small
            color="primary"
        >
          <v-icon>mdi-cogs</v-icon>
        </v-btn>
      </v-speed-dial>
    </v-toolbar>

<!--    Globalni tag fioke sa elementima kategorija i potkategorija-->
    <v-navigation-drawer style="width: min-content"
                         v-model="drawer"
                         absolute
                         bottom
                         temporary
                         light
                         color="tertiary"
    >

      <v-list-item class="primary" style="height: 75px" dark>
        <v-list-item-content>
          <v-list-item-title class="text-h5" style="">
            Browse
          </v-list-item-title>
        </v-list-item-content>
        <v-btn
            icon
            @click.stop="drawer = !drawer"
        >
          <v-icon>mdi-chevron-left</v-icon>
        </v-btn>
      </v-list-item>
      <v-list
          nav
          dense
      >
        <v-list-item-group align="left"
            v-model="group"
            light
        >
          <v-list-group
              :value="false"
              no-action
              sub-group>
            <template v-slot:activator>
              <v-list-item-content>
                <v-list-item-title class="text-h6" style="width: 240px">Electronics</v-list-item-title>
              </v-list-item-content>
            </template>
            <v-list-item
                v-for="([title], i) in electronics"
                :key="i"
                link
            >
              <v-list-item-title v-text="title" style="font-size: 15px"></v-list-item-title>
            </v-list-item>
          </v-list-group>

          <v-divider style="margin-top: 8px"> </v-divider>

          <v-list-group style="margin-top: 8px"
                        :value="false"
                        no-action
                        sub-group>
            <template v-slot:activator>
              <v-list-item-content>
                <v-list-item-title class="text-h6">Books, Movies & Music</v-list-item-title>
              </v-list-item-content>
            </template>
            <v-list-item
                v-for="([title, icon], i) in collectiblesandart"
                :key="i"
                link
            >
              <v-list-item-title v-text="title" style="font-size: 15px"></v-list-item-title>

              <v-list-item-icon>
                <v-icon v-text="icon"></v-icon>
              </v-list-item-icon>
            </v-list-item>
          </v-list-group>
        </v-list-item-group>
      </v-list>

      <v-divider style="margin-bottom: 15px"> </v-divider>

    </v-navigation-drawer>
  </div>
</template>

<script>
export default {
name: "Navbar",
  data: () => ({
    drawer: false,
    group: null,
    direction: 'left',
    fab: false,
    fling: false,
    hover: false,
    tabs: null,
    top: false,
    right: true,
    bottom: false,
    left: false,
    transition: 'slide-x-reverse-transition',
    electronics: [
      ['All'],
      ['Computers, Tablets & Network Hardware'],
      ['Cell Phones, Smart Watches & Accessories'],
      ['TV, Video & Home Audio Electronics'],
      ['Surveillance & Smart Home Electronics'],
      ['Video Games & Consoles'],
    ],
    collectiblesandart: [
      ['All'],
      ['Collectibles'],
      ['Sports Memorabilia, Fan Shop & Sports Cards'],
      ['Antiques'],
      ['Pottery & Glass'],
      ['Art'],
    ],
  }),
  watch: {
    // group() {
    //   this.drawer = false
    // },
    top (val) {
      this.bottom = !val
    },
    right (val) {
      this.left = !val
    },
    bottom (val) {
      this.top = !val
    },
    left (val) {
      this.right = !val
    },
  },
}
</script>

<style scoped>

</style>