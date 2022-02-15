<template>
  <v-card>
    <v-footer
        v-bind="localAttrs"
        :padless="padless"
    >
      <v-card
          flat
          tile
          width="100%"
          class="accent text-center"
      >
        <v-card-text>
          <v-btn
              v-for="icon in icons"
              :key="icon"
              class="mx-4"
              icon
          >
            <v-icon size="24px">
              {{ icon }}
            </v-icon>
          </v-btn>
        </v-card-text>

        <v-divider></v-divider>

        <v-card-text class="white--text">
          {{ new Date().getFullYear() }} — <strong>The Auction House</strong>
        </v-card-text>
      </v-card>
    </v-footer>

  </v-card>

</template>

<script>
export default {
name: "Footer",
  data: () => ({
    icons: [
      'mdi-home',
      'mdi-email',
      'mdi-calendar',
      'mdi-delete',
    ],
    items: [
      'default',
      'absolute',
      'fixed',
    ],
    padless: true,
  }),
  computed: {
    localAttrs () {
      const attrs = {}

      if (this.variant === 'default') {
        attrs.absolute = false
        attrs.fixed = true
      } else {
        attrs[this.variant] = true
      }
      return attrs
    },
  },

}
</script>

<style scoped>

</style>