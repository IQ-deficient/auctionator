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
                class="mb-8"
        >
          <v-btn :loading="dataLoading" color="primary" class="mr-3"
                 @click="getAuctions()">
            <v-icon>mdi-database-refresh-outline</v-icon>
          </v-btn>
          <v-toolbar-title><h2>Auctions</h2></v-toolbar-title>
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
                      class="mb-2"
                      v-bind="attrs"
                      v-on="on"
              >
                <v-icon left>mdi-plus-circle-multiple-outline</v-icon>
                Create auction
              </v-btn>
            </template>
            <template v-slot:default="dialog">
              <validation-observer ref="form">
                <form @submit.prevent="createAuction()">
                  <v-card color="info">
                    <v-card-actions class="justify-end">
                      <v-btn
                              small
                              fab
                              @click="clearForm(); dialog.value = false"
                              dark
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
                              <td style="width:1px; padding: 10px; white-space: nowrap;"><h2 style="color: white">Item
                                details</h2></td>
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
                                      rules="required|image|mimes:image/jpeg,image/png|size:2000">
                                <v-file-input multiple type="file"
                                              v-model="imageUpload"
                                              :error-messages="errors"
                                              show-size
                                              label="Select Image"
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
                              <td style="width:1px; padding: 10px; white-space: nowrap;"><h2 style="color: white">Auction
                                details</h2></td>
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
                                      rules="required|min:3|max:64"
                              >
                                <v-text-field
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
                                      rules="required|alpha|min:3|max:32"
                              >
                                <v-text-field
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
                              <!--                              <validation-provider>-->
                              <v-datetime-picker
                                      v-model="addStartDate"
                                      label="Pick start date and time"
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
                              <!--                              </validation-provider>-->
                            </v-col>
                            <v-col cols="6" sm="6">
                              <!--                              <validation-provider>-->
                              <v-datetime-picker
                                      v-model="addEndDate"
                                      label="Pick start date and time"
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
                              <!--                              </validation-provider>-->
                            </v-col>
                          </v-row>
                          <v-row>
                            <v-col cols="12" sm="12">
                              <validation-provider
                                      v-slot="{ errors }"
                                      name="buyout"
                                      rules="required|min_value:1"
                              >
                                <v-text-field
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
                        Post
                      </v-btn>
                    </v-card-text>
                  </v-card>
                </form>
              </validation-observer>
            </template>
          </v-dialog>
