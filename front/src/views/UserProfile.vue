<template>
  <div>
    <v-parallax
        height="325"
        src="../assets/Mercy.png"
    >
    </v-parallax>
    <validation-observer
        ref="observer"
    >
    <v-card width="50%"
            style="margin: 0 auto; position: relative" color="info">
      <v-row>
        <v-col cols="12" sm="10">
          <div style="position: relative; width: 0; height: 0; margin: 0 auto">
            <div style="position: absolute; top: -120px">
              <v-avatar size="145px">
                <img alt="user" src="../assets/Marc.png">
              </v-avatar>
            </div>
<!--            <a href="#" style="text-decoration: none"></a>-->
            <v-dialog
                transition="scale-transition"
                max-width="35%"
            >
              <template v-slot:activator="{ on, attrs }">
                <a
                       v-bind="attrs"
                       v-on="on"
                ><v-icon dark large>
                  mdi-pencil-outline
                </v-icon></a>
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
                          <img style="width: 100%" class="preview my-3" :src="previewImage" alt="" />
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
                    >Close</v-btn>
                  </v-card-actions>
                </v-card>
              </template>
            </v-dialog>
          </div>
        </v-col>
        <v-col cols="12" sm="1" class="mt-1">
          <v-btn color="accent"><v-icon left>mdi-pencil</v-icon>Edit</v-btn>
        </v-col>
      </v-row>
      <v-form>
        <v-container>
          <v-row style="margin: 0 auto; width: 90%">
            <v-col cols="12"
                   sm="5"
                   style="margin: 0 auto">
              <v-text-field style="text-align: center"
                  value="marc.thompson"
                  label="Username"
                            append-icon="mdi-account-edit-outline"
                  solo-inverted
                  shaped
                  dark
                  disabled
              ></v-text-field>
            </v-col>
          </v-row>
          <v-row style="margin: 0 auto; width: 90%">
            <v-col
                cols="12"
                sm="6"
            >
              <v-text-field
                  value="Marc"
                  label="First name"
                  solo-inverted
                  dark
                  readonly
              ></v-text-field>
            </v-col>
            <v-col
                cols="12"
                sm="6"
            >
              <v-text-field
                  value="Thompson"
                  label="Last name"
                  solo-inverted
                  dark
                  readonly
              ></v-text-field>
            </v-col>
          </v-row>
          <v-row style="margin: 0 auto; width: 90%">
            <v-col style="margin: 0 auto"
                cols="12"
                sm="7">
              <v-text-field
                  value="marc.thompson@gmail.com"
                  label="Email"
                  append-icon="mdi-email-edit-outline"
                  shaped
                  solo-inverted
                  dark
                  disabled
              ></v-text-field>
            </v-col>
          </v-row>
          <v-row style="margin: 0 auto; width: 90%">
            <v-col
                cols="12"
                sm="5">
              <v-select
                  value="Male (Apex)"
                  label="Gender"
                  solo-inverted
                  dark
                  disabled
              ></v-select>
            </v-col>
            <v-col
                cols="12"
                sm="7">
              <v-text-field
                  value="11/27/1998"
                  label="Birthday"
                  append-icon="mdi-cake-variant-outline"
                  solo-inverted
                  dark
                  readonly
              ></v-text-field>
            </v-col>
          </v-row>
          <v-row style="margin: 0 auto; width: 90%">
            <v-col
                cols="12"
                sm="5">
              <v-select
                  value="Montenegro"
                  label="Country"
                  solo-inverted
                  dark
                  disabled
              ></v-select>
            </v-col>
            <v-col
                cols="12"
                sm="7">
              <v-text-field
                  value="+38267816707"
                  label="Phone number"
                  append-icon="mdi-phone-classic"
                  solo-inverted
                  dark
                  readonly
              ></v-text-field>
            </v-col>
          </v-row>
          <v-row style="margin: 0 auto; width: 90%">
            <v-col cols="12" sm="5"
                   style="margin: 0 auto">
              <v-dialog
                  transition="dialog-bottom-transition"
                  max-width="35%"
              >
                <template v-slot:activator="{ on, attrs }">
                  <v-btn large
                         color="accent"
                         v-bind="attrs"
                         v-on="on"
                  >Change password</v-btn>
                </template>
                <template v-slot:default="dialog">
                  <v-card>
                    <v-card-text>
                      <div class="pa-4">
                        <validation-provider
                          v-slot="{ errors }"
                          name="oldPassword"
                          rules="required"
                          clearable
                      >
                        <v-text-field
                            v-model="oldPassword"
                            :error-messages="errors"
                            label="Old password"
                            :type="showOldPassword ? 'text' : 'password'"
                            :counter="8"
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
                            name="newPassword"
                            rules="required"
                            clearable
                        >
                          <v-text-field
                              v-model="newPassword"
                              :error-messages="errors"
                              label="New password"
                              :type="showNewPassword ? 'text' : 'password'"
                              :counter="8"
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
                            name="confirmNewPassword"
                            rules="required"
                            clearable
                        >
                          <v-text-field
                              v-model="confirmNewPassword"
                              :error-messages="errors"
                              label="Confirm new password"
                              :type="showConfirmNewPassword ? 'text' : 'password'"
                              :counter="8"
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
                      >
                        <v-icon left class="mr-1">mdi-lock-check</v-icon>
                        Update password
                      </v-btn>
                    </v-card-text>
                    <v-card-actions class="justify-end">
                      <v-btn
                          text
                          @click="dialog.value = false"
                      >Close</v-btn>
                    </v-card-actions>
                  </v-card>
                </template>
              </v-dialog>
            </v-col>
          </v-row>
          <v-row style="margin: 0 auto; width: 90%" class="mt-7">
