<template>
  <div>
    <v-data-table
      ref="table"
      :loading="dataLoading"
      style="width: 95%; margin: 0 auto; padding-top: 6px"
      :headers="headers"
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
          <validation-observer>
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
                <v-card color="#2c3e50">
                  <v-card-actions class="justify-end">
                    <v-btn
                      small
                      fab
                      @click="dialog.value = false"
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
                          name="Title"
                          rules="required|min:3|max:64"
                          clearable
                        >
                          <v-text-field
                            v-model="addItemTitle"
                            :error-messages="errors"
                            label="Item title"
                            required
                            clearable
                          >
                          </v-text-field>
                        </validation-provider>
                        <validation-provider
                          v-slot="{ errors }"
                          name="Description"
                          clearable
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
                              name="Warehouse information"
                              rules="required"
                            >
                              <v-select
                                v-model="addItemWarehouse"
                                :error-messages="errors"
                                label="Warehouse"
                                :items="warehouses"
                                item-text="name"
                                required
                                clearable
                                return-object
                              >
                              </v-select>
                            </validation-provider>
                          </v-col>
                          <v-col cols="12" sm="6">
                            <validation-provider
                              v-slot="{ errors }"
                              name="Item category"
                              rules="required"
                            >
                              <v-select
                                v-model="addItemCategory"
                                :error-messages="errors"
                                label="Category"
                                :items="categories"
                                item-text="name"
                                required
                                clearable
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
                              name="Item subcategory"
                              rules="required"
                            >
                              <v-select
                                v-model="addItemSubCategory"
                                :error-messages="errors"
                                label="Subcategory"
                                :items="subCategories"
                                item-text="name"
                                required
                                clearable
                                :disabled="!addItemCategory"
                              >
                              </v-select>
                            </validation-provider>
                          </v-col>
                          <v-col cols="12" sm="5">
                            <validation-provider
                              v-slot="{ errors }"
                              name="Item condition"
                              rules="required"
                            >
                              <v-select
                                v-model="addItemCondition"
                                :error-messages="errors"
                                label="Condition"
                                :items="conditions"
                                item-text="condition"
                                required
                                clearable
                                :disabled="!addItemCategory"
                              >
                              </v-select>
                            </validation-provider>
                          </v-col>
                        </v-row>

                        <!--                                <v-card v-if="imageInfos.length > 0" class="mx-auto">-->
                        <!--                                  <v-list>-->
                        <!--                                    <v-subheader>List of Images</v-subheader>-->
                        <!--                                    <v-list-item-group color="primary">-->
                        <!--                                      <v-list-item v-for="(image, index) in imageInfos" :key="index">-->
                        <!--                                        <a :href="image.url">{{ image.name }}</a>-->
                        <!--                                      </v-list-item>-->
                        <!--                                    </v-list-item-group>-->
                        <!--                                  </v-list>-->
                        <!--                                </v-card>-->
                        <!--                              </v-card-text>-->
                        <div>
                          <div v-if="progressInfos">
                            <div class="mb-2"
                                 v-for="(progressInfo, index) in progressInfos"
                                 :key="index"
                            >
                              <span>{{ progressInfo.fileName }}</span>
                              <div class="progress">
                                <div class="progress-bar progress-bar-info"
                                     role="progressbar"
                                     :aria-valuenow="progressInfo.percentage"
                                     aria-valuemin="0"
                                     aria-valuemax="100"
                                     :style="{ width: progressInfo.percentage + '%' }"
                                >
                                  {{ progressInfo.percentage }}%
                                </div>
                              </div>
                            </div>
                          </div>
                          <v-row no-gutters justify="center" align="center">
                            <v-col cols="8">
                              <v-file-input multiple type="file"
                                            v-model="imageUpload"
                                            show-size
                                            label="Select Image"
                                            accept="image/*"
                                            @change="selectImage"
                              ></v-file-input>
                            </v-col>
                            <v-col cols="4" class="pl-2">
                              <v-btn color="primary"
                                     dark
                                     :disabled="!selectedFiles"
                                     @click="uploadImages">
                                <v-icon left dark>mdi-cloud-upload</v-icon>
                                Upload
                              </v-btn>
                            </v-col>
                          </v-row>
                          <div v-if="message" class="alert alert-light" role="alert">
                            <ul>
                              <li v-for="(ms, i) in message.split('\n')" :key="i">
                                {{ ms }}
                              </li>
                            </ul>
                          </div>
                          <div class="card">
                            <div class="card-header">List of Files</div>
                            <ul class="list-group list-group-flush">
                              <li class="list-group-item"
                                  v-for="(file, index) in fileInfos"
                                  :key="index"
                              >
                                <a :href="file.url">{{ file.name }}</a>
                              </li>
                            </ul>
                          </div>
                        </div>

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
                              name="title"
                              rules="required|min:3|max:64"
                              clearable
                            >
                              <v-text-field
                                v-model="addAuctionTitle"
                                :error-messages="errors"
                                label="Auction title"
                                required
                                clearable
                              >
                              </v-text-field>
                            </validation-provider>
                          </v-col>
                          <v-col cols="12" sm="12">
                            <validation-provider
                              v-slot="{ errors }"
                              name="Seller"
                              rules="required|min:3|max:64"
                              clearable
                            >
                              <v-text-field
                                v-model="addAuctionSeller"
                                :error-messages="errors"
                                label="Seller"
                                required
                                clearable
                              >
                              </v-text-field>
                            </validation-provider>
                          </v-col>
                        </v-row>
                        <v-row>
                          <v-col cols="6" sm="6">
                            <validation-provider>
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
                            </validation-provider>
                          </v-col>
                          <v-col cols="6" sm="6">
                            <validation-provider>
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
                            </validation-provider>
                          </v-col>
                        </v-row>
                        <v-row>
                          <v-col cols="12" sm="12">
                            <validation-provider
                              v-slot="{ errors }"
                              name="buyout"
                              rules="required|min_value:1"
                              clearable
                            >
                              <v-text-field
                                v-model="addAuctionBuyout"
                                :error-messages="errors"
                                label="Buyout"
                                required
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
                           @click="createAuction()"
                    >
                      <v-icon left class="mr-1">mdi-pencil-plus-outline</v-icon>
                      Post
                    </v-btn>
                  </v-card-text>
                </v-card>
              </template>
            </v-dialog>
            <v-dialog v-model="dialogDelete" max-width="35%">
              <v-card>
                <v-card-title class="text-h5">Are you sure you want to delete this item?</v-card-title>
                <v-card-actions>
                  <v-spacer></v-spacer>
                  <!--                <v-btn color="blue darken-1" text @click="closeDelete">Cancel</v-btn>-->
                  <!--                <v-btn color="blue darken-1" text @click="deleteItemConfirm">OK</v-btn>-->
                  <v-spacer></v-spacer>
                </v-card-actions>
              </v-card>
            </v-dialog>
          </validation-observer>

        </v-toolbar>
      </template>
      <template v-slot:item.actions="{ item }">
        <v-icon v-if="item.is_active == false"
                class="mr-2"
                color="secondary"
        >
          mdi-pencil
        </v-icon>
        <v-icon v-else-if="selectStatus == 'Created'"
                class="mr-2"
                @click="editAuction(item)"
        >
          mdi-pencil
        </v-icon>

        <!--          <v-icon-->
        <!--              @click="deleteAuction(item)"-->
        <!--          >-->
        <!--            mdi-delete-->
        <!--          </v-icon>-->
      </template>
    </v-data-table>
    <edit-auction
      v-if="editAuctionDialog"
      @close="editAuctionDialog = false"
      :show-dialog="editAuctionDialog"
      :auction="chosenAuction"
    />
  </div>
