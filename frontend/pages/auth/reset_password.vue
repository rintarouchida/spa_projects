<template>
  <div style="text-align: center">
    <div class="password_reset_form">
      <h2 style="margin-bottom:50px;">パスワード<br>リセット画面</h2>
      <p>指定のアドレス先にメールを送信します。</p>
      <h3 class="password_reset_items">メールアドレス</h3>
      <input
        v-model="email"
        class="input_form"
        type="email"
        placeholder="メールアドレス"
      />
      <p v-show="validation.email.length" v-for="(email, index) in validation.email" :key="index" class="validation_error">・{{email}}</p>
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
      validation: {
        errors: [],
        email: [],
      },
    }
  },
  methods: {
    async sendEmail() {
      await this.$axios.get("sanctum/csrf-cookie");
      await this.$axios
        .post('/api/send_email', {
          email: this.email,
        })
        .then((res) => {
          console.log(res)
          window.alert(res.data.message)
          this.$router.push('/auth/login')
        }).catch((err) => {
          console.log(err);
          if (err.response.status === 422) {
            this.validation.errors = err.response.data.errors;
            if ("email" in this.validation.errors) {
              this.validation.email = this.validation.errors.email;
            }
          }
          if (err.response.status === 400) {
            console.log(err.response.data)
            window.alert(err.response.data.message)
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

@media (max-width: 400px) {
  .password_reset_form {
    margin-left: 5%;
    width: 90%;
  }
  .password_reset_items {
    font-size:14px;
  }
  .input_form{
    height:30px;
  }
}
</style>
