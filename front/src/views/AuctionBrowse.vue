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
    <validation-observer>
      <v-row style="justify-content: center">
        <v-card
            v-for="auction in auctions" :key="auction.id"
            class="ma-5 card-body d-flex flex-column h-100"
            max-width="22%"
            color="tertiary"
        >
          <v-img
              src="https://cdn.vuetifyjs.com/images/cards/sunshine.jpg"
              class="h-50"
              height="200px"
          ></v-img>

          <v-card-title style="word-break: normal">
            {{ auction.title }}
          </v-card-title>
          <br>
          <v-card-subtitle>
            {{ auction.item.title }}
          </v-card-subtitle>
          <hr>
          <v-card-text class="text-sm-h6">
            <div color="primary" v-if="auction.bid != null"> {{ "Current bid: " + auction.bid.value + "€" }}</div>
            <div style="color: #42b983" v-else> {{ "No bids on this item!" }}</div>
          </v-card-text>

          <v-card-actions>
            <!--          <v-btn-->
            <!--              icon-->
            <!--              @click="show = !show"-->
            <!--          >-->
            <!--            <v-icon>{{ show ? 'mdi-chevron-up' : 'mdi-chevron-down' }}</v-icon>-->
            <!--          </v-btn>-->
<!--            ⠀⠀⠀⠀⠀⢰⡿⠋⠁⠀⠀⠈⠉⠙⠻⣷⣄⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀-->
<!--            ⠀⠀⠀⠀⢀⣿⠇⠀⢀⣴⣶⡾⠿⠿⠿⢿⣿⣦⡀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀-->
<!--            ⠀⠀⣀⣀⣸⡿⠀⠀⢸⣿⣇⠀⠀⠀⠀⠀⠀⠙⣷⡀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀-->
<!--            ⠀⣾⡟⠛⣿⡇⠀⠀⢸⣿⣿⣷⣤⣤⣤⣤⣶⣶⣿⠇⠀⠀⠀⠀⠀⠀⠀⣀⠀⠀-->
<!--            ⢀⣿⠀⢀⣿⡇⠀⠀⠀⠻⢿⣿⣿⣿⣿⣿⠿⣿⡏⠀⠀⠀⠀⢴⣶⣶⣿⣿⣿⣆-->
<!--            ⢸⣿⠀⢸⣿⡇⠀⠀⠀⠀⠀⠈⠉⠁⠀⠀⠀⣿⡇⣀⣠⣴⣾⣮⣝⠿⠿⠿⣻⡟-->
<!--            ⢸⣿⠀⠘⣿⡇⠀⠀⠀⠀⠀⠀⠀⣠⣶⣾⣿⣿⣿⣿⣿⣿⣿⣿⣿⣿⡿⠁⠉⠀-->
<!--            ⠸⣿⠀⠀⣿⡇⠀⠀⠀⠀⠀⣠⣾⣿⣿⣿⣿⣿⣿⣿⣿⣿⣿⡿⠟⠉⠀⠀⠀⠀-->
<!--            ⠀⠻⣷⣶⣿⣇⠀⠀⠀⢠⣼⣿⣿⣿⣿⣿⣿⣿⣛⣛⣻⠉⠁⠀⠀⠀⠀⠀⠀⠀-->
<!--            ⠀⠀⠀⠀⢸⣿⠀⠀⠀⢸⣿⣿⣿⣿⣿⣿⣿⣿⣿⣿⣿⡇⠀⠀⠀⠀⠀⠀⠀⠀-->
<!--            ⠀⠀⠀⠀⢸⣿⣀⣀⣀⣼⡿⢿⣿⣿⣿⣿⣿⡿⣿⣿⡿-->
            <v-spacer></v-spacer>
            <v-dialog
                transition="dialog-bottom-transition"
                max-width="35%"
            >
              <template v-slot:activator="{ on, attrs }">
                <v-btn class="mx-1"
                       color="primary"
                       v-bind="attrs"
                       v-on="on"
                >Bid
                </v-btn>
                <v-card-text class="v-text-field--full-width">
                  <div v-if="auction.buyout != null"> {{ "Buyout price: " + auction.buyout + "€" }}</div>
                  <div v-else> {{ "N/A" }}</div>
                </v-card-text>
              </template>
              <template v-slot:default="dialog">
                <v-card>
                  <v-card-text>
                    <div class="pa-4">
                      <v-carousel hide-delimiters style="height: 300px">
                        <v-carousel-item
                            v-for="(item,i) in pictures"
                            :key="i"
                            :src="item.src"
                        ></v-carousel-item>
                      </v-carousel>
                      <div>
                        <v-card-text class="text-sm-h5">
                          {{ auction.item.title }}
                        </v-card-text>
                        <hr>
                      </div>
                      <v-card-text class="text-sm-h6" style="word-break: normal; justify-content: start">
                        <div v-if="auction.bid != null"> {{ "Current bid: " + auction.bid.value + "€"}}</div>
                        <div style="color: #42b983" v-else> {{ "No bids on this item!" }}</div>
                      </v-card-text>
                      <v-card-text class="text-sm-h6" style="word-break: normal">
                        <div v-if="auction.buyout != null"> {{ "Buyout price: " + auction.buyout + "€" }}</div>
                        <div v-else> {{ "N/A" }}</div>
                      </v-card-text>
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
                    </div>
                    <div>
                        <v-btn large
                               type="submit"
                               color="primary"
                               class="mr-4"
                        >
                          <v-icon left class="mr-1">mdi-gavel</v-icon>
                          Place bid
                        </v-btn>
                        <v-btn large
                               type="submit"
                               color="success"
                        >
                          <v-icon left class="mr-1">mdi-cash-multiple</v-icon>
                          Buyout
                        </v-btn>

                    </div>

                  </v-card-text>
                  <v-card-actions class="justify-end">
                    <v-btn
                        icon
                        @click="show1 = !show1"
                    >
                      <v-icon>{{ show1 ? 'mdi-chevron-right' : 'mdi-chevron-left' }}</v-icon>
                    </v-btn>
                    <v-spacer></v-spacer>
                    <v-btn
                        text
                        @click="dialog.value = false"
                    >Close
                    </v-btn>
                  </v-card-actions>
                  <v-expand-transition>
                    <div v-show="show1">
                      <v-divider></v-divider>
                      <v-card-text>
                        {{ auction.item.description }}
                      </v-card-text>
                    </div>
                  </v-expand-transition>
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