<!--            <hr style="width: 90%; margin: 0 auto" class="mb-3">-->
            <v-col
                cols="12"
            >
              <v-btn large color="primary"
              ><v-icon left>mdi-check</v-icon>Save</v-btn>
            </v-col>
            <v-col style="position: absolute;"
                cols="12"
                sm="1">
              <v-btn color="accent"
              ><v-icon left>mdi-close</v-icon>Cancel</v-btn>
            </v-col>
          </v-row>
        </v-container>
      </v-form>
    </v-card>
    </validation-observer>
    </div>
</template>

<script>
import { required, digits, email, max, regex } from 'vee-validate/dist/rules'
import { extend, ValidationObserver, ValidationProvider, setInteractionMode } from 'vee-validate'
import UploadService from "../services/UploadFilesService";

// import axios from "axios";
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
  name: "UserProfile",

  components: {
    ValidationProvider,
    ValidationObserver,
  },

  data: () => ({
    firstName: '',
    lastName: '',
    username: '',
    email: '',
    country: '',
    phoneNumber: '',
    oldPassword: '',
    newPassword: '',
    confirmNewPassword: '',
    countries: [
      'Ukraine',
      'Russia',
    ],
    showOldPassword: false,
    showNewPassword: false,
    showConfirmNewPassword: false,

    currentImage: undefined,
    previewImage: undefined,
    progress: 0,
    message: "",
    imageInfos: [],
  }),
  methods: {
    submit () {
      this.$refs.observer.validate()
    },
    clear () {
      this.email = ''
      this.password = ''
      this.checkbox = null
      this.$refs.observer.reset()
    },

    selectImage(image) {
      this.currentImage = image;
      this.previewImage = URL.createObjectURL(this.currentImage);
      this.progress = 0;
      this.message = "";
    },
    upload() {
      if (!this.currentImage) {
        this.message = "Please select an Image!";
        return;
      }
      this.progress = 0;
      UploadService.upload(this.currentImage, (event) => {
        this.progress = Math.round((100 * event.loaded) / event.total);
      })
          .then((response) => {
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
          });
    },
  },

}

</script>

<style scoped>

</style>