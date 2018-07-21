<template>
  <div class="hello">
    hello world
    <h1 :style="{ color: color }">{{ test }}</h1>
    <span class="test">233</span>
  </div>
</template>

<script>
export default {
  name: 'ReadShare',
  computed: {
    color () {
      return this.$config.color
    }
  }
}
</script>

<style scoped>
  .test {
    color: red;
  }
</style>
