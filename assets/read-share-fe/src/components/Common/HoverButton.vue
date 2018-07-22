<template>
  <div class="hover-button" @click="$emit('click')">{{ title }}</div>
</template>

<script>
export default {
  name: 'HoverButton',
  props: {
    title: { type: String, required: true }
  }
}
</script>

<style scoped>
    .hover-button {
        position: absolute;
        cursor: pointer;
        height: 22px;
        width: 60px;
        text-align: center;
        color: black;
        background-color: #ddd;
        vertical-align:middle;
        right: 10%;
        bottom: 15%;
        padding: 8px 15px 8px 15px;
        border-radius: 30px;
        box-shadow: 1px 1px 2px #333333;
    }
</style>
