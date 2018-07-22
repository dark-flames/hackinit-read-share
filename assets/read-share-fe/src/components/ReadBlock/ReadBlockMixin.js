export default {
  data () {
    return {
      content: '',
      loading: false
    }
  },
  watch: {
    content (newValue) {
      this.$emit('change', newValue)
    }
  }
}
