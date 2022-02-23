<template>
  <v-data-table
      style="width: 95%; margin: 0 auto; padding-top: 6px"
      :headers="headers"
      :items="auctions"
      :search="search"
      sort-by="title"
      class="elevation-1; rounded-card; mt-10"
  >

    <template v-slot:top>
      <v-toolbar
          flat
          class="mb-8"
      >
        <v-toolbar-title><h2>Auctions</h2></v-toolbar-title>
        <v-divider
            class="mx-4"
            inset
            vertical
        ></v-divider>
        <v-row>
          <v-col cols="12" md="4"><v-text-field
            v-model="search"
            append-icon="mdi-magnify"
            label="Search"
            single-line
            hide-details
        ></v-text-field></v-col>
          <v-divider
              class="mx-4"
              inset
              vertical
          ></v-divider>
          <v-col cols="12" md="4"><v-select
            single-line
            hide-details
            v-model="enabled"
            :items="slots"
            label="Status"
            clearable
        ></v-select></v-col></v-row>

        <v-spacer></v-spacer>
        <v-dialog
            v-model="dialog"
            max-width="500px"
        >
          <template v-slot:activator="{ on, attrs }">
            <v-btn
                color="primary"
                dark
                class="mb-2"
                v-bind="attrs"
                v-on="on"
            >
              Create auction
            </v-btn>
          </template>
          <v-card>
            <v-card-title>
              <span class="text-h5">{{ formTitle }}</span>
            </v-card-title>

            <v-card-text>
              <v-container>
                <v-row>
                  <v-col
                      cols="12"
                      sm="6"
                      md="4"
                  >
                    <v-text-field
                        v-model="editedItem.title"
                        label="Title"
                    ></v-text-field>
                  </v-col>
                  <v-col
                      cols="12"
                      sm="6"
                      md="4"
                  >
                    <v-text-field
                        v-model="editedItem.seller"
                        label="Seller"
                    ></v-text-field>
                  </v-col>
                  <v-col
                      cols="12"
                      sm="6"
                      md="4"
                  >
                    <v-text-field
                        v-model="editedItem.buyout"
                        label="Buyout"
                    ></v-text-field>
                  </v-col>
                  <v-col
                      cols="12"
                      sm="6"
                      md="6"
                  >
                    <v-menu
                        v-model="menu2"
                        :close-on-content-click="false"
                        :nudge-right="40"
                        transition="scale-transition"
                        offset-y
                        min-width="auto"
                    >
                      <template v-slot:activator="{ on, attrs }">
                        <v-text-field
                            v-model="date"
                            label="Start date"
                            prepend-icon="mdi-calendar"
                            readonly
                            v-bind="attrs"
                            v-on="on"
                        ></v-text-field>
                      </template>
                      <v-date-picker
                          v-model="date"
                          @input="menu2 = false"
                      ></v-date-picker>
                    </v-menu>
                  </v-col>
                  <v-col
                      cols="12"
                      sm="6"
                      md="6"
                  >
                    <v-menu
                        v-model="menu3"
                        :close-on-content-click="false"
                        :nudge-right="40"
                        transition="scale-transition"
                        offset-y
                        min-width="auto"
                    >
                      <template v-slot:activator="{ on, attrs }">
                        <v-text-field
                            v-model="date"
                            label="End date"
                            prepend-icon="mdi-calendar"
                            readonly
                            v-bind="attrs"
                            v-on="on"
                        ></v-text-field>
                      </template>
                      <v-date-picker
                          v-model="date"
                          @input="menu3 = false"
                      ></v-date-picker>
                    </v-menu>
                  </v-col>
                </v-row>
              </v-container>
            </v-card-text>

            <v-card-actions>
              <v-spacer></v-spacer>
              <v-btn
                  color="blue darken-1"
                  text
                  @click="close"
              >
                Cancel
              </v-btn>
              <v-btn
                  color="blue darken-1"
                  text
                  @click="save"
              >
                Save
              </v-btn>
            </v-card-actions>
          </v-card>
        </v-dialog>
        <v-dialog v-model="dialogDelete" max-width="500px">
          <v-card>
            <v-card-title class="text-h5">Are you sure you want to delete this item?</v-card-title>
            <v-card-actions>
              <v-spacer></v-spacer>
              <v-btn color="blue darken-1" text @click="closeDelete">Cancel</v-btn>
              <v-btn color="blue darken-1" text @click="deleteItemConfirm">OK</v-btn>
              <v-spacer></v-spacer>
            </v-card-actions>
          </v-card>
        </v-dialog>
      </v-toolbar>
    </template>
    <template v-slot:item.actions="{ item }">
      <v-icon
          class="mr-2"
      >
        mdi-magnify
      </v-icon>
      <v-icon
          class="mr-2"
          @click="editItem(item)"
      >
        mdi-pencil
      </v-icon>
      <v-icon
          @click="deleteItem(item)"
      >
        mdi-delete
      </v-icon>
    </template>
