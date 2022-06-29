<template>
  <div>

      <v-row>
        <v-col cols="12" sm="12">
          <v-toolbar
                  dark
                  color="info"
          >
            <v-toolbar-title>Looking for something specific?</v-toolbar-title>
            <v-text-field
                    v-model="searchString"
                    class="mx-4"
                    clearable
                    @keyup.enter="filterByString"
            ></v-text-field>
            <v-btn
                    text
                    @click="filterByString()"
            >
              <v-icon class="pr-2">mdi-magnify</v-icon>
              <span>Search</span>
            </v-btn>
          </v-toolbar>
        </v-col>

      </v-row>
      <!--      <v-autocomplete style="width: 50%"-->
      <!--                      v-model="select"-->
      <!--                      :loading="loading"-->
      <!--                      :items="items"-->
      <!--                      :search-input.sync="search"-->
      <!--                      cache-items-->
      <!--                      class="mx-4"-->
      <!--                      flat-->
      <!--                      hide-no-data-->
      <!--                      hide-details-->
      <!--                      solo-inverted-->
      <!--      ></v-autocomplete>-->

    <v-app-bar dark color="#0d111a">
      <v-breadcrumbs
        :items="items"
        large
      >
        <template v-slot:divider>
          <v-icon>mdi-chevron-right</v-icon>
        </template>
      </v-breadcrumbs>
      <v-spacer></v-spacer>
      <v-btn class="mr-2"
        @click="sortByPrice('asc')">
        <v-icon>mdi-sort-reverse-variant</v-icon>
        <!--        <v-icon>mdi-arrow-up-thin</v-icon>-->
      </v-btn>
      <v-btn
        @click="sortByPrice('desc')">
        <v-icon>mdi-sort-variant</v-icon>
        <!--        <v-icon>mdi-arrow-down-thin</v-icon>-->
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
                            margin: 0 auto" src="../assets/no-items.svg"></v-img>
      <v-card-text class="text-lg-h2" style="color: black">No items found</v-card-text>
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
              <v-col cols="12" sm="12">
                <v-img
                  v-if="auction.images.length != 0"
                  style="background-color: #0d111a"
                  contain
                  :src="require('../../../back/public/' + auction.images[0].image)"
                  alt="Item image"
                  min-height="275px"
                  max-height="275px"
                ></v-img>
                <v-img
                  v-else
                  contain
                  src="../assets/no-image-item.svg"
                  alt="No item image"
                  min-height="275px"
                  max-height="275px"
                >
                </v-img>
              </v-col>
            </v-row>
            <hr>
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
            <v-card-actions v-if="role == 'Client'">
              <v-dialog
                transition="dialog-bottom-transition"
                max-width="75%"
                persistent
              >
                <template v-slot:activator="{ on, attrs }">
                  <v-row>
                    <v-col cols="12" sm="12">
                      <v-btn style="position: absolute; bottom: 8px; right: 8px"
                             color="primary"
                             v-bind="attrs"
                             v-on="on"
                      >
                        <v-icon left>mdi-cart</v-icon>
                        Buy
                      </v-btn>
                    </v-col>
                  </v-row>
                </template>
                <template v-slot:default="dialog">
                  <v-card>
                    <form @submit.prevent="postBid(auction.id)">
                      <v-card-text>
                        <v-row>
                          <v-col cols="12" sm="5" class="pt-8">
                            <v-carousel
                              v-if="auction.images.length != 0"
                              hide-delimiters style="height: 100%">
                              <v-carousel-item
                                style="background-color: #0d111a"
                                contain
                                v-for="(item,i) in auction.images"
                                :key="i"
                                :src="require('../../../back/public/' + item.image)"
                              ></v-carousel-item>
                            </v-carousel>
                            <v-carousel
                              v-else
                              hide-delimiters style="height: 100%">
                              <v-carousel-item
                                contain src="../assets/no-image-item.svg">
                                <!--                              <v-img-->
                                <!--                                src="../assets/no-image-item.svg"-->
                                <!--                              ></v-img>-->
                              </v-carousel-item>
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
                              </v-col>
                              <v-col cols="12" sm="1">
                                <v-card-actions style="justify-content: end">
                                  <v-btn
                                    class="my-2"
                                    small
                                    fab
                                    @click="clearForm(); dialog.value = false;"
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
                                <v-card-title v-if="auction.buyout != null" style="justify-content: start"
                                              color="primary">
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
                                <v-card-title v-else style="color: #529363; justify-content: start">
                                  {{ "No bids on this item!" }}
                                </v-card-title>
                              </v-col>
                            </v-row>
                            <validation-provider
                              v-slot="{ errors }"
                              name="bid"
                              rules="required|numeric"
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
                              >
                                <v-icon left class="mr-1">mdi-gavel</v-icon>
                                Place bid
                              </v-btn>
                              <v-btn large
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
                      <!--                    <v-row>-->
                      <v-col cols="12" sm="12">
                        <v-card-title style="position: absolute; bottom: 8px; right: 8px"
                                      class="text-sm-body-1">
                          {{ "Expires at: " + auction.end_datetime }}
                        </v-card-title>
                      </v-col>
                      <!--                    </v-row>-->
                    </form>
                  </v-card>
                </template>
              </v-dialog>
            </v-card-actions>
          </v-card>
        </v-row>
      </validation-observer>
    </div>

  </div>

</template>

<script>
import axios from "axios";
import {extend, ValidationObserver, ValidationProvider, setInteractionMode} from 'vee-validate'
import {required, numeric} from "vee-validate/dist/rules";
import Swal from "sweetalert2"

setInteractionMode('eager')

extend('required', {
  ...required,
  message: 'The {_field_} field is required.',
})

extend('numeric', {
  ...numeric,
  message: 'The {_field_} must be a number.',
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
    // This auction is no longer eligible for bids.
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

    postBid(auction_id) {
      this.$refs.form.validate().then(success => {
        if (success) {
          Swal.fire({
            title: 'Are you sure you want to bid on this item?',
            // text: "You won't be able to revert this!",
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#605290',
            cancelButtonColor: '#819fC9',
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
                    // console.log(error.response.data.message)
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
        confirmButtonColor: '#605290',
        cancelButtonColor: '#819fC9',
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
    // console.log(this.category)
    if (this.category == '' || this.category == null) {
      this.$router.push('/pageNotFound')
    }
    if (window.localStorage.getItem('token')) {
      this.getLoggedUser()
    }
    this.showAuctions()

  },

  mounted() {
    document.title = 'Browse - Auction House'
  }

}

</script>


<style scoped>

</style>