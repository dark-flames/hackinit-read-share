<template>
  <div class="picture-read-block">
    <div :style="{ 'background-color': color }" class="ocr" >
      <span v-if="loading">loading...</span>
      <span v-else> 选择文件 </span>
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
  computed: {
    color () {
      return this.$config.theme.mainColor
    }
  },
  methods: {
    async uploadImage () {
      this.loading = true
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
      this.loading = false
    }
  }
}
</script>

<style scoped>
  .ocr {
    padding: 4px 10px;
    height: 30px;
    width: 65px;
    line-height: 30px;
    cursor: pointer;
    color: white;
    border-radius: 4px;
    overflow: hidden;
    display: inline-block;
    *display: inline;
    *zoom: 1
  }
  input {
    position: absolute;
    font-size: 100px;
    right: 0;
    top: 0;
    opacity: 0;
    cursor: pointer
  }
  .picture-read-block {
    width: 70px;
  }
</style>
