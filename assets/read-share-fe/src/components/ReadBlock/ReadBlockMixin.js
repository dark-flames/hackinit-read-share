export default {
  data () {
    return {
      content: ''
    }
  },
  watch: {
    content (newValue) {
      this.$emit('change', newValue)
    }
  }
}
