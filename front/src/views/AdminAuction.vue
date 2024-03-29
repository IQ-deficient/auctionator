<template>
  <div>
    <v-data-table
      ref="table"
      :loading="dataLoading"
      style="width: 95%; margin: 0 auto; padding-top: 6px"
      :headers="headers"
      :items="tableData"
      :search="search"
      sort-by="id"
      class="elevation-1; rounded-card; mt-10"
    >
      <template v-slot:top>
        <v-toolbar
          flat
          class="mb-5"
        >
          <v-btn
            :loading="dataLoading"
            color="primary"
            class="mr-3"
            @click="getAuctions()"
          >
            <v-icon>mdi-database-refresh-outline</v-icon>
          </v-btn>
          <v-toolbar-title>
            <h2>Auction Management</h2>
          </v-toolbar-title>
          <v-divider
            class="mx-4"
            inset
            vertical
          ></v-divider>
          <v-row>
            <v-col cols="12" md="4">
              <v-text-field
                v-model="search"
                append-icon="mdi-magnify"
                label="Search"
                single-line
                hide-details
              ></v-text-field>
            </v-col>
            <v-divider
              class="mx-4"
              inset
              vertical
            ></v-divider>
            <v-col cols="12" md="4">
              <v-select
                single-line
                hide-details
                v-model="selectStatus"
                :items="statuses"
                item-text="status"
                label="Status"
                @change="updateTableData()"
              ></v-select>
            </v-col>
          </v-row>
          <v-spacer></v-spacer>
          <v-dialog
            v-model="modal"
            max-width="60%"
          >
            <template v-slot:activator="{ on, attrs }">
              <v-btn
                color="primary"
                dark
                class="mb-2 mt-2"
                v-bind="attrs"
                v-on="on"
              >
                <v-icon left>mdi-plus-circle-multiple-outline</v-icon>
                Create new auction
              </v-btn>
            </template>
            <template v-slot:default="dialog">
              <validation-observer ref="form">
                <form @submit.prevent="createAuction()">
                  <v-card color="info">
                    <v-card-actions class="justify-end">
                      <v-btn
                        @click="clearForm(); dialog.value = false"
                        color="secondary"
                      >
                        <v-icon>mdi-close</v-icon>
                      </v-btn>
                    </v-card-actions>
                    <v-card-text>
                      <div class="pa-1">
                        <v-toolbar-title>
                          <table style="width: 100%">
                            <tr>
                              <td>
                                <hr/>
                              </td>
                              <td style="width:1px; padding: 10px; white-space: nowrap;">
                                <h2 style="color: white">Item details</h2>
                              </td>
                              <td>
                                <hr/>
                              </td>
                            </tr>
                          </table>
                        </v-toolbar-title>
                        <v-card class="pa-4">
                          <validation-provider
                            v-slot="{ errors }"
                            name="item title"
                            rules="required|min:3|max:64"
                          >
                            <v-text-field
                              prepend-icon="mdi-tag-text-outline"
                              v-model="addItemTitle"
                              :error-messages="errors"
                              label="Item title"
                              clearable
                            >
                            </v-text-field>
                          </validation-provider>
                          <validation-provider
                            v-slot="{ errors }"
                            name="description"
                            rules="required|min:3|max:500"
                          >
                            <v-textarea
                              prepend-icon="mdi-format-list-text"
                              v-model="addItemDescription"
                              :error-messages="errors"
                              :counter="500"
                              label="Item description"
                              auto-grow
                              solo
                              clearable
                            >
                            </v-textarea>
                          </validation-provider>
                          <v-row>
                            <v-col cols="12" sm="6">
                              <validation-provider
                                v-slot="{ errors }"
                                name="warehouse information"
                                rules="required"
                              >
                                <v-select
                                  prepend-icon="mdi-warehouse"
                                  v-model="addItemWarehouse"
                                  :error-messages="errors"
                                  label="Warehouse"
                                  :items="warehouses"
                                  item-text="name"
                                  return-object
                                >
                                </v-select>
                              </validation-provider>
                            </v-col>
                            <v-col cols="12" sm="6">
                              <validation-provider
                                v-slot="{ errors }"
                                name="item category"
                                rules="required"
                              >
                                <v-select
                                  prepend-icon="mdi-format-list-bulleted-square"
                                  v-model="addItemCategory"
                                  :error-messages="errors"
                                  label="Category"
                                  :items="categories"
                                  item-text="name"
                                  @change="getSubCategoriesAndConditions()"
                                >
                                </v-select>
                              </validation-provider>
                            </v-col>
                          </v-row>
                          <v-row>
                            <v-col cols="12" sm="7">
                              <validation-provider
                                v-slot="{ errors }"
                                name="item subcategory"
                                rules="required"
                              >
                                <v-select
                                  prepend-icon="mdi-shape-outline"
                                  v-model="addItemSubCategory"
                                  :error-messages="errors"
                                  label="Subcategory"
                                  :items="subCategories"
                                  item-text="name"
                                  :disabled="!addItemCategory"
                                >
                                </v-select>
                              </validation-provider>
                            </v-col>
                            <v-col cols="12" sm="5">
                              <validation-provider
                                v-slot="{ errors }"
                                name="item condition"
                                rules="required"
                              >
                                <v-select
                                  prepend-icon="mdi-star-check-outline"
                                  v-model="addItemCondition"
                                  :error-messages="errors"
                                  label="Condition"
                                  :items="conditions"
                                  item-text="condition"
                                  :disabled="!addItemCategory"
                                >
                                </v-select>
                              </validation-provider>
                            </v-col>
                          </v-row>
                        </v-card>
                        <v-toolbar-title class="pa-4">
                          <table style="width: 100%">
                            <tr>
                              <td>
                                <hr/>
                              </td>
                              <td style="width:1px; padding: 10px; white-space: nowrap;">
                                <h2 style="color: white">Image upload</h2></td>
                              <td>
                                <hr/>
                              </td>
                            </tr>
                          </table>
                        </v-toolbar-title>
                        <v-card class="pa-4">
                          <v-row no-gutters justify="center" align="center">
                            <v-col cols="12" sm="12">
                              <validation-provider
                                v-slot="{ errors }"
                                name="image"
                                rules="required|image|mimes:image/jpeg,image/png,image/jpg|size:2000">
                                <v-file-input multiple type="file"
                                              v-model="imageUpload"
                                              :error-messages="errors"
                                              show-size
                                              label="Select images"
                                              @change="selectImage"
                                ></v-file-input>
                              </validation-provider>
                            </v-col>
                          </v-row>
                          <v-row no-gutters justify="center" align="center" style="width: 100%" class="my-1"
                                 v-for="(ms, i) in messages" :key="i">
                            <v-col cols="12" sm="12">
                              <div v-if="messages">
                                <v-list-item class="list-group list-group-flush">
                                  <v-list-item-content>
                                    <v-alert color="error" dark>
                                      {{ ms }}
                                    </v-alert>
                                  </v-list-item-content>
                                </v-list-item>
                              </div>
                            </v-col>
                          </v-row>
                          <v-row no-gutters justify="center" align="center">
                            <v-col cols="12" sm="12">
                              <div v-if="previewImages">
                                <v-list-item class="list-group list-group-flush">
                                  <v-list-item-content class="mx-1"
                                                       v-for="(file, index) in previewImages"
                                                       :key="index"
                                  >
                                    <v-img style="width: 100%" class="preview my-3" :src="file"></v-img>
                                  </v-list-item-content>
                                </v-list-item>
                              </div>
                            </v-col>
                          </v-row>
                        </v-card>
                        <v-toolbar-title class="pa-4">
                          <table style="width: 100%">
                            <tr>
                              <td>
                                <hr/>
                              </td>
                              <td style="width:1px; padding: 10px; white-space: nowrap;">
                                <h2 style="color: white">Auction details</h2>
                              </td>
                              <td>
                                <hr/>
                              </td>
                            </tr>
                          </table>
                        </v-toolbar-title>
                        <v-card class="pa-4">
                          <v-row>
                            <v-col cols="12" sm="12">
                              <validation-provider
                                v-slot="{ errors }"
                                name="auction title"
                                rules="required|min:3|max:128"
                              >
                                <v-text-field
                                  prepend-icon="mdi-form-textbox"
                                  v-model="addAuctionTitle"
                                  :error-messages="errors"
                                  label="Auction title"
                                  clearable
                                >
                                </v-text-field>
                              </validation-provider>
                            </v-col>
                            <v-col cols="12" sm="12">
                              <validation-provider
                                v-slot="{ errors }"
                                name="seller"
                                rules="required|alpha_spaces|min:3|max:32"
                              >
                                <v-text-field
                                  prepend-icon="mdi-account-tie"
                                  v-model="addAuctionSeller"
                                  :error-messages="errors"
                                  label="Seller"
                                  clearable
                                >
                                </v-text-field>
                              </validation-provider>
                            </v-col>
                          </v-row>
                          <v-row>
                            <v-col cols="6" sm="6">
                              <v-datetime-picker
                                v-model="addStartDate"
                                label="Pick START date and time"
                                prepend-icon="mdi-calendar"
                                :text-field-props="textFieldProps"
                                :time-picker-props="timeProps"
                              >
                                <template slot="dateIcon">
                                  <v-icon>mdi-calendar</v-icon>
                                </template>
                                <template slot="timeIcon">
                                  <v-icon>mdi-clock</v-icon>
                                </template>
                              </v-datetime-picker>
                            </v-col>
                            <v-col cols="6" sm="6">
                              <v-datetime-picker
                                v-model="addEndDate"
                                label="Pick END date and time"
                                prepend-icon="mdi-calendar"
                                :text-field-props="textFieldProps"
                                :time-picker-props="timeProps"
                              >
                                <template slot="dateIcon">
                                  <v-icon>mdi-calendar</v-icon>
                                </template>
                                <template slot="timeIcon">
                                  <v-icon>mdi-clock</v-icon>
                                </template>
                              </v-datetime-picker>
                            </v-col>
                          </v-row>
                          <v-row>
                            <v-col cols="6" sm="6">
                              <validation-provider
                                v-slot="{ errors }"
                                name="minBidValue"
                                rules="required|double|min_value:0"
                              >
                                <v-text-field
                                  prepend-icon="mdi-currency-eur"
                                  v-model="minimumBidValue"
                                  :error-messages="errors"
                                  label="Minimum BId Value"
                                  clearable
                                >
                                </v-text-field>
                              </validation-provider>
                            </v-col>
                            <v-col cols="6" sm="6">
                              <validation-provider
                                v-slot="{ errors }"
                                name="buyout"
                                :rules=minBuyoutValueRules()
                              >
                                <v-text-field
                                  prepend-inner-icon="mdi-currency-eur"
                                  v-model="addAuctionBuyout"
                                  :error-messages="errors"
                                  label="Buyout"
                                  clearable
                                >
                                </v-text-field>
                              </validation-provider>
                            </v-col>
                          </v-row>
                        </v-card>
                      </div>
                      <v-btn large
                             dark
                             type="submit"
                             color="primary"
                             class="mt-6"
                             :loading="loading"
                      >
                        <v-icon left class="mr-1">mdi-pencil-plus-outline</v-icon>
                        Post new auction
                      </v-btn>
                    </v-card-text>
                  </v-card>
                </form>
              </validation-observer>
            </template>
          </v-dialog>
        </v-toolbar>
      </template>
      <template v-slot:item.views="{ item }">
        <v-row v-if="selectStatus != 'Inactive'">
          <v-col cols="12" sm="12">
            <v-icon
              class="ml-2"
              color="primary"
              @click="showAdminAuction(item)"
            >
              mdi-eye
            </v-icon>
          </v-col>
        </v-row>
        <v-row v-else>
          <v-col cols="12" sm="12">
            <v-icon
              class="mr-2"
              color="primary"
              disabled
            >
              mdi-eye-off
            </v-icon>
          </v-col>
        </v-row>
      </template>
      <template v-slot:item.actions="{ item }">
        <v-row v-if="selectStatus == 'Created'">
          <v-col cols="12" sm="4">
            <v-icon v-if="item.is_active == false"
                    class="mr-2"
                    color="secondary"
            >
              mdi-pencil
            </v-icon>
            <v-icon v-else
                    class="mr-2"
                    color="primary"
                    @click="editAuction(item)"
            >
              mdi-pencil
            </v-icon>
          </v-col>
          <v-col cols="12" sm="4">
            <v-icon v-if="item.is_active == false"
                    class="mr-2"
                    color="secondary"
            >
              mdi-delete-clock
            </v-icon>
            <v-icon v-else
                    class="mr-2"
                    color="primary"
                    @click="disableAuction(item)"
            >
              mdi-delete-clock
            </v-icon>
          </v-col>
          <v-col cols="12" sm="4">
            <v-icon
              color="primary"
              class="mr-2"
              @click="hardDelete(item)"
            >
              mdi-delete-forever
            </v-icon>
          </v-col>
        </v-row>
        <v-row v-else-if="selectStatus == 'Ongoing'">
          <v-col cols="12" sm="6">
            <v-icon v-if="item.is_active == false"
                    class="mr-2"
                    color="secondary"
            >
              mdi-delete-clock
            </v-icon>
            <v-icon v-else
                    class="mr-2"
                    color="primary"
                    @click="disableAuction(item)"
            >
              mdi-delete-clock
            </v-icon>
          </v-col>
          <v-col cols="12" sm="6">
            <v-icon
              color="primary"
              class="mr-2"
              @click="hardDelete(item)"
            >
              mdi-delete-forever
            </v-icon>
          </v-col>
        </v-row>
        <v-row v-else-if="selectStatus == 'NA'">
          <v-col cols="12" sm="6">
            <v-icon
              class="mr-2"
              color="primary"
              @click="restoreAuction(item)"
            >
              mdi-delete-restore
            </v-icon>
          </v-col>
          <v-col cols="12" sm="6">
            <v-icon
              color="primary"
              class="mr-2"
              @click="hardDelete(item)"
            >
              mdi-delete-forever
            </v-icon>
          </v-col>
        </v-row>
        <v-row v-else>
          <v-col cols="12" sm="12">
            <v-icon
              class="mr-2"
              color="primary"
              disabled
            >
              mdi-pencil-off
            </v-icon>
          </v-col>
        </v-row>
      </template>
    </v-data-table>
    <show-admin-auction
      v-if="showAdminAuctionDialog"
      @close="showAdminAuctionDialog = false"
      :show-dialog="showAdminAuctionDialog"
      :auction="chosenAuction"
    />
    <edit-auction
      v-if="editAuctionDialog"
      @close="editAuctionDialog = false"
      :show-dialog="editAuctionDialog"
      @reload="getAuctions()"
      :auction="chosenAuction"
    />
  </div>
