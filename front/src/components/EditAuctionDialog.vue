<template>
  <v-container>
      <v-dialog
        v-model="showDialog"
        max-width="60%"
        persistent
      >
        <template>
          <validation-observer ref="form" v-slot="{invalid}">
            <form @submit.prevent="updateAuction()">
              <v-card color="info">
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
                              name="item title"
                              rules="required|min:3|max:64"
                      >
                        <v-text-field
                          v-model="addItemTitle"
                          :loading="dataLoading"
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
                                  name="warehouse information"
                                  rules="required"
                          >
                            <v-select
                              v-model="addItemWarehouse"
                              :loading="dataLoading"
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
                              :loading="categoryLoading"
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
                        <v-col cols="12" sm="6">
                          <validation-provider
                                  v-slot="{ errors }"
                                  name="item subcategory"
                                  rules="required"
                          >
                            <v-select
                              v-model="addItemSubCategory"
                              :loading="categoryLoading"
                              :error-messages="errors"
                              label="Subcategory"
                              :items="subCategories"
                              item-text="name"
                              :disabled="!addItemCategory"
                            >
                            </v-select>
                          </validation-provider>
                        </v-col>
                        <v-col cols="12" sm="6">
                          <validation-provider
                                  v-slot="{ errors }"
                                  name="item condition"
                                  rules="required"
                          >
                            <v-select
                              v-model="addItemCondition"
                              :loading="categoryLoading"
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
    <!--                  <div>-->
    <!--                    <div v-if="progressInfos">-->
    <!--                      <div class="mb-2"-->
    <!--                           v-for="(progressInfo, index) in progressInfos"-->
    <!--                           :key="index"-->
    <!--                      >-->
    <!--                        <span>{{progressInfo.fileName}}</span>-->
    <!--                        <div class="progress">-->
    <!--                          <div class="progress-bar progress-bar-info"-->
    <!--                               role="progressbar"-->
    <!--                               :aria-valuenow="progressInfo.percentage"-->
    <!--                               aria-valuemin="0"-->
    <!--                               aria-valuemax="100"-->
    <!--                               :style="{ width: progressInfo.percentage + '%' }"-->
    <!--                          >-->
    <!--                            {{progressInfo.percentage}}%-->
    <!--                          </div>-->
    <!--                        </div>-->
    <!--                      </div>-->
    <!--                    </div>-->
    <!--                    <v-row no-gutters justify="center" align="center">-->
    <!--                      <v-col cols="8">-->
    <!--                        <v-file-input multiple type="file"-->
    <!--                                      v-model="imageUpload"-->
    <!--                                      show-size-->
    <!--                                      label="Select Image"-->
    <!--                                      accept="image/*"-->
    <!--                                      @change="selectImage"-->
    <!--                        ></v-file-input>-->
    <!--                      </v-col>-->
    <!--                      <v-col cols="4" class="pl-2">-->
    <!--                        <v-btn color="primary"-->
    <!--                               dark-->
    <!--                               :disabled="!selectedFiles"-->
    <!--                               @click="uploadImages">-->
    <!--                          <v-icon left dark>mdi-cloud-upload</v-icon>-->
    <!--                          Upload-->
    <!--                        </v-btn>-->
    <!--                      </v-col>-->
    <!--                    </v-row>-->
    <!--                    <div v-if="message" class="alert alert-light" role="alert">-->
    <!--                      <ul>-->
    <!--                        <li v-for="(ms, i) in message.split('\n')" :key="i">-->
    <!--                          {{ ms }}-->
    <!--                        </li>-->
    <!--                      </ul>-->
    <!--                    </div>-->
    <!--                    <div class="card">-->
    <!--                      <div class="card-header">List of Files</div>-->
    <!--                      <ul class="list-group list-group-flush">-->
    <!--                        <li class="list-group-item"-->
    <!--                            v-for="(file, index) in fileInfos"-->
    <!--                            :key="index"-->
    <!--                        >-->
    <!--                          <a :href="file.url">{{ file.name }}</a>-->
    <!--                        </li>-->
    <!--                      </ul>-->
    <!--                    </div>-->
    <!--                  </div>-->
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
                              :loading="dataLoading"
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
                                  name="buyout"
                                  rules="required|min_value:1"
                          >
                            <v-text-field
                              v-model="addAuctionBuyout"
                              :loading="dataLoading"
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
                         :loading="loading"
                         dark
                         type="submit"
                         color="primary"
                         class="mt-6"
                         :disabled="invalid"
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
  </v-container>
</template>

<script>
  import {required, min, max, alpha, min_value, image, mimes, size} from 'vee-validate/dist/rules'
  import {extend, ValidationObserver, ValidationProvider, setInteractionMode} from 'vee-validate'
  import axios from "axios";
  import Swal from "sweetalert2";
// import MultipleImageUpload from "../services/MultipleImageUpload";

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
    categoryLoading: true,
    loading: false,

    //image data
    //
    // currentImage: undefined,
    // previewImage: undefined,
    // progress: 0,
    // message: "",
    // imageInfos: [],
    // imageUpload: null,
    //
    // selectedFiles: undefined,
    // progressInfos: [],
    // message: "",
    // fileInfos: [],
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

    updateAuction() {
      this.loading = true
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
        buyout: this.addAuctionBuyout
      })
              .then(response => {
                if (response) {
                  this.$emit('close')
                  this.$emit('reload')
                  Swal.fire({
                    title: 'Done!',
                    text: 'Auction updated successfully.',
                    icon: 'success'
                  })
                  this.showDialog = false
                  this.loading = false
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
    },

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

    // selectImage() {
    //   // this.currentImage = image;
    //   // this.previewImage = URL.createObjectURL(this.currentImage);
    //   // this.progress = 0;
    //   // this.message = "";
    //   this.progressInfos = [];
    //   this.selectedFiles = event.target.files;
    // },
    //
    // uploadImages() {
    //   this.message = "";
    //   for (let i = 0; i < this.selectedFiles.length; i++) {
    //     console.log(this.selectedFiles.name)
    //     this.upload(i, this.selectedFiles[i]);
    //   }
    // },
    //
    // upload(idx, file) {
    //   this.progressInfos[idx] = { percentage: 0, fileName: file.name };
    //   MultipleImageUpload.upload(file, (event) => {
    //     this.progressInfos[idx].percentage = Math.round(100 * event.loaded / event.total);
    //     console.log(idx)
    //     console.log(this.progressInfos[idx])
    //     console.log(file)
    //   })
    //           .then((response) => {
    //
    //             let prevMessage = this.message ? this.message + "\n" : "";
    //             this.message = prevMessage + response.data.message;
    //             return MultipleImageUpload.getFiles();
    //           })
    //           .then((files) => {
    //             this.fileInfos = files.data;
    //           })
    //           .catch(() => {
    //             this.progressInfos[idx].percentage = 0;
    //             this.message = "Could not upload the file:" + file.name;
    //           });
    // },

  }
}
</script>

<style scoped>

</style>