<template>
  <div class="user-avatar">
    <img :src="avatarURL" class="avatar-img">
  </div>
</template>

<script>
import gravatar from 'gravatar'

export default {
  name: 'UserAvatar',
  props: {
    email: { type: String, required: true }
  },
  computed: {
    avatarURL () {
      return gravatar.url(this.email, { s: '30', r: 'x', d: 'retro' })
    }

  }
}
</script>

<style scoped>
    .avatar-img {
        width: 30px;
        height: 30px;
        border-radius: 30px;
    }
    .user-avatar {
      margin-top: 0;
    }
</style>
