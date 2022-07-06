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
          <v-form ref="form" v-model="valid" lazy-validation class="ma-4">
            <v-row>
              <v-col cols="12" sm="12">
                <v-text-field v-model="title" :counter="10" :rules="titleRules" label="Subject" required></v-text-field>
              </v-col>
            </v-row>
            <v-row>
              <v-col cols="12" sm="12">
                <v-text-field v-model="email" :rules="emailRules" label="E-mail" required></v-text-field>
              </v-col>
            </v-row>
            <v-row>
              <v-col cols="12" sm="12">
                <v-textarea v-model="message" :rules="messageRules" label="Message" required></v-textarea>
              </v-col>
            </v-row>
            <v-row>
              <v-col cols="12" sm="12">
                <v-btn :disabled="!valid" color="primary" class="mr-4" @click="validate">Send</v-btn>
              </v-col>
            </v-row>
          </v-form>
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
export default {
  name: "Contact",
  data: () => ({
    valid: true,

    title: "",
    titleRules: [
      v => !!v || "Title is required",
      v => (v && v.length <= 20) || "Title must be less than 10 characters"
    ],
    email: "",
    emailRules: [
      v => !!v || "E-mail is required",
      v => /.+@.+\..+/.test(v) || "E-mail must be valid"
    ],
    message: "",
    messageRules: [
      v => !!v || "Message is required",
      v => (v && v.length >= 10) || "Message must be more than 10 characters"
    ],
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

    }
  },

  mounted() {
    document.title = 'Contact - Auction House'
  }
};
</script>