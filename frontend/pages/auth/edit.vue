<template>
  <div>
    <h1>編集画面</h1>
    <input type="text" placeholder="ユーザー名前" v-model="user.name">
    <input type="text" placeholder="メールアドレス" v-model="user.email">
    <v-btn @click="editData">編集する</v-btn>
  </div>
</template>

<script>
export default {
  data() {
    return {
       user: this.getData(),
    }
  },
  methods: {
    async getData() {
      this.user = await this.$axios.get('/api/user_info').then(res => {
        return res.data;
      });
    },
    async editData() {
      await this.$axios.post('/api/edit',
        {
          name: this.user.name,
          email: this.user.email,
        }
       ).then(() => {
        window.alert("編集完了しました");
        this.$router.push("/");

      }).catch(err => {
        console.log(err)
      })
    }
  },
}
</script>

<style>

</style>
