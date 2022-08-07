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
          class="mb-5"
        >
          <v-btn :loading="dataLoading" color="primary" class="mr-3"
                 @click="getUsers()">
            <v-icon>mdi-database-refresh-outline</v-icon>
          </v-btn>
          <v-toolbar-title><h2>User Management</h2></v-toolbar-title>
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
                :loading="dataLoading"
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
          <v-dialog
            v-model="modal"
            max-width="60%"
            ref="form"
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
                Enroll an employee
              </v-btn>
            </template>
            <template v-slot:default="dialog">
              <validation-observer ref="form">
                <form @submit.prevent="createUser()">
                  <v-card color="info" class="pa-4">
                    <v-card-title>
                      <v-row class="justify-end">
                        <v-btn
                          @click="clearForm(); dialog.value = false"
                          color="secondary"
                        >
                          <v-icon>mdi-close</v-icon>
                        </v-btn>
                      </v-row>
                    </v-card-title>
                    <v-toolbar-title class="pa-1">
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
                        <v-col cols="6">
                          <validation-provider
                            v-slot="{ errors }"
                            name="first name"
                            rules="required|alpha_spaces|min:1|max:32"
                          >
                            <v-text-field
                              v-model="firstName"
                              :error-messages="errors"
                              label="First name"
                              clearable
                            ></v-text-field>
                          </validation-provider>
                        </v-col>
                        <v-col cols="6"
                        >
                          <validation-provider
                            v-slot="{ errors }"
                            name="last name"
                            rules="required|alpha_spaces|min:1|max:32"
                          >
                            <v-text-field
                              v-model="lastName"
                              :error-messages="errors"
                              label="Last name"
                              clearable
                            ></v-text-field>
                          </validation-provider>
                        </v-col>
                      </v-row>
                      <v-row>
                        <v-col cols="12">
                          <validation-provider
                            v-slot="{ errors }"
                            name="username"
                            rules="required|min:3|max:32"
                          >
                            <v-text-field
                              v-model="username"
                              :error-messages="errors"
                              label="Username"
                              clearable
                            ></v-text-field>
                          </validation-provider>
                        </v-col>
                      </v-row>
                      <v-row>
                        <v-col cols="6">
                          <validation-provider
                            v-slot="{ errors }"
                            name="country"
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
                        <v-col cols="6">
                          <validation-provider
                            v-slot="{ errors }"
                            name="phone number"
                            rules="required|numeric|min:6|max:15"
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
                        <v-col cols="12">
                          <validation-provider
                            v-slot="{ errors }"
                            name="email"
                            rules="required|email|min:10|max:254"
                          >
                            <v-text-field
                              v-model="email"
                              :error-messages="errors"
                              hint="example@mail.com"
                              append-icon="mdi-email"
                              label="Email"
                              clearable
                            ></v-text-field>
                          </validation-provider>
                        </v-col>
                      </v-row>
                      <v-row>
                        <v-col cols="12">
                          <validation-provider
                            v-slot="{ errors }"
                            name="password"
                            rules="required|min:8|max:128"
                            clearable
                          >
                            <v-text-field
                              v-model="password"
                              :error-messages="errors"
                              label="Password"
                              :type="showPassword ? 'text' : 'password'"
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
                        <v-col cols="12">
                          <validation-provider
                            v-slot="{ errors }"
                            name="password confirmation"
                            rules="required|password:@password"
                            clearable
                          >
                            <v-text-field
                              v-model="confirmPassword"
                              :error-messages="errors"
                              label="Confirm password"
                              :type="showConfirmPass ? 'text' : 'password'"
                              :append-icon="showConfirmPass ? 'mdi-eye' : 'mdi-eye-off'"
                              @click:append="showConfirmPass = !showConfirmPass"
                              clearable
                            >
                            </v-text-field>
                          </validation-provider>
                        </v-col>
                      </v-row>
                      <v-row>
                        <v-col cols="12">
                          <validation-provider
                            v-slot="{ errors }"
                            name="employee role"
                            rules="required"
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
                    <v-btn large
                           dark
                           type="submit"
                           color="primary"
                           class="mt-4"
                    >
                      <v-icon left class="mr-1">mdi-account-plus-outline</v-icon>
                      Create
                    </v-btn>
                  </v-card>
                </form>
              </validation-observer>
            </template>
          </v-dialog>
        </v-toolbar>
      </template>
      <template v-slot:item.actions="{ item }">
        <v-row>
          <v-col cols="12" sm="4">
            <v-icon
              color="primary"
              @click="editUser(item)"
            >
              mdi-pencil
            </v-icon>
          </v-col>
          <v-col cols="12" sm="4">
            <v-icon
              color="primary"
              @click="deleteUser(item)"
            >
              mdi-account-cancel-outline
            </v-icon>
          </v-col>
        </v-row>
      </template>
    </v-data-table>
    <edit-user
      v-if="editUserDialogue"
      @close="editUserDialogue = false"
      @reload="getUsers()"
      :edit-type="selectRole"
      :show-dialog="editUserDialogue"
      :user="chosenUser"
      :employee-roles="employeeRoles"
    />
  </div>
