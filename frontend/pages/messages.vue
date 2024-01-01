<template>
  <div v-show="show">
    <span v-show="message_lists.length == 0">
      <h1>メッセージはまだありません</h1>
    </span>
    <div v-show="message_lists.length > 0" style="text-align: center">
      <div class="message_form">
        <h2>メッセージ一覧</h2>
        <div v-for="(message_list, index) in message_lists" :key="index">
          <router-link
            style="color: black; text-decoration: none"
            :to="`message_group/${message_list.id}`"
          >
            <div class="message_box">
              <h4>タイトル: {{ message_list.party_theme }}</h4>
              <p>{{ message_list.latest_message }}</p>
              <p>{{ message_list.latest_message_time }}</p>
            </div>
          </router-link>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  data() {
    return {
      show: false,
      message_lists: [],
    }
  },
  async created() {
    setTimeout(() => this.$nuxt.$loading.start(), 500)
    this.message_lists = await this.$axios
      .get('/api/message/index')
      .then((res) => {
        return res.data
      })
    this.show = true
    this.$nuxt.$loading.finish()
  },
}
</script>

<style>
.message_form {
  margin-top: 20px;
  margin-left: 15%;
  width: 70%;
  padding-left: 5%;
  padding-right: 5%;
  background-color: #e8e6e6;
  padding-bottom: 20px;
  height:100vh;
}
.message_box {
  background-color: white;
  border: 1px solid black;
  text-align: left;
  padding: 5px;
}
</style>
