<template>
  <div class="input-area">
    <div class="topbar">
      <a class="close-btn" @click="$emit('close')"><h4 class="str">关闭</h4></a>
    </div>
    <div class="text-area-container">
      <textarea v-model="content" class="text-area"/>
    </div>
    <div class="btn-container">
      <big-button title="发布" @click="$emit('finish', content)"/>
    </div>
  </div>
</template>

<script>
import BigButton from './BigButton.vue'

export default {
  name: 'InputAeraVue',
  components: { BigButton },
  data () {
    return {
      content: 'asdfasf'
    }
  },
  computed: {
    color () {
      return this.$config.theme.mainColor
    }
  }

}
</script>

<style scoped>
    h4 {
      margin: 0;
    }
    .close-btn {
        display: inline-block;
        color: darkgray;
        cursor: pointer;
    }
    .input-area {
        position: relative;
        display: block;
        width: 100%;
        height: 100%;
    }

    textarea {
        display: block;
        height: 100%;
        width: 100%;
        padding: 8px;
        resize: none;
        border: none;
        word-wrap: break-word;
        overflow-x: hidden;
        overflow-y: auto;
    }

    textarea:focus {
      outline: none;
    }

    .topbar {
        display: block;
        padding-left: 10px;
        width: 100%;
        height: 30px;
        box-sizing: border-box;
        vertical-align:middle;
        border-bottom: 1px solid #f2f2f2;
    }

    .btn-container {
        display: block;
        position: absolute;
        bottom: 5px;
        height: 40px;
        width: 100%;
        box-sizing: border-box;
    }

    .text-area-container {
        display: block;
        position: absolute;
        width: 100%;
        top: 35px;
        bottom: 45px;
    }

</style>
