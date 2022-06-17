<template>
  <div>
    <v-toolbar
      dark
      color="accent"
    >
      <v-toolbar-title>Looking for something specific?</v-toolbar-title>
      <v-autocomplete style="width: 50%"
                      v-model="select"
                      :loading="loading"
                      :items="items"
                      :search-input.sync="search"
                      cache-items
                      class="mx-4"
                      flat
                      hide-no-data
                      hide-details
                      solo-inverted
      ></v-autocomplete>
      <v-btn icon>
        <v-icon>mdi-magnify</v-icon>
      </v-btn>
    </v-toolbar>
    <v-app-bar dark style="background-color: #1a202c">
      <v-breadcrumbs
        :items="items"
        large
      >
        <template v-slot:divider>
          <v-icon>mdi-chevron-right</v-icon>
        </template>
      </v-breadcrumbs>
      <v-spacer></v-spacer>
      <v-icon>mdi-sort-reverse-variant</v-icon>
      <v-icon class="mr-6">mdi-arrow-up-thin</v-icon>

      <v-icon>mdi-sort-variant</v-icon>
      <v-icon>mdi-arrow-down-thin</v-icon>

    </v-app-bar>

    <div v-if="dataLoading" class="mt-16">
      <v-progress-circular
        :size="100"
        :width="7"
        color="white"
        indeterminate
      ></v-progress-circular>
    </div>
    <div v-else-if="auctions == ''">
      <v-img dark class="mt-16" style="width: 18%; height: 18%;
                            margin: 0 auto" src="../assets/no-items.svg"></v-img>
      <v-card-text class="text-lg-h2" style="color: black">No items found</v-card-text>
    </div>
    <div v-else>
      <validation-observer>
        <v-row style="justify-content: start;" class="ma-2">
          <v-card
            v-for="auction in auctions" :key="auction.id"
            class="mx-1 card-body d-flex flex-column h-100"
            max-width="19%"
            min-width="19%"

            color="tertiary"
          >
            <v-row>
              <v-col cols="12" sm="12">
            <v-img
                    style="background-color: #1a202c"
                    contain
              :src="require('../../../back/public/' + auction.images[0].image)"
              min-height="275px"
              max-height="275px"

            ></v-img>
              </v-col>
            </v-row>
            <v-row>
              <v-col cols="12" sm="12">
                <v-card-title style="word-break: normal; justify-content: start" class="text-sm-body-1">
                  {{ auction.title }}
                </v-card-title>
              </v-col>
            </v-row>
            <hr>
            <v-row>
              <v-col cols="12" sm="12">
                <v-card-title v-if="auction.bid != null" style="justify-content: start" class="text-sm-body-1">
                  {{ "Current bid: " + auction.bid.value + "€" }}
                </v-card-title>
                <v-card-title v-else style="color: #42b983; justify-content: start" class="text-sm-body-1">
                  {{ "No bids on this item!" }}
                </v-card-title>
                <v-card-title v-if="auction.buyout != null" style="justify-content: start" class="text-sm-body-1" color="primary">
                  {{ "Buyout price: " + auction.buyout + "€" }}
                </v-card-title>
                <v-card-title v-else style="justify-content: start">
                  NA
                </v-card-title>
              </v-col>
            </v-row>
            <br>
            <v-card-actions>
              <v-dialog
                transition="dialog-bottom-transition"
                max-width="75%"
              >
                <template v-slot:activator="{ on, attrs }">
                  <v-row>
                    <v-col cols="12" sm="12">
                    <v-btn v-if="role == 'Client'" style="position: absolute; bottom: 8px; right: 8px"
                           color="primary"
                           v-bind="attrs"
                           v-on="on"
                    ><v-icon left>mdi-cart</v-icon>Buy
                    </v-btn>
                    </v-col>
                  </v-row>
                </template>
                <template v-slot:default="dialog">
                  <v-card>
                    <v-card-text>
                      <v-row>
                        <v-col cols="12" sm="5" class="pt-8">
                          <v-carousel hide-delimiters style="height: 100%">
                            <v-carousel-item
                                    style="background-color: #1a202c"
                                    contain
                                    v-for="(item,i) in auction.images"
                                    :key="i"
                                    :src="require('../../../back/public/' + item.image)"
                            ></v-carousel-item>
                          </v-carousel>
                        </v-col>
                        <v-col cols="12" sm="7">
                            <v-row>
                              <v-col cols="12" sm="11">
                                <v-card-title class="text-lg-h5" style="justify-content: start">
                                  {{ auction.item.title }}
                                  <v-card-title class="text-sm-body-1; " style="justify-content: start">
                                    <v-divider class="mr-4" vertical></v-divider>

                                    {{ auction.item.condition }}
                                  </v-card-title>

                                  <v-btn class="mx-2"
                                          large
                                          icon
                                          @click="show1 = !show1"
                                  >
                                    <v-icon>{{ show1 ? 'mdi-chevron-up' : 'mdi-chevron-down' }}</v-icon>
                                  </v-btn>
                                </v-card-title>
                                <v-row>

                                </v-row>

                              </v-col>
                              <v-col cols="12" sm="1">
                                <v-card-actions style="justify-content: center">
                                  <v-btn
                                          class="my-2"
                                          small
                                          fab
                                          @click="dialog.value = false"
                                  >
                                    <v-icon>mdi-close</v-icon>
                                  </v-btn>
                                </v-card-actions>
                              </v-col>
                                <v-divider></v-divider>
                            </v-row>
                          <v-row>
                          </v-row>
                            <v-expand-transition>
                              <div v-show="show1">
                                <v-card-title style="word-break: normal; text-align: start" class="text-sm-body-2">
                                  {{ auction.item.description }}
                                </v-card-title>
                                <v-divider></v-divider>
                              </div>
                            </v-expand-transition>
                            <v-row>
                              <v-col cols="12" sm="12">
                                <v-card-title style="word-break: normal; justify-content: start" class="text-sm-body-1">
                                  {{ auction.title }}
                                </v-card-title>
                                <v-divider></v-divider>
                                <v-card-title v-if="auction.buyout != null" style="justify-content: start" color="primary">
                                  {{ "Buyout price: " + auction.buyout + "€" }}
                                </v-card-title>
                                <v-card-title v-else style="justify-content: start">
                                  NA
                                </v-card-title>
                              </v-col>
                            </v-row>
                            <v-row>
                              <v-col cols="12" sm="12">
                                <v-card-title v-if="auction.bid != null" style="justify-content: start" color="primary">
                                  {{ "Current bid: " + auction.bid.value + "€" }}
                                </v-card-title>
                                <v-card-title v-else style="color: #42b983; justify-content: start">
                                  {{ "No bids on this item!" }}
                                </v-card-title>
                              </v-col>
                            </v-row>
                            <validation-provider
                                    v-slot="{ errors }"
                                    name="bidInput"
                                    clearable
                            >
                              <v-row>
                                <v-card-title style="word-break: normal" class="mb-4">
                                  Your bid:
                                </v-card-title>
                                <v-text-field
                                        v-model="bidInput"
                                        :error-messages="errors"
                                        hint="*Must be at least 3% higher than the current value."
                                        clearable
                                >
                                </v-text-field>
                              </v-row>
                            </validation-provider>
                          <v-row style="justify-content: center" class="mt-2">
                              <v-btn large
                                     type="submit"
                                     color="primary"
                                     class="mr-4"
                                     @click="postBid(auction.id)"
                              >
                                <v-icon left class="mr-1">mdi-gavel</v-icon>
                                Place bid
                              </v-btn>
                              <v-btn large
                                     type="submit"
                                     color="success"
                                     @click="buyout(auction.id)"
                              >
                                <v-icon left class="mr-1">mdi-cash-multiple</v-icon>
                                Buyout
                              </v-btn>
                          </v-row>
                        </v-col>
                      </v-row>
                    </v-card-text>
                  </v-card>
                </template>
              </v-dialog>
            </v-card-actions>
            <!--        <v-expand-transition>-->
            <!--          <div v-show="show">-->
            <!--            <v-divider></v-divider>-->
            <!--            <v-card-text>-->
            <!--              Item description placeholder xd-->
            <!--            </v-card-text>-->
            <!--          </div>-->
            <!--        </v-expand-transition>-->
          </v-card>
        </v-row>

      </validation-observer>
    </div>

  </div>

