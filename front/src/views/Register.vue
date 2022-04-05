<template>
  <div style="margin-top: 4%">
<!--    <v-progress-linear-->
<!--        :active="loading"-->
<!--        :indeterminate="loading"-->
<!--        absolute-->
<!--        top-->
<!--        color="primary"-->
<!--    ></v-progress-linear>-->
    <v-card
        class="mx-auto"
        max-width="38%"
        style="justify-content: end"
    >
      <validation-observer
          ref="observer"
      >
        <form @submit.prevent="submit" style="height: 490px; width: 92%; margin: 0 auto">
          <v-row>
            <v-col cols="6">
              <!--          Polje za ime-->
              <validation-provider
                  v-slot="{ errors }"
                  name="firstName"
                  rules="required|min:3|max:32"
              >
                <v-text-field
                    v-model="firstName"
                    :error-messages="errors"
                    label="First name"
                    clearable
                    required
                ></v-text-field>
              </validation-provider>
            </v-col>
            <!--          Polje za prezime-->
            <v-col cols="6">
              <validation-provider
                  v-slot="{ errors }"
                  name="lastName"
                  rules="required|min:3|max:32"
              >
                <v-text-field
                    v-model="lastName"
                    :error-messages="errors"
                    label="Last name"
                    required
                    clearable
                ></v-text-field>
              </validation-provider>
            </v-col>
          </v-row>
          <!--          Polje za korisnicko ime-->
          <v-row>
          </v-row>
          <validation-provider
              v-slot="{ errors }"
              name="username"
              rules="required|min:3|max:32"
          >
            <v-text-field
                v-model="username"
                :error-messages="errors"
                label="Username"
                required
                clearable
            ></v-text-field>
          </validation-provider>
          <v-row>
            <v-col cols="5">
              <!--          Padajuci meni za drzavu-->
              <validation-provider
                  v-slot="{ errors }"
                  name="country"
                  rules="required"
              >
                <v-select
                    v-model="selectCountry"
                    :items="countries"
                    item-text="name"
                    :error-messages="errors"
                    label="Country"
                    data-vv-name="select"
                    clearable
                    required
                ></v-select>
              </validation-provider>
            </v-col>
            <!--          Polje za broj telefona-->
            <v-col cols="7">
              <validation-provider
                  v-slot="{ errors }"
                  name="phoneNumber"
              >
                <v-text-field
                    v-model="phoneNumber"
                    :error-messages="errors"
                    label="Phone number"
                    required
                    clearable
                ></v-text-field>
              </validation-provider>
            </v-col>
          </v-row>
          <!--          Polje za imejl-->
          <validation-provider
              v-slot="{ errors }"
              name="Email"
              rules="required|email"
          >
            <v-text-field
                v-model="email"
                :error-messages="errors"
                label="E-mail"
                required
                clearable
            ></v-text-field>
          </validation-provider>
          <!--          Polje za lozinku-->
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
                required
                hint="Must be at least 8 characters."
                :append-icon="showPassword ? 'mdi-eye' : 'mdi-eye-off'"
                @click:append="showPassword = !showPassword"
                clearable
            >
            </v-text-field>
          </validation-provider>
          <!--          Polje za potvrdu lozinke-->
          <validation-provider
              v-slot="{ errors }"
              name="confirmPassword"
              rules="required"
          >
            <v-text-field
                v-model="confirmPassword"
                :error-messages="errors"
                label="Confirm password"
                :type="showConfirmPass ? 'text' : 'password'"
                required
                :append-icon="showConfirmPass ? 'mdi-eye' : 'mdi-eye-off'"
                @click:append="showConfirmPass = !showConfirmPass"
                clearable
            >
            </v-text-field>
          </validation-provider>
          <v-btn
              type="submit"
              color="primary"
              @click="register()"
          >
            <v-icon
                left
            >mdi-account-plus
            </v-icon>
            Create account
          </v-btn>
          <!--          <v-btn @click="clear">-->
          <!--            clear-->
          <!--          </v-btn>-->
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
    <v-btn
        color="accent"
        class="mt-4"
        @click="testLoad()">
      Load
    </v-btn>
  </div>
</template>

<script>
import {required, digits, email, max, regex, min} from 'vee-validate/dist/rules'
import {extend, ValidationObserver, ValidationProvider, setInteractionMode} from 'vee-validate'
import axios from "axios";

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

extend('min', {
  ...min,
  message: 'The {_field_} must be at least {length} characters.',
})

extend('max', {
  ...max,
  message: 'The {_field_} must not be greater than {length} characters.',
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
          })
          .catch(error => {
            console.log(error)
          })
    },

    register() {
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
              }
          )
          .catch(error => {
            console.log(error)
            this.loading = false
            this.error = error.response.data.message;
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