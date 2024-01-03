<template>
  <div>
    <h2>{{ user.name }}さんのプロフィール</h2>
    <div style="width: 30%; float: left">
      <p class="picture"></p>
    </div>
    <div style="width: 70%; float: right">
      <h2 style="margin-top: 15px">{{ user.name }}さん</h2>
      <p style="margin-top: 15px">{{ user.old }}才</p>
      <p style="margin-top: 15px">{{ user.pref_name }}在住</p>
    </div>
    <div class="clear"></div>
    <p>{{ user.introduction }}</p>
  </div>
</template>

<script>
export default {
  data() {
    return {
      user: '',
    }
  },
  async created() {
    setTimeout(() => this.$nuxt.$loading.start(), 500)

    this.user = await this.$axios
      .get(`api/user/get/${this.$route.params.id}`)
      .then((res) => {
        return res.data
      }).catch((err) => {
      if (err.response.status === 500) {
        this.$router.push('/errors/error_500')
      }
    })

    this.$nuxt.$loading.finish()
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
