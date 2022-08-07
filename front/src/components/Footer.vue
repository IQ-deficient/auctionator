<template>
  <v-footer padless>
    <v-card
      flat
      tile
      class="text-center"
      width="100%"
    >
      <v-card-text block>
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
        class="pt-0 pl-10 pr-10"
      >
        Online auction house platform for buyers and sellers of valuables. Buy and sell gold, coins, jewelry, precious
        metals, antiques, and collectibles. Buy or sell items from around the world through our secure, fast, and
        efficient platform.
      </v-card-text>
      <v-divider></v-divider>
      <v-card-text>
        <strong> © All rights reserved {{ new Date().getFullYear() }} -
          Vladimir Gazivoda</strong>
      </v-card-text>
    </v-card>
  </v-footer>
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