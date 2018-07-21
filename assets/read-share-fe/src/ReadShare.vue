<template>
  <div class="hello" style="width: 30%;height: 700px;">
    <comment-layout :comments="commentList"/>
  </div>
</template>

<script>
import UploadFileTest from './components/UploadFileTest.vue'
import PictureReadBlock from './components/ReadBlock/PictureReadBlock.vue'
import CommentsContainer from './components/Comment/CommentsContainer.vue'
import CommentLayout from './layout/CommentLayout'

export default {
  name: 'ReadShare',
  components: { UploadFileTest, PictureReadBlock, CommentsContainer, CommentLayout },
  data () {
    return {
      content: '',
      commentList: [
        {
          id: 1,
          author: this.$currentUser,
          content: 'hficuasduhajshlfkjhsdfjksahfkjs'
        },
        {
          id: 2,
          author: this.$currentUser,
          content: 'hficuasduhaasdasdfasdfjshlfkjhsdfjksahfkjs'
        },
        {
          id: 3,
          author: this.$currentUser,
          content: 'hficuasduhajasasdfasdfshlfkjhsdfjksahfkjs'
        }
      ]
    }
  },
  computed: {
    currentUser () {
      return this.$currentUser
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
