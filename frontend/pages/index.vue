<template>
  <div>
    <h1>ログイン中</h1>
    <v-btn type="primary" @click="logout">ログアウトする</v-btn>
    <v-btn type="primary" @click="getData">データを取得する</v-btn><br>
    ユーザーID: {{user.id}}<br>
    ユーザー名: {{user.name}}<br>
    メールアドレス: {{user.email}}<br>
    <v-btn type="primary" to="/auth/edit">編集画面へ</v-btn><br>
  </div>
</template>

<script>
export default {
  data() {
    return {
       user: this.getData(),
    }
  },
  methods: {
    async logout() {
      await this.$auth.logout().
        then(() => {
          this.$router.push("/auth/login");
        }).
        catch((e)=> console.log(e))
    },
    async getData() {
      this.user = await this.$axios.get('/api/user_info').then(res => {
        return res.data;
      });
    },
  },
}
</script>

<style>

</style>
