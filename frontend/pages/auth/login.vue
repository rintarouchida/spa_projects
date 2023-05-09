<template>
  <div>
    <h1>ログイン画面</h1>
    <input type="text" v-model="email" placeholder="メールアドレス">
    <input type="password" v-model="password" placeholder="パスワード">
    <button @click="login">ログイン</button>
    <v-btn to="/auth/register">登録画面へ</v-btn>
  </div>
</template>

<script>
    export default {
        auth: false,
        data() {
            return {
                email: null,
                password: null
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
                        .then(() => {
                            // ログインに成功したら、/にページ遷移
                            window.alert("ログインしました");
                            this.$router.push("/");
                        });
                    console.log(response);
                } catch (error) {
                    // ログインに失敗したら、コンソールに出力する
                    window.alert("ログイン失敗");
                    console.log(error);
                }
            }
        }
    }
</script>
