<template>
  <v-container>
    <validation-observer ref="observer" v-slot="{ invalid }" tag="form" @submit.prevent="updateAuction()">
      <v-dialog
        v-model="showDialog"
        max-width="60%"
        persistent
      >
        <template>
          <v-card color="#2c3e50">
            <v-card-actions class="justify-end">
              <v-btn
                small
                fab
                @click="$emit('close')"
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
                      :loading="dataLoading"
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
                    rules="required|min:3|max:500"
                    clearable
                  >
                    <v-textarea
                      v-model="addItemDescription"
                      :loading="dataLoading"
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
                          :loading="dataLoading"
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
                          :loading="categoryLoading"
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
                    <v-col cols="12" sm="6">
                      <validation-provider
                        v-slot="{ errors }"
                        name="Item subcategory"
                        rules="required"
                      >
                        <v-select
                          v-model="addItemSubCategory"
                          :loading="categoryLoading"
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
                    <v-col cols="12" sm="6">
                      <validation-provider
                        v-slot="{ errors }"
                        name="Item condition"
                        rules="required"
                      >
                        <v-select
                          v-model="addItemCondition"
                          :loading="categoryLoading"
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
                        name="title"
                        rules="required|min:3|max:64"
                        clearable
                      >
                        <v-text-field
                          v-model="addAuctionTitle"
                          :loading="dataLoading"
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
                    <v-col cols="12" sm="12">
                      <validation-provider
                        v-slot="{ errors }"
                        name="Buyout"
                        rules="required|min_value:1"
                        clearable
                      >
                        <v-text-field
                          v-model="addAuctionBuyout"
                          :loading="dataLoading"
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
                     :disabled="invalid || !addItemTitle || !addItemDescription || !addItemCategory || !addItemSubCategory ||
                                    !addItemCondition || !addItemWarehouse || !addAuctionTitle || !addAuctionBuyout"
                     @click="updateAuction()"
              >
                <v-icon left class="mr-1">mdi-pencil-plus-outline</v-icon>
                Post
              </v-btn>
            </v-card-text>
          </v-card>
        </template>
      </v-dialog>
      <!--      <v-dialog v-model="dialogDelete" max-width="35%">-->
      <!--        <v-card>-->
      <!--          <v-card-title class="text-h5">Are you sure you want to delete this item?</v-card-title>-->
      <!--          <v-card-actions>-->
      <!--            <v-spacer></v-spacer>-->
      <!--            <v-btn color="blue darken-1" text @click="closeDelete">Cancel</v-btn>-->
      <!--            <v-btn color="blue darken-1" text @click="deleteItemConfirm">OK</v-btn>-->
      <!--            <v-spacer></v-spacer>-->
      <!--          </v-card-actions>-->
      <!--        </v-card>-->
      <!--      </v-dialog>-->
    </validation-observer>

  </v-container>
</template>

<script>
import {required, min, max, min_value} from 'vee-validate/dist/rules'
import {extend, ValidationObserver, ValidationProvider, setInteractionMode} from 'vee-validate'
import axios from "axios";
import Swal from "sweetalert2";
// import UploadService from "../services/UploadFilesService";

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
  name: "EditAuctionDialog",
  props: {
    showDialog: {
      type: Boolean,
      default: false
    },
    auction: null
  },

  components: {
    ValidationProvider,
    ValidationObserver,
  },

  data: () => ({
    addItemTitle: '',
    addItemDescription: '',
    addItemWarehouse: '',
    warehouses: [],
    addItemCategory: '',
    categories: [],
    addItemSubCategory: '',
    subCategories: [],
    addItemCondition: '',
    conditions: [],
    addAuctionTitle: '',
    addAuctionSeller: '',
    addStartDate: '',
    addEndDate: '',
    textFieldProps: {
      prependIcon: 'mdi-calendar'
    },
    timeProps: {
      useSeconds: true,
      ampmInTitle: true
    },
    addAuctionBuyout: '',
    dataLoading: true,
    categoryLoading: true
  }),

  created() {
    this.fetchMasterCategory()
    this.getParentCategories()
    this.getWarehouse()
    this.addItemTitle = this.auction.item.title
    this.addItemDescription = this.auction.item.description
    this.addItemWarehouse = this.auction.item.warehouse
    this.addAuctionTitle = this.auction.title
    this.addAuctionSeller = this.auction.seller
    this.addStartDate = new Date(this.auction.start_datetime)
    this.addEndDate = new Date(this.auction.end_datetime)
    this.addAuctionBuyout = this.auction.buyout
    this.addItemSubCategory = this.auction.item.category.name
    this.addItemCondition = this.auction.item.condition
  },

  methods: {

    fetchMasterCategory() {
      this.categoryLoading = true
      axios.get('/category/' + this.auction.item.category.master_category_id)
        .then(response => {
          if (response.data) {
            this.addItemCategory = response.data.name
            this.categoryLoading = false
            this.getSubCategoriesAndConditions()
          }
        })
        .catch(error => {
          console.log(error)
          this.categoryLoading = false
        })
    },

    getParentCategories() {
      this.categoryLoading = true
      axios.get('/parent_categories')
        .then(response => {
          if (response.data) {
            this.categories = response.data
            this.categoryLoading = false
          }
        })
        .catch(error => {
          console.log(error)
          this.categoryLoading = false
        })
    },

    getSubCategoriesAndConditions() {
      this.categoryLoading = true
      axios.post('/child_categories_conditions', {
        category: this.addItemCategory
      })
        .then(response => {
          if (response.data) {
            this.subCategories = response.data.categories
            this.conditions = response.data.conditions
          }
          this.categoryLoading = false
        })
        .catch(error => {
          console.log(error)
          this.categoryLoading = false
        })
      // console.log(this.addItemCategory)
    },

    getWarehouse() {
      this.dataLoading = true
      axios.get('/warehouses')
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

    updateAuction() {
      console.log(this)
      axios.put('/auction/' + this.auction.id, {
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
              Swal.fire({
                title: 'Done!',
                text: 'User updated successfully.',
                icon: 'success'
              }).then(() => {
                this.showDialog = false;
                this.loading = false;
              })
            }
          }
        )
        .catch(error => {
          console.log(error)
          this.loading = false
          this.error = error.response.data.message;
        })
    },

  }
}
</script>

<style scoped>

</style>