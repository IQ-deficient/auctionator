<template>
  <div style="margin-top: 8%">
    <v-card
        class="mx-auto"
        max-width="28%"
        style="justify-content: end"
    >
      <validation-observer
          ref="observer"
      >
        <form @submit.prevent="submit"
              style="height: 280px; width: 88%; margin: 0 auto"
        class="pt-3">
<!--          Polje za unos imejla-->
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
<!--          Polje za unos lozinke-->
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
                required
            ></v-text-field>
          </validation-provider>
          <validation-provider
              v-slot="{ errors }"
              name="checkbox"
          >
            <v-checkbox
                v-model="checkbox"
                :error-messages="errors"
                value="1"
                label="Remember me"
                type="checkbox"
                required
            ></v-checkbox>
          </validation-provider>

          <v-btn
              type="submit"
              class="mb-1"
              color="primary"
          >
            Login
          </v-btn>
<!--          <v-btn @click="clear">-->
<!--            clear-->
<!--          </v-btn>-->
        </form>
      </validation-observer>
    </v-card>
    <div style="width: 26%; color: white; margin: 0 auto" class="mt-4">
    <table width="100%">
      <tr>
        <td><hr/></td>
        <td style="width:1px; padding: 0 10px; white-space: nowrap;">New to our platform?</td>
        <td><hr/></td>
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
name: "Login",

  components: {
    ValidationProvider,
    ValidationObserver,
  },

  data: () => ({
    email: '',
    password: '',
    checkbox: null,
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