<template>
  <div>
    <v-row>
      <v-col cols="12">
        <v-toolbar
          dark
          color="info"
        >
          <v-toolbar-title>Search current category by keyword</v-toolbar-title>
          <v-text-field
            v-model="searchString"
            class="mt-4 ml-5 mr-5"
            clearable
            @keyup.enter="filterByString"
          ></v-text-field>
          <v-btn
            text
            @click="filterByString()"
            outlined
          >
            <v-icon class="pr-2">mdi-magnify</v-icon>
            <span>Search</span>
          </v-btn>
        </v-toolbar>
      </v-col>

    </v-row>
    <v-app-bar dark color="primary">
      <v-breadcrumbs
        :items="items"
        large
      >
        <template v-slot:divider>
          <v-icon>mdi-chevron-right</v-icon>
        </template>
      </v-breadcrumbs>
      <v-spacer></v-spacer>
      <v-btn
        color="primary"
        class="mr-2 darken-2"
        @click="sortByPrice('asc')">
        <v-icon>mdi-sort-reverse-variant</v-icon>
      </v-btn>
      <v-btn
        color="primary"
        class="mr-2 darken-2"
        @click="sortByPrice('desc')">
        <v-icon>mdi-sort-variant</v-icon>
      </v-btn>

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
                            margin: 0 auto" src="../assets/auction/no-items-found.svg"></v-img>
      <v-card-text class="text-lg-h2" style="color: black">No items found</v-card-text>
      <v-card-text class="text-sm-body-1" style="color: black">Try searching other categories</v-card-text>
    </div>
    <div v-else>
      <validation-observer ref="form">
        <v-row style="justify-content: start;" class="ma-1">
          <v-card
            v-for="auction in auctions" :key="auction.id"
            class="mx-1 my-1 card-body d-flex flex-column h-100"
            max-width="19%"
            min-width="19%"
            color="tertiary"
          >
            <v-row>
              <v-col cols="12">
                <v-img
                  v-if="auction.images.length != 0"
                  style="background-color: #0d111a"
                  contain
                  :lazy-src="'/api/item/'+ auction.images[0].image"
                  :src="'/api/item/' + auction.images[0].image"
                  min-height="275px"
                  max-height="275px"
                ></v-img>
                <v-img
                  v-else
                  contain
                  src="../assets/auction/no-image-item.svg"
                  alt="No item image"
                  min-height="275px"
                  max-height="275px"
                >
                </v-img>
              </v-col>
            </v-row>
            <hr>
            <v-row>
              <v-col cols="12">
                <v-card-title style="word-break: normal; justify-content: start" class="text-sm-body-1">
                  {{ auction.title }}
                </v-card-title>
              </v-col>
            </v-row>
            <hr>
            <v-row>
              <v-col cols="12">
                <v-card-title v-if="auction.bid != null" style="justify-content: start" class="text-sm-body-1">
                  {{ "Current bid: " + auction.bid.value + "€" }}
                </v-card-title>
                <v-card-title v-else style="color: #42b983; justify-content: start" class="text-lg-h6">
                  {{ "No bids on this item yet!" }}
                </v-card-title>
                <v-card-title v-if="auction.buyout != null" style="justify-content: start" class="text-sm-body-1"
                              color="primary">
                  {{ "Buyout price: " + auction.buyout + "€" }}
                </v-card-title>
                <v-card-title v-else style="justify-content: start">
                  NA
                </v-card-title>
              </v-col>
            </v-row>
            <br>
            <v-dialog
              transition="dialog-bottom-transition"
              max-width="75%"
              persistent
            >
              <template v-slot:activator="{ on, attrs }">
                <v-row>
                  <v-col cols="12">
                    <v-btn
                      style="position: absolute; bottom: 8px; right: 8px"
                      color="primary"
                      v-bind="attrs"
                      v-on="on"
                    >
                      <v-icon left>mdi-cube-scan</v-icon>
                      View
                    </v-btn>
                  </v-col>
                </v-row>
              </template>
              <template v-slot:default="dialog">
                <v-card>
                  <v-card-title class="justify-end">
                    <v-btn
                      color="secondary"
                      @click="clearForm(); dialog.value = false;"
                    >
                      <v-icon>mdi-close</v-icon>
                    </v-btn>
                  </v-card-title>
                  <form @submit.prevent="postBid(auction.id)">
                    <v-card-text>
                      <v-row>
                        <v-col cols="5" class="pt-8">
                          <v-carousel
                            v-if="auction.images.length != 0"
                            hide-delimiters
                            style="height: 100%">
                            <v-carousel-item
                              contain
                              v-for="(item,i) in auction.images"
                              :key="i"
                              :lazy-src="'/api/item/'+ item.image"
                              :src="'/api/item/' + item.image"
                            ></v-carousel-item>
                          </v-carousel>
                          <v-carousel
                            v-else
                            hide-delimiters
                            style="height: 100%">
                            <v-carousel-item
                              contain
                              src="../assets/auction/no-image-item.svg">
                            </v-carousel-item>
                          </v-carousel>
                        </v-col>
                        <v-col cols="7">
                          <v-row>
                            <v-col cols="11">
                              <v-card-title class="text-lg-h5" style="justify-content: start">
                                {{ auction.item.title }}
                              </v-card-title>
                              <v-card-title>
                                {{ auction.item.condition }}
                                <v-divider class="ml-4 mr-0" vertical></v-divider>
                                <v-btn
                                  class=""
                                  large
                                  icon
                                  @click="show1 = !show1"
                                >
                                  <v-icon>{{ show1 ? 'mdi-chevron-up' : 'mdi-chevron-down' }}</v-icon>
                                </v-btn>
                              </v-card-title>
                            </v-col>
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
                            <v-col cols="12">
                              <v-card-title style="word-break: normal; justify-content: start" class="text-sm-body-1">
                                AUCTION: {{ auction.title }}
                              </v-card-title>
                              <v-divider></v-divider>
                              <v-card-title
                                v-if="auction.buyout != null"
                                style="justify-content: start"
                                color="primary">
                                <v-card-text class="text-lg-h5">
                                  {{ "Buyout price: " + auction.buyout + "€" }}
                                </v-card-text>
                                <v-card-text v-if="auction.bid == null" class="text-lg-h6">
                                  {{ "Minimum required bid value: " + auction.min_bid_value + "€" }}
                                </v-card-text>
                              </v-card-title>
                              <v-card-title v-else style="justify-content: start">
                                NA
                              </v-card-title>
                            </v-col>
                          </v-row>
                          <v-row>
                            <v-col cols="12">
                              <v-card-text class="text-lg-h6" v-if="auction.bid != null" style="justify-content: start"
                                           color="primary">
                                {{ "Current bid: " + auction.bid.value + "€" }}
                              </v-card-text>
                              <v-card-text class="text-lg-h6" v-else style="color: #529363; justify-content: start">
                                {{ "No bids on this item!" }}
                              </v-card-text>
                            </v-col>
                          </v-row>
                          <validation-provider
                            v-slot="{ errors }"
                            name="bid"
                            :rules=bidRules(auction)
                            clearable
                          >
                            <v-row>
                              <v-card-title style="word-break: normal" class="mb-4">
                                Your bid:
                              </v-card-title>
                              <v-text-field
                                :disabled="role != 'Client'"
                                class="mr-3"
                                v-model="bidInput"
                                :error-messages="errors"
                                hint="*Must be at least 2% higher than the current highest bid value."
                                clearable
                                :placeholder="role !== 'Client' ? 'Please login as a Client to place bids.': 'Place your bid price.' "
                              >
                              </v-text-field>
                            </v-row>
                          </validation-provider>
                          <v-row style="justify-content: center" class="mt-2">
                            <v-btn
                              :disabled="role != 'Client'"
                              large
                              type="submit"
                              color="accent"
                              class="mr-4"
                            >
                              <v-icon left class="mr-1">mdi-gavel</v-icon>
                              Place bid
                            </v-btn>
                            <v-btn
                              :disabled="role != 'Client'"
                              large
                              color="primary"
                              @click="buyout(auction.id)"
                            >
                              <v-icon left class="mr-1">mdi-cash-multiple</v-icon>
                              Buyout
                            </v-btn>
                          </v-row>
                          <v-col cols="12">
                            <v-card-text
                              class="text-sm-body-1"
                            >
                              {{ "Expires at: " + auction.end_datetime }}
                            </v-card-text>
                          </v-col>
                        </v-col>
                      </v-row>
                    </v-card-text>
                  </form>
                </v-card>
              </template>
            </v-dialog>
          </v-card>
        </v-row>
      </validation-observer>
    </div>

  </div>

