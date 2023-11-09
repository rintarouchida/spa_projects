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
      <p v-show="validation.password.length" v-for="(password, index) in validation.password" :key="index" class="validation_error">・{{password}}</p>
      <h3 class="password_reset_items">パスワード(確認用)</h3>
      <input
        v-model="password_confirm"
        class="input_form"
        type="password"
        placeholder="パスワード(確認用)"
      />
      <p v-show="validation.password_confirm.length" v-for="(password_confirm, index) in validation.password_confirm" :key="index" class="validation_error">・{{password_confirm}}</p>
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
      validation: {
        errors: [],
        password: [],
        password_confirm: [],
      },
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
          password_confirm: this.password_confirm,
        })
        .then((res) => {
          console.log(res)
          window.alert(res.data.message)
        }).catch((err) => {
          console.log(err.response.data.errors);
          if (err.response.status === 422) {
            this.validation.errors = err.response.data.errors;
            if ("password" in this.validation.errors) {
              this.validation.password = this.validation.errors.password;
            }
            if ("password_confirm" in this.validation.errors) {
              this.validation.password_confirm = this.validation.errors.password_confirm;
            }
          }
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
.validation_error{
    color:red;
    text-align:left;
  }
</style>