</template>

<script>
import axios from "axios";
import {extend, ValidationObserver, ValidationProvider, setInteractionMode} from 'vee-validate'
import {digits, max, regex, required} from "vee-validate/dist/rules";

setInteractionMode('eager')

extend('digits', {
  ...digits,
})

extend('required', {
  ...required,
  // message: '{_field_} can not be empty',
  message: 'Required.'
})

extend('max', {
  ...max,
  message: '{_field_} may not be greater than {length} characters',
})

extend('regex', {
  ...regex,
  message: '{_field_} {_value_} does not match {regex}',
})

export default {
  name: "AuctionBrowse",

  components: {
    ValidationProvider,
    ValidationObserver,
  },

  data() {
    return {
      loading: false,
      items: [
        {
          text: 'Home',
          disabled: false,
        },
        {
          text: 'Main category placeholder',
          disabled: true,
        },
        {
          text: localStorage.getItem('search_category'),
          disabled: true,
        },
      ],
      search: null,
      select: null,
      states: [],
      show: false,
      show1: false,
      auctions: [],
      bidInput: '',
      dataLoading: false,
      category: "",
      conditions: [],
      role: localStorage.getItem('user_roles'),
      username: '',
      auction_id: ''
    }
  },

  watch: {
    search(val) {
      val && val !== this.select && this.querySelections(val)
    },
  },

  methods: {
    querySelections(v) {
      this.loading = true
      setTimeout(() => {
        this.items = this.states.filter(e => {
          return (e || '').toLowerCase().indexOf((v || '').toLowerCase()) > -1
        })
        this.loading = false
      }, 500)
    },

    showAuction() {
      this.dataLoading = true
      this.auctions = []
      axios.post('/filtered_auctions', {
        category: this.category
      })
        .then(response => {
          if (response.data) {
            this.auctions = response.data.auctions
            this.conditions = response.data.conditions
            // for (let i = 0; i < response.data.length; i++) {
            //   // console.log(response.data)//   this.auctions = response.data
            // }
          }
          this.dataLoading = false
        })
        .catch(error => {
          console.log(error)
          this.dataLoading = false
        })
    },

    getAuctionId() {
      this.dataLoading = true
      axios.get('/auction/', {
        category: this.category
      })
        .then(response => {
          if (response.data) {
            console.log(response.data.auctions)
            this.auctions = response.data.auctions
            this.auction_id = response.data.id
            this.conditions = response.data.conditions
            // for (let i = 0; i < response.data.length; i++) {
            //   // console.log(response.data)//   this.auctions = response.data
            // }
          }
          this.dataLoading = false
        })
        .catch(error => {
          console.log(error)
          this.dataLoading = false
        })
    },

    getLoggedUser() {
      this.pageLoading = true
      axios.get('/auth/user')
        .then(response => {
          if (response.data) {
            this.username = response.data.username
            this.pageLoading = false
          }
        })
        .catch(error => {
          console.log(error)
          this.pageLoading = false
        })
    },
    buyout(auction_id) {
      this.loading = true
      axios.post('/history', {
        auction_id: auction_id,
      }).then(response => {
        if (response.data) {
          this.loading = false
        }
      }).catch(error => {
        console.log(auction_id)
        console.log(error)
        this.loading = false
      })
    },
    // todo: objasni milosu komunikacije i for loop i komponente
    postBid(auction_id) {
      this.loading = true
      axios.post('/bid', {
        value: this.bidInput,
        auction_id: auction_id
      })
        .then(response => {
          if (response.data) {

            this.loading = false
          }
        })
        .catch(error => {
          console.log(auction_id)
          console.log(error)
          this.loading = false
        })
    },
  },

  created() {
    // Local Storage variable used to update the category a user searches by from Menu (Navbar.vue)
    this.category = localStorage.getItem('search_category')
    console.log(this.category)
    if (this.category == '' || this.category == null) {
      this.$router.push('/pageNotFound')
    }
    if (window.localStorage.getItem('token')) {
      this.getLoggedUser()
    }
    this.showAuction();
  },

  mounted() {
    document.title = 'Browse - Auction House'
  }

}

</script>


<style scoped>

</style>