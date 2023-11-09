<template>
  <div style="text-align: center">
    <div class="password_reset_form">
      <h1>パスワード再設定画面</h1>
      <p>新しいパスワードを入力してください。</p>
      <h3 class="password_reset_items">パスワード</h3>
      <input
        v-model="password"
        class="input_form"
        type="password"
        placeholder="パスワード"
      />
      <h3 class="password_reset_items">パスワード(確認用)</h3>
      <input
        v-model="password_confirm"
        class="input_form"
        type="password"
        placeholder="パスワード"
      />
      <v-btn color="red" style="color: white" @click="resetPassword">送信する</v-btn
      >
    </div>
  </div>
</template>

<script>
export default {
  layout: 'guest',
  auth: false,
  data() {
    return {
      token: this.$route.params.token,
      email: this.$route.query.email,
      password: "",
      password_confirm: "",
    }
  },
  methods: {
    async resetPassword() {
      await this.$axios.get("sanctum/csrf-cookie");
      await this.$axios
        .post('/api/reset_password', {
          email: this.email,
          token: this.token,
          password: this.password,
        })
        .then((res) => {
          console.log(res)
          window.alert(res.data.message)
        }).catch((res) => {
          console.log(res);
        })
    },
  },
}
</script>

<style scoped>
.password_reset_form {
  margin-top: 20px;
  margin-left: 15%;
  width: 70%;
  padding-left: 5%;
  padding-right: 5%;
  background-color: #e8e6e6;
  padding-bottom: 20px;
}
.password_reset_items {
  text-align: left;
}
.input_form {
  border: 1px solid black;
  background-color: white;
  width: 100%;
  height: 40px;
  margin-bottom: 20px;
}
</style>
