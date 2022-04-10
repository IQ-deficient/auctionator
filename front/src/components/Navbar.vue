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
        ></v-img>
      </router-link>

      <v-divider id="divider" vertical style="margin-left: 28px; border-right: 2px solid black"></v-divider>

      <v-toolbar-title style="margin-left: 28px">Auction House</v-toolbar-title>
      <v-spacer></v-spacer>
      <router-link to="/register/" style="text-decoration: none">
        <v-btn value="center"
               color="transparent" depressed>
          <v-icon left class="mr-2">mdi-pencil-box-outline</v-icon>
          <span class="hidden-sm-and-down">Sign up</span>
        </v-btn>
      </router-link>
      <router-link to="/login/" style="text-decoration: none">
        <v-btn value="center"
               color="primary" class="ml-2">
          <v-icon left class="mr-2">mdi-login-variant</v-icon>
          <span class="hidden-sm-and-down">Sign in</span>
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
            @click="logout()"
        >
          <v-icon>mdi-logout-variant</v-icon>
        </v-btn>
        <router-link to="/user-profile">
          <v-btn
              fab
              dark
              small
              color="primary"
          >
            <v-icon>mdi-account-details</v-icon>
          </v-btn>
        </router-link>
      </v-speed-dial>
    </v-toolbar>

    <!------- Globalni tag fioke sa elementima kategorija i potkategorija ------->
    <v-navigation-drawer style="min-width: 28%; width: fit-content"
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
<!--        <v-subheader>REPORTS</v-subheader>-->
<!--        <v-list-item-group align="left"-->
<!--                           v-model="selectedCategory"-->
<!--                           light-->
<!--        >-->
<!--          <v-list-group-->
<!--              :value="false"-->
<!--              no-action-->
<!--              sub-group>-->
<!--            <template v-slot:activator>-->
<!--              <v-list-item-content>-->
<!--                <v-list-item-title id="category" class="text-h6" style="width: 240px">el</v-list-item-title>-->
<!--              </v-list-item-content>-->
<!--            </template>-->
<!--            <v-list-item-->
<!--                v-for="([title], i) in subcategories"-->
<!--                :key="i"-->
<!--                link-->
<!--            >-->
<!--              <v-list-item-title v-text="title" style="font-size: 15px"></v-list-item-title>-->
<!--            </v-list-item>-->
<!--          </v-list-group>-->
<!--        </v-list-item-group>-->

        <v-treeview
        v-model="selectedCategory"
        :items="categories"
        activatable
        item-key="name"
        open-on-click
        hoverable
        transition
        >
<!--        <template v-slot:prepend="{ item, open }">-->
<!--          <v-icon v-if="!item.file">-->
<!--            {{ open ? 'mdi-folder-open' : 'mdi-folder' }}-->
<!--          </v-icon>-->
<!--          <v-icon v-else>-->
<!--            {{ files[item.file] }}-->
<!--          </v-icon>-->
<!--        </template>-->
        </v-treeview>
      </v-list>


    </v-navigation-drawer>
  </div>
</template>

<script>
import axios from "axios";

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
    selectedCategory: [],
    categories: [],
    subcategories: [],
  }),

  created() {
    this.getCategories()
  },

  methods: {
    getCategories() {
      this.categories = []
      axios.get('/menu_categories')
          .then(response => {
            if (response.data) {
              // for (let i = 0; i < response.data.length; i++) {
                this.categories = response.data;
                // console.log(response.data)
              // }
            }
          })
          .catch(error => {
            console.log(error)
          })
    },

    getSubCategories() {
      this.categories = []
      axios.get('/active_categories')
          .then(response => {
            if (response.data) {
              for (let i = 0; i < response.data.length; i++) {
                this.categories = response.data;
                // console.log(response.data)
              }
            }
          })
          .catch(error => {
            console.log(error)
          })
    },

    logout() {
      const config = {
        headers: {'Authorization': 'Bearer ' + JSON.parse(localStorage.getItem('token'))}
      };
      const bodyParameters = {
        key: "token"
      };

      this.loading = true
      axios.post('/auth/logout', bodyParameters, config)
          .then(response => {
                if (response) {
                  localStorage.removeItem("token");
                  this.$router.push('/');
                  this.$router.go(0)
                  // console.log(response.data)
                  this.loading = false;
                }
              }
          )
          .catch(error => {
            console.log(error)
            // console.log(JSON.parse(localStorage.getItem('token')).access_token);
            this.loading = false
            this.error = error.response.data.message;

          })
      // sessionStorage.removeItem("key");
      // localStorage.removeItem("user");
      // this.$router.push('/home');
    },
  },

  watch: {
    // group() {
    //   this.drawer = false
    // },
    top(val) {
      this.bottom = !val
    },
    right(val) {
      this.left = !val
    },
    bottom(val) {
      this.top = !val
    },
    left(val) {
      this.right = !val
    },
  },
}
</script>

<style scoped>

</style>