</template>

<script>
import axios from "axios";
import {required, min, max, alpha_spaces, min_value, image, mimes, size, numeric, double} from 'vee-validate/dist/rules'
import {extend, ValidationObserver, ValidationProvider, setInteractionMode} from 'vee-validate'
import MultipleImageUpload from "../services/MultipleImageUpload";
import EditAuctionDialog from "../components/EditAuctionDialog";
import showAdminAuctionDialog from "../components/ShowAdminAuctionDialog";
import Swal from "sweetalert2";

setInteractionMode('eager')

extend('greater', {
  params: ['target'],
  validate(value, {target}) {
    return value > target;
  },
  message: 'The {_field_} must have a greater value than minimum required bid value.'
});

extend('double', {
  ...double,
  message: 'The {_field_} must be a number and may contain decimals.',
})

extend('numeric', {
  ...numeric,
  message: 'The {_field_} must be a number.',
})

extend('required', {
  ...required,
  message: 'The {_field_} field is required.',
})

extend('min', {
  ...min,
  message: 'The {_field_} must be at least {length} characters.'
})

extend('max', {
  ...max,
  message: 'The {_field_} may not be greater than {length} characters.'
})

extend('alpha_spaces', {
  ...alpha_spaces,
  message: 'The {_field_} may only contain letters or spaces.',
})

