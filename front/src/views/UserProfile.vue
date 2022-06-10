<template>
  <div>
    <v-parallax height="175" src="https://cdn.vuetifyjs.com/images/parallax/material2.jpg">
      <div class="fill-height repeating-gradient"></div>
    </v-parallax>

    <validation-observer ref="observer" v-slot="{ invalid }" tag="form" @submit.prevent="updateProfile()">
      <v-card width="50%"
              style="margin: 0 auto; position: relative; top: -25px" color="info" class="pa-4">
        <v-row class="">
          <v-col cols="12" sm="12">
            <div style="position: relative; width: 145px; height: 0; margin: 0 auto">
              <div style="position: absolute; top: -120px">
                <v-avatar size="145">
                  <v-img v-if="userImage"
                         :loading="pageLoading"
                         :src="require('../../../back/public/' + userImage)"
                         lazy-src="../assets/user image.svg"
                         :alt="loggedUser.first_name">
                    <template v-slot:placeholder>
                      <v-row
                          class="fill-height ma-0"
                          align="center"
                          justify="center"
                      >
                        <v-progress-circular
                            indeterminate
                            width="14"
                            :size="145"
                            color="primary"
                        ></v-progress-circular>
                      </v-row>
                    </template>
                  </v-img>
                  <!-- TODO: LOADING FOR IMAGE AND OTHER FILLABLE FIELDS -->
                  <v-img v-else
                         lazy-src="../assets/user image.svg"
                         src="../assets/user image.svg"
                         alt="User image placeholder">
                  </v-img>
                </v-avatar>
              </div>
              <v-row>
                <v-dialog
                    v-model="imageDialog"
                    transition="scale-transition"
                    max-width="45%"
                >
                  <template v-slot:activator="{ on, attrs }">
                    <a
                        v-bind="attrs"
                        v-on="on"
                    >
                      <v-icon dark large left>
                        mdi-pencil-outline
                      </v-icon>

                    </a>
                  </template>
                  <template v-slot:default="dialog">
                    <v-card class="pa-4">
                      <v-card-text>
                        <div>
                          <v-row no-gutters justify="center" align="center">
                            <v-col cols="8">
                              <v-file-input
                                  v-model="imageUpload"
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
                                  color="success"
                                  height="25"
                                  reactive
                              >
                                <strong>{{ progress }} %</strong>
                              </v-progress-linear>
                            </div>
                          </div>

                          <v-alert v-if="message" border="left" color="error" dark>
                            {{ message }}
                          </v-alert>

                          <div v-if="previewImage">
                            <div>
                              <img style="width: 100%" class="preview my-3" :src="previewImage" alt=""/>
                            </div>
                          </div>

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
                            @click="dialog.value = false;
                          currentImage = undefined;
                          previewImage = undefined;
                          progress = 0;
                          message = '';"
                        >Close
                        </v-btn>
                      </v-card-actions>
                    </v-card>
                  </template>
                </v-dialog>
              </v-row>
            </div>
          </v-col>
        </v-row>
        <v-form>
          <v-container class="pa-4">
            <v-row style="margin-top: auto">
              <v-col cols="12"
                     sm="6"
                     style="margin: 0 auto">
                <v-text-field style="text-align: center"
                              :loading="pageLoading"
                              v-model="username"
                              label="Username"
                              append-icon="mdi-account-edit-outline"
                              shaped
                              dark
                              disabled
                ></v-text-field>
              </v-col>
              <v-col style="margin: 0 auto"
                     cols="12"
                     sm="6">
                <v-text-field
                    v-model="email"
                    :loading="pageLoading"
                    label="Email"
                    append-icon="mdi-email-edit-outline"
                    shaped
                    dark
                    disabled
                ></v-text-field>
              </v-col>
            </v-row>
            <v-col cols="12" sm="12" class="mb-6">
              <div class="row justify-space-between">
                <div v-if="edit">
                  <v-btn
                      color="accent"
                      @click="edit = !edit">

                    <v-icon left>mdi-pencil</v-icon>
                    Edit
                  </v-btn>
                </div>
                <div v-else>
                  <v-btn
                      color="accent"
                      @click="edit = !edit">

                    <v-icon left>mdi-close</v-icon>
                    Cancel
                  </v-btn>
                </div>
                <v-spacer></v-spacer>
                <v-dialog
                    transition="dialog-bottom-transition"
                    max-width="35%"
                    v-model="passwordDialog"
                >
                  <template v-slot:activator="{ on, attrs }">
                    <v-btn
                        color="accent"
                        v-bind="attrs"
                        v-on="on"
                    >
                      <v-icon left>mdi-lock-reset</v-icon>
                      Change password
                    </v-btn>
                  </template>
                  <template v-slot:default="dialog"
                  >
                    <v-card class="pa-4">
                      <v-card-text>
                        <div>
                          <validation-provider
                              v-slot="{ errors }"
                              name="oldPassword"
                              rules="required"
                              clearable
                          >
                            <v-text-field
                                v-model="old_password"
                                :error-messages="errors"
                                label="Old password"
                                :type="showOldPassword ? 'text' : 'password'"
                                required
                                hint="Must be at least 8 characters."
                                :append-icon="showOldPassword ? 'mdi-eye' : 'mdi-eye-off'"
                                @click:append="showOldPassword = !showOldPassword"
                                clearable
                            >
                            </v-text-field>
                          </validation-provider>
                          <validation-provider
                              v-slot="{ errors }"
                              name="New password"
                              rules="required|min:8|max:128"
                              clearable
                          >
                            <v-text-field
                                v-model="newPassword"
                                :error-messages="errors"
                                label="New password"
                                :type="showNewPassword ? 'text' : 'password'"
                                counter
                                required
                                hint="Must be at least 8 characters."
                                :append-icon="showNewPassword ? 'mdi-eye' : 'mdi-eye-off'"
                                @click:append="showNewPassword = !showNewPassword"
                                clearable
                            >
                            </v-text-field>
                          </validation-provider>
                          <validation-provider
                              v-slot="{ errors }"
                              name="Password confirmation"
                              rules="required|min:8|max:128|password:@New password"
                              clearable
                          >
                            <v-text-field
                                v-model="confirmNewPassword"
                                :error-messages="errors"
                                label="Confirm new password"
                                :type="showConfirmNewPassword ? 'text' : 'password'"
                                counter
                                required
                                hint="Must be at least 8 characters."
                                :append-icon="showConfirmNewPassword ? 'mdi-eye' : 'mdi-eye-off'"
                                @click:append="showConfirmNewPassword = !showConfirmNewPassword"
                                clearable
                            >
                            </v-text-field>
                          </validation-provider>
                        </div>
                        <v-btn large
                               type="submit"
                               color="primary"
                               @click="updatePassword()"
                        >
                          <v-icon left class="mr-1">mdi-lock-check</v-icon>
                          Update password
                        </v-btn>
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
              </div>
            </v-col>
            <v-row style="">
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
                      :loading="pageLoading"
                      :error-messages="errors"
                      label="First name"
                      required
                      dark
                      :disabled="edit"
                      solo-inverted
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
                      :loading="pageLoading"
                      :error-messages="errors"
                      label="Last name"
                      required
                      dark
                      :disabled="edit"
                      solo-inverted
                  ></v-text-field>
                </validation-provider>
              </v-col>
            </v-row>
            <v-row style="">
              <v-col
                  cols="12"
                  sm="6">
                <validation-provider
                    v-slot="{ errors }"
                    name="Gender"
                    clearable
                >
                  <v-select
                      v-model="selectGender"
                      :loading="pageLoading"
                      :items="genders"
                      :error-messages="errors"
                      item-text="name"
                      label="Gender"
                      dark
                      :disabled="edit"
                      solo-inverted
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
                      v-model="modal"
                      :return-value.sync="date"
                      persistent
                      width="290px"
                  >
                    <template v-slot:activator="{ on, attrs }">
                      <v-text-field
                          v-model="birthdate"
                          :loading="pageLoading"
                          :error-messages="errors"
                          label="Pick a date"
                          append-icon="mdi-cake-variant-outline"
                          dark
                          :disabled="edit"
                          solo-inverted
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
            <v-row style="">
              <v-col
                  cols="12"
                  sm="6">
                <validation-provider
                    v-slot="{ errors }"
                    name="Country"
                    rules="required"
                    clearable
                >
                  <v-select
                      v-model="selectCountry"
                      :items="countries"
                      :loading="pageLoading"
                      item-text="name"
                      :error-messages="errors"
                      label="Country"
                      dark
                      :disabled="edit"
                      solo-inverted
                      return-object
                      @change="updateCountryCode()"
                  ></v-select>
                </validation-provider>
              </v-col>
              <v-col
                  cols="12"
                  sm="6">
                <validation-provider
                    v-slot="{ errors }"
                    name="Phone number"
                    rules="required|numeric|min:8|max:15"
                    clearable
                >
                  <v-text-field v-if="phoneCode != null"
                                :prefix="'(' + phoneCode + ')'"
                                :loading="pageLoading"
                                v-model="phoneNumber"
                                :error-messages="errors"
                                label="Phone number"
                                append-icon="mdi-phone-classic"
                                dark
                                :disabled="edit"
                                solo-inverted
                  ></v-text-field>
                  <v-text-field v-else
                                v-model="phoneNumber"
                                :error-messages="errors"
                                label="Phone number"
                                append-icon="mdi-phone-classic"
                                dark
                                :disabled="edit"
                                solo-inverted
                  ></v-text-field>
                </validation-provider>
              </v-col>
            </v-row>
            <div v-if="!edit">
              <v-row class="ma-1">
                <v-col
                    cols="12"
                    sm="12"
                >
                  <v-btn dark
                         large color="primary"
                         :disabled=" invalid || !firstName || !lastName || !selectCountry ||
                                    !phoneNumber"
                         @click="updateProfile()"
                  >
                    <v-icon left>mdi-check</v-icon>
                    Save
                  </v-btn>
                </v-col>
              </v-row>
            </div>
          </v-container>
        </v-form>
      </v-card>
    </validation-observer>
  </div>
