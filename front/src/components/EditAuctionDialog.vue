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
                  @click="$emit('close')"
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
                            v-model="addAuctionTitle"
                            :loading="dataLoading"
                            :error-messages="errors"
                            label="Auction title"
                            clearable
                            prepend-icon="mdi-form-textbox"
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
                            v-model="addAuctionSeller"
                            :loading="dataLoading"
                            :error-messages="errors"
                            label="Seller"
                            clearable
                            prepend-icon="mdi-account-tie"
                          >
                          </v-text-field>
                        </validation-provider>
                      </v-col>
                    </v-row>
                    <v-row>
                      <v-col cols="6" sm="6">
                        <validation-provider
                          v-slot="{ errors }"
                          name="minimum bid value"
                          rules="required|double|min_value:0"
                        >
                          <v-text-field
                            prepend-icon="mdi-currency-eur"
                            :loading="dataLoading"
                            v-model="minimumBidValue"
                            :error-messages="errors"
                            label="Minimum Bid Value"
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
                            :loading="dataLoading"
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
import {required, min, max, alpha_spaces, min_value, image, mimes, size} from 'vee-validate/dist/rules'
import {extend, ValidationObserver, ValidationProvider, setInteractionMode} from 'vee-validate'
import axios from "axios";
import Swal from "sweetalert2";

setInteractionMode('eager')

extend('greater', {
  params: ['target'],
  validate(value, {target}) {
    return value > target;
  },
  message: 'The {_field_} must have a greater value than minimum required bid value.'
});

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
  message: 'The {_field_} must be at least 2€ (Euro).'
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
    minimumBidValue: '',
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
    this.minimumBidValue = this.auction.min_bid_value
    this.addAuctionBuyout = this.auction.buyout
    this.addItemSubCategory = this.auction.item.category.name
    this.addItemCondition = this.auction.item.condition
  },

  methods: {

    minBuyoutValueRules() {
      return "required|greater:@minimum bid value|double|min_value_buyout:" + process.env.VUE_APP_MIN_BUYOUT + ""
    },

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
        buyout: this.addAuctionBuyout,
        min_bid_value: this.minimumBidValue
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
            this.loading = false
          }
        })
        .catch(error => {
          if (error.response.data['end_datetime'] == 'The end datetime must be a date after start datetime.') {
            Swal.fire({
              icon: 'error',
              text: error.response.data['end_datetime'],
            })
            console.log(error)
          } else if (error.response.data['start_datetime'] == 'The start datetime must be a date after today.') {
            Swal.fire({
              icon: 'error',
              text: error.response.data['start_datetime'],
            })
            console.log(error)
          } else if (error.response.status == 400 || error.response.status == 410 || error.response.status == 422) {
            Swal.fire({
              icon: 'error',
              text: error.response.data.message,
            })
            console.log(error)
            // todo: console.log(error.response.data, 'response error')
          } else {
            Swal.fire({
              icon: 'error',
              title: 'Error',
              text: error.response.data.message,
            })
            console.log(error)
          }
          this.loading = false
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

  }
}
</script>

<style scoped>

</style>