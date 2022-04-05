<template>
  <div>
    <v-progress-linear
        :active="loading"
        :indeterminate="loading"
        absolute
        top
        color="primary"
    ></v-progress-linear>
  </div>
</template>

<script>
export default {
  name: "Loader",

  data: () => ({
    loading: false,
  }),

  watch: {
    loading (val) {
      if (!val) return

      setTimeout(() => (this.loading = false), 3000)
    },
  },
}
</script>

<style scoped>

</style>