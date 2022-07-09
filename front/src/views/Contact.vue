<template>
  <v-main>
    <div class="staticHero">
      <v-img src="../assets/images/contact.jpg" max-height="250">
        <v-row align="end" class="lightbox white--text pa-2 fill-height">
          <v-col>
            <v-container>
              <div class="headline">Contact Us</div>
            </v-container>
          </v-col>
        </v-row>
      </v-img>
    </div>
    <div class="block">
      <v-container>
        <v-card class="ma-1">
          <validation-observer ref="form">
            <v-form ref="form" v-model="valid" lazy-validation class="ma-4" @submit.prevent="sendMail()">
              <v-row>
                <v-col cols="12" sm="12">
                  <validation-provider
                    v-slot="{ errors }"
                    name="subject"
                    rules="required|min:1|max:30"
                  >
                    <v-text-field :error-messages="errors"
                                  v-model="title" :rules="titleRules" label="Subject" required></v-text-field>
                  </validation-provider>
                </v-col>
              </v-row>
              <v-row>
                <v-col cols="12" sm="12">
                  <validation-provider
                    v-slot="{ errors }"
                    name="email"
                    rules="required|email"
                  >
                    <v-text-field :error-messages="errors"
                                  v-model="email" :rules="emailRules" label="E-mail" required></v-text-field>
                  </validation-provider>
                </v-col>
              </v-row>
              <v-row>
                <v-col cols="12" sm="12">
                  <validation-provider
                    v-slot="{ errors }"
                    name="message"
                    rules="required|min:10|max:1000"
                  >
                    <v-textarea :error-messages="errors"
                                v-model="message" :rules="messageRules" label="Message" required></v-textarea>
                  </validation-provider>
                </v-col>
              </v-row>
              <v-row>
                <v-col cols="12" sm="12">
                  <v-btn type="submit" :loading="loading" color="primary" class="mr-4">Send</v-btn>
                </v-col>
              </v-row>
            </v-form>
          </validation-observer>
        </v-card>
      </v-container>
    </div>
    <div style="width: 100%">
      <iframe width="100%" height="450" frameborder="0" scrolling="no" marginheight="0" marginwidth="0"
              src="https://maps.google.com/maps?width=100%25&amp;height=450&amp;hl=en&amp;q=+(Univerzitet%20Mediteran%20Podgorica)&amp;t=&amp;z=16&amp;ie=UTF8&amp;iwloc=B&amp;output=embed">
        <a href="https://www.gps.ie/wearable-gps/">gps smartwatches</a></iframe>
    </div>
  </v-main>
</template>
<script>
import axios from "axios";
import Swal from "sweetalert2";
import {required, email, min, max} from 'vee-validate/dist/rules'
import {extend, ValidationObserver, ValidationProvider, setInteractionMode} from 'vee-validate'

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
  message: 'The {_field_} must be at least {length} characters.'
})

extend('max', {
  ...max,
  message: 'The {_field_} may not be greater than {length} characters.'
})

export default {
  name: "Contact",

  components: {
    ValidationProvider,
    ValidationObserver,
  },

  data: () => ({
    valid: true,
    loading: false,
    title: "",
    email: "",
    message: "",
    rules: [
      {
        titleRules: [
          v => !!v || "Title is required",
          v => (v && v.length <= 20) || "Title must be less than 10 characters"
        ],
      },
      {
        emailRules: [
          v => !!v || "E-mail is required",
          v => /.+@.+\..+/.test(v) || "E-mail must be valid"
        ],
      },
      {
        messageRules: [
          v => !!v || "Message is required",
          v => (v && v.length >= 10) || "Message must be more than 10 characters"
        ],
      }
    ]
  }),

  methods: {
    validate() {
      if (this.$refs.form.validate()) {
        this.snackbar = true;
      }
    },

    reset() {
      this.$refs.form.reset();
    },

    sendMail() {
      this.$refs.form.validate().then(success => {
        if (success) {
          this.loading = true
          axios.post('contact_us', {
            email: this.email,
            title: this.title,
            message: this.message,
          })
            .then(response => {
                if (response) {
                  Swal.fire({
                    title: 'Email Sent!',
                    icon: 'success'
                  })
                  this.loading = false
                  this.reset()
                }
              }
            )
            .catch(error => {
              console.log(error)
              this.dataLoading = false
            })
        }
      })
    }
  },

  mounted() {
  }
};
</script>