</template>

<script>
import axios from "axios";
import {required, min, max, min_value} from 'vee-validate/dist/rules'
import {extend, ValidationObserver, ValidationProvider, setInteractionMode} from 'vee-validate'
import MultipleImageUpload from "../services/MultipleImageUpload";
import EditAuctionDialog from "../components/EditAuctionDialog";

setInteractionMode('eager')

extend('min', {
  ...min,
  message: '{_field_} must be at least {length} characters.',
})

extend('max', {
  ...max,
  message: '{_field_} must not be greater than {length} characters.'
})

extend('min_value', {
  ...min_value,
  message: '{_field_} must be at least 1 euro.'
})

extend('required', {
  ...required,
  message: '{_field_} is required.'
})

export default {
  name: "AdminAuction",

  components: {
    ValidationProvider,
    ValidationObserver,
    'edit-auction': EditAuctionDialog,
  },

  data: () => ({
    dialog: false,
    dialogDelete: false,
    headers: [
      {text: '', align: 'start', sortable: false, value: 'title'},
      {text: 'Seller', value: 'seller'},
      {text: 'Highest bidder', value: '.bid.username'},
      {text: 'Bid amount (€)', value: '.bid.value'},
      {text: 'Buyout amount (€)', value: 'buyout'},
      {text: 'Start date', value: 'start_datetime'},
      {text: 'End date', value: 'end_datetime'},
      {text: 'Auctioneer', value: '.user.username'},
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

    //image data
    //
    // currentImage: undefined,
    // previewImage: undefined,
    // progress: 0,
    // message: "",
    // imageInfos: [],
    imageUpload: null,

    selectedFiles: undefined,
    progressInfos: [],
    message: "",
    fileInfos: [],


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
      } else {
        this.tableData = this.auctions
      }
    },
    submit() {
      this.$refs.observer.validate()
    },
    editAuction(item) {
      this.editAuctionDialog = true
      this.chosenAuction = item
    },
    clear() {
      this.email = ''
      this.password = ''
      this.checkbox = null
      this.$refs.observer.reset()
    },
    selectImage() {
      // this.currentImage = image;
      // this.previewImage = URL.createObjectURL(this.currentImage);
      // this.progress = 0;
      // this.message = "";
      this.progressInfos = [];
      this.selectedFiles = event.target.files;
    },

    uploadImages(item_id) {
      this.message = "";
      for (let i = 0; i < this.selectedFiles.length; i++) {
        console.log(this.selectedFiles.name)
        this.upload(i, this.selectedFiles[i], item_id);
      }
    },

    upload(idx, file, item_id) {
      this.progressInfos[idx] = {percentage: 0, fileName: file.name};
      MultipleImageUpload.upload(file, item_id, (event) => {
        this.progressInfos[idx].percentage = Math.round(100 * event.loaded / event.total);
        console.log(idx)
        console.log(this.progressInfos[idx])
        console.log(file)
      })
        .then((response) => {

          let prevMessage = this.message ? this.message + "\n" : "";
          this.message = prevMessage + response.data.message;
          return MultipleImageUpload.getFiles();
        })
        .then((files) => {
          this.fileInfos = files.data;
        })
        .catch(() => {
          this.progressInfos[idx].percentage = 0;
          this.message = "Could not upload the file:" + file.name;
        });
    },

    getAuctions() {
      this.dataLoading = true
      this.auctions = []
      axios.get('/auctions')
        .then(response => {
          if (response.data) {
            // for (let i = 0; i < response.data.length; i++) {
            // console.log(response.data)
            this.auctions = response.data
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
    createAuction() {
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
        start_datetime: this.addStartDate.toISOString().replace('Z', ' ').replace('T', ' '),
        end_datetime: this.addEndDate.toISOString().replace('Z', ' ').replace('T', ' '),
        buyout: this.addAuctionBuyout,
      })
        .then(response => {
            if (response) {
              // After we are sure Item and Auction for said item is created we upload Images for it
              this.uploadImages(response.data.item_id)
              window.alert('bravo kretenu nemas sweetalert')
              this.loading = false
              this.modal = false
              this.getAuctions()
            }
          }
        )
        .catch(error => {
          console.log(error)
          this.loading = false
          this.error = error.response.data.message;
        })
    },
  },

  mounted() {
    if (!this.allowedRoles) {
      this.$router.push('/pageNotFound')
    }
    // MultipleImageUpload.getFiles().then((response) => {
    //   this.fileInfos = response.data;
    // });

    document.title = 'Auction Management - Auction House'
  }

}
</script>

<style scoped>

</style>