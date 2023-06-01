<template>
  <div style="text-align:center;">
    <div class="register_form">
      <h2>ユーザー登録</h2>

      <h3 class="register_items">ユーザー名<span class="required">必須</span></h3>
      <input class="input_form" type="text" placeholder="ユーザー名前" v-model="name"><br>
      <p v-show="validation.name.length" v-for="(name, index) in validation.name" :key="index" class="validation_error">・{{name}}</p>

      <h3 class="register_items">メールアドレス<span class="required">必須</span></h3>
      <input class="input_form" type="email" placeholder="メールアドレス" v-model="email"><br>
      <p v-show="validation.email.length" v-for="(email, index) in validation.email" :key="index" class="validation_error">・{{email}}</p>

      <h3 class="register_items">パスワード(8文字以上)<span class="required">必須</span></h3>
      <input class="input_form" type="password" placeholder="パスワード" v-model="password"><br>
      <p v-show="validation.password.length" v-for="(password, index) in validation.password" :key="index" class="validation_error">・{{password}}</p>

      <h3 class="register_items">生年月日</h3>
      <input class="input_form" type="date" placeholder="誕生日" v-model="birthday"><br>
      <p v-show="validation.birthday.length" v-for="(birthday, index) in validation.birthday" :key="index" class="validation_error">・{{birthday}}</p>

      <h3 class="register_items">住んでる都道府県<span class="required">必須</span></h3>
      <select class="input_form" v-model="pref_id">
      <option class="input_form" selected v-bind:value="null">都道府県を選択</option>
      <option class="input_form" v-for="(pref, index) in prefs" :key="index" v-bind:value="pref.id">
        {{ pref.name }}
      </option>
      </select><br>
      <p v-show="validation.pref_id.length" v-for="(pref_id, index) in validation.pref_id" :key="index" class="validation_error">・{{pref_id}}</p>

      <h3 class="register_items">自己紹介</h3>
      <textarea class="input_form" type="text" placeholder="自己紹介" v-model="introduction"></textarea>
      <p v-show="validation.introduction.length" v-for="(introduction, index) in validation.introduction" :key="index" class="validation_error">・{{introduction}}</p>

      <h3 class="register_items">twitterのURL<span class="validation_error"></span></h3>
      <textarea class="input_form" type="text" placeholder="twitterのURL" v-model="twitter_url"></textarea>
      <p v-show="validation.twitter_url.length" v-for="(twitter, index) in validation.twitter_url" :key="index" class="validation_error">・{{twitter}}</p>
      <v-btn color="primary"  @click="register">登録する</v-btn>
    </div>
  </div>
</template>

<script>
export default {
  layout: 'guest',
  auth: false,
  data(){
    return {
      name: '',
      email: '',
      password: '',
      birthday: null,
      address: '',
      introduction: null,
      twitter_url: null,
      prefs: '',
      pref_id: '',
      err: null,
      validation: {
        errors: [],
        twitter_url: [],
        name: [],
        email: [],
        password: [],
        pref_id: [],
        birthday: [],
        introduction: [],
      },
    }
  },
  async mounted() {
    this.prefs = await this.$axios.get('/api/get_prefs').then(res => {
      return res.data;
    });
  },
  methods:{
    async register(){
      await this.$axios.post('/api/register',
        {
          name: this.name,
          email: this.email,
          password: this.password,
          birthday: this.birthday,
          pref_id: this.pref_id,
          address: this.address,
          introduction: this.introduction,
          twitter_url: this.twitter_url,
        }
      ).then((res) => {
        console.log(res);
        window.alert(res.data.message);
        this.$router.push("/");
      }).catch(err => {
        console.log('エラー発生');
        if (err.response.status === 422) {
          this.validation.errors = err.response.data.errors;
          if ("twitter_url" in this.validation.errors) {
            this.validation.twitter_url = this.validation.errors.twitter_url;
          }
          if ("name" in this.validation.errors) {
            this.validation.name = this.validation.errors.name;
          }
          if ("email" in this.validation.errors) {
            this.validation.email = this.validation.errors.email;
          }
          if ("password" in this.validation.errors) {
            this.validation.password = this.validation.errors.password;
          }
          if ("pref_id" in this.validation.errors) {
            this.validation.pref_id = this.validation.errors.pref_id;
          }
          if ("birthday" in this.validation.errors) {
            this.validation.birthday = this.validation.errors.birthday;
          }
          if ("introduction" in this.validation.errors) {
            this.validation.introduction = this.validation.errors.introduction;
          }
        }
      })
    }
  }
}
</script>

<style>
  .register_form{
    margin-top:20px;
    margin-left:15%;
    width:70%;
    padding-left:5%;
    padding-right:5%;
    background-color:#E8E6E6;
    padding-bottom: 20px;
  }
  .register_items{
    text-align:left;
  }
  .input_form{
    border: 1px solid black;
    background-color: white;
    width:100%;
    height:40px;
    margin-bottom:20px;
  }

  .register_btn{
    margin-bottom:20px;
  }

  .required{
    font-size:12px;
    margin-left:5px;
    color:white;
    background-color:red;
    padding:2px;
  }

  .validation_error{
    color:red;
    text-align:left;
  }
</style>
