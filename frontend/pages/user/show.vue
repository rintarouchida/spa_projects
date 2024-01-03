<template>
  <div v-show="show">
    <h2>ログインユーザーのプロフィール</h2>
    <div style="width: 30%; float: left">
      <p class="picture">
        <img
          :src="user.image"
          alt=""
          style="display: block; width: 100%; height: 100%"
        />
      </p>
    </div>
    <div style="width: 70%; padding-left:10%; float: right">
      <h2 style="margin-top: 15px">{{ user.name }}さん</h2>
      <p style="margin-top: 15px">(年齢){{ user.old }}才</p>
      <p style="margin-top: 15px">(在住){{ user.pref_name }}</p>
      <p>(TwitterのURL)<a :href="user.twitter_url">{{ user.twitter_url }}</a></p>
    </div>
    <div class="clear"></div>
    <p style="white-space: pre-wrap;">(自己紹介)<br>{{ user.introduction }}</p>
    <v-btn type="primary" style="float: right" @click="edit">編集する</v-btn>
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
  background-color: yellow;
  width: 100%;
  height: 200px;
}
.clear {
  clear: both;
}

@media (max-width: 600px) {
  .picture{
    height: 200px;
    height: 200px;
  }
}
</style>
