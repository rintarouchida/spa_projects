<template>
  <div style="text-align: center">
    <div class="register_form">
      <h2>ログインユーザー情報編集</h2>
      <h3 class="register_items">
        ユーザー名<span class="required">必須</span>
      </h3>
      <input
        v-model="old_data.name"
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
        v-model="old_data.email"
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

      <h3 class="register_items">生年月日</h3>
      <input
        v-model="old_data.birthday"
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
        v-model="old_data.pref_id"
        class="input_form"
        @change="getPrefName(old_data.pref_id)"
      >
        <option class="input_form" :value="null">都道府県を選択</option>
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
        v-model="old_data.introduction"
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
        v-model="old_data.twitter_url"
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
      <UpdateUserModal
        :name="old_data.name"
        :email="old_data.email"
        :birthday="old_data.birthday"
        :twitter_url="old_data.twitter_url"
        :introduction="old_data.introduction"
        :pref_name="pref_name"
        @close-modal="update"
      />
    </div>
  </div>
</template>

<script>
import UpdateUserModal from '~/components/Modal/UpdateUserModal.vue'
export default {
  components: {
    UpdateUserModal,
  },
  layout: 'default',
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
      uploadFile: null,
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
      old_data: [],
    }
  },
  async created() {
    setTimeout(() => this.$nuxt.$loading.start(), 500)
    this.prefs = this.$PREF
    this.old_data = await this.$axios.get(`api/get_auth`).then((res) => {
      return res.data
    })
    this.getPrefName(this.old_data.pref_id)
    this.$nuxt.$loading.finish()
  },
  methods: {
    selectedFile(e) {
      // 選択された File の情報を保存しておく
      const files = e.target.files
      this.uploadFile = files[0]
      console.log(this.uploadFile)
    },
    async update() {
      const formData = new FormData()
      formData.append('name', this.old_data.name)
      formData.append('email', this.old_data.email)
      formData.append('birthday', this.old_data.birthday)
      formData.append('pref_id', this.old_data.pref_id)
      formData.append('address', this.old_data.address)
      formData.append('introduction', this.old_data.introduction)
      formData.append(
        'twitter_url',
        this.old_data.twitter_url !== null ? this.old_data.twitter_url : ''
      )
      formData.append('image', this.uploadFile)
      const config = {
        headers: {
          'content-type': 'multipart/form-data',
          'X-HTTP-Method-Override': 'PUT',
        },
      }
      await this.$axios
        .post(`/api/update_auth/${this.old_data.id}`, formData, config)
        .then((res) => {
          console.log(res)
          window.alert(res.data.message)
          this.$router.push('/')
        })
        .catch((err) => {
          console.log('エラー発生')
          console.log(err.response.status)
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

.validation_error {
  color: red;
  text-align: left;
}
</style>
