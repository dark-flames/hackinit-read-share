<template>
  <div
    :style="{ 'background-color': color }" class="btn"
    @click="$emit('click')">{{ title }}</div>
</template>

<script>
export default {
  name: 'BigButton',
  props: {
    title: { type: String, required: true }
  },
  computed: {
    color () {
      return this.$config.theme.mainColor
    }
  }
}
</script>

<style scoped>
    span {
      display: block;
    }
    .btn {
        cursor: pointer;
        height: 100%;
        display: block;
        border-radius: 5px;
        font-size: 1.14285714rem;
        color: #fff;
        text-align: center;
        vertical-align:middle;
        box-sizing: border-box;
        padding: 8px 15px 8px 15px;
    }
</style>
