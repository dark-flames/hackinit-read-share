<template>
  <div>
    <input type="file" @change="uploadImage">
    <div>{{ ocrContent }}</div>
  </div>
</template>

<script>
import { requestPost } from '../utils/request'

export default {
  name: 'UploadFileTest',
  data () {
    return {
      ocrContent: ''
    }
  },
  methods: {
    async uploadImage (event) {
      let file = event.target.files[0]
      let reader = new FileReader()

      if (!/image\/\w+/.test(file.type)) {
        console.log('not image')
      } else {
        reader.onload = async (e) => {
          let content = e.target.result
          this.ocrContent = await requestPost(this.$config.ocrURL, {
            'file': content
          })
        }
        reader.readAsText(file)
      }
    }
  }
}
</script>
