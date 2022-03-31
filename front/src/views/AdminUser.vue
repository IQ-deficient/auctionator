<template>
  <v-data-table
      style="width: 95%; margin: 0 auto; padding-top: 6px"
      :headers="headers"
      :items="users"
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
          <v-divider
              class="mx-4"
              inset
              vertical
          ></v-divider>
          <v-col cols="12" md="4">
            <v-select
                single-line
                hide-details
                v-model="selectRole"
                :items="roles"
                item-text="name"
                label="Roles"
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
                <v-icon left>mdi-account-multiple-plus-outline</v-icon>
                Create user
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
                      <validation-provider
                          v-slot="{ errors }"
                          name="addUserUsername"
                          rules="required"
                          clearable
                      >
                        <v-text-field
                            v-model="addUserUsername"
                            :error-messages="errors"
                            label="Username"
                            required
                            clearable
                        >
                        </v-text-field>
                      </validation-provider>
                      <v-row>
                        <v-col cols="12" sm="6">
                          <validation-provider
                              v-slot="{ errors }"
                              name="addUserFirstName"
                              rules="required"
                              clearable
                          >
                            <v-text-field
                                v-model="addUserFirstName"
                                :error-messages="errors"
                                label="First name"
                                required
                                clearable
                            >
                            </v-text-field>
                          </validation-provider>
                        </v-col>
                        <v-col cols="12" sm="6">
                          <validation-provider
                              v-slot="{ errors }"
                              name="addUserLastName"
                              rules="required"
                              clearable
                          >
                            <v-text-field
                                v-model="addUserLastName"
                                :error-messages="errors"
                                label="Last name"
                                required
                                clearable
                            >
                            </v-text-field>
                          </validation-provider>
                        </v-col>
                      </v-row>
                      <validation-provider
                          v-slot="{ errors }"
                          name="addUserEmail"
                          rules="required"
                          clearable
                      >
                        <v-text-field
                            v-model="addUserEmail"
                            :error-messages="errors"
                            label="Email"
                            required
                            clearable
                        >
                        </v-text-field>
                      </validation-provider>
                      <v-row>
                        <v-col cols="12" sm="6">
                          <validation-provider
                              v-slot="{ errors }"
                              name="addUserCountry"
                              rules="required"
                          >
                            <v-select
                                v-model="addUserCountry"
                                :error-messages="errors"
                                label="Country"
                                :items="countries"
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
                              name="addUserPhone"
                              rules="required"
                              clearable
                          >
                            <v-text-field
                                v-model="addUserPhone"
                                :error-messages="errors"
                                label="Phone number"
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
                    <v-icon left class="mr-1">mdi-account-plus-outline</v-icon>
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
// import UploadService from "../services/UploadFilesService";

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
  name: "AdminUser",

  components: {
    ValidationProvider,
    ValidationObserver,
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
    ],
    search: '',
    selectRole: '',
    roles: [],
    users: [],
    addUserUsername: '',
    addUserFirstName: '',
    addUserLastName: '',
    addUserEmail: '',
    addUserCountry: '',
    countries: [],
    addUserPhone: '',
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
    this.getUsers()
    this.getRoles()
    this.getCountries();
    // this.getParentCategories()
    // this.getWarehouse()
  },

  methods: {
    submit() {
      this.$refs.observer.validate()
    },
    clear() {
      this.email = ''
      this.password = ''
      this.checkbox = null
      this.$refs.observer.reset()
    },

    getUsers() {
      this.users = []
      axios.get('/users')
          .then(response => {
            if (response.data) {
              for (let i = 0; i < response.data.length; i++) {
                // console.log(response.data)
                this.users = response.data
              }
            }
          })
          .catch(error => {
            console.log(error)
          })
    },

    getRoles() {
      this.users = []
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

    getCountries() {
      this.users = []
      axios.get('/countries')
          .then(response => {
            if (response.data) {
              // console.log(response.data)
              this.countries = response.data
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
  },

  mounted() {
    document.title = 'Administration - Auction House'
  }
}
</script>

<style scoped>

</style>