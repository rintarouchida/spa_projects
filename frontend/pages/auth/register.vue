<template>
  <div>
    <h1>登録画面</h1>
    <input type="text" placeholder="ユーザー名前" v-model="name">
    <input type="text" placeholder="メールアドレス" v-model="email">
    <input type="text" placeholder="パスワード" v-model="password">
    <v-btn @click="register">登録する</v-btn>
    <v-btn to="/auth/login">ログイン画面へ</v-btn>
  </div>
</template>

<script>
export default {
  auth: false,
  data(){
    return {
      name: '',
      email: '',
      password: '',
    }
  },
  methods:{
    async register(){
      await this.$axios.post('/api/register',
        {
          name: this.name,
          email: this.email,
          password: this.password,
        }
      ).then(() => {
        window.alert("登録完了しました");
        this.$router.push("/");

      }).catch(err => {
        console.log(err)
      })
    }
  }
}
</script>

<style>

</style>
