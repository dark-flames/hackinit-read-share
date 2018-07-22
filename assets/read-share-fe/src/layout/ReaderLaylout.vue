<template>
  <div class="reader-layout">
    <div class="reader">
      <picture-read-block @change="newContent"/>
    </div>
  </div>
</template>

<script>
import PictureReadBlock from '../components/ReadBlock/PictureReadBlock'
export default {
  name: 'ReaderLayout',
  components: { PictureReadBlock },
  methods: {
    newContent (content) {
      this.$emit('change', content)
    }
  }
}
</script>

<style scoped>
    .reader-layout {
        position: relative;
        height: 100%;
        width: 100%;
    }

    .reader {
        position: absolute;
        top: 50%;
        left: 50%;
        margin: auto;
        width: 70px;
        height: 40px;
    }
</style>
