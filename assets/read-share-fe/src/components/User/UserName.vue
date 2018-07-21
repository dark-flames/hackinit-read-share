<template>
  <div class="username">
    <h3 :style="{ color: color }" class="username-str">{{ username }}</h3>
  </div>
</template>

<script>
export default {
  name: 'Username',
  props: {
    username: { type: String, required: true }
  },
  computed: {
    color () {
      return this.$config.theme.mainColor
    }
  }
}
</script>

<style scoped>
    .username-str {
      margin-bottom: 0;
      margin-top: 10px;
    }
</style>
