<template>
  <div style="text-align: center">
    <div class="register_form">
      <h2 style="margin-bottom:50px;">ユーザー登録</h2>

      <h3 class="register_items">
        ユーザー名<span class="required">必須</span>
      </h3>
      <input
        v-model="name"
        class="input_form"
        type="text"
        placeholder="ユーザー名前"
      /><br />
      <p
        v-for="(name, index) in validation.name"
        v-show="validation.name.length"
        :key="index"
        class="validation_error"
      >
        ・{{ name }}
      </p>

      <h3 class="register_items">
        メールアドレス<span class="required">必須</span>
      </h3>
      <input
        v-model="email"
        class="input_form"
        type="email"
        placeholder="メールアドレス"
      /><br />
      <p
        v-for="(email, index) in validation.email"
        v-show="validation.email.length"
        :key="index"
        class="validation_error"
      >
        ・{{ email }}
      </p>

      <h3 class="register_items">
        パスワード(8文字以上)<span class="required">必須</span>
      </h3>
      <input
        v-model="password"
        class="input_form"
        type="password"
        placeholder="パスワード"
      /><br />
      <p
        v-for="(password, index) in validation.password"
        v-show="validation.password.length"
        :key="index"
        class="validation_error"
      >
        ・{{ password }}
      </p>

      <h3 class="register_items">生年月日<span class="alert">※18歳未満の方は登録できません</span></h3>
      <input
        v-model="birthday"
        class="input_form"
        type="date"
        placeholder="誕生日"
      /><br />
      <p
        v-for="(birthday, index) in validation.birthday"
        v-show="validation.birthday.length"
        :key="index"
        class="validation_error"
      >
        ・{{ birthday }}
      </p>

      <h3 class="register_items">
        住んでる都道府県<span class="required">必須</span>
      </h3>
      <select
        v-model="pref_id"
        class="input_form"
        @change="getPrefName(pref_id)"
      >
        <option class="input_form" selected :value="null">
          都道府県を選択
        </option>
        <option
          v-for="(pref, index) in prefs"
          :key="index"
          class="input_form"
          :value="pref.id"
        >
          {{ pref.name }}
        </option></select
      ><br />
      <p
        v-for="(pref_id, index) in validation.pref_id"
        v-show="validation.pref_id.length"
        :key="index"
        class="validation_error"
      >
        ・{{ pref_id }}
      </p>

      <h3 class="register_items">自己紹介</h3>
      <textarea
        v-model="introduction"
        class="input_form"
        type="text"
        placeholder="自己紹介"
      ></textarea>
      <p
        v-for="(introduction, index) in validation.introduction"
        v-show="validation.introduction.length"
        :key="index"
        class="validation_error"
      >
        ・{{ introduction }}
      </p>

      <h3 class="register_items">
        twitterのURL<span class="validation_error"></span>
      </h3>
      <textarea
        v-model="twitter_url"
        class="input_form"
        type="text"
        placeholder="twitterのURL"
      ></textarea>
      <p
        v-for="(twitter, index) in validation.twitter_url"
        v-show="validation.twitter_url.length"
        :key="index"
        class="validation_error"
      >
        ・{{ twitter }}
      </p>

      <h3 class="register_items">プロフィール画像</h3>
      <input class="input_form" type="file" @change="selectedFile" />
      <p
        v-for="(image, index) in validation.image"
        v-show="validation.image.length"
        :key="index"
        class="validation_error"
      >
        ・{{ image }}
      </p>
      <RegisterModal
        :name="name"
        :email="email"
        :birthday="birthday"
        :twitter_url="twitter_url"
        :introduction="introduction"
        :pref_name="pref_name"
        :password="password"
        @close-modal="register"
      />
    </div>
  </div>
</template>

<script>
import RegisterModal from '~/components/Modal/RegisterModal.vue'
export default {
  components: {
    RegisterModal,
  },
  layout: 'guest',
  auth: false,
  data() {
    return {
      name: '',
      email: '',
      password: '',
      birthday: '',
      address: '',
      introduction: '',
      twitter_url: '',
      prefs: '',
      pref_id: '',
      pref_name: '',
      uploadFile: '',
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
        image: [],
      },
    }
  },
  created() {
    this.prefs = this.$PREF
  },
  methods: {
    selectedFile(e) {
      // 選択された File の情報を保存しておく
      const files = e.target.files
      this.uploadFile = files[0]
      console.log(this.uploadFile)
    },

    async register() {
      Object.keys(this.validation).forEach(key => {
        this.$set(this.validation, key, []);
      });

      const formData = new FormData()
      formData.append('name', this.name)
      formData.append('email', this.email)
      formData.append('password', this.password)
      formData.append('birthday', this.birthday)
      formData.append('pref_id', this.pref_id)
      formData.append('address', this.address)
      formData.append('introduction', this.introduction)
      formData.append('twitter_url', this.twitter_url)
      if (typeof this.uploadFile === "undefined") {
        this.uploadFile = ''
      }
      formData.append('image', this.uploadFile)
      const config = {
        headers: {
          'content-type': 'multipart/form-data',
          'X-HTTP-Method-Override': 'PUT',
        },
      }
      await this.$axios.get('sanctum/csrf-cookie')
      await this.$axios
        .post('/api/register', formData, config)
        .then((res) => {
          console.log(res)
          window.alert(res.data.message)
          this.$router.push('/')
        })
        .catch((err) => {
          console.log('エラー発生')
          if (err.response.status === 422) {
            this.validation.errors = err.response.data.errors
            if ('twitter_url' in this.validation.errors) {
              this.validation.twitter_url = this.validation.errors.twitter_url
            }
            if ('name' in this.validation.errors) {
              this.validation.name = this.validation.errors.name
            }
            if ('email' in this.validation.errors) {
              this.validation.email = this.validation.errors.email
            }
            if ('password' in this.validation.errors) {
              this.validation.password = this.validation.errors.password
            }
            if ('pref_id' in this.validation.errors) {
              this.validation.pref_id = this.validation.errors.pref_id
            }
            if ('birthday' in this.validation.errors) {
              this.validation.birthday = this.validation.errors.birthday
            }
            if ('introduction' in this.validation.errors) {
              this.validation.introduction = this.validation.errors.introduction
            }
            if ('image' in this.validation.errors) {
              this.validation.image = this.validation.errors.image
            }
          }
        })
    },
    getPrefName(prefId) {
      if (prefId !== null) {
        console.log(prefId)
        this.pref_name = this.prefs[prefId - 1].name
      } else {
        this.pref_name = ''
      }
      return null
    },
  },
}
</script>

<style>
.register_form {
  margin-top: 20px;
  margin-left: 15%;
  width: 70%;
  padding-left: 5%;
  padding-right: 5%;
  background-color: #e8e6e6;
  padding-bottom: 20px;
}
.register_items {
  text-align: left;
}
.input_form {
  border: 1px solid black;
  background-color: white;
  width: 100%;
  height: 40px;
  margin-bottom: 20px;
  border-radius: 4px;
}

.register_btn {
  margin-bottom: 20px;
}

.required {
  font-size: 12px;
  margin-left: 5px;
  color: white;
  background-color: red;
  padding: 2px;
}

.alert{
  font-size: 12px;
  margin-left: 5px;
  color: red;
}

.validation_error {
  color: red;
  text-align: left;
}

@media (max-width: 400px) {
  .register_form {
    margin-left: 5%;
    width: 90%;
  }
  .register_items {
    font-size:14px;
  }
  .input_form{
    height:30px;
  }
}
</style>
