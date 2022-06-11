<template>
  <div>
    <v-data-table
        style="width: 95%; margin: 0 auto; padding-top: 6px"
        :loading="dataLoading"
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
          <v-toolbar-title><h2>Users</h2></v-toolbar-title>
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
            <v-divider v-if="isAdmin"
                       class="mx-4"
                       inset
                       vertical
            ></v-divider>
            <v-col cols="12" md="4" v-if="isAdmin">
              <v-select
                  single-line
                  hide-details
                  v-model="selectRole"
                  :items="roles"
                  item-text="name"
                  label="Roles"
                  @change="updateTableData()"
              ></v-select>
            </v-col>
          </v-row>

          <v-spacer></v-spacer>
          <validation-observer ref="observer" v-slot="{ invalid }" tag="form" @submit.prevent="createUser()">
            <v-dialog
                v-model="dialog"
                max-width="60%"
            >
              <template v-slot:activator="{ on, attrs }">
                <v-btn v-if="isAdmin"
                       color="primary"
                       dark
                       class="mb-2"
                       v-bind="attrs"
                       v-on="on"
                >
                  <v-icon left>mdi-account-multiple-plus-outline</v-icon>
                  Create employee
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
                          <v-col cols="12" sm="12">
                            <validation-provider
                                v-slot="{ errors }"
                                name="Username"
                                rules="required|min:3|max:32"
                                clearable
                            >
                              <v-text-field
                                  v-model="username"
                                  :error-messages="errors"
                                  label="Username"
                                  required
                              ></v-text-field>
                            </validation-provider>
                          </v-col>
                        </v-row>
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
                          <v-col cols="12" sm="12">
                            <validation-provider
                                v-slot="{ errors }"
                                name="Email"
                                rules="required|email|min:3|max:254"
                                clearable
                            >
                              <v-text-field
                                  v-model="email"
                                  :error-messages="errors"
                                  label="Email"
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
                              <v-text-field v-if="phoneCode != null"
                                            :prefix="'(' + phoneCode + ')'"
                                            v-model="phoneNumber"
                                            :error-messages="errors"
                                            label="Phone number"
                                            append-icon="mdi-phone-classic"
                              ></v-text-field>
                              <v-text-field v-else
                                            v-model="phoneNumber"
                                            :error-messages="errors"
                                            label="Phone number"
                                            append-icon="mdi-phone-classic"
                              ></v-text-field>
                            </validation-provider>
                          </v-col>
                        </v-row>
                        <v-row>
                          <v-col
                              cols="12"
                              sm="12">
                            <validation-provider
                                v-slot="{ errors }"
                                name="Password"
                                rules="required|min:8|max:128"
                                clearable
                            >
                              <v-text-field
                                  v-model="password"
                                  :error-messages="errors"
                                  label="Password"
                                  :type="showPassword ? 'text' : 'password'"
                                  counter
                                  required
                                  hint="Must be at least 8 characters."
                                  :append-icon="showPassword ? 'mdi-eye' : 'mdi-eye-off'"
                                  @click:append="showPassword = !showPassword"
                                  clearable
                              >
                              </v-text-field>
                            </validation-provider>
                          </v-col>
                        </v-row>
                        <v-row>
                          <v-col
                              cols="12"
                              sm="12">
                            <validation-provider
                                v-slot="{ errors }"
                                name="Password confirmation"
                                rules="required|min:8|max:128|password:@Password"
                                clearable
                            >
                              <v-text-field
                                  v-model="confirmPassword"
                                  :error-messages="errors"
                                  label="Confirm password"
                                  :type="showConfirmPassword ? 'text' : 'password'"
                                  counter
                                  required
                                  hint="Must be at least 8 characters."
                                  :append-icon="showConfirmPassword ? 'mdi-eye' : 'mdi-eye-off'"
                                  @click:append="showConfirmPassword = !showConfirmPassword"
                                  clearable
                              >
                              </v-text-field>
                            </validation-provider>
                          </v-col>
                        </v-row>
                        <v-row>
                          <v-col
                              cols="12"
                              sm="12">
                            <validation-provider
                                v-slot="{ errors }"
                                name="Country"
                                rules="required"
                                clearable
                            >
                              <v-select
                                  v-model="selectEmployeeRoles"
                                  :error-messages="errors"
                                  :items="employee_roles"
                                  label="Choose role"
                                  multiple
                                  chips
                                  persistent-hint
                              ></v-select>
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
                           :disabled="invalid || !username || !lastName || !email ||
                                    !selectCountry || !phoneNumber || !password || !confirmPassword"
                           @click="createUser()"
                    >
                      <v-icon left class="mr-1">mdi-account-plus-outline</v-icon>
                      Create
                    </v-btn>
                  </v-card-text>
                </v-card>
              </template>
            </v-dialog>
            <!--            <v-dialog v-model="dialogDelete" max-width="35%">-->
            <!--              <v-card>-->
            <!--                <v-card-title class="text-h5">Are you sure you want to delete this item?</v-card-title>-->
            <!--                <v-card-actions>-->
            <!--                  <v-spacer></v-spacer>-->
            <!--                  <v-btn color="blue darken-1" text @click="closeDelete">Cancel</v-btn>-->
            <!--                  <v-btn color="blue darken-1" text @click="deleteItemConfirm">OK</v-btn>-->
            <!--                  <v-spacer></v-spacer>-->
            <!--                </v-card-actions>-->
            <!--              </v-card>-->
            <!--            </v-dialog>-->
          </validation-observer>

        </v-toolbar>
      </template>
      <template v-slot:item.actions="{ item }">
        <v-icon
            class="mr-2"
            @click="editUser(item)"
        >
          mdi-pencil
        </v-icon>
        <!--          <v-icon-->
        <!--              @click="deleteUser(item)"-->
        <!--          >-->
        <!--            mdi-delete-->
        <!--          </v-icon>-->
      </template>
    </v-data-table>
    <edit-user
        v-if="editUserDialogue"
        @close="editUserDialogue = false"
        :edit-type="selectRole"
        :show-dialog="editUserDialogue"
        :user="chosenUser"
        :employee-roles="employeeRoles"
    />
  </div>
