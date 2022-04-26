<template>
  <v-container>
    <v-dialog
        v-model="showDialog"
        max-width="95%"
        persistent
    >
      <template v-slot:default="dialog">
        <v-card color="#2c3e50">
          <v-card-text>
            <div class="pa-1">
              <v-toolbar-title class="pa-4">
                <table style="width: 100%">
                  <tr>
                    <td>
                      <hr/>
                    </td>
                    <td style="width:1px; padding: 10px; white-space: nowrap;"><h2 style="color: white">Item
                      details</h2></td>
                    <td>
                      <hr/>
                    </td>
                  </tr>
                </table>
              </v-toolbar-title>
              <v-row>
                <v-col cols="12" sm="5">
                  <v-card class="pa-1">
                    <v-row>
                      <v-col cols="12" sm="12">
                        <v-carousel hide-delimiters style="height: 300px">
                          <v-carousel-item
                              v-for="(item,i) in pictures"
                              :key="i"
                              :src="item.src"
                          ></v-carousel-item>
                        </v-carousel>
                      </v-col>
                    </v-row>
                  </v-card>

                </v-col>
                <v-col cols="12" sm="7">
                  <v-card class="pa-4">
                    <v-row>
                      <v-col cols="12" sm="12">
                        <v-card-title
                        >Title:<v-spacer></v-spacer>{{ auction_data.auction.item.title }}
                        </v-card-title>
                      </v-col>
                    </v-row>
                    <v-col cols="12" sm="12">
                      <hr/>
                    </v-col>
                    <v-row>
                      <v-col cols="12" sm="12">
                        <v-card-text
                        >{{ auction_data.auction.item.description }}
                        </v-card-text>
                      </v-col>
                    </v-row>
                    <v-col cols="12" sm="12">

                      <hr/>
                    </v-col>
                    <v-row>
                      <v-col cols="12" sm="12">
                        <v-card-title
                        >Warehouse:<v-spacer></v-spacer>{{ auction_data.auction.item.warehouse.name }}
                        </v-card-title>
                      </v-col>
                    </v-row>
                    <v-col cols="12" sm="12">
                      <hr/>
                    </v-col>
                    <v-row>
                      <v-col cols="12" sm="12">
                        <v-card-title
                        >Category:<v-spacer></v-spacer>{{ auction_data.auction.item.category.name }}
                        </v-card-title>
                      </v-col>
                    </v-row>
                    <v-col cols="12" sm="12">
                      <hr/>
                    </v-col>
                    <v-row>
                      <v-col cols="12" sm="12">
                        <v-card-title
                        >Condition:<v-spacer></v-spacer>{{ auction_data.auction.item.condition }}
                        </v-card-title>
                      </v-col>
                    </v-row>
                  </v-card>

                </v-col>
              </v-row>
              <v-toolbar-title class="pa-4">
              </v-toolbar-title>
            </div>
          </v-card-text>
          <v-card-actions class="justify-end">
            <v-btn
                text
                @click="dialog.value = false"
                dark
            >Close
            </v-btn>
          </v-card-actions>
        </v-card>
      </template>
    </v-dialog>
  </v-container>
</template>

<script>

export default {
  name: "ShowAuctionDialog",
  props: {
    showDialog: {
      type: Boolean,
      default: false
    },
    auction_data: null
  },

  data: () => ({
    itemTitle: '',
    itemDescription: '',
    itemWarehouse: '',
    itemCategory: '',
    itemSubCategory: '',
    itemCondition: '',
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

  }),

  created() {
  },
  methods: {

    //todo : ovdje ide logika methods() i axios
  }
}
</script>

<style scoped>

</style>