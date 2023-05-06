<template>
  <div>
    <h1>ログイン中</h1>
    <v-btn type="primary" @click="logout">ログアウトする</v-btn>
  </div>
</template>

<script>
export default {
  methods: {
    async logout() {
      await this.$auth.logout().
        then(location.reload()).
        catch()
    }
  },
}
</script>

<style>

</style>