</template>

<script>
import axios from "axios";
import {extend, ValidationObserver, ValidationProvider, setInteractionMode} from 'vee-validate'
import {digits, max, regex, required} from "vee-validate/dist/rules";

setInteractionMode('eager')

extend('digits', {
  ...digits,
  // message: '{_field_} needs to be {length} digits. ({_value_})',


  //                                           ⠀⠀⠀⠀⠀⢀⣴⡾⠿⠿⠿⠿⢶⣦⣄⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀
  //                                           ⠀⠀⠀⠀⢠⣿⠁⠀⠀⠀⣀⣀⣀⣈⣻⣷⡄⠀⠀⠀⠀⠀⠀⠀⠀
  //                                           ⠀⠀⠀⠀⣾⡇⠀⠀⣾⣟⠛⠋⠉⠉⠙⠛⢷⣄⠀⠀⠀⠀⠀⠀⠀
  //                                           ⢀⣤⣴⣶⣿⠀⠀⢸⣿⣿⣧⠀⠀⠀⠀⢀⣀⢹⡆⠀⠀⠀⠀⠀⠀
  //                                           ⢸⡏⠀⢸⣿⠀⠀⠀⢿⣿⣿⣷⣶⣶⣿⣿⣿⣿⠃⠀⠀⠀⠀⠀⠀
  //                                           ⣼⡇⠀⢸⣿⠀⠀⠀⠈⠻⠿⣿⣿⠿⠿⠛⢻⡇⠀⠀⠀⠀⠀⠀⠀
  //                                           ⣿⡇⠀⢸⣿⠀⠀⠀⠀⠀⠀⠀⠀⠀⣀⣤⣼⣷⣶⣶⣶⣤⡀⠀⠀
  //                                           ⣿⡇⠀⢸⣿⠀⠀⠀⠀⠀⠀⣀⣴⣾⣿⣿⣿⣿⣿⣿⣿⣿⣿⣦⡀
  //                                           ⢻⡇⠀⢸⣿⠀⠀⠀⠀⢀⣾⣿⣿⣿⣿⣿⣿⣿⡿⠿⣿⣿⣿⣿⡇
  //                                           ⠈⠻⠷⠾⣿⠀⠀⠀⠀⣾⣿⣿⣿⣿⣿⣿⣿⣿⡇⠀⢸⣿⣿⣿⣇
  //                                           ⠀⠀⠀⠀⣿⠀⠀⠀⠀⣿⣿⣿⣿⣿⣿⣿⣿⣿⠃⠀⢸⣿⣿⣿⡿
  //                                           ⠀⠀⠀⠀⢿⣧⣀⣠⣴⡿⠙⠛⠿⠿⠿⠿⠉⠀⠀⢠⣿⣿⣿⣿⠇
  //                                           ⠀⠀⠀⠀⠀⢈⣩⣭⣥⣤⣤⣤⣤⣤⣤⣤⣤⣤⣶⣿⣿⣿⣿⠏⠀
  //                                           ⠀⠀⠀⠀⣴⣿⣿⣿⣿⣿⣿⣿⣿⣿⣿⣿⣿⣿⣿⣿⣿⡿⠋⠀⠀
  //                                           ⠀⠀⠀⢸⣿⣿⣿⡟⠛⠛⠛⠛⠛⠛⠛⠛⠛⠛⠛⠋⠁⠀⠀⠀⠀
  //                                           ⠀⠀⠀⢸⣿⣿⣿⣷⣄⣀⣀⣀⣀⣀⣀⣀⣀⣀⡀⠀⠀⠀⠀⠀⠀
  //                                           ⠀⠀⠀⠀⠻⣿⣿⣿⣿⣿⣿⣿⣿⣿⣿⣿⣿⣿⣿⣷⣦⡀⠀⠀⠀
  //                                           ⠀⠀⠀⠀⠀⠈⠛⠿⠿⣿⣿⣿⣿⣿⠿⠿⢿⣿⣿⣿⣿⣿⡄⠀⠀
  //                                           ⠀⠀⠀⠀⠀⠀⢀⣀⣀⣀⡀⠀⠀⠀⠀⠀⠀⢀⣹⣿⣿⣿⡇⠀⠀
  //                                           ⠀⠀⠀⠀⠀⢰⣿⣿⣿⣿⣿⣿⣿⣿⣿⣿⣿⣿⣿⣿⣿⡿⠁⠀⠀
  //                                           ⠀⠀⠀⠀⣼⣿⣿⣿⣿⣿⣿⣿⣿⣿⣿⣿⣿⣿⣿⠿⠛⠁⠀⠀⠀
  //                                           ⠀⠀⠀⠀⣿⣿⣿⣿⠁⠀⠀⠀⠀⠀⠉⠉⠁⢤⣤⣤⣤⣤⣤⣤⡀
  //                                           ⠀⠀⠀⠀⢿⣿⣿⣿⣷⣶⣶⣶⣶⣾⣿⣿⣿⣆⢻⣿⣿⣿⣿⣿⡇
  //                                           ⠀⠀⠀⠀⠈⠻⣿⣿⣿⣿⣿⣿⣿⣿⣿⣿⣿⣿⣦⠻⣿⣿⣿⡿⠁
  //                                           ⠀⠀⠀⠀⠀⠀⠈⠙⠛⠛⠛⠛⠛⠛⠛⠛⠛⠛⠉⠀⠙⠛⠉⠀⠀
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
      pictures: [
        {
          src: 'https://cdn.vuetifyjs.com/images/carousel/squirrel.jpg',
        },
        {
          src: 'https://cdn.vuetifyjs.com/images/carousel/sky.jpg',
        },
        {
          src: 'https://cdn.vuetifyjs.com/images/carousel/bird.jpg',
        },
        {
          src: 'https://cdn.vuetifyjs.com/images/carousel/planet.jpg',
        },
      ],

      items: [{
        text: 'Home',
        disabled: false,
        href: '/home',
      },
        {
          text: 'Main category placeholder',
          disabled: true,
          href: 'breadcrumbs_link_1',
        },
        {
          text: 'Sub category placeholder',
          disabled: true,
          href: 'breadcrumbs_link_2',
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
      axios.get('/auctions')
          .then(response => {
            if (response.data) {
              this.auctions = response.data
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
  },

  created() {
    this.showAuction();
  },
  mounted() {
    document.title = 'Browse - Auction House'
  }
}

</script>


<style scoped>

</style>