<template>
  <v-container>
      <v-dialog
          v-model="showDialog"
          max-width="60%"
          persistent
      >
        <template>
          <validation-observer ref="form" v-slot="{invalid}">
            <form @submit.prevent="updateUser()">
          <v-card color="info">
            <v-card-actions class="justify-end">
              <v-btn
                  small
                  fab
                  @click="$emit('close')"
                  dark
              ><v-icon>mdi-close</v-icon>
              </v-btn>
            </v-card-actions>
            <v-card-text>
              <div class="pa-1">
                <v-toolbar-title class="pa-4">
                  <table style="width: 100%">
                    <tr>
                      <td>
                        <hr/>
                      </td>
                      <td style="width:1px; padding: 10px; white-space: nowrap;">
                        <h2 style="color: white">
                          User information</h2>
                      </td>
                      <td>
                        <hr/>
                      </td>
                    </tr>
                  </table>
                </v-toolbar-title>
                    <v-card class="pa-4">
                      <v-row>
                        <v-col
                            cols="12"
                            sm="6"
                        >
                          <validation-provider
                                  v-slot="{ errors }"
                                  name="first name"
                                  rules="required|alpha|min:1|max:32"
                          >
                            <v-text-field
                                    :loading="dataLoading"
                                    v-model="firstName"
                                    :error-messages="errors"
                                    label="First name"
                                    clearable
                            ></v-text-field>
                          </validation-provider>
                        </v-col>
                        <v-col
                            cols="12"
                            sm="6"
                        >
                          <validation-provider
                                  v-slot="{ errors }"
                                  name="last name"
                                  rules="required|alpha|min:1|max:32"
                          >
                            <v-text-field
                                    :loading="dataLoading"
                                    v-model="lastName"
                                    :error-messages="errors"
                                    label="Last name"
                                    clearable
                            ></v-text-field>
                          </validation-provider>
                        </v-col>
                      </v-row>
                      <v-row>
                        <v-col
                            cols="12"
                            sm="6">
                          <validation-provider
                                  v-slot="{ errors }"
                                  name="country"
                                  rules="required"
                                  clearable
                          >
                            <v-select
                                    v-model="selectCountry"
                                    :loading="dataLoading"
                                    :items="countries"
                                    item-text="name"
                                    :error-messages="errors"
                                    label="Country"
                                    return-object
                                    @change="updateCountryCode()"
                            ></v-select>
                          </validation-provider>
                        </v-col>
                        <v-col
                            cols="12"
                            sm="6">
<!--                          <validation-provider-->
<!--                              v-slot="{ errors }"-->
<!--                              name="Phone number"-->
<!--                              rules="required|numeric|min:8|max:15"-->
<!--                              clearable-->
<!--                          >-->
<!--                            <v-text-field-->
<!--                                :loading="dataLoading"-->
<!--                                :prefix="'(' + phoneCode + ')'"-->
<!--                                v-model="phoneNumber"-->
<!--                                :error-messages="errors"-->
<!--                                label="Phone number"-->
<!--                                append-icon="mdi-phone-classic"-->
<!--                            ></v-text-field>-->
<!--                          </validation-provider>-->
                          <validation-provider
                                  v-slot="{ errors }"
                                  name="phone number"
                                  rules="required|numeric|min:6|max:15"
                                  clearable
                          >
                            <v-text-field v-if="phoneCode != null"
                                          :loading="dataLoading"
                                          :prefix="'(' + phoneCode + ')'"
                                          v-model="phoneNumber"
                                          :error-messages="errors"
                                          label="Phone number"
                                          append-icon="mdi-phone-classic"
                            ></v-text-field>
                            <v-text-field v-else
                                          :loading="dataLoading"
                                          v-model="phoneNumber"
                                          :error-messages="errors"
                                          label="Phone number"
                                          append-icon="mdi-phone-classic"
                            ></v-text-field>
                          </validation-provider>
                        </v-col>
    <!--                    <v-col v-if="isAdmin"-->
    <!--                           cols="12"-->
    <!--                           sm="6">-->
    <!--                      <validation-provider-->
    <!--                          v-slot="{ errors }"-->
    <!--                          name="Role"-->
    <!--                          clearable-->
    <!--                      >-->
    <!--                        <v-select-->
    <!--                            :loading="dataLoading"-->
    <!--                            v-model="selectRole"-->
    <!--                            :items="roles"-->
    <!--                            :error-messages="errors"-->
    <!--                            item-text="name"-->
    <!--                            label="Role"-->
    <!--                            multiple-->
    <!--                        ></v-select>-->
    <!--                      </validation-provider>-->
    <!--                    </v-col>-->
                      </v-row>
                      <v-row>
                        <v-col
                                cols="12"
                                sm="6">
                          <validation-provider
                                  v-slot="{ errors }"
                                  name="Gender"
                                  clearable
                          >
                            <v-select
                                    :loading="dataLoading"
                                    v-model="selectGender"
                                    :items="genders"
                                    :error-messages="errors"
                                    item-text="name"
                                    label="Gender"
                            ></v-select>
                          </validation-provider>
                        </v-col>
                        <v-col
                                cols="12"
                                sm="6">
                          <validation-provider
                                  v-slot="{ errors }"
                                  name="Birth date"
                                  clearable
                          >
                            <v-dialog
                                    ref="dialog"
                                    v-model="dateDialog"
                                    :return-value.sync="date"
                                    persistent
                                    width="290px"
                            >
                              <template v-slot:activator="{ on, attrs }">
                                <v-text-field
                                        :loading="dataLoading"
                                        v-model="birthdate"
                                        :error-messages="errors"
                                        label="Pick a date"
                                        append-icon="mdi-cake-variant-outline"
                                        v-bind="attrs"
                                        v-on="on"
                                ></v-text-field>
                              </template>
                              <v-date-picker
                                      v-model="birthdate"
                                      scrollable
                              >
                                <v-spacer></v-spacer>
                                <v-btn
                                        text
                                        color="primary"
                                        @click="dateDialog = false"
                                >
                                  Cancel
                                </v-btn>
                                <v-btn
                                        text
                                        color="primary"
                                        @click="$refs.dialog.save(date)"
                                >
                                  OK
                                </v-btn>
                              </v-date-picker>
                            </v-dialog>
                          </validation-provider>
                        </v-col>
                      </v-row>
                      <v-row>
                        <v-col>
                          <validation-provider
                                  v-slot="{ errors }"
                                  name="user roles"
                                  rules="required"
                                  clearable
                          >
                          <v-select
                              :loading="dataLoading"
                              :error-messages="errors"
                              v-if="editType == 'Employees'"
                              v-model="checkedRoles"
                              label="Select user roles"
                              multiple
                              :items="employeeRoles"
                              item-text="name"
                          >
                          </v-select>
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
                     :disabled="invalid"
              >
                <v-icon left class="mr-1">mdi-account-plus-outline</v-icon>
                Update
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
import {required, digits, max,} from 'vee-validate/dist/rules'
import {extend, ValidationObserver, ValidationProvider, setInteractionMode} from 'vee-validate'
import axios from "axios";
import Swal from "sweetalert2";
// import UploadService from "../services/UploadFilesService";

