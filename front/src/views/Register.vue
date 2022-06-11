<template>
  <div style="margin-top: 4%">
    <v-card
        class="pa-6"
        max-width="48%"
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
      <validation-observer ref="observer" tag="form" @submit.prevent="register()"
      >
        <form @submit.prevent="submit">
          <v-row>
            <v-col cols="12"
                   sm="6">
              <validation-provider
                  v-slot="{ errors }"
                  name="First name"
                  rules="required|min:1|max:32"
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
                  name="Last name"
                  rules="required|min:1|max:32"
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
                  name="Username"
                  rules="required|min:3|max:32"
              >
                <v-text-field
                    v-model="username"
                    :error-messages="errors"
                    label="Username"
                    clearable
                ></v-text-field>
                <span>{{ errors[0] }}</span>
              </validation-provider>
            </v-col>
          </v-row>
          <v-row>
            <v-col cols="12"
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
            <v-col cols="12" sm="12">
              <validation-provider
                  v-slot="{ errors }"
                  name="Email"
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
                  name="Password"
                  rules="required"
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
                  name="Password confirmation"
                  rules="required"
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
                @click="register()"
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
import {required, numeric, email, max, min} from 'vee-validate/dist/rules'
import {extend, ValidationObserver, ValidationProvider, setInteractionMode} from 'vee-validate'
import axios from "axios";
// import Swal from "sweetalert2";

setInteractionMode('eager')

extend('numeric', {
  ...numeric,
  message: '{_field_} must be a number.',
})

extend('required', {
  ...required,
  // message: '{_field_} can not be empty',
  message: 'Required.'
})

extend('min', {
  ...min,
  message: '{_field_} must be at least {length} characters.',
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

extend('email', {
  ...email,
  message: 'Must be a valid email.',
})

// extend('taken', {
//   message: "{_field _} already taken.",
//   validate: value => {
//     return "Condition that evaluates to true of false"
//   }
// })

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

  // watch: {
  //   loading (val) {
  //     if (!val) return
  //
  //     setTimeout(() => (this.loading = false), 3000)
  //   },
  // },

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
      // this.$refs.observer.setErrors({
      //   username: ['The username has already been taken.']
      // });
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
              this.$router.push('/login');
              this.loading = false;
            }
            return response.data;
          })
          .catch(error => {
            console.log(error.response.data)
            this.loading = false
            this.error = error.response.data.message;
            this.$refs.observer.setErrors(error.response.data)
          })
    },
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