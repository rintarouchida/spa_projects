<template>
  <div class="head">
    <router-link to="/" style="text-decoration: none; color:black;">もくもくMAP</router-link>
    <router-link to="/create_party" style="text-decoration: none; color:black;">もくもく会を作成する</router-link>
    <v-btn type="primary" @click="logout" style="float:right;">ログアウトする</v-btn>
  </div>
</template>

<script>
export default {
   methods: {
    async logout() {
      await this.$auth.logout().
        then(() => {
          this.$router.push("/auth/login");
        }).
        catch((e)=> console.log(e))
    },
  },
}
</script>

<style>
  .head{
    height:60px;
    border-bottom:2px solid #887878;
    height:60px;
    line-height:60px;
  }
</style>
