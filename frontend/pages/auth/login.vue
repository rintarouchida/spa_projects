<template>
  <div style="text-align:center;">
    <div class="login_form">
      <h2 style="margin-bottom:50px;">ログイン画面</h2>
      <h3 class="login_items">メールアドレス</h3>
      <input class="input_form" type="text" v-model="email" placeholder="メールアドレス"><br>
      <h3 class="login_items">パスワード</h3>
      <input class="input_form" type="password" v-model="password" placeholder="パスワード"><br>
      <p class="failed_message">{{error_message}}</p>
      <v-btn color="red" style="color:white" @click="login">ログイン</v-btn>
      <router-link to="/auth/reset_password" class="links" style="text-decoration: none; color:blue;">パスワードを忘れたら</router-link>
      <router-link to="/auth/register" class="links">ユーザー登録はこちらから</router-link>
    </div>
  </div>
</template>

<script>
    export default {
        layout: 'guest',
        auth: false,
        data() {
            return {
                email: null,
                password: null,
                error_message: null,
            }
        },
        methods: {
            async login() {
                try {
                    // ログインする
                    const response = await this.$auth
                        .loginWith("laravelApi", {
                            data: {
                                email: this.email,
                                password: this.password
                            }
                        })
                        .then((res) => {
                            // ログインに成功したら、/にページ遷移
                            window.alert(res.data.message);
                            this.$router.push("/");
                        });
                    console.log(response);
                } catch (error) {
                    // ログインに失敗したら、コンソールに出力する
                    this.error_message = error.response.data.message;
                }
            }
        }
    }
</script>

<style>
.login_form{
    margin-top:20px;
    margin-left:15%;
    width:70%;
    padding-left:5%;
    padding-right:5%;
    background-color:#E8E6E6;
    padding-bottom: 20px;
  }
  .login_items{
    text-align:left;
  }
  .input_form{
    border: 1px solid black;
    background-color: white;
    width:100%;
    height:40px;
    margin-bottom:20px;
  }
  .links {
    display:block;
    text-decoration: none;
    color:blue;
    margin-top:20px;
    margin-bottom:20px;
  }
  .failed_message{
    color:red;
    text-align:left;
  }

@media (max-width: 400px) {
  .login_form {
    margin-left: 5%;
    width: 90%;
  }
  .login_items{
    font-size:14px;
  }
  .failed_message{
    font-size:14px;
  }
  .input_form{
    height:30px;
  }
}
</style>
