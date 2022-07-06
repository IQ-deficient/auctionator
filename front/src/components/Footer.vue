<template>
  <v-container>
    <v-footer padless>
      <v-card flat class="text-center">
        <v-card-text>
          <v-btn v-for="icon in icons" :key="icon" class="mx-4" icon>
            <v-icon size="24px">{{ icon }}</v-icon>
          </v-btn>
        </v-card-text>
        <v-card-text
            class="pt-0">
          Sodales ut etiam sit amet nisl purus in mollis nunc. At urna condimentum mattis pellentesque. Lectus urna duis
          convallis convallis tellus. Quis viverra nibh cras pulvinar mattis nunc sed blandit. Elementum sagittis vitae
          et leo duis ut. Amet est placerat in egestas erat imperdiet. Odio eu feugiat pretium nibh ipsum consequat
          nisl. Dignissim convallis aenean et tortor at risus. Ullamcorper dignissim cras tincidunt lobortis feugiat
          vivamus at augue. In est ante in nibh mauris. Nisi est sit amet facilisis magna etiam tempor orci.
        </v-card-text>
        <v-divider></v-divider>
        <v-card-text>
          {{ new Date().getFullYear() }} —
          <strong>Auction House</strong>
        </v-card-text>
      </v-card>
    </v-footer>
  </v-container>
</template>

<script>
export default {
  name: "Footer",
  data: () => ({
    icons: [
      "mdi-facebook",
      "mdi-twitter",
      "mdi-google-plus",
      "mdi-linkedin",
      "mdi-instagram"
    ]
  })
};
</script>