<template>
  <main>
    <h2>Current counts: <span>{{ count }}</span> !</h2>
  </main>
</template>

<script>
  export default {
    data () {
      return {
        count: 0
      }
    },

    created () {
      setInterval(() => {
        this.count++
      }, 1000)
    }
  }
</script>
