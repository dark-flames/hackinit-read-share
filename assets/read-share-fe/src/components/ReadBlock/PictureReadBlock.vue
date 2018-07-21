<template>
  <div class="picture-read-block">
    <div class="ocr">
      <input type="file" accept="image/*" @change="uploadImage">
    </div>
  </div>
</template>

<script>
import ReadBlockMixin from './ReadBlockMixin'
import { requestPostQS } from '../../utils/request'

export default {
  name: 'PictureReadBlock',
  mixins: [ ReadBlockMixin ],
  methods: {
    async uploadImage () {
      let image = event.target.files[0]

      const readImage = async (file) => {
        return new Promise((resolve, reject) => {
          let reader = new FileReader()
          reader.onload = () => {
            resolve(reader.result)
          }
          reader.onerror = () => {
            reject(new Error('Fail to open file'))
          }
          reader.readAsDataURL(file)
        })
      }

      const imageContent = await readImage(image)

      const { content } = await requestPostQS(this.$config.ocrHost, { image: imageContent })

      this.content = content
    }
  }
}
</script>

<style scoped>

</style>