</template>

<script>
import axios from "axios";
import {required, digits, email, max} from 'vee-validate/dist/rules'
import {extend, ValidationObserver, ValidationProvider, setInteractionMode} from 'vee-validate'
import EditUserDialog from "../components/EditUserDialog";
import Swal from "sweetalert2";
// import UploadService from "../services/UploadFilesService";

setInteractionMode('eager')

extend('digit', {
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
  message: '{_field_} may not be greater than {length} characters.',
})

extend('password', {
  params: ['target'], validate(value, {target}) {
    return value === target;
  },
  message: 'Passwords do not match.'
});

extend('email', {
  ...email,
  message: 'Must be a valid email.',
})


export default {
  name: "AdminUser",

  components: {
    ValidationProvider,
    ValidationObserver,
    'edit-user': EditUserDialog,
  },

  data: () => ({
    dialog: false,
    dialogDelete: false,
    headers: [
      {
        text: 'ID',
        align: 'start',
        sortable: false,
        value: 'id',
      },
      {text: 'Username', value: 'username'},
      {text: 'First name', value: 'first_name'},
      {text: 'Last name', value: 'last_name'},
      {text: 'Email', value: 'email'},
      {text: 'Country', value: 'country'},
      {text: 'Phone number', value: 'phone_number'},
      {text: '', value: 'actions', sortable: false},
      {text: '', value: 'roles', sortable: false},
    ],
    search: '',
    selectRole: '',
    roles: [
      'Clients',
      'Employees'
    ],
    users: [],
    username: '',
    firstName: '',
    lastName: '',
    email: '',
    selectCountry: '',
    countries: [],
    phoneCode: '',
    phoneNumber: '',
    // closeDelete: '',
    // deleteItemConfirm: '',
    password: '',
    confirmPassword: '',
    showPassword: false,
    showConfirmPassword: false,
    user_roles: [],
    tableData: [],
    editUserDialogue: false,
    chosenUser: '',
    selectEmployeeRoles: '',
    employee_roles: [
      'Manager',
      'Auctioneer'
    ],
    dataLoading: false,
    employeeRoles: [],
    isAdmin: window.localStorage.user_roles.includes('Administrator'),
    allowedRoles: window.localStorage.user_roles.includes('Administrator') || window.localStorage.user_roles.includes('Manager')
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
    // this.getUserRoles()
    this.getUsers()
    // this.getRoles()
    this.getCountries()
    this.getEmployeeRoles()
  },

  methods: {
    updateTableData() {
      if (this.selectRole == 'Clients') {
        this.tableData = this.users.clients
      } else {
        this.tableData = this.users.managers_auctioneers
      }
    },
    submit() {
      this.$refs.observer.validate()
    },

    editUser(user) {
      this.editUserDialogue = true
      this.chosenUser = user
    },

    clear() {
      this.email = ''
      this.password = ''
      this.checkbox = null
      this.$refs.observer.reset()
    },

    getUsers() {
      this.dataLoading = true;
      this.users = []
      axios.get('/users')
          .then(response => {
            if (response.data) {
              this.users = response.data
              this.tableData = response.data.clients
            }
            this.selectRole = this.roles[0]
            this.dataLoading = false;
          })
          .catch(error => {
            console.log(error)
            this.dataLoading = false;
          })
    },

    getRoles() {
      this.roles = []
      axios.get('/roles')
          .then(response => {
            if (response.data) {
              // console.log(response.data)
              this.roles = response.data
            }
          })
          .catch(error => {
            console.log(error)
          })
    },

    getEmployeeRoles() {
      axios.get('/employees')
          .then(response => {
            if (response.data) {
              this.employeeRoles = response.data
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

    // getUserRoles() {
    //   this.user_roles = []
    //   axios.get('/auth_roles')
    //       .then(response => {
    //         if (response.data) {
    //           this.user_roles = response.data
    //         }
    //         if (!this.user_roles.includes('Administrator')) {
    //           this.$router.push('/pageNotFound')
    //         }
    //       })
    //       .catch(error => {
    //         console.log(error)
    //       })
    // },

    createUser() {
      this.loading = true
      axios.post('employee', {
        username: this.username,
        first_name: this.firstName,
        last_name: this.lastName,
        email: this.email,
        country: this.selectCountry.name,
        phone_number: this.phoneNumber,
        password: this.password,
        password_confirmation: this.confirmPassword,
        roles: this.selectEmployeeRoles

      })
          .then(response => {
                if (response) {
                  Swal.fire({
                    title: 'Done!',
                    text: 'Employee created successfully.',
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
  },

  mounted() {
    if (!this.allowedRoles) {
      this.$router.push('/pageNotFound')
    }
    document.title = 'User Management - Auction House'
  }
}
</script>

<style scoped>

</style>