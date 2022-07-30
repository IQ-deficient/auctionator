<template>
  <div>
    <v-parallax v-if="userImage" height="175"
                :loading="pageLoading"
                :lazy-src="'/api/user/'+ userImage"
                :src="'/api/user/'+ userImage">
      <div class="fill-height repeating-gradient"></div>
    </v-parallax>
    <v-parallax v-else height="175" style="background-color: #819fC9">
      <div class="fill-height repeating-gradient"></div>
    </v-parallax>

    <v-card width="50%"
            style="margin: 0 auto; position: relative; top: -25px" color="#0d111a" class="pa-1">
      <v-row class="">
        <v-col cols="12">
          <div style="position: relative; width: 145px; height: 0; margin: 0 auto">
            <div style="position: absolute; top: -120px">
              <v-avatar size="145">
                <v-img v-if="userImage"
                       :loading="pageLoading"
                       :lazy-src="'/api/user/'+ userImage"
                       :src="'/api/user/'+ userImage">
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
                <v-img
                  v-else
                  lazy-src="../assets/user/no-user-image.svg"
                  src="../assets/user/no-user-image.svg">
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
                  <v-card class="pa-3">
                    <v-card-title class="justify-end">
                      <v-btn
                        color="secondary"
                        @click="clearImageDialog(); dialog.value = false"
                      >
                        <v-icon>mdi-close</v-icon>
                      </v-btn>
                    </v-card-title>
                    <validation-observer ref="imageForm">
                      <form name="imageForm" @submit.prevent="upload()">
                        <v-row justify="center" align="center">
                          <v-col cols="7">
                            <validation-provider
                              v-slot="{ errors }"
                              name="image"
                              rules="required|image|mimes:image/jpeg,image/png|size:2000">
                              <v-file-input
                                v-model="imageUpload"
                                show-size
                                :error-messages="errors"
                                label="Select image"
                                accept="image/*"
                                @change="selectImage"
                              ></v-file-input>
                            </validation-provider>
                          </v-col>
                          <v-col cols="3" class="justify-end">
                            <v-btn color="primary"
                                   dark
                                   type="submit">
                              <v-icon left dark>mdi-cloud-upload</v-icon>
                              Upload
                            </v-btn>
                          </v-col>
                        </v-row>
                        <div v-if="progress">
                          <v-progress-linear
                            v-model="progress"
                            color="success"
                            height="25"
                            reactive
                          >
                            <strong>{{ progress }} %</strong>
                          </v-progress-linear>
                        </div>
                        <v-alert v-if="message" color="error" dark>
                          {{ message }}
                        </v-alert>
                        <v-img v-if="currentImage"
                               style="background-color: #0D111A; width: 100%; height: 30vw; object-fit: cover"
                               max-height="100%"
                               min-height="100%"
                               contain
                               :src="previewImage">
                        </v-img>
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
                      </form>
                    </validation-observer>
                  </v-card>
                </template>
              </v-dialog>
            </v-row>
          </div>
        </v-col>
      </v-row>
      <v-container class="pa-4">
        <v-row style="margin-top: auto">
          <v-col cols="6" style="margin: 0 auto">
            <v-text-field style="text-align: center"
                          :loading="pageLoading"
                          v-model="username"
                          label="Username"
                          append-icon="mdi-account-edit-outline"
                          dark
                          disabled
            ></v-text-field>
          </v-col>
          <v-col cols="6" style="margin: 0 auto">
            <v-text-field
              v-model="email"
              :loading="pageLoading"
              label="Email"
              append-icon="mdi-email-edit-outline"
              dark
              disabled
            ></v-text-field>
          </v-col>
        </v-row>
        <v-col cols="12" class="mb-6">
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
              persistent
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
              <template v-slot:default="dialog">
                <v-card class="pa-4">
                  <div>
                    <v-btn
                      color="secondary"
                      @click="clearPasswordDialog(); dialog.value = false;"
                    >
                      <v-icon>mdi-close</v-icon>
                    </v-btn>
                  </div>
                  <validation-observer ref="passwordForm">
                    <form name="passwordForm" @submit.prevent="updatePassword()">
                      <v-row>
                        <v-col cols="12">
                          <validation-provider
                            v-slot="{ errors }"
                            name="old password"
                            rules="required"
                          >
                            <v-text-field
                              v-model="oldPassword"
                              :error-messages="errors"
                              label="Old password"
                              :type="showOldPassword ? 'text' : 'password'"
                              hint="Must be at least 8 characters."
                              :append-icon="showOldPassword ? 'mdi-eye' : 'mdi-eye-off'"
                              @click:append="showOldPassword = !showOldPassword"
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
                            name="new password"
                            rules="required|min:8|max:128"
                          >
                            <v-text-field
                              v-model="newPassword"
                              :error-messages="errors"
                              label="New password"
                              :type="showNewPassword ? 'text' : 'password'"
                              hint="Must be at least 8 characters."
                              :append-icon="showNewPassword ? 'mdi-eye' : 'mdi-eye-off'"
                              @click:append="showNewPassword = !showNewPassword"
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
                            rules="required|password:@new password"
                          >
                            <v-text-field class="mb-4"
                                          v-model="confirmNewPassword"
                                          :error-messages="errors"
                                          label="Confirm new password"
                                          :type="showConfirmNewPassword ? 'text' : 'password'"
                                          hint="Must be at least 8 characters."
                                          :append-icon="showConfirmNewPassword ? 'mdi-eye' : 'mdi-eye-off'"
                                          @click:append="showConfirmNewPassword = !showConfirmNewPassword"
                                          clearable
                            >
                            </v-text-field>
                          </validation-provider>
                        </v-col>
                      </v-row>
                      <v-btn large
                             type="submit"
                             color="primary"
                      >
                        <v-icon left class="mr-1">mdi-lock-check</v-icon>
                        Update password
                      </v-btn>
                    </form>
                  </validation-observer>
                </v-card>
              </template>
            </v-dialog>
          </div>
        </v-col>
        <validation-observer ref="profileForm" v-slot="{invalid}">
          <form name="profileForm" @submit.prevent="updateProfile()">
            <v-row>
              <v-col cols="6">
                <validation-provider
                  v-slot="{ errors }"
                  name="first name"
                  rules="required|alpha_spaces|min:1|max:32"
                >
                  <v-text-field
                    v-model="firstName"
                    :loading="pageLoading"
                    :error-messages="errors"
                    label="First name"
                    dark
                    :disabled="edit"
                    solo-inverted
                  ></v-text-field>
                </validation-provider>
              </v-col>
              <v-col cols="6">
                <validation-provider
                  v-slot="{ errors }"
                  name="Last name"
                  rules="required|alpha_spaces|min:1|max:32"
                >
                  <v-text-field
                    v-model="lastName"
                    :loading="pageLoading"
                    :error-messages="errors"
                    label="Last name"
                    dark
                    :disabled="edit"
                    solo-inverted
                  ></v-text-field>
                </validation-provider>
              </v-col>
            </v-row>
            <v-row>
              <v-col cols="6">
                <validation-provider
                  v-slot="{ errors }"
                  name="Gender"
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
                    clearable
                  ></v-select>
                </validation-provider>
              </v-col>
              <v-col cols="6">
                <validation-provider
                  v-slot="{ errors }"
                  name="Birth date"
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
            <v-row>
              <v-col cols="6">
                <validation-provider
                  v-slot="{ errors }"
                  name="Country"
                  rules="required"
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
              <v-col cols="6">
                <validation-provider
                  v-slot="{ errors }"
                  name="Phone number"
                  rules="required|numeric|min:6|max:15"
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
            <v-row v-if="!edit">
              <v-col cols="12">
                <v-btn dark
                       large color="primary"
                       :disabled="invalid"
                       @click="updateProfile()"
                >
                  <v-icon left>mdi-check</v-icon>
                  Save
                </v-btn>
              </v-col>
            </v-row>
          </form>
        </validation-observer>
      </v-container>
    </v-card>
  </div>