setInteractionMode('eager')

extend('digits', {
  ...digits,
  message: '{_field_} needs to be a digit.',
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

extend('password', {
  params: ['target'], validate(value, {target}) {
    return value === target;
  },
  message: 'Passwords do not match'
});

export default {
  name: "EditAuctionDialog",
  props: {
    showDialog: {
      type: Boolean,
      default: false
    },
    user: null,
    editType: null,
    employeeRoles: null,
  },

  components: {
    ValidationProvider,
    ValidationObserver,
  },

  data: () => ({
    dataLoading: false,
    firstName: '',
    lastName: '',
    selectGender: '',
    genders: [],
    roles: [],
    birthdate: '',
    isAdmin: window.localStorage.user_roles.includes('Administrator'),

    date: (new Date(Date.now() - (new Date()).getTimezoneOffset() * 60000)).toISOString().substr(0, 10),
    dateDialog: false,

    selectCountry: '',
    countries: [],
    phoneCode: '',
    phoneNumber: '',
    checkedRoles: [],
  }),

  created() {
    this.getGenders();
    this.getCountries();
    this.getUserRoles();

    this.firstName = this.user.first_name
    this.lastName = this.user.last_name
    this.selectGender = this.user.gender
    this.birthdate = this.user.birthdate
    this.selectCountry = this.user.country
    this.phoneNumber = this.user.phone_number
  },

  methods: {

    getGenders() {
      this.dataLoading = true;
      axios.get('/genders')
          .then(response => {
            if (response.data) {
              this.genders = response.data
              this.dataLoading = false;
            }
          })
          .catch(error => {
            this.dataLoading = false;
            console.log(error)
          })
    },

    getUserRoles() {
      this.dataLoading = true;
      axios.get('/user_roles/' + this.user.id)
          .then(response => {
            if (response.data) {
              this.checkedRoles = response.data
              this.dataLoading = false;
            }
          })
          .catch(error => {
            this.dataLoading = false;
            console.log(error)
          })
    },

    getCountries() {
      this.dataLoading = true;
      axios.get('/active_countries')
          .then(response => {
            if (response.data) {
              this.countries = response.data
              this.dataLoading = false
            }
            for (let i = 0; i < this.countries.length; i++) {
              if (this.countries[i].name == this.selectCountry) {
                this.selectCountry = this.countries[i]
              }
            }
            this.phoneCode = this.selectCountry.phone_code
          })
          .catch(error => {
            this.dataLoading = false
            console.log(error)
          })
    },

    updateCountryCode() {
      this.phoneCode = this.selectCountry.phone_code
    },

    updateUser() {
      this.$refs.form.validate().then(success => {
        if (success) {
          this.loading = true
          axios.put('/manage/' + this.user.id, {
            first_name: this.firstName,
            last_name: this.lastName,
            gender: this.selectGender,
            birthdate: this.birthdate,
            country: this.selectCountry.name,
            phone_number: this.phoneNumber,
            roles: this.checkedRoles
          })
                  .then(response => {
                            if (response) {
                              this.$emit('close')
                              this.$emit('reload')
                              Swal.fire({
                                title: 'Done!',
                                text: 'User updated successfully.',
                                icon: 'success'
                              })
                              this.showDialog = false;
                              this.loading = false;
                            }
                          }
                  )
                  .catch(error => {
                    console.log(error)
                    this.loading = false
                    this.error = error.response.data.message;
                  })
        }
      })
    },
  }
}
</script>

<style scoped>

</style>