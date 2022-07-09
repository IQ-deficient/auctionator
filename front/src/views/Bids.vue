<template>
  <div>
    <v-data-table
        style="width: 95%; margin: 0 auto; padding-top: 6px"
        :headers="headers"
        :loading="dataLoading"
        :items="tableData"
        :search="search"
        sort-by="title"
        class="elevation-1; rounded-card; mt-10"
    >
      <template v-slot:top>
        <v-toolbar
            flat
            class="mb-8"
        >
          <v-toolbar-title><h2>Bids</h2></v-toolbar-title>
          <v-divider
              class="mx-4"
              inset
              vertical
          ></v-divider>
          <v-icon large>mdi-cart</v-icon>
          <v-spacer></v-spacer>
          <v-col cols="12" md="3">
            <v-text-field
                v-model="search"
                append-icon="mdi-magnify"
                label="Search"
                single-line
                hide-details
            ></v-text-field>
          </v-col>
        </v-toolbar>
      </template>
      <template v-slot:item.actions="{ item }">
        <v-row>
          <v-col cols="12" sm="12">
            <v-icon
                class="ml-2"
                color="primary"
                @click="showAuction(item)"
            >
              mdi-eye
            </v-icon>
          </v-col>
        </v-row>
      </template>
    </v-data-table>
    <show-auction
        v-if="showAuctionDialog"
        @close="showAuctionDialog = false"
        :show-dialog="showAuctionDialog"
        :auction_data="chosenAuction"
    />
  </div>
</template>

<script>
import axios from "axios";
import showAuctionDialog from "../components/ShowAuctionDialog";

export default {
  name: "Bids",

  components: {
    'show-auction': showAuctionDialog,
  },

  data: () => ({
    dialog: false,
    dialogDelete: false,
    headers: [
      {text: '', align: 'start', sortable: false, value: 'auction.title'},
      {text: 'Bid amount (€):', value: 'value'},
      {text: 'Bid time:', value: 'updated_at'},
      {text: 'Ends on:', value: 'auction.end_datetime'},
      {text: 'Item details', value: 'actions', sortable: false},
    ],
    search: '',
    showAuctionDialog: false,
    chosenAuction: '',
    tableData: [],
    dataLoading: false
  }),

  created() {
    this.getBids()
  },

  methods: {

    getBids() {
      this.dataLoading = true
      this.bids = []
      axios.get('/user_bids')
          .then(response => {
            if (response.data) {
              this.bids = response.data
              this.tableData = response.data
              this.dataLoading = false
            }
          })
          .catch(error => {
            this.dataLoading = false
            console.log(error)
          })
    },

    showAuction(item) {
      this.showAuctionDialog = true
      this.chosenAuction = item
    }
  },

  mounted() {
    if (!window.localStorage.user_roles.includes('Client')) {
      this.$router.push('/pageNotFound')
    }
  }

}
</script>

<style scoped>

</style>