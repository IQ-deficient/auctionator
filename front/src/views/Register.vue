<template>
  <div style="margin-top: 2.5%">
    <v-card
        class="mx-auto"
        max-width="38%"
        style="justify-content: end; padding: 20px"
    >
      <validation-observer
          ref="observer"
      >
        <form @submit.prevent="submit">
          <v-row>
            <v-col cols="6">
<!--          Polje za ime-->
          <validation-provider
              v-slot="{ errors }"
              name="firstName"
              rules="required|max:32"
          >
            <v-text-field
                v-model="firstName"
                :error-messages="errors"
                label="First name"
                required
            ></v-text-field>
          </validation-provider>
            </v-col>
<!--          Polje za prezime-->
            <v-col cols="6">
            <validation-provider
              v-slot="{ errors }"
              name="lastName"
              rules="required|max:32"
          >
            <v-text-field
                v-model="lastName"
                :error-messages="errors"
                label="Last name"
                required
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
              rules="required|max:32"
          >
            <v-text-field
                v-model="username"
                :error-messages="errors"
                label="Username"
                required
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
                v-model="country"
                :items="countries"
                :error-messages="errors"
                label="Country"
                data-vv-name="select"
                required
            ></v-select>
          </validation-provider>
            </v-col>
<!--          Polje za broj telefona-->
            <v-col cols="7">
          <validation-provider
              v-slot="{ errors }"
              name="phoneNumber"
              :rules="{
          required: true,
          digits: 7,
          regex: '^(71|72|74|76|81|82|84|85|86|87|88|89)\\d{5}$'
        }"
          >
            <v-text-field
                v-model="phoneNumber"
                :counter="7"
                :error-messages="errors"
                label="Phone Number"
                required
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
            ></v-text-field>
          </validation-provider>
<!--          Polje za lozinku-->
          <validation-provider
              v-slot="{ errors }"
              name="Password"
              rules="required"
          >
            <v-text-field
                v-model="password"
                :error-messages="errors"
                label="Password"
                type="password"
                :counter="8"
                required
            ></v-text-field>
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
                type="password"
                required
                class="mb-2"
            ></v-text-field>
          </validation-provider>
          <v-btn
              type="submit"
              class="mb-2"
              color="primary"
          >
            Sign up
          </v-btn>
          <!--          <v-btn @click="clear">-->
          <!--            clear-->
          <!--          </v-btn>-->
        </form>
      </validation-observer>
    </v-card>
    <div style="width: 36%; color: white; margin: 0 auto" class="mt-4">
      <table width="100%">
        <tr>
          <td><hr/></td>
          <td style="width:1px; padding: 0 10px; white-space: nowrap;">Already have an account?</td>
          <td><hr/></td>
        </tr>
      </table>
    </div>

    <router-link to="/login" style="text-decoration: none">
      <v-btn
          color="accent"
          class="mt-4">
        Sign in here
      </v-btn>
    </router-link>
  </div>
</template>

<script>
import { required, digits, email, max, regex } from 'vee-validate/dist/rules'
import { extend, ValidationObserver, ValidationProvider, setInteractionMode } from 'vee-validate'

// import axios from "axios";
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
name: "Register",

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
    password: '',
    confirmPassword: '',
    countries: [
        'Ukraine',
        'Russia',
    ]
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
  },
}
</script>

<style scoped>

</style>