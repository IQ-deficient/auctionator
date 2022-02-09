<template>
  <div class="about">
    <h1>This is an about page</h1>
  </div>
</template>

<script>

export default {
  name: 'About',
  data: () => ({
  }),
  created() {
    this.$swal({
      title: 'Good job!',
      text: 'Do you want to continue',
      icon: 'error',
      confirmButtonText: 'Cool'
    })
  }
}

</script>
