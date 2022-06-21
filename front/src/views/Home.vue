<template>
  <div class="">
    <h1 style="color: white" class="pt-6 pb-2 text-decoration-underline">Welcome, here is your Reminder list</h1>
    <h5 style="color: white" class="pb-6 text-decoration-underline">These are the things that need to be checked</h5>
    <ul style="color: chartreuse">
      <li v-for="item in todoList" v-bind:key="item">
        {{ item }}
      </li>
    </ul>
  </div>
</template>

<script>
// @ is an alias to /src
// import HelloWorld from '@/components/HelloWorld.vue'

export default {
  name: 'Home',
  components: {
    // HelloWorld
  },
  data: () => ({
    todoList: [
      'Design home page',
      'Add Sweetalert props where needed',
      'Add functional Loading attribute to components that need it',
      'Clean up Todo-s throughout the code',
    ]
  }),

  mounted() {
    document.title = 'Auction House'
  }
}
</script>
