<template>
  <div>
    <h1>パスワード再設定</h1>
    <p>{{ token }}</p>
  </div>
</template>

<script>
export default {
  layout: 'guest',
  auth: false,
  data() {
    return {
      token: this.$route.params.token,
    }
  },
}
</script>

<style>
</style>
