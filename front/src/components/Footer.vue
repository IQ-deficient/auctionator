<template>
  <v-container>
    <v-footer padless>
      <v-card flat class="text-center">
        <v-card-text>
          <div
            v-for="(icon, id) in icons"
            :key="id"
            class="mx-4"
            style="display: inline"
          >
            <v-btn
              class="ma-2"
              text
              icon
              color="primary"
              :href="icon.link"
              target="_blank"
            >
              <v-icon size="24px">{{ icon.icon }}</v-icon>
            </v-btn>
          </div>
        </v-card-text>
        <v-card-text
          class="pt-0"
        >
          Sodales ut etiam sit amet nisl purus in mollis nunc. At urna condimentum mattis pellentesque. Lectus urna duis
          convallis convallis tellus. Quis viverra nibh cras pulvinar mattis nunc sed blandit. Elementum sagittis vitae
          et leo duis ut. Amet est placerat in egestas erat imperdiet. Odio eu feugiat pretium nibh ipsum consequat
          nisl. Dignissim convallis aenean et tortor at risus. Ullamcorper dignissim cras tincidunt lobortis feugiat
          vivamus at augue. In est ante in nibh mauris. Nisi est sit amet facilisis magna etiam tempor orci.
        </v-card-text>
        <v-divider></v-divider>
        <v-card-text>
          <strong> © {{ new Date().getFullYear() }} -
            Vladimir Gazivoda</strong>
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
      {
        id: 1,
        icon: "mdi-github",
        link: "https://github.com/IQ-deficient"
      },
      {
        id: 2,
        icon: "mdi-linkedin",
        link: "https://www.linkedin.com/in/vladimir-gazivoda/"
      },
      {
        id: 3,
        icon: "mdi-instagram",
        link: "https://www.instagram.com/gruesome_v/"
      },
    ]
  })
};
</script>