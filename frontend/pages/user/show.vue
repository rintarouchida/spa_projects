<template>
  <div v-show="show">
    <h2>ログインユーザーのプロフィール</h2>
    <v-row>
      <v-col cols="12" sm="12" md="3" lg="3" xl="3">
        <img
          :src="user.image"
          alt=""
          class="picture"
        />
      </v-col>
      <v-col cols="12" sm="12" md="9" lg="9" xl="9">
        <h1 style="margin-top: 15px">{{ user.name }}</h1>
        <p style="margin-top: 10px"><span style="font-weight:bold;">年齢</span>  {{ user.old }}才</p>
        <p style="margin-top: 10px"><span style="font-weight:bold;">在住</span>  {{ user.pref_name }}</p>
        <p style="margin-top: 10px"><span style="font-weight:bold;">TwitterのURL</span>  <a :href="user.twitter_url">{{ user.twitter_url }}</a></p>
        <p style="margin-top: 10px"><span style="font-weight:bold;">自己紹介</span> <br>{{ user.introduction }}</p>
      </v-col>
    </v-row>
    <v-row class="justify-center">
      <v-btn color="primary" @click="edit">編集する</v-btn>
    </v-row>
  </div>
</template>

<script>
export default {
  data() {
    return {
      show: false,
      user: '',
    }
  },
  async created() {
    setTimeout(() => this.$nuxt.$loading.start(), 500)

    this.user = await this.$axios.get(`api/get_auth`).then((res) => {
      return res.data
    }).catch((err) => {
      if (err.response.status === 500) {
        this.$router.push('/errors/error_500')
      }
    })
    this.show = true
    this.$nuxt.$loading.finish()
  },
  methods: {
    edit() {
      this.$router.push('/user/edit')
    },
  },
}
</script>

<style>
.picture {
  display: block;
  width: 200px;
  height: 200px;
  margin-top:15px;
}

@media (max-width: 600px) {
  .picture{
    height: 150px;
    width: 150px;
  }
}
</style>
