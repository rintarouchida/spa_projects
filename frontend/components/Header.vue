<template>
  <div class="head">
    <router-link to="/" style="text-decoration: none; color:black;">もくもくMAP</router-link>
    <router-link to="/create_party" style="text-decoration: none; color:black;">もくもく会を作成する</router-link>
    <router-link to="/messages" style="text-decoration: none; color:black;">メッセージ</router-link>
    <router-link to="/messages_for_leader" style="text-decoration: none; color:black;">メッセージ(主催者用)</router-link>
    <router-link to="/party/created_list" style="text-decoration: none; color:black;">作成したもくもく会一覧</router-link>
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
