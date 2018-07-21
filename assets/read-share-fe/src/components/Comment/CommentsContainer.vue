<template>
  <div class="comments-container">
    <comment-block v-for="comment in commentList" :comment="comment" :key="comment.id"/>
  </div>
</template>
<script>
import CommentBlock from './CommentBlock.vue'

export default {
  name: 'CommentsContainer',
  components: { CommentBlock },
  props: { commentList: { type: Array, required: true } }
}
</script>

<style scoped>
    .comments-container {
        width: 100%;
    }
</style>