</template>

<script>
import axios from "axios";
import {extend, ValidationObserver, ValidationProvider, setInteractionMode} from 'vee-validate'
import {required, numeric, double, min_value} from "vee-validate/dist/rules";
import Swal from "sweetalert2"

setInteractionMode('eager')

extend('min_value', {
  ...min_value,
  message: 'The {_field_} must at least match the minimum required bid value.'
})

extend('min_value_bid', {
  ...min_value,
  message: 'The {_field_} must be at least 2% higher than the current highest bid value.'
})

extend('required', {
  ...required,
  message: 'The {_field_} field is required.',
})

extend('numeric', {
  ...numeric,
  message: 'The {_field_} must be a number.',
})

extend('double', {
  ...double,
  message: 'The {_field_} must be a number and may contain decimals.',
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
          text: 'Chosen category',
          disabled: true
        },
        // {
        //   text: 'Home',
        //   disabled: false,
        // },
        // {
        //   text: 'Main category placeholder',
        //   disabled: true,
        // },
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
      initialAuctions: [],
      auctions: [],
      bidInput: '',
      dataLoading: false,
      category: "",
      conditions: [],
      role: localStorage.getItem('user_roles'),
      username: '',
      auction_id: '',
      modal: false,
      searchString: '',
    }
  },

  watch: {
    search(val) {
      val && val !== this.select && this.querySelections(val)
    },
  },

  methods: {

    bidRules(auction) {
      if (auction.bid == null) return "required|double|min_value:" + auction.min_bid_value
      return "required|double|min_value_bid:" + (auction.bid.value * 1.02)
    },

    sortByPrice(type) {
      if (type == 'asc') {
        this.auctions = this.auctions.sort((a, b) => a.buyout - b.buyout)
      } else {
        this.auctions = this.auctions.sort((a, b) => b.buyout - a.buyout)
      }
    },

    filterByString() {
      // When we reset the array in this manner there will always be data to be filtered
      this.auctions = this.initialAuctions
      if (this.searchString == '' || this.searchString == null) return
      let filteredData = []
      for (let i = 0; i < this.auctions.length; i++) {
        if (this.auctions[i].item.title.toLowerCase().includes(this.searchString.toLowerCase())) filteredData.push(this.auctions[i])
      }
      this.auctions = filteredData
    },

    querySelections(v) {
      this.loading = true
      setTimeout(() => {
        this.items = this.states.filter(e => {
          return (e || '').toLowerCase().indexOf((v || '').toLowerCase()) > -1
        })
        this.loading = false
      }, 500)
    },

    showAuctions() {
      this.dataLoading = true
      this.auctions = []
      axios.post('/filtered_auctions', {
        category: this.category
      })
        .then(response => {
          if (response.data) {
            this.auctions = response.data.auctions
            this.initialAuctions = response.data.auctions
            this.conditions = response.data.conditions
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

    postBid(auction_id) {
      this.$refs.form.validate().then(success => {
        if (success) {
          Swal.fire({
            title: 'Are you sure you want to bid on this item?',
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#915A64',
            cancelButtonColor: '#909C6B',
            confirmButtonText: "Yes, I'm sure!"
          }).then((result) => {
            if (result.isConfirmed) {
              this.loading = true
              axios.post('/bid', {
                value: this.bidInput,
                auction_id: auction_id
              }).then(response => {
                if (response.data) {
                  Swal.fire(
                    'Congratulations!',
                    "You can take a look at your current bids on the 'Bids' page.",
                    'success'
                  )
                  this.loading = false
                  this.modal = false
                  this.showAuctions()
                  this.clearForm()
                }
              })
                .catch(error => {
                  if (error.response.status == 400) {
                    Swal.fire({
                      icon: 'error',
                      title: error.response.data.message,
                    })
                    console.log(error)
                    this.loading = false
                  } else if (error.response.status == 404 || error.response.status == 410) {
                    Swal.fire({
                      icon: 'error',
                      title: error.response.data.message,
                    })
                    console.log(error)
                    this.loading = false
                    this.modal = false
                    this.showAuctions()
                    this.clearForm()
                  } else if (error.response.status == 403) {
                    Swal.fire({
                      icon: 'error',
                      title: error.response.data.message,
                    })
                    console.log(error)
                    this.loading = false
                    this.modal = false
                    this.showAuctions()
                    this.clearForm()
                  }
                  console.log(error)
                  this.loading = false
                })
            }
          })
        }
      })
    },

    buyout(auction_id) {
      Swal.fire({
        title: 'Are you sure you want to buy this item?',
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#915A64',
        cancelButtonColor: '#909C6B',
        confirmButtonText: "Yes, I'm sure!"
      }).then((result) => {
        if (result.isConfirmed) {
          this.loading = true
          axios.post('/history', {
            auction_id: auction_id,
          }).then(response => {
            if (response.data) {
              Swal.fire(
                'Success!',
                "You can review your bought items on the 'History' page.",
                'success'
              )
              this.loading = false
              this.modal = false
              this.showAuctions()
              this.clearForm()
            }
          }).catch(error => {
            if (error.response.status == 404 || error.response.status == 410) {
              Swal.fire({
                icon: 'error',
                text: 'This auction no longer exists.',
              })
              console.log(error)
              this.loading = false
              this.modal = false
              this.showAuctions()
              this.clearForm()
            }
          })
        }
      })
    },

    clearForm() {
      this.bidInput = null;
      this.$refs.form.reset();
    }
  },

  created() {
    // Local Storage variable used to update the category a user searches by from Menu (Navbar.vue)
    this.category = localStorage.getItem('search_category')
    if (this.category == '' || this.category == null) {
      this.$router.push('/pageNotFound')
    }
    if (window.localStorage.getItem('token')) {
      this.getLoggedUser()
    }
    this.showAuctions()

  },

  mounted() {
  }

}

</script>


<style scoped>

</style>