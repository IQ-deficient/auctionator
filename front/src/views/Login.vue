<template>
  <div style="margin-top: 6%">
    <v-card class="pa-6" max-width="28%" style="margin: 0 auto">
      <validation-observer ref="form">
        <form @submit.prevent="login">
          <v-row class="justify-start">
            <v-img src="../assets/architecture-icon.svg"
                   style="margin-left: 0px"
                   max-height="70px"
                   max-width="70px"
            ></v-img>
            <v-divider vertical style="margin-left: 18px; border-right: 2px solid black"></v-divider>
            <v-card-title style="margin-left: 8px">Sign in</v-card-title>
          </v-row>
          <v-row>
            <v-col
                cols="12"
                sm="12"
            >
              <validation-provider
                  v-slot="{ errors }"
                  name="email"
                  rules="required|email"
              >
                <v-text-field
                    v-model="email"
                    :error-messages="errors"
                    label="E-mail"
                    clearable
                ></v-text-field>
              </validation-provider>
            </v-col>
          </v-row>
          <v-row>
            <v-col
                cols="12"
                sm="12"
            >
              <validation-provider
                  v-slot="{ errors }"
                  name="password"
                  rules="required|min:8|max:128"
              >
                <v-text-field
                    v-model="password"
                    :error-messages="errors"
                    label="Password"
                    :type="showPassword ? 'text' : 'password'"
                    :append-icon="showPassword ? 'mdi-eye' : 'mdi-eye-off'"
                    @click:append="showPassword = !showPassword"
                    clearable
                ></v-text-field>
              </validation-provider>
            </v-col>
          </v-row>
          <v-row class="ma-4">
            <v-btn
                :loading="loading"
                width="100%"
                class="mb-1"
                type="submit"
                color="primary"
            >
              <v-icon left class="mr-2">mdi-login</v-icon>
              Login
            </v-btn>
          </v-row>
          <v-row class="justify-end">
            <router-link to="/" style="text-decoration: none">
            <span>
              <v-icon left class="pa-1">mdi-lock-outline</v-icon>
              Forgot password?
            </span>
            </router-link>
          </v-row>
        </form>
      </validation-observer>
    </v-card>
    <div style="width: 26%; color: white; margin: 0 auto" class="mt-4">
      <table style="width: 100%">
        <tr>
          <td>
            <hr/>
          </td>
          <td style="width: 1px; padding: 0px; white-space: nowrap;">New to our platform?</td>
          <td>
            <hr/>
          </td>
        </tr>
      </table>
    </div>
    <router-link to="/register" style="text-decoration: none">
      <v-btn
          color="accent"
          class="mt-4">
        Create your account
      </v-btn>
    </router-link>
  </div>
</template>

<script>

import axios from "axios";
import {required, email, min, max} from 'vee-validate/dist/rules'
import {extend, ValidationObserver, ValidationProvider, setInteractionMode} from 'vee-validate'
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
  params: ['min'],
  message: 'The {_field_} must be at least {min} characters.'
})

extend('max', {
  ...max,
  params: ['max'],
  message: 'The {_field_} may not be greater than {max} characters.'
})

export default {
  name: "Login",

  components: {
    ValidationProvider,
    ValidationObserver,
  },

  data: () => ({
    email: '',
    password: '',
    showPassword: false,
    loading: false,
  }),

  methods: {

    login() {
      this.$refs.form.validate().then(success => {
        if (success) {
          const config = {
            headers: {'Authorization': 'Bearer ' + JSON.parse(localStorage.getItem('token'))},
            key: "token"
          };

          this.loading = true
          axios.post('/auth/login', {
            email: this.email,
            password: this.password,
          }, config)
              .then(response => {
                if (response) {
                  localStorage.setItem("token", JSON.stringify(response.data.access_token));
                  localStorage.setItem("user_roles", response.data.user_roles);
                  this.$router.push('/home');
                  this.$router.go(0)
                  this.loading = false;
                }
                this.$nextTick(() => {
                  this.$refs.form.reset();
                });
                return response.data;
              })
              .catch(error => {
                this.loading = false
                this.error = error.response.data;
                console.log(this.error)
                if (error.response.data.error == "Something went wrong.") {
                  Swal.fire(
                      'Oops!',
                      'Email and password don\'t match.',
                      'error'
                  )
                }
                if (error.response.data.message == "This user is inactive!") {
                  Swal.fire(
                      'This account has been suspended.',
                      'If you think this is a mistake, please contact our management team.',
                      'error'
                  )
                }
              })
        }
      })
    },
  },

  mounted() {
    document.title = 'Login - Auction House'
    if (localStorage.getItem('token')) {
      this.$router.push('/home')
    }
  },

}
</script>

<style scoped>

</style>