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
        <v-img src="../assets/architecture-icon.svg"
               style="margin-left: 28px"
               max-height="60px"
               max-width="60px"
        ></v-img>
      </router-link>

      <v-divider id="divider" vertical style="margin-left: 28px; border-right: 2px solid black"></v-divider>

      <v-toolbar-title style="margin-left: 28px">Auction House</v-toolbar-title>
      <v-spacer></v-spacer>
      <div v-if="token == null">
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
      </div>
      <div v-else>
        <v-container
            fluid
        >
          <v-row justify="center">
            <v-menu
                bottom
                min-width="20%"
                offset-y
            >
              <template v-slot:activator="{ on }">
                <v-btn
                    icon
                    x-large
                    v-on="on"
                >
                  <v-avatar size="50">
                    <v-img v-if="userImage"
                           :loading="pageLoading"
                           :src="require('../../../back/public/' + userImage)"
                           lazy-src="../assets/user-image-nav.svg"
                           :alt="loggedUser.first_name">
                      <template v-slot:placeholder>
                        <v-row
                            class="fill-height ma-0"
                            align="center"
                            justify="center"
                        >
                          <v-progress-circular
                              indeterminate
                              width="14"
                              :size="145"
                              color="primary"
                          ></v-progress-circular>
                        </v-row>
                      </template>
                    </v-img>
                    <v-img v-else
                           lazy-src="../assets/user-image-nav.svg"
                           src="../assets/user-image-nav.svg"
                           alt="User image placeholder">
                    </v-img>
                  </v-avatar>
                </v-btn>
              </template>
              <v-card>
                <v-list-item-content class="justify-center">
                  <div class="mx-auto text-center">
                    <router-link to="/user-profile" style="text-decoration: none">

                      <v-avatar size="50" class="mb-2">
                        <v-img v-if="userImage"
                               :loading="pageLoading"
                               :src="require('../../../back/public/' + userImage)"
                               lazy-src="../assets/user-image-nav.svg"
                               :alt="loggedUser.first_name">
                          <template v-slot:placeholder>
                            <v-row
                                class="fill-height ma-0"
                                align="center"
                                justify="center"
                            >
                              <v-progress-circular
                                  indeterminate
                                  width="14"
                                  :size="145"
                                  color="primary"
                              ></v-progress-circular>
                            </v-row>
                          </template>
                        </v-img>
                        <v-img v-else
                               lazy-src="../assets/user-image-nav.svg"
                               src="../assets/user-image-nav.svg"
                               alt="User image placeholder">
                        </v-img>
                      </v-avatar>
                    </router-link>
                    <h3>{{ this.loggedUser.username }}</h3>
                    <p class="text-caption mt-1">
                      {{ this.loggedUser.email }}
                    </p>
                    <v-divider class="my-3"></v-divider>
                    <div v-if="role == 'Client'">
                      <v-container
                          fluid
                      >
                        <v-row justify="center">
                          <v-menu
                              left
                              min-width="12%"
                              offset-x
                          >
                            <template v-slot:activator="{ on }">
                              <v-btn
                                  depressed
                                  text
                                  v-on="on"
                              >
                                <v-icon left class="mr-2">mdi-tag-arrow-left-outline</v-icon>
                                Bid options
                              </v-btn>
                            </template>
                            <v-card>
                              <v-list-item-content class="justify-center">
                                <div class="mx-auto text-center">
                                  <router-link to="/bids" style="text-decoration: none">
                                    <v-btn
                                        depressed
                                        text
                                    >
                                      <v-icon left class="mr-2">mdi-alarm-multiple</v-icon>
                                      Bids
                                    </v-btn>
                                  </router-link>
                                  <v-divider class="my-1"></v-divider>
                                  <router-link to="/history" style="text-decoration: none">
                                    <v-btn
                                        depressed
                                        text
                                    >
                                      <v-icon left class="mr-2">mdi-history</v-icon>
                                      History
                                    </v-btn>
                                  </router-link>
                                </div>
                              </v-list-item-content>
                            </v-card>
                          </v-menu>
                        </v-row>
                      </v-container>
                      <v-divider class="my-3"></v-divider>

                    </div>
                    <div v-if="role == 'Administrator'">
                      <v-container
                          fluid
                      >
                        <v-row justify="center">
                          <v-menu
                              left
                              min-width="12%"
                              offset-x
                          >
                            <template v-slot:activator="{ on }">
                              <v-btn
                                  depressed
                                  text
                                  v-on="on"
                              >
                                <v-icon left class="mr-2">mdi-shield-crown-outline</v-icon>
                                Admin Function
                              </v-btn>
                            </template>
                            <v-card>
                              <v-list-item-content class="justify-center">
                                <div class="mx-auto text-center">
                                  <router-link to="/admin-auction" style="text-decoration: none">
                                    <v-btn
                                        depressed
                                        text
                                    >
                                      <v-icon left class="mr-2">mdi-store-search-outline</v-icon>
                                      Auctions
                                    </v-btn>
                                  </router-link>
                                  <v-divider class="my-1"></v-divider>
                                  <router-link to="/admin-user" style="text-decoration: none">
                                    <v-btn
                                        depressed
                                        text
                                    >
                                      <v-icon left class="mr-2">mdi-account-search-outline</v-icon>
                                      Users
                                    </v-btn>
                                  </router-link>
                                </div>
                              </v-list-item-content>
                            </v-card>
                          </v-menu>
                        </v-row>
                      </v-container>
                      <v-divider class="my-3"></v-divider>

                    </div>
                    <div v-if="role.includes('Auctioneer')">
                      <router-link to="/admin-auction" style="text-decoration: none">
                        <v-btn
                            depressed
                            text
                        >
                          <v-icon left class="mr-2">mdi-store-search-outline</v-icon>
                          Manage Auctions
                        </v-btn>
                      </router-link>
                      <v-divider class="my-3"></v-divider>
                    </div>
                    <div v-if="role.includes('Manager')">
                      <router-link to="/admin-user" style="text-decoration: none">
                        <v-btn
                            depressed
                            text
                        >
                          <v-icon left class="mr-2">mdi-account-search-outline</v-icon>
                          Manage Users
                        </v-btn>
                      </router-link>
                      <v-divider class="my-3"></v-divider>
                    </div>

                    <router-link to="/user-profile" style="text-decoration: none">
                      <v-btn
                          text
                      >
                        <v-icon left class="mr-2">mdi-account-cog-outline</v-icon>
                        Account settings
                      </v-btn>
                    </router-link>
                    <v-divider class="my-3"></v-divider>
                    <v-btn
                        depressed
                        color="primary"
                        @click="logout()"
                    >
                      <v-icon left class="mr-2">mdi-logout-variant</v-icon>

                      Logout
                    </v-btn>
                  </div>
                </v-list-item-content>
              </v-card>
            </v-menu>
          </v-row>
        </v-container>
      </div>
    </v-toolbar>

    <v-navigation-drawer style="min-width: 28%; width: fit-content"
                         v-model="drawer"
                         absolute
                         bottom
                         temporary
                         light
                         height="100vh"
                         color="tertiary"
    >
      <v-list-item class="primary" style="height: 75px;" dark>
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

      <v-list class="d-flex flex-column"
              nav
              dense
              min-height="65%"
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
            activatable
            open-on-click
            hoverable
            transition
            return-object
            :items="categories"
            item-key="name"
            :active.sync="selectedCategory"
            @update:active="browseAuctionsByCategory()"
        >
          <!--          <template v-slot:label="{ item }">-->
          <!--            <a @click="updateSearchCategory(item)">{{ item.name }}</a>-->
          <!--          </template>-->
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
<!--      <div v-if="token">-->
<!--        <v-list class="d-flex flex-column"-->
<!--                nav-->
<!--                dense-->
<!--        >-->
<!--          <div v-if="role == 'Client'">-->
<!--            <router-link to="/bids" style="text-decoration: none">-->
<!--              <v-btn style="float: left"-->
<!--                     color="transparent"-->
<!--                     depressed-->
<!--                     class="ma-1"-->
<!--              >-->
<!--                <v-icon left class="mr-2">mdi-alarm-multiple</v-icon>-->
<!--                <span class="hidden-sm-and-down">Bids</span>-->
<!--              </v-btn>-->
<!--            </router-link>-->
<!--          </div>-->
<!--          <div v-if="role == 'Client'">-->
<!--            <hr>-->
<!--            <router-link to="/history" style="text-decoration: none">-->
<!--              <v-btn style="float: left"-->
<!--                     color="transparent"-->
<!--                     depressed-->
<!--                     class="ma-1"-->