extend('min_value', {
  ...min_value,
  message: 'The {_field_} must be at least 0€.'
})

extend('min_value_buyout', {
  ...min_value,
  message: 'The {_field_} must be at least ' + process.env.VUE_APP_MIN_BUYOUT + '€ (Euro).'
})

extend('image', {
  ...image,
  message: 'The {_field_} must be an image.'
})

extend('mimes', {
  ...mimes,
  message: 'The {_field_} is invalid, allowed extensions are JPEG and PNG.'
})

extend('size', {
  ...size,
  message: 'The {_field_} size must be under 2 MB.'
})

export default {
  name: "AdminAuction",

  components: {
    ValidationProvider,
    ValidationObserver,
    'edit-auction': EditAuctionDialog,
    'show-admin-auction': showAdminAuctionDialog,
  },
  computed: {},

  data: () => ({
    dialog: false,
    headers: [
      {text: 'ID', align: 'start', sortable: false, value: 'id'},
      {text: 'Title', align: 'start', sortable: false, value: 'title'},
      {text: 'Seller', value: 'seller'},
      // {text: 'Highest bidder', value: '.bid.username'},
      // {text: 'Bid amount (€)', value: '.bid.value'},
      {text: 'Minimum Bid Value', value: 'min_bid_value'},
      {text: 'Buyout amount (€)', value: 'buyout'},
      {text: 'Start date', value: 'start_datetime', width: 165},
      {text: 'End date', value: 'end_datetime', width: 165},
      {text: 'Creator', value: '.user.username'},
      {text: 'Details', value: 'views', sortable: false},
      {text: 'Actions', value: 'actions', sortable: false, align: 'center'},
    ],
    date: '',
    modal: false,
    search: '',
    selectStatus: '',
    statuses: [],
    auctions: [],
    addItemTitle: '',
    addItemDescription: '',
    addItemCategory: '',
    categories: [],
    addItemSubCategory: '',
    subCategories: [],
    addItemCondition: '',
    conditions: [],
    addItemWarehouse: '',
    warehouses: [],
    addAuctionTitle: '',
    addAuctionSeller: '',
    addAuctionBuyout: '',
    editAuctionDialog: false,
    showAdminAuctionDialog: false,
    chosenAuction: '',
    tableData: [],
    textFieldProps: {
      prependIcon: 'mdi-calendar'
    },
    timeProps: {
      useSeconds: true,
      ampmInTitle: true
    },
    addStartDate: new Date(),
    addEndDate: new Date(),
    isAdmin: window.localStorage.user_roles.includes('Administrator'),
    allowedRoles: window.localStorage.user_roles.includes('Administrator') || window.localStorage.user_roles.includes('Auctioneer'),
    dataLoading: false,
    loading: false,
    isValid: true,
    auction_id: null,
    previewImages: undefined,
    imageUpload: null,
    imageName: '',
    selectedFiles: undefined,
    progressInfos: [],
    message: "",
    messages: undefined,
    fileInfos: [],
    extensions: [".jpg", ".jpeg", ".png"],
    minimumBuyoutValue: process.env.VUE_APP_MIN_BUYOUT,
    minimumBidValue: ''
  }),

  created() {
    this.getAuctions()
    this.getStatuses()
    this.getParentCategories()
    this.getWarehouse()
  },

  methods: {

    minBuyoutValueRules() {
      return "required|greater:@minBidValue|double|min_value_buyout:" + process.env.VUE_APP_MIN_BUYOUT + ""
    },

    updateTableData() {
      if (this.selectStatus == 'Created') {
        this.tableData = this.auctions.created
      } else if (this.selectStatus == 'Ongoing') {
        this.tableData = this.auctions.ongoing
      } else if (this.selectStatus == 'Expired') {
        this.tableData = this.auctions.expired
      } else if (this.selectStatus == 'Sold') {
        this.tableData = this.auctions.sold
      } else if (this.selectStatus == 'NA') {
        this.tableData = this.auctions.na
      } else if (this.selectStatus == 'Inactive') {
        this.tableData = this.auctions.inactive
      } else {
        this.tableData = []
      }
    },

    showAdminAuction(item) {
      this.showAdminAuctionDialog = true
      this.chosenAuction = item
    },

    editAuction(item) {
      this.editAuctionDialog = true
      this.chosenAuction = item
    },

    selectImage() {
      this.progressInfos = [];
      this.previewImages = [];
      this.selectedFiles = event.target.files;
      for (let i = 0; i < this.selectedFiles.length; i++) {
        this.previewImages.push(URL.createObjectURL(this.selectedFiles[i]))
      }
    },

    uploadImages(item_id) {
      for (let i = 0; i < this.selectedFiles.length; i++) {
        this.upload(i, this.selectedFiles[i], item_id);
      }
    },

    upload(idx, file, item_id) {
      this.progressInfos[idx] = {percentage: 0, fileName: file.name};

      MultipleImageUpload.upload(file, item_id, (event) => {
        this.progressInfos[idx].percentage = Math.round(100 * event.loaded / event.total);
      })
        .then((response) => {
          let prevMessage = this.message ? this.message + "\n" : "";
          this.message = prevMessage + response.data.message;
        })
        .then((files) => {
          this.fileInfos = files.data;
        })
        .catch(() => {
          this.progressInfos[idx].percentage = 0;
          this.message = "Could not upload the file:" + file.name;
        });
    },

    createAuction() {
      this.$refs.form.validate().then(success => {
        if (success) {
          this.loading = true
          axios.post('/auction', {
            title_item: this.addItemTitle,
            description: this.addItemDescription,
            category: this.addItemSubCategory,
            condition: this.addItemCondition,
            warehouse_id: this.addItemWarehouse.id,
            title: this.addAuctionTitle,
            seller: this.addAuctionSeller,
            min_bid_value: this.minimumBidValue,
            start_datetime: new Date(this.addStartDate.getTime() - this.addStartDate.getTimezoneOffset() * 60000).toISOString().replace('Z', '').replace('T', ' '),
            end_datetime: new Date(this.addEndDate.getTime() - this.addEndDate.getTimezoneOffset() * 60000).toISOString().replace('Z', '').replace('T', ' '),
            buyout: this.addAuctionBuyout,
          })
            .then(response => {
              if (response) {
                Swal.fire({
                  title: 'Done!',
                  text: 'Auction has been created successfully.',
                  icon: 'success'
                })
                this.uploadImages(response.data.item_id)
                this.loading = false
                this.modal = false
              }
            })
            .then(() => {
              this.getAuctions()
              this.clearForm()
            })
            .catch(error => {
              if (error.response.data['end_datetime'] == 'The end datetime must be a date after start datetime.') {
                Swal.fire({
                  icon: 'error',
                  text: error.response.data['end_datetime'],
                })
                console.log(error)
                this.loading = false
              }
              if (error.response.data['start_datetime'] == 'The start datetime must be a date after today.') {
                Swal.fire({
                  icon: 'error',
                  text: error.response.data['start_datetime'],
                })
                console.log(error)
                this.loading = false
              }
              let check = 'The min bid value must be less than ' + this.addAuctionBuyout + '.'
              if (error.response.data['min_bid_value'][0] == check) {
                Swal.fire({
                  icon: 'error',
                  text: 'The min bid value must be lesser than the buyout.',
                })
                console.log(error)
                this.loading = false
              }
              console.log(error)
              this.dataLoading = false
            })
        }
      })
    },

    disableAuction(item) {
      Swal.fire({
        title: 'Are you sure you want to temporarily disable this auction?',
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#915A64',
        cancelButtonColor: '#909C6B',
        confirmButtonText: "Yes, I'm sure!"
      }).then((result) => {
        if (result.isConfirmed) {
          this.loading = true;
          axios.delete('auction_soft/' + item.id)
            .then(response => {
              if (response.data) {
                Swal.fire(
                  'Success!',
                  "Chosen auction has been temporarily disabled.",
                  'success',
                )
                this.loading = false
                this.getAuctions()
              }
            })
            .catch(error => {
              if (error.response.status == 400 || error.response.status == 410 ||
                error.response.status == 422) {
                Swal.fire({
                  icon: 'error',
                  text: error.response.data.message,
                })
                console.log(error)
                this.loading = false
              }
              console.log(error)
              this.dataLoading = false
            })
        }
      })
    },

    restoreAuction(item) {
      Swal.fire({
        title: 'Are you sure you want to restore this auction?',
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#915A64',
        cancelButtonColor: '#909C6B',
        confirmButtonText: "Yes, I'm sure!"
      }).then((result) => {
        if (result.isConfirmed) {
          this.loading = true;
          axios.delete('auction_soft/' + item.id)
            .then(response => {
              if (response.data) {
                Swal.fire(
                  'Success!',
                  "Chosen auction has been restored.",
                  'success',
                )
                this.loading = false
                this.getAuctions()
              }
            })
            .catch(error => {
              if (error.response.status == 400 || error.response.status == 410 ||
                error.response.status == 422) {
                Swal.fire({
                  icon: 'error',
                  title: error.response.data.message,
                })
                console.log(error)
                this.loading = false
              }
              console.log(error)
              this.dataLoading = false
            })
        }
      })
    },

    hardDelete(item) {
      Swal.fire({
        title: 'Are you sure you want to permanently deactivate this auction?',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#915A64',
        cancelButtonColor: '#909C6B',
        confirmButtonText: "Yes, I'm sure!"
      }).then((result) => {
        if (result.isConfirmed) {
          this.loading = true;
          axios.delete('auction/' + item.id)
            .then(response => {
              if (response.data) {
                Swal.fire(
                  'Success!',
                  "Chosen auction has been permanently removed.",
                  'success',
                )
                this.loading = false
                this.getAuctions()
              }
            })
            .catch(error => {
              if (error.response.status == 400) {
                Swal.fire({
                  icon: 'error',
                  text: error.response.data.message,
                })
                console.log(error)
                this.loading = false
              }
              console.log(error)
              this.dataLoading = false
            })
        }
      })
    },

    getAuctions() {
      this.dataLoading = true
      this.auctions = []
      axios.get('/auctions')
        .then(response => {
          this.auctions = response.data

          if (response.data) {
            this.auctions = response.data
            this.tableData = this.auctions.created
          }
          this.dataLoading = false
        })
        .catch(error => {
          console.log(error)
          this.dataLoading = false
        })
    },

    getStatuses() {
      this.dataLoading = true
      axios.get('/statuses')
        .then(response => {
          if (response.data) {
            this.statuses = response.data
          }
          this.selectStatus = this.statuses[0].status
          this.dataLoading = false
        })
        .catch(error => {
          console.log(error)
          this.dataLoading = false
        })
    },

    getParentCategories() {
      this.dataLoading = true
      axios.get('/parent_categories')
        .then(response => {
          if (response.data) {
            this.categories = response.data
          }
          this.dataLoading = false
        })
        .catch(error => {
          console.log(error)
          this.dataLoading = false
        })
    },

    getSubCategoriesAndConditions() {
      this.dataLoading = true
      axios.post('/child_categories_conditions', {
        category: this.addItemCategory
      })
        .then(response => {
          if (response.data) {
            this.subCategories = response.data.categories
            this.conditions = response.data.conditions
          }
          this.dataLoading = false
        })
        .catch(error => {
          console.log(error)
          this.dataLoading = false
        })
    },

    getWarehouse() {
      this.dataLoading = true
      axios.get('/active_warehouses')
        .then(response => {
          if (response.data) {
            this.warehouses = response.data
          }
          this.dataLoading = false
        })
        .catch(error => {
          console.log(error)
          this.dataLoading = false
        })
    },

    clearForm() {
      this.addItemTitle = ''
      this.addItemDescription = ''
      this.addItemCategory = ''
      this.addItemSubCategory = ''
      this.addItemCondition = ''
      this.addItemWarehouse = ''
      this.addAuctionTitle = ''
      this.imageUpload = null
      this.previewImages = undefined
      this.messages = undefined
      this.addAuctionSeller = ''
      this.addStartDate = new Date()
      this.addEndDate = new Date()
      this.addAuctionBuyout = ''
      this.minimumBidValue = ''
      this.$refs.form.reset()
    }
  },

  mounted() {
    if (!this.allowedRoles) {
      this.$router.push('/pageNotFound')
    }
  }

}
</script>

<style lang="scss">
//tbody {
//  tr:hover {
//    background-color: transparent !important;
//  }
//}
</style>