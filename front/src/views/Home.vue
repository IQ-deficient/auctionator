<template>
  <div class="">
    <h2 style="color: white" class="pt-6 pb-4 text-decoration-underline">Welcome, here is your TODO list</h2>
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
      'Bid and History pages should be made functional at least',
      'Add Sweetalert props where needed',
      'Add functional Loading attribute to components that need it',
      'Clean up Todo-s throughout the code',
      'Proofread code and seeders for profanity',
      'Fix the issue where after failed Login attempt page reloads and gives no message',
      'Close the user actions dialog on some actions',
    ]
  }),

  mounted() {
    document.title = 'Auction House'
  }
}
</script>