<!--              >-->
<!--                <v-icon left class="mr-2">mdi-history</v-icon>-->
<!--                <span class="hidden-sm-and-down">History</span>-->
<!--              </v-btn>-->
<!--            </router-link>-->
<!--          </div>-->
<!--          <div v-if="role == 'Administrator' || role == 'Auctioneer'">-->
<!--            <router-link to="/admin-auction" style="text-decoration: none">-->
<!--              <v-btn style="float: left"-->
<!--                     color="transparent"-->
<!--                     depressed-->
<!--                     class="ma-1"-->

<!--              >-->
<!--                <v-icon left class="mr-2">mdi-store-search-outline</v-icon>-->
<!--                <span class="hidden-sm-and-down">Auctions</span>-->
<!--              </v-btn>-->
<!--            </router-link>-->
<!--          </div>-->
<!--          <div v-if="role == 'Administrator' || role == 'Manager'">-->
<!--            <hr>-->
<!--            <router-link to="/admin-user" style="text-decoration: none">-->
<!--              <v-btn style="float: left"-->
<!--                     color="transparent"-->
<!--                     depressed-->
<!--                     class="ma-1"-->

<!--              >-->
<!--                <v-icon left class="mr-2">mdi-account-search-outline</v-icon>-->
<!--                <span class="hidden-sm-and-down">Users</span>-->
<!--              </v-btn>-->
<!--            </router-link>-->
<!--          </div>-->
<!--          <hr>-->
<!--          <router-link to="/user-profile" style="text-decoration: none">-->
<!--            <v-btn style="float: left"-->
<!--                   color="transparent"-->
<!--                   depressed-->
<!--                   class="ma-1"-->
<!--            >-->
<!--              <v-icon left class="mr-2">mdi-account-cog-outline</v-icon>-->
<!--              <span class="hidden-sm-and-down">Account settings</span>-->
<!--            </v-btn>-->
<!--          </router-link>-->
<!--          <div>-->