<!--          <v-dialog v-model="dialogDelete" max-width="35%">-->
<!--            <v-card>-->
<!--              <v-card-title class="text-h5">Are you sure you want to delete this item?</v-card-title>-->
<!--              <v-card-actions>-->
<!--                <v-spacer></v-spacer>-->
<!--                <v-spacer></v-spacer>-->
<!--              </v-card-actions>-->
<!--            </v-card>-->
<!--          </v-dialog>-->
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
        <v-row v-if="selectStatus == 'Ongoing'">
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
        <v-row v-if="selectStatus == 'NA'">
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
<!--        <v-icon-->
<!--                color="primary"-->
<!--                class="mr-2"-->
<!--                @click="showAdminAuction(item)"-->
<!--        >-->
<!--          mdi-eye-->
<!--        </v-icon>-->
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
  import {required, min, max, alpha, min_value, image, mimes, size} from 'vee-validate/dist/rules'
  import {extend, ValidationObserver, ValidationProvider, setInteractionMode} from 'vee-validate'
  import MultipleImageUpload from "../services/MultipleImageUpload";
  import EditAuctionDialog from "../components/EditAuctionDialog";
  import showAdminAuctionDialog from "../components/ShowAdminAuctionDialog";
  import Swal from "sweetalert2";


  setInteractionMode('eager')

  extend('required', {
    ...required,
    message: 'The {_field_} field is required.',
  })

  extend('min', {
    ...min,
    message: 'The {_field_} must be at least {min} characters.'
  })

  extend('max', {
    ...max,
    message: 'The {_field_} may not be greater than {max} characters.'
  })

  extend('alpha', {
    ...alpha,
    message: 'The {_field_} may only contain letters.',
  })

  // extend('numeric', {
  //   ...numeric,
  //   message: 'The {_field_} must be a number.',
  // })

  extend('min_value', {
    ...min_value,
    message: 'The {_field_} must be at least 1 euro (€).'
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

    data: () => ({
      dialog: false,
      // dialogDelete: false,
      headers: [
        {text: '', align: 'start', sortable: false, value: 'id'},
        {text: '', align: 'start', sortable: false, value: 'title'},
        {text: 'Seller', value: 'seller'},
        {text: 'Highest bidder', value: '.bid.username'},
        {text: 'Bid amount (€)', value: '.bid.value'},
        {text: 'Buyout amount (€)', value: 'buyout'},
        {text: 'Start date', value: 'start_datetime'},
        {text: 'End date', value: 'end_datetime'},
        {text: 'Auctioneer', value: '.user.username'},
        {text: 'Details', value: 'views', sortable: false},
        {text: '', value: 'actions', sortable: false},
      ],
      date: '',
      // menu: false,
      modal: false,
      // menu2: false,
      // menu3: false,
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
      //image data
      //
      previewImages: undefined,
      // progress: 0,
      // message: "",
      // imageInfos: [],
      imageUpload: null,
      imageName: '',

      selectedFiles: undefined,
      progressInfos: [],
      message: "",
      messages: undefined,
      fileInfos: [],
      extensions: [".jpg", ".jpeg", ".png"]
    }),

    created() {
      this.getAuctions()
      this.getStatuses()
      this.getParentCategories()
      this.getWarehouse()
    },

    methods: {

      // text: item => item.name + ' — ' + item.address,
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
          this.tableData = this.auctions
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
        // this.progress = 0;
        // this.message = "";
        this.progressInfos = [];
        this.previewImages = [];
        this.selectedFiles = event.target.files;
        for (let i = 0; i < this.selectedFiles.length; i++) {
          // console.log(this.selectedFiles[i])
          this.previewImages.push(URL.createObjectURL(this.selectedFiles[i]))
        }
      },

      // This function will be responsible to validate and show errors based on decided logic like size and file type
      // validateImages() {
      //   this.message = "";
      //   this.messages = [];
      //   if (this.selectedFiles) {
      //
      //     if (this.selectedFiles.length > 5) {
      //       this.message = "You may upload up to 5 images.";
      //       this.messages.push(this.message)
      //       return false
      //     }
      //
      //     for (let i = 0; i < this.selectedFiles.length; i++) {
      //
      //       this.imageName = this.selectedFiles[i].name
      //       this.fileSize = this.selectedFiles[i].size
      //
      //       for (let j = 0; j < this.extensions.length; j++) {
      //         this.currentExtension = this.extensions[j];
      //
      //         if (this.imageName.substr(this.imageName.length - this.currentExtension.length,
      //           this.currentExtension.length).toLowerCase() === this.currentExtension.toLowerCase()) {
      //           this.isValid = true
      //         }
      //       }
      //       if (!this.isValid) {
      //         this.message = "Sorry, '" + this.imageName + "' is invalid, allowed extensions are: " + this.extensions.join(", ") + ".";
      //         this.messages.push(this.message)
      //         return false
      //       }
      //
      //       if (this.fileSize > 2097152) {
      //         this.message = "Sorry, '" + this.imageName + "' is invalid, file size cannot be greater than 2MB.";
      //         this.messages.push(this.message)
      //         return false
      //       }
      //       // this.upload(i, this.selectedFiles[i], item_id);
      //     }
      //   } else {
      //     this.message = "Please select an image."
      //     this.messages.push(this.message)
      //     return false
      //     // return
      //   }
      //   return true
      // },

      // This method will only be used after validateImages() returns true meaning no validations failed
      uploadImages(item_id) {
        for (let i = 0; i < this.selectedFiles.length; i++) {
          this.upload(i, this.selectedFiles[i], item_id);
        }
      },

      // Finally, this will call an api to store images one by one
      upload(idx, file, item_id) {
        this.progressInfos[idx] = {percentage: 0, fileName: file.name};

        MultipleImageUpload.upload(file, item_id, (event) => {
          this.progressInfos[idx].percentage = Math.round(100 * event.loaded / event.total);
          // console.log(idx)
          // console.log(this.progressInfos[idx])
          // console.log(file)
        })
                .then((response) => {
                  let prevMessage = this.message ? this.message + "\n" : "";
                  this.message = prevMessage + response.data.message;
                  // return MultipleImageUpload.getFiles();
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
            // If this validation fails simply abort further action
            // let aborted = this.validateImages()
            // if (!aborted) return
            this.loading = true
            // console.log(this.addItemWarehouse.id)
            axios.post('/auction', {
              title_item: this.addItemTitle,
              description: this.addItemDescription,
              category: this.addItemSubCategory,
              condition: this.addItemCondition,
              warehouse_id: this.addItemWarehouse.id,
              title: this.addAuctionTitle,
              seller: this.addAuctionSeller,
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
                        }).then(() => {
                          // If all is well and Auction was created with an Item, give that item_id to images being stored
                        })
                        this.uploadImages(response.data.item_id)
                        this.loading = false
                        this.modal = false
                        this.getAuctions()
                        this.clearForm()
                      }
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
                      console.log(error)
                      this.dataLoading = false
                    })
          }
        })
      },

      disableAuction(item) {
        Swal.fire({
          title: 'Are you sure you want to disable this auction?',
          icon: 'question',
          showCancelButton: true,
          confirmButtonColor: '#605290',
          cancelButtonColor: '#819fC9',
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
          confirmButtonColor: '#605290',
          cancelButtonColor: '#819fC9',
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
          title: 'Are you sure you want to delete this auction?',
          icon: 'question',
          showCancelButton: true,
          confirmButtonColor: '#605290',
          cancelButtonColor: '#819fC9',
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
                    // for (let i = 0; i < response.data.length; i++) {
                    // console.log(response.data)
                    this.auctions = response.data
                    // this auctions.created meddles with proper status and auctions being shown together
                    this.tableData = this.auctions.created
                  }
                  // this.selectStatus = this.statuses[0]
                  // console.log(this.selectStatus)
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
                    // console.log(response.data)
                  }
                  this.selectStatus = this.statuses[0].status
                  // console.log(this.selectStatus)
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
        // console.log(this.addItemCategory)
      },

      getWarehouse() {
        this.dataLoading = true
        axios.get('/active_warehouses')
                .then(response => {
                  if (response.data) {
                    this.warehouses = response.data
                    // console.log(response.data)
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
        this.$refs.form.reset()
      }
    },

    mounted() {
      if (!this.allowedRoles) {
        this.$router.push('/pageNotFound')
      }
      document.title = 'Auction Management - Auction House'
    }

  }
</script>

<style scoped>

</style>