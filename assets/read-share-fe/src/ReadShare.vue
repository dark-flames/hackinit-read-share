<template>
  <div class="hello">
    hello world
    <h1 :style="{ color: color }">{{ test }}</h1>
    <span class="test">233</span>
    <upload-file-test/>
  </div>
</template>

<script>
import UploadFileTest from './components/UploadFileTest.vue'

export default {
  name: 'ReadShare',
  components: {UploadFileTest},
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
