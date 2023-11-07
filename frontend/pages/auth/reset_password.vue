<template>
  <div style="text-align: center">
    <div class="password_reset_form">
      <h1>パスワードリセット画面</h1>
      <p>指定のアドレス先にメールを送信します。</p>
      <h3 class="password_reset_items">メールアドレス</h3>
      <input
        v-model="email"
        class="input_form"
        type="email"
        placeholder="メールアドレス"
      />
      <v-btn color="red" style="color: white" @click="sendEmail"
        >送信する</v-btn
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
      email: '',
    }
  },
  methods: {
    async sendEmail() {
      await this.$axios
        .post('/api/reset_password/send_email', {
          email: this.email,
        })
        .then((res) => {
          console.log(res)
          window.alert(res.data.message)
          this.$router.push('/')
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
