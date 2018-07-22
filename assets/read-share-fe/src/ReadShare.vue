<template>
  <div class="read-share">
    <div
      :class="{
        'reader-layout-full': readerOnly,
        'reader-layout-half': !readerOnly
    }" class="reader-layout layout">
      <reader-layout @change="contentChange"/>
      <hover-button
        v-if="!readerOnly" class="big-screen-btn" title="返回"
        @click="content=''"/>
    </div>
    <div
      :class="{
        'comment-layout-half': !readerOnly,
        'comment-layout-hidden': readerOnly
    }" class="comment-layout layout">
      <comment-layout :comments="commentList" @new="newComment"/>

    </div>
    <hover-button
      v-if="!readerOnly" title="返回" class="small-screen-btn"
      @click="content=''"/>
  </div>
</template>

<script>
import ReaderLayout from './layout/ReaderLaylout.vue'
import CommentLayout from './layout/CommentLayout.vue'
import HoverButton from './components/Common/HoverButton.vue'

import { requestPost, requestGet } from './utils/request'

export default {
  name: 'ReadShare',
  components: { ReaderLayout, CommentLayout, HoverButton },
  data () {
    return {
      content: '',
      commentList: []
    }
  },
  computed: {
    currentUser () {
      return this.$currentUser
    },
    readerOnly () {
      return !this.content || this.content === ''
    }
  },
  methods: {
    async newComment (commetContent) {
      let { comment } = await requestPost('/comment/newComment', {
        content: commetContent,
        targetContent: this.content
      })

      this.commentList.push(comment)
    },
    async contentChange (newContent) {
      let { comments } = await requestGet('/comment/getComments', {
        targetContent: newContent
      })
      this.commentList = comments
      this.content = newContent
    }
  }
}
</script>

<style scoped>
  .layout {
    position: relative;
    box-sizing: border-box;
    margin: 0;
    float:left;
  }

  .comment-layout {
    border-left: 1px solid #ddd;
  }
  .read-share {
    height: 100%;
    width: 100%;
  }
  .reader-layout-half {
    display: none;
  }

  .reader-layout-full {
    height: 100%;
    width: 100%;
  }

  .comment-layout-half {
    height: 100%;
    width: 100%;
  }

  .comment-layout-hidden {
    display: none;
  }
  .big-screen-btn {
    display: none;
  }

  .small-screen-btn {
    display: block;
  }

  @media only screen and (min-width: 768px) {
    .reader-layout-half {
      display: block;
      height: 100%;
      width: 70%;
    }

    .comment-layout-half {
      display: block;
      height: 100%;
      width: 30%;
    }

    .big-screen-btn {
      display: block;
    }

    .small-screen-btn {
      display: none;
    }
  }
</style>
