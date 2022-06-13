<template>
  <div style="margin-top: 6%">
    <v-card class="pa-6" max-width="28%" style="margin: 0 auto">
      <validation-observer ref="observer" v-slot="{ invalid }" tag="form" @submit.prevent="login()"
      >
        <form @submit.prevent="submit">
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
                name="Email"
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
                name="Password"
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
              type="submit"
              class="mb-1"
              color="primary"
              @click="login()"
              @submit.prevent="invalid"
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
import {required, digits, email, max, regex} from 'vee-validate/dist/rules'
import {extend, ValidationObserver, ValidationProvider, setInteractionMode} from 'vee-validate'
import Swal from "sweetalert2";

setInteractionMode('eager')


extend('digits', {
  ...digits,
  message: '{_field_} needs to be {length} digits. ({_value_})',
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
  name: "Login",

  components: {
    ValidationProvider,
    ValidationObserver,
  },

  data: () => ({
    email: '',
    password: '',
    checkbox: null,
    showPassword: false,
    loading: false,
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
    login() {
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
            // localStorage.setItem('expires_in', JSON.stringify(response.data.expires_in));
            this.$router.push('/home');
            this.$router.go(0)
            this.loading = false;
          }
          return response.data;
        })
        // TODO: OVO NE RADI NA CATCH; STRANICA SE RELOADUJE AKO SE UNESU POGRESNI KREDENCIJALI
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
        })
    },
  },

  mounted() {
    document.title = 'Login - Auction House'
  }
}
</script>

<style scoped>

</style>