</template>

<script>
import {required, digits, max,} from 'vee-validate/dist/rules'
import {extend, ValidationObserver, ValidationProvider, setInteractionMode} from 'vee-validate'
import UploadService from "../services/UploadFilesService";

import axios from "axios";
import Swal from "sweetalert2";

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

// extend('regex', {
//   ...regex,
//   message: '{_field_} {_value_} does not match {regex}',
// })

// extend('email', {
//   ...email,
//   message: 'Must be a valid email.',
// })

export default {
  name: "UserProfile",

  components: {
    ValidationProvider,
    ValidationObserver,
  },

  data: () => ({
    loggedUser: '',
    edit: true,
    username: '',
    firstName: '',
    lastName: '',
    email: '',
    selectGender: '',
    genders: [],
    birthdate: '',
    date: (new Date(Date.now() - (new Date()).getTimezoneOffset() * 60000)).toISOString().substr(0, 10),
    menu: false,
    modal: false,
    menu2: false,
    passwordDialog: false,
    imageDialog: false,
    imageUpload: null,
    selectCountry: '',
    countries: [],
    phoneCode: '',
    phoneNumber: '',
    old_password: '',
    newPassword: '',
    confirmNewPassword: '',
    showOldPassword: false,
    showNewPassword: false,
    showConfirmNewPassword: false,
    currentImage: undefined,
    previewImage: undefined,
    progress: 0,
    message: "",
    imageName: '',
    imageInfos: [],
    userImage: "",
    pageLoading: false,
    isValid: false,
    extensions: [".jpg", ".jpeg", ".png"]

  }),
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

    getCountries() {
      this.pageLoading = true
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
            this.pageLoading = false
          })
          .catch(error => {
            console.log(error)
            this.pageLoading = false
          })
    },

    updateCountryCode() {
      this.phoneCode = this.selectCountry.phone_code
    },

    getLoggedUser() {
      this.pageLoading = true
      axios.get('/auth/user')
          .then(response => {
            if (response.data) {
              this.username = response.data.username
              this.firstName = response.data.first_name
              this.lastName = response.data.last_name
              this.email = response.data.email
              this.selectGender = response.data.gender
              this.birthdate = response.data.birthdate
              this.selectCountry = response.data.country
              this.phoneNumber = response.data.phone_number
              this.loggedUser = response.data
              if (response.data.image) this.userImage = response.data.image
              // console.log(response.data.image, 'RESPONSE DATA IMAGE')
              // console.log(this.userImage, 'USER IMAGE VARIABLE')
              this.pageLoading = false
            }
          })
          .catch(error => {
            console.log(error)
            this.pageLoading = false
          })
    },

    updateProfile() {
      this.loading = true
      axios.put('/user/' + this.loggedUser.id, {
        first_name: this.firstName,
        last_name: this.lastName,
        gender: this.selectGender,
        birthdate: this.birthdate,
        country: this.selectCountry.name,
        phone_number: this.phoneNumber,
      })
          .then(response => {
                if (response) {
                  Swal.fire(
                      'Done!',
                      'Your information has been updated.',
                      'success'
                  )

                  this.edit = true
                  this.loading = false;
                }
              }
          )
          .catch(error => {
            this.loading = false
            this.error = error.response.data;
            // console.log(error.response.data.phone_number)
            if (error.response.data.phone_number == "The phone number has already been taken.") {
              Swal.fire(
                  'Oops!',
                  'The phone number has already been taken.',
                  'error'
              )
            }
          })
    },

    updatePassword() {
      this.loading = true
      axios.put('/password/' + this.loggedUser.id, {
        old_password: this.old_password,
        password: this.newPassword,
        password_confirmation: this.confirmNewPassword
      })
          .then(response => {
                if (response) {
                  Swal.fire(
                      'Done!',
                      'Your information has been updated.',
                      'success'
                  )
                  this.passwordDialog = false
                  this.modal = false
                }
              }
          )
          .catch(error => {
            console.log(error)
            this.loading = false
            this.error = error.response.data;
            if (error.response.data.message == "Old password is not correct.") {
              Swal.fire(
                  'Oops!',
                  'Old password is incorrect.',
                  'error'
              )
            }
          })
    },

    selectImage(image) {
      this.currentImage = image;
      this.previewImage = URL.createObjectURL(this.currentImage);
      this.progress = 0;
      this.message = "";
    },
    upload() {

      if (this.currentImage) {
        this.imageName = this.currentImage.name
        for (let j = 0; j < this.extensions.length; j++) {
          this.currentExtension = this.extensions[j];
          if (this.imageName.substr(this.imageName.length - this.currentExtension.length,
              this.currentExtension.length).toLowerCase() == this.currentExtension.toLowerCase()) {
            this.isValid = true;
          }
        }

        if (!this.isValid) {
          this.message = "Sorry, " + this.imageName + " is invalid, allowed extensions are: " + this.extensions.join(", ");
          return;
        }
      } else {
        this.message = "Please select an image!";
        return;
      }

      if (this.currentImage.size > 2097152) {
        this.message = "File size cannot be greater than 2MB!";
        return;
      }
      this.loading = true
      this.progress = 0;
      UploadService.upload(this.currentImage, (event) => {
        this.progress = Math.round((100 * event.loaded) / event.total);
        Swal.fire({
          title: 'Done!',
          text: 'Your profile image has been updated.',
          icon: 'success'
        }).then(() => {
          // if (result.isConfirmed) {
          window.location.reload()
          // }
        })
      }).then((response) => {
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
            this.imageDialog = false
          });
    },
  },

  mounted() {
    if (window.localStorage.getItem('token') === null) {
      this.$router.push('/pageNotFound')
    }
    document.title = 'Edit Profile - Auction House'
  },
  created() {
    this.getGenders();
    this.getLoggedUser();
    this.getCountries();
  }
}

</script>

<style scoped>

.repeating-gradient {
  background-image: repeating-linear-gradient(-45deg,
  rgba(255, 0, 0, .25),
  rgba(255, 0, 0, .25) 5px,
  rgba(0, 0, 255, .25) 5px,
  rgba(0, 0, 255, .25) 10px
  );
}

</style>