<!--          </div>-->
<!--          <v-btn value="center" width="100%" style="text-decoration: none"-->
<!--                 color="info"-->
<!--                 depressed-->
<!--                 @click="logout()"-->
<!--          >-->
<!--            <v-icon left class="mr-2">mdi-logout-variant</v-icon>-->
<!--            <span class="hidden-sm-and-down">Logout</span>-->
<!--          </v-btn>-->
<!--        </v-list>-->
<!--      </div>-->

      <!--      <div class="mt-auto">-->
      <!--        <hr>-->
      <!--        -->
      <!--      </div>-->
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
    selectedMaster: [],
    categories: [],
    subcategories: [],
    user_roles: [],
    loggedUser: '',
    userImage: '',
    pageLoading: false,
    token: localStorage.getItem('token'),
    role: localStorage.getItem('user_roles'),
  }),

  created() {
    this.getCategories()
    if (localStorage.getItem('token')) {
      this.getLoggedUser()
    }
  },

  methods: {

    browseAuctionsByCategory() {
      // First, store the selected category in localstorage
      localStorage.setItem("search_category", this.selectedCategory[0].name);

      // Then, redirect to Auction Browse page, which will load data using the Category string we previously stored
      this.drawer = false
      if (this.$router.currentRoute.path !== '/auction-browse') {
        this.$router.push('/auction-browse')
      } else {
        this.$router.go(0)
      }

    },

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

    logout() {
      const config = {
        headers: {'Authorization': 'Bearer ' + JSON.parse(localStorage.getItem('token'))},
        key: "token"
      };
      // const bodyParameters = {
      //   key: "token"
      // };

      this.loading = true
      axios.post('/auth/logout', config)
          .then(response => {
                if (response) {
                  localStorage.clear();
                  this.$router.push('/home');
                  // todo: vidi moze li bez ovoga ikako (najbolje i bez onog istog smeca u login)
                  this.$router.go(0)
                  // console.log(response.data)
                  this.loading = false;
                }
              }
          )
          .catch(error => {
            console.log(error)
            localStorage.clear();
            this.$router.go(0)
            // console.log(JSON.parse(localStorage.getItem('token')).access_token);
            this.loading = false
            this.error = error.response.data.message;

          })
      // sessionStorage.removeItem("key");
      // localStorage.removeItem("user");
      // this.$router.push('/home');
    },

    getLoggedUser() {
      this.pageLoading = true
      axios.get('/auth/user')
          .then(response => {
            if (response.data) {
              this.loggedUser = response.data
              // this.username = response.data.username
              // this.email = response.data.email
              if (response.data.image) this.userImage = response.data.image
              this.pageLoading = false
            }
          })
          .catch(error => {
            console.log(error)
            this.pageLoading = false
          })
    },
  },

  watch: {

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