</template>

<script>
import axios from "axios";
import {required, numeric, email, min, max, alpha_spaces, alpha_num} from 'vee-validate/dist/rules'
import {extend, ValidationObserver, ValidationProvider, setInteractionMode} from 'vee-validate'
import EditUserDialog from "../components/EditUserDialog";
import Swal from "sweetalert2";

setInteractionMode('eager')

extend('required', {
  ...required,
  message: 'The {_field_} field is required.',
})

extend('email', {
  ...email,
  message: 'The {_field_} must be a valid email address.',
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

extend('alpha_num', {
  ...alpha_num,
  message: 'The {_field_} may only contain letters and numbers.',
})

extend('numeric', {
  ...numeric,
  message: 'The {_field_} must be a number.',
})

extend('password', {
  params: ['target'], validate(value, {target}) {
    return value === target;
  },
  message: 'The {_field_} confirmation does not match.'
});


export default {
  name: "AdminUser",

  components: {
    ValidationProvider,
    ValidationObserver,
    'edit-user': EditUserDialog,
  },

  data: () => ({
    dialog: false,
    modal: false,
    dialogDelete: false,
    headers: [
      {text: 'ID', align: 'start', sortable: false, value: 'id',},
      {text: 'Username', value: 'username'},
      {text: 'Email', value: 'email'},
      {text: 'First name', value: 'first_name'},
      {text: 'Last name', value: 'last_name'},
      {text: 'Country', value: 'country'},
      {text: 'Phone number', value: 'phone_number'},
      {text: 'Actions', value: 'actions', sortable: false},
      {text: '', value: 'roles', sortable: false, align: 'center'},
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
    password: '',
    confirmPassword: '',
    showPassword: false,
    showConfirmPass: false,
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

  created() {
    this.getUsers()
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

    deleteUser(item) {
      Swal.fire({
        title: 'Are you sure you want to permanently remove this user?',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#915A64',
        cancelButtonColor: '#909C6B',
        confirmButtonText: "Yes, I'm sure!"
      }).then((result) => {
        if (result.isConfirmed) {
          this.loading = true;
          axios.delete('user/' + item.id)
            .then(response => {
              if (response.data) {
                Swal.fire(
                  'Success!',
                  "The user has been suspended.",
                  'success',
                )
                this.loading = false
                this.modal = false
                this.getUsers()
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

    createUser() {
      this.$refs.form.validate().then(success => {
        if (success) {
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
                  })
                  this.loading = false
                  this.modal = false
                  this.getUsers()
                  this.clearForm()
                }
              }
            )
            .catch(error => {
              if (error.response.status == 403) {
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

    editUser(user) {
      this.editUserDialogue = true
      this.chosenUser = user
    },

    getUsers() {
      this.dataLoading = true
      this.users = []
      axios.get('/users')
        .then(response => {
          if (response.data) {
            this.users = response.data
            this.tableData = response.data.clients
          }
          this.selectRole = this.roles[0]
          this.dataLoading = false
        })
        .catch(error => {
          console.log(error)
          this.dataLoading = false
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

    clearForm() {
      this.username = ''
      this.firstName = ''
      this.lastName = ''
      this.email = ''
      this.selectCountry = ''
      this.phoneCode = ''
      this.phoneNumber = ''
      this.password = ''
      this.confirmPassword = ''
      this.selectEmployeeRoles = ''
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

<style scoped>

</style>