<template>
  <div class="user-card clear-fix">
    <span class="user-avatar">
      <user-avatar :email="user.email"/>
    </span>
    <span class="user-name">
      <user-name :username="user.username"/>
    </span>
  </div>
</template>

<script>
import UserAvatar from './UserAvatar.vue'
import UserName from './UserName.vue'

export default {
  name: 'UserCard',
  components: { UserAvatar, UserName },
  props: {
    user: { type: Object, required: true }
  }
}
</script>

<style scoped>
    .user-card {
        width: 100%;
    }

    .user-avatar {
        display: block;
        margin-right: 6px;
        margin-left: 6px;
        margin-top: 6px;
        float: left;
    }
    .user-name {
        display: block;
        float: left;
    }
    .clear-fix:after{
      content:"";
      height:0;
      line-height:0;
      display:block;
      visibility:hidden;
      clear:both;
    }
</style>
