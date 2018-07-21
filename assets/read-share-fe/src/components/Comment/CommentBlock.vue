<template>
  <div class="comment">
    <div class="user-card">
      <user-card :user="comment.author"/>
    </div>
    <div class="content-container">
      <p class="content">
        {{ comment.content }}
      </p>
    </div>
  </div>
</template>

<script>
import UserCard from '../User/UserCard'
export default {
  name: 'CommentBlock',
  components: { UserCard },
  props: {
    comment: { type: Object, required: true }
  }
}
</script>

<style scoped>
    .comment {
        display: block;
        border: 1px solid #ddd;
        border-radius: 3px;
        margin: 10px 6px 10px 6px;
    }
    .user-card {
        display: block;
        border-bottom: 1px solid #f2f2f2;
    }
    .content {
        display: block;
        margin: 20px 13px 20px 13px;
        word-wrap:break-word;
        overflow: hidden;
    }
</style>