</template>

<script>
import {required, min, max, alpha_spaces, numeric, mimes, size, image} from 'vee-validate/dist/rules'
import {extend, ValidationObserver, ValidationProvider, setInteractionMode} from 'vee-validate'
import UploadService from "../services/UploadFilesService";

import axios from "axios";
import Swal from "sweetalert2";

setInteractionMode('eager')

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
    oldPassword: '',
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

    clearPasswordDialog() {
      this.oldPassword = ''
      this.newPassword = ''
      this.confirmNewPassword = ''
      this.$refs.passwordForm.reset()
    },

    clearImageDialog() {
      this.imageUpload = null
      this.currentImage = null
      this.previewImage = undefined
      this.progress = 0
      this.message = ''
      this.$refs.imageForm.reset()
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
          this.updateCountryCode()
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
            this.pageLoading = false
          }
        })
        .catch(error => {
          console.log(error)
          this.pageLoading = false
        })
      this.getCountries()
    },

    getUserImage() {
      this.pageLoading = true
      axios.get('/auth/image')
        .then(response => {
          if (response.data) {
            this.userImage = response.data
            this.pageLoading = false
          }
        })
        .catch(error => {
          console.log(error)
          this.pageLoading = false
        })
    },

    updateProfile() {
      this.$refs.profileForm.validate().then(success => {
        if (success) {
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
              if (error.response.data.phone_number == "The phone number has already been taken.") {
                Swal.fire(
                  'Oops!',
                  'The phone number has already been taken.',
                  'error'
                )
              }
            })
        }
      })
    },

    updatePassword() {
      this.$refs.passwordForm.validate().then(success => {
        if (success) {
          this.loading = true
          axios.put('/password/' + this.loggedUser.id, {
            old_password: this.oldPassword,
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
                  this.clearPasswordDialog()
                }
              }
            )
            .catch(error => {
              console.log(error)
              this.loading = false
              this.error = error.response.data;
              if (error.response.data.message == "Old password is not correct.") {
                Swal.fire(
                  'Password mismatch!',
                  'Old password is incorrect.',
                  'error'
                )
              } else if (error.response.data.message == "New password is identical to old password.") {
                Swal.fire(
                  'Oops!',
                  'New password is identical to old password.',
                  'error'
                )
              }

            })
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
      this.$refs.imageForm.validate().then(success => {
        if (success) {
          this.loading = true
          this.progress = 0;
          UploadService.upload(this.currentImage, (event) => {
            this.progress = Math.round((100 * event.loaded) / event.total)
            this.userImage = this.loggedUser.image
            Swal.fire({
              title: 'Done!',
              text: 'Your profile image has been updated.',
              icon: 'success'
            }).then(() => {
              this.getUserImage()
              this.clearImageDialog()
              this.imageDialog = false
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
        }
      })
    },
  },

  mounted() {
    if (window.localStorage.getItem('token') === null) {
      this.$router.push('/pageNotFound')
    }
  },

  created() {
    this.getGenders()
    this.getLoggedUser()
    this.getCountries()
  },

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