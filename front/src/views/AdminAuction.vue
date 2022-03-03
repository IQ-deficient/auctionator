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
                clearable
            ></v-select>
          </v-col>
        </v-row>

        <v-spacer></v-spacer>
        <validation-observer>
          <v-dialog
              v-model="dialog"
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
                    <v-card class="pa-4">
                      <validation-provider
                          v-slot="{ errors }"
                          name="itemTitle"
                          rules="required"
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
                          name="addItemDescription"
                          rules="required"
                          clearable
                      >
                        <v-textarea
                            v-model="addItemDescription"
                            :error-messages="errors"
                            label="Item description"
                            auto-grow
                            solo
                            required
                            clearable
                        >
                        </v-textarea>
                      </validation-provider>
                      <v-row>
                        <v-col cols="12" sm="6">
                          <validation-provider
                              v-slot="{ errors }"
                              name="itemWarehouse"
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
                            >
                            </v-select>
                          </validation-provider>
                        </v-col>
                        <v-col cols="12" sm="6">
                          <validation-provider
                              v-slot="{ errors }"
                              name="itemCategory"
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
                              name="itemSubCategory"
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
                              name="itemCondition"
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
                      <v-dialog
                          transition="scale-transition"
                          max-width="35%"
                      >
                        <template v-slot:activator="{ on, attrs }">
                          <a
                              v-bind="attrs"
                              v-on="on"
                          >
                            <v-icon large>
                              mdi-pencil-outline
                            </v-icon>
                          </a>
                        </template>
                        <template v-slot:default="dialog">
                          <v-card>
                            <v-card-text>
                              <div class="pa-4">
                                <v-row no-gutters justify="center" align="center">
                                  <v-col cols="8">
                                    <v-file-input
                                        show-size
                                        label="Select Image"
                                        accept="image/*"
                                        @change="selectImage"
                                    ></v-file-input>
                                  </v-col>
                                  <v-col cols="4" class="pl-2">
                                    <v-btn color="primary" dark @click="upload">
                                      <v-icon left dark>mdi-cloud-upload</v-icon>
                                      Upload
                                    </v-btn>
                                  </v-col>
                                </v-row>
                                <div v-if="progress">
                                  <div>
                                    <v-progress-linear
                                        v-model="progress"
                                        color="light-blue"
                                        height="25"
                                        reactive
                                    >
                                      <strong>{{ progress }} %</strong>
                                    </v-progress-linear>
                                  </div>
                                </div>

                                <div v-if="previewImage">
                                  <div>
                                    <img style="width: 100%" class="preview my-3" :src="previewImage" alt=""/>
                                  </div>
                                </div>

                                <v-alert v-if="message" border="left" color="blue-grey" dark>
                                  {{ message }}
                                </v-alert>

                                <v-card v-if="imageInfos.length > 0" class="mx-auto">
                                  <v-list>
                                    <v-subheader>List of Images</v-subheader>
                                    <v-list-item-group color="primary">
                                      <v-list-item v-for="(image, index) in imageInfos" :key="index">
                                        <a :href="image.url">{{ image.name }}</a>
                                      </v-list-item>
                                    </v-list-item-group>
                                  </v-list>
                                </v-card>
                              </div>
                            </v-card-text>
                            <v-card-actions class="justify-end">
                              <v-btn
                                  text
                                  @click="dialog.value = false"
                              >Close
                              </v-btn>
                            </v-card-actions>
                          </v-card>
                        </template>
                      </v-dialog>
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
                              name="auctionTitle"
                              rules="required"
                              clearable
                          >
                            <v-text-field sty
                                          v-model="addAuctionTitle"
                                          :error-messages="errors"
                                          label="Auction title"
                                          required
                                          clearable
                            >
                            </v-text-field>
                          </validation-provider>
                        </v-col>
                      </v-row>
                      <v-row>
                        <v-col cols="12" sm="3">
                          <v-menu
                              ref="menu1"
                              v-model="menu1"
                              :close-on-content-click="false"
                              transition="scale-transition"
                              offset-y
                              max-width="290px"
                              min-width="auto"
                          >
                            <template v-slot:activator="{ on, attrs }">
                              <v-text-field
                                  v-model="dateFormatted"
                                  label="Start date"
                                  prepend-icon="mdi-calendar"
                                  v-bind="attrs"
                                  @blur="date = parseDate(dateFormatted)"
                                  v-on="on"
                              ></v-text-field>
                            </template>
                            <v-date-picker
                                v-model="date"
                                no-title
                                @input="menu1 = false"
                            ></v-date-picker>
                          </v-menu>
                        </v-col>
                        <v-col cols="12" sm="2">
                          <v-menu
                              ref="menu"
                              v-model="menu2"
                              :close-on-content-click="false"
                              :nudge-right="40"
                              :return-value.sync="time"
                              transition="scale-transition"
                              offset-y
                              max-width="290px"
                              min-width="290px"
                          >
                            <template v-slot:activator="{ on, attrs }">
                              <v-text-field
                                  v-model="time"
                                  label="Start time"
                                  prepend-icon="mdi-clock-time-four-outline"
                                  readonly
                                  v-bind="attrs"
                                  v-on="on"
                              ></v-text-field>
                            </template>
                            <v-time-picker
                                v-if="menu2"
                                v-model="time"
                                full-width
                                @click:minute="$refs.menu.save(time)"
                            ></v-time-picker>
                          </v-menu>
                        </v-col>
                        <v-spacer></v-spacer>
                        <v-divider
                            class="mx-4"
                            inset
                            vertical
                        ></v-divider>
                        <v-spacer></v-spacer>

                        <v-col cols="12" sm="3">
                          <v-menu
                              ref="menu1"
                              v-model="menu1"
                              :close-on-content-click="false"
                              transition="scale-transition"
                              offset-y
                              max-width="290px"
                              min-width="auto"
                          >
                            <template v-slot:activator="{ on, attrs }">
                              <v-text-field
                                  v-model="dateFormatted"
                                  label="End date"
                                  prepend-icon="mdi-calendar"
                                  v-bind="attrs"
                                  @blur="date = parseDate(dateFormatted)"
                                  v-on="on"
                              ></v-text-field>
                            </template>
                            <v-date-picker
                                v-model="date"
                                no-title
                                @input="menu1 = false"
                            ></v-date-picker>
                          </v-menu>
                        </v-col>
                        <v-col cols="12" sm="2">
                          <v-menu
                              ref="menu"
                              v-model="menu2"
                              :close-on-content-click="false"
                              :nudge-right="40"
                              :return-value.sync="time"
                              transition="scale-transition"
                              offset-y
                              max-width="290px"
                              min-width="290px"
                          >
                            <template v-slot:activator="{ on, attrs }">
                              <v-text-field
                                  v-model="time"
                                  label="End time"
                                  prepend-icon="mdi-clock-time-four-outline"
                                  readonly
                                  v-bind="attrs"
                                  v-on="on"
                              ></v-text-field>
                            </template>
                            <v-time-picker
                                v-if="menu2"
                                v-model="time"
                                full-width
                                @click:minute="$refs.menu.save(time)"
                            ></v-time-picker>
                          </v-menu>
                        </v-col>
                      </v-row>
                      <v-row>
                        <v-col cols="12" sm="12">
                          <validation-provider
                              v-slot="{ errors }"
                              name="addAuctionBuyout"
                              rules="required"
                              clearable
                          >
                            <v-text-field sty
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
                         type="submit"
                         color="primary"
                         class="mt-6"
                  >
                    <v-icon left class="mr-1">mdi-pencil-plus-outline</v-icon>
                    Post
                  </v-btn>
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
          <v-dialog v-model="dialogDelete" max-width="35%">
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
        </validation-observer>

      </v-toolbar>
    </template>
    <!--    <template v-slot:item.actions="{ item }">-->
    <!--      <v-icon-->
    <!--          class="mr-2"-->
    <!--      >-->
    <!--        mdi-magnify-->
    <!--      </v-icon>-->
    <!--      <v-icon-->
    <!--          class="mr-2"-->
    <!--          @click="editItem(item)"-->
    <!--      >-->
    <!--        mdi-pencil-->
    <!--      </v-icon>-->
    <!--      <v-icon-->
    <!--          @click="deleteItem(item)"-->
    <!--      >-->
    <!--        mdi-delete-->
    <!--      </v-icon>-->
    <!--    </template>-->
  </v-data-table>
