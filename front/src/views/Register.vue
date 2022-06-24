<template>
  <div style="margin-top: 4%">
    <v-card
      class="pa-6"
      max-width="38%"
      style="margin: 0 auto"
    >
      <v-row class="justify-start">
        <v-img src="../assets/architecture-icon.svg"
               style="margin-left: 0px"
               max-height="70px"
               max-width="70px"
        ></v-img>
        <v-divider vertical style="margin-left: 18px; border-right: 2px solid black"></v-divider>
        <v-card-title style="margin-left: 8px">Create a new account</v-card-title>
      </v-row>
      <validation-observer ref="form">
        <form @submit.prevent="register">
          <v-row>
            <v-col cols="12"
                   sm="6">
              <validation-provider
                v-slot="{ errors }"
                name="first name"
                rules="required|alpha|min:1|max:32"
              >
                <v-text-field
                  v-model="firstName"
                  :error-messages="errors"
                  label="First name"
                  clearable
                ></v-text-field>
              </validation-provider>

            </v-col>
            <v-col cols="12"
                   sm="6">
              <validation-provider
                v-slot="{ errors }"
                name="last name"
                rules="required|alpha|min:1|max:32"
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
            <v-col cols="12"
                   sm="12">
              <validation-provider
                v-slot="{ errors }"
                name="username"
                rules="required|alpha_num|min:3|max:32"
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
            <v-col cols="12"
                   sm="6">
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
            <v-col cols="12"
                   sm="6">
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
            <v-col cols="12" sm="12">
              <validation-provider
                v-slot="{ errors }"
                name="email"
                rules="required|email|min:10|max:254"
              >
                <v-text-field
                  v-model="email"
                  :error-messages="errors"
                  label="Email"
                  clearable
                ></v-text-field>
              </validation-provider>
            </v-col>
          </v-row>
          <v-row>
            <v-col cols="12" sm="12">
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
                  :counter="8"
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
            <v-col cols="12" sm="12">
              <validation-provider
                v-slot="{ errors }"
                name="password"
                rules="required|min:8|max:128"
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
          <v-row class="ma-4">
            <v-btn
              width="100%"
              type="submit"
              color="primary"
            >
              <v-icon
                left
              >mdi-account-plus
              </v-icon>
              Register
            </v-btn>
          </v-row>
        </form>
      </validation-observer>
    </v-card>
    <div style="width: 36%; color: white; margin: 0 auto" class="mt-4">
      <table style="width: 100%">
        <tr>
          <td>
            <hr/>
          </td>
          <td style="width:1px; padding: 0 10px; white-space: nowrap;">Already have an account?</td>
          <td>
            <hr/>
          </td>
        </tr>
      </table>
    </div>

    <router-link to="/login" style="text-decoration: none">
      <v-btn
        color="accent"
        class="mt-4">
        Sign in
      </v-btn>
    </router-link>
  </div>
</template>

<script>
import {required, numeric, email, min, max, alpha, alpha_num} from 'vee-validate/dist/rules'
import {extend, ValidationObserver, ValidationProvider, setInteractionMode} from 'vee-validate'
import axios from "axios";
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
  name: "Register",

  components: {
    ValidationProvider,
    ValidationObserver,
  },

  data: () => ({
    firstName: '',
    lastName: '',
    username: '',
    selectCountry: '',
    countries: [],
    phoneNumber: '',
    phoneCode: '',
    email: '',
    password: '',
    confirmPassword: '',
    showPassword: false,
    showConfirmPass: false,
  }),

  methods: {

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

    register() {
      this.$refs.form.validate().then(success => {
        if (success) {
          this.loading = true
          axios.post('auth/register', {
            first_name: this.firstName,
            last_name: this.lastName,
            username: this.username,
            country: this.selectCountry.name,
            phone_number: this.phoneNumber,
            email: this.email,
            password: this.password,
            password_confirmation: this.confirmPassword
          })
            .then(response => {
              if (response) {
                Swal.fire(
                  'Success!',
                  "You have been successfully registered. Please sign in.",
                  'success'
                )
                this.$router.push('/login');
                this.loading = false;
              }
              return response.data;
            })
            .catch(error => {
              if (error.response.data.message == 400) {
                Swal.fire({
                  icon: 'error',
                  text: error.response.data.message,
                })
                console.log(error)
                this.loading = false
                // console.log(error.response.data)
              }
              this.loading = false
              this.error = error.response.data.message;
            })
        }
      })
    }
  },

  created() {
    this.getCountries()
  },

  mounted() {
    document.title = 'Register - Auction House'
  }

}

</script>

<style scoped>

</style>