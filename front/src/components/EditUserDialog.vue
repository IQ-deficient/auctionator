<template>
  <v-container>
    <validation-observer ref="observer" v-slot="{ invalid }" tag="form" @submit.prevent="updateUser()">
      <v-dialog
        v-model="showDialog"
        max-width="60%"
        persistent
      >
        <template>
          <v-card color="#2c3e50">
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
                        name="First name"
                        rules="required|min:3|max:32"
                        clearable
                      >
                        <v-text-field
                          v-model="firstName"
                          :error-messages="errors"
                          label="First name"
                          required
                        ></v-text-field>
                      </validation-provider>
                    </v-col>
                    <v-col
                      cols="12"
                      sm="6"
                    >
                      <validation-provider
                        v-slot="{ errors }"
                        name="Last name"
                        rules="required|min:3|max:32"
                        clearable
                      >
                        <v-text-field
                          v-model="lastName"
                          :error-messages="errors"
                          label="Last name"
                          required
                        ></v-text-field>
                      </validation-provider>
                    </v-col>
                  </v-row>
                  <v-row>
                    <v-col
                      cols="12"
                      sm="5">
                      <validation-provider
                        v-slot="{ errors }"
                        name="Gender"
                        clearable
                      >
                        <v-select
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
                      sm="7">
                      <validation-provider
                        v-slot="{ errors }"
                        name="Birth date"
                        clearable
                      >
                        <v-dialog
                          ref="dialog"
                          v-model="modal"
                          :return-value.sync="date"
                          persistent
                          width="290px"
                        >
                          <template v-slot:activator="{ on, attrs }">
                            <v-text-field
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
                              @click="modal = false"
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
                    <v-col
                      cols="12"
                      sm="5">
                      <validation-provider
                        v-slot="{ errors }"
                        name="Country"
                        rules="required"
                        clearable
                      >
                        <v-select
                          v-model="selectCountry"
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
                      sm="7">
                      <validation-provider
                        v-slot="{ errors }"
                        name="Phone number"
                        rules="required|numeric|min:8|max:15"
                        clearable
                      >
                        <v-text-field
                          :prefix="'(' + phoneCode + ')'"
                          v-model="phoneNumber"
                          :error-messages="errors"
                          label="Phone number"
                          append-icon="mdi-phone-classic"
                        ></v-text-field>
                      </validation-provider>
                    </v-col>

                      <v-col
                          cols="12"
                          sm="6">
                        <validation-provider
                            v-slot="{ errors }"
                            name="Role"
                            clearable
                        >
                          <v-select
                              v-model="selectRole"
                              :items="roles"
                              :error-messages="errors"
                              item-text="name"
                              label="Role"
                              multiple
                          ></v-select>
                        </validation-provider>
                      </v-col>

                  </v-row>
                  <v-row>
                    <v-col>
                      <v-select
                        v-if="editType == 'Employees'"
                        v-model="checkedRoles"
                        label="Select user roles"
                        multiple
                        :items="employeeRoles"
                        item-text="name"
                      >
                      </v-select>
                    </v-col>
                  </v-row>
                </v-card>
              </div>
              <v-btn large
                     dark
                     type="submit"
                     color="primary"
                     class="mt-6"
                     :disabled="invalid || !firstName || !lastName || !selectCountry ||
                                    !phoneNumber"
                     @click="updateUser()"
              >
                <v-icon left class="mr-1">mdi-account-plus-outline</v-icon>
                Update
              </v-btn>
            </v-card-text>
            <v-card-actions class="justify-end">
              <v-btn
                text
                @click="$emit('close')"
                dark
              >Close
              </v-btn>
            </v-card-actions>
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
import {required, digits, max,} from 'vee-validate/dist/rules'
import {extend, ValidationObserver, ValidationProvider, setInteractionMode} from 'vee-validate'
import axios from "axios";
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
    // username: '',
    firstName: '',
    lastName: '',
    // email: '',
    selectGender: '',
    genders: [],
    roles: [],
    birthdate: '',

    date: (new Date(Date.now() - (new Date()).getTimezoneOffset() * 60000)).toISOString().substr(0, 10),
    menu: false,
    modal: false,
    menu2: false,

    selectCountry: '',
    countries: [],
    phoneCode: '',
    phoneNumber: '',
    checkedRoles: [],
    dataLoading: false
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
      axios.get('/genders')
        .then(response => {
          if (response.data) {
            this.genders = response.data
          }
        })
        .catch(error => {
          console.log(error)
        })
    },

    getUserRoles() {
      axios.get('/user_roles/' + this.user.id)
        .then(response => {
          if (response.data) {
            this.checkedRoles = response.data
          }
        })
        .catch(error => {
          console.log(error)
        })
    },

    getCountries() {
      axios.get('/active_countries')
        .then(response => {
          if (response.data) {
            this.countries = response.data
          }
          for (let i = 0; i < this.countries.length; i++) {
            if (this.countries[i].name == this.selectCountry) {
              this.selectCountry = this.countries[i]
            }
          }
          this.phoneCode = this.selectCountry.phone_code
        })
        .catch(error => {
          console.log(error)
        })
    },

    updateCountryCode() {
      this.phoneCode = this.selectCountry.phone_code
    },

    updateUser() {
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
              window.alert('bravo kretenu nemas sweetalert')
              this.loading = false;
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
  //todo : ovdje ide logika methods() i axios
}
</script>

<style scoped>

</style>