</template>

<script>
import axios from "axios";
import {required, digits, email, max, regex} from 'vee-validate/dist/rules'
import {extend, ValidationObserver, ValidationProvider, setInteractionMode} from 'vee-validate'
import UploadService from "../services/UploadFilesService";

setInteractionMode('eager')

extend('digits', {
  ...digits,
  // message: '{_field_} needs to be {length} digits. ({_value_})',
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

extend('email', {
  ...email,
  message: 'Must be a valid email.',
})

export default {
  name: "AdminAuction",

  components: {
    ValidationProvider,
    ValidationObserver,
  },

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
    menu: false,
    modal: false,
    menu2: false,
    menu3: false,
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

    currentImage: undefined,
    previewImage: undefined,
    progress: 0,
    message: "",
    imageInfos: [],

  }),

  // computed: {
  //   formTitle () {
  //     return this.editedIndex === -1 ? 'New auction' : 'Edit auction'
  //   },
  // },

  // watch: {
  //   dialog (val) {
  //     val || this.close()
  //   },
  //   dialogDelete (val) {
  //     val || this.closeDelete()
  //   },
  // },

  created() {
    this.getAuctions()
    this.getStatuses()
    this.getParentCategories()
    this.getWarehouse()
  },

  methods: {
    // text: item => item.name + ' — ' + item.address,
    submit() {
      this.$refs.observer.validate()
    },
    clear() {
      this.email = ''
      this.password = ''
      this.checkbox = null
      this.$refs.observer.reset()
    },
    selectImage(image) {
      this.currentImage = image;
      this.previewImage = URL.createObjectURL(this.currentImage);
      this.progress = 0;
      this.message = "";
    },
    upload() {
      if (!this.currentImage) {
        this.message = "Please select an Image!";
        return;
      }
      this.progress = 0;
      UploadService.upload(this.currentImage, (event) => {
        this.progress = Math.round((100 * event.loaded) / event.total);
      })
          .then((response) => {
            this.message = response.data.message;
            return UploadService.getFiles();
          })
          .then((images) => {
            this.imageInfos = images.data;
          })
          .catch((err) => {
            this.progress = 0;
            this.message = "Could not upload the image! " + err;
            this.currentImage = undefined;
          });
    },

    getAuctions() {
      this.auctions = []
      axios.get('/auctions')
          .then(response => {
            if (response.data) {
              for (let i = 0; i < response.data.length; i++) {
                // console.log(response.data)
                this.auctions = response.data
              }
            }
          })
          .catch(error => {
            console.log(error)
          })
    },
    getStatuses() {
      axios.get('/statuses')
          .then(response => {
            if (response.data) {
              this.statuses = response.data
            }
          })
          .catch(error => {
            console.log(error)
          })
    },
    getParentCategories() {
      axios.get('/parent_categories')
          .then(response => {
            if (response.data) {
              this.categories = response.data
            }
          })
          .catch(error => {
            console.log(error)
          })
    },
    getSubCategoriesAndConditions() {
      axios.post('/child_categories_conditions', {
        category: this.addItemCategory
      })
          .then(response => {
            if (response.data) {
              this.subCategories = response.data.categories
              this.conditions = response.data.conditions
            }
          })
          .catch(error => {
            console.log(error)
          })
      console.log(this.addItemCategory)

    },
    getWarehouse() {
      axios.get('/warehouses')
          .then(response => {
            if (response.data) {
              this.warehouses = response.data
            }
          })
          .catch(error => {
            console.log(error)
          })
    },
  },

}
</script>

<style scoped>

</style>