<template>
  <div class="comment-layout">
    <div v-if="!inEdit" class="comment-container container">
      <div class="comment-list">
        <comment-list :comment-list="comments"/>
      </div>
      <div class="btn">
        <big-button title="新建" @click="inEdit = true"/>
      </div>
    </div>
    <div v-else class="comment-input container">
      <input-area @close="inEdit = false" @finish="finishInput"/>
    </div>
  </div>
</template>

<script>
import CommentList from '../components/Comment/CommentsContainer.vue'
import InputArea from '../components/Common/InputAera.vue'
import BigButton from '../components/Common/BigButton.vue'

export default {
  name: 'CommentLayout',
  components: { CommentList, InputArea, BigButton },
  props: {
    comments: {type: Array, required: true}
  },
  data () {
    return {
      commentContent: '',
      inEdit: false
    }
  },
  methods: {
    closeInputArea () {
      this.inEdit = false
    },
    finishInput (content) {
      this.commentContent = content
      this.closeInputArea()
    }
  }
}
</script>

<style scoped>
  .comment-layout {
      position: relative;
      width: 100%;
      height: 100%;
      padding-right: 10px;
      padding-left: 10px;
  }
  .container {
    position: relative;
    width: 100%;
    height: 100%;
  }
  .comment-list {
    display: block;
    position: absolute;
    width: 100%;
    top: 0;
    bottom: 45px;
  }

  .btn {
    display: block;
    position: absolute;
    bottom: 5px;
    height: 40px;
    width: 100%;
    box-sizing: border-box;
  }

</style>