<!--    <template v-slot:no-data>-->
<!--      <v-btn-->
<!--          color="primary"-->
<!--          @click="initialize"-->
<!--      >-->
<!--        Reset-->
<!--      </v-btn>-->
<!--    </template>-->
  </v-data-table>
</template>

<script>
import axios from "axios";
export default {
  name: "AdminAuction",

  data: () => ({
    dialog: false,
    dialogDelete: false,
    headers: [
      {
        text: '',
        align: 'start',
        sortable: false,
        value: 'title',
      },
      { text: 'Seller', value: 'seller' },
      { text: 'Highest bidder', value: '.bid.username' },
      { text: 'Bid amount (€)', value: '.bid.value' },
      { text: 'Buyout amount (€)', value: 'buyout' },
      { text: 'Start date', value: 'start_datetime' },
      { text: 'End date', value: 'end_datetime' },
      { text: 'Auctioneer', value: 'user_id' },
      { text: '', value: 'actions', sortable: false },
    ],
    date: '',
    menu: false,
    modal: false,
    menu2: false,
    menu3: false,
    search: '',
    auctions: [],
    editedIndex: -1,
    editedItem: {
      title: '',
      seller: '',
      bidUsername: '',
      bidValue: 0,
      buyout: 0,
      startDate: '',
      endDate: '',
      auctioneer: '',
    },
    defaultItem: {
      title: '',
      seller: '',
      bidUsername: '',
      bidValue: 0,
      buyout: 0,
      startDate: '',
      endDate: '',
      auctioneer: '',
    },
  }),

  computed: {
    formTitle () {
      return this.editedIndex === -1 ? 'New auction' : 'Edit auction'
    },
  },

  watch: {
    dialog (val) {
      val || this.close()
    },
    dialogDelete (val) {
      val || this.closeDelete()
    },
  },

  created () {
    this.getAuctions()
  },

  methods: {
    getAuctions() {
      this.auctions = []
      axios.get('http://127.0.0.1:8000/api/auctions')
          .then(response => {
            if (response.data) {
              for (let i = 0; i < response.data.length; i++){
                console.log(response.data)
                this.auctions = response.data
              }
            }
          })
          .catch(error => {
            console.log(error)
          })
    },

    editItem (item) {
      this.editedIndex = this.auctions.indexOf(item)
      this.editedItem = Object.assign({}, item)
      this.dialog = true
    },

    deleteItem (item) {
      this.editedIndex = this.auctions.indexOf(item)
      this.editedItem = Object.assign({}, item)
      this.dialogDelete = true
    },

    deleteItemConfirm () {
      this.auctions.splice(this.editedIndex, 1)
      this.closeDelete()
    },

    close () {
      this.dialog = false
      this.$nextTick(() => {
        this.editedItem = Object.assign({}, this.defaultItem)
        this.editedIndex = -1
      })
    },

    closeDelete () {
      this.dialogDelete = false
      this.$nextTick(() => {
        this.editedItem = Object.assign({}, this.defaultItem)
        this.editedIndex = -1
      })
    },

    save () {
      if (this.editedIndex > -1) {
        Object.assign(this.auctions[this.editedIndex], this.editedItem)
      } else {
        this.auctions.push(this.editedItem)
      }
      this.close()
    },
  },

}
</script>

<style scoped>

</style>