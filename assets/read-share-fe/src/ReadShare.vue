<template>
  <div class="hello">
    <picture-read-block @change="updateContent"/>
    {{ content }}
  </div>
</template>

<script>
import UploadFileTest from './components/UploadFileTest.vue'
import PictureReadBlock from './components/ReadBlock/PictureReadBlock.vue'

export default {
  name: 'ReadShare',
  components: { UploadFileTest, PictureReadBlock },
  data () {
    return {
      content: ''
    }
  },
  methods: {
    updateContent (data) {
      this.content = data
    }
  }
}
</script>

<style scoped>
  .test {
    color: red;
  }
</style>
