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
    <div style="width: 70%; float: right">
      <h2 style="margin-top: 15px">{{ user.name }}さん</h2>
      <p style="margin-top: 15px">{{ user.old }}才</p>
      <p style="margin-top: 15px">{{ user.pref_name }}在住</p>
    </div>
    <div class="clear"></div>
    <p>{{ user.introduction }}</p>
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
</style>
