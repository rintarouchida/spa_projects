<template>
  <div v-show="show">
    <span v-show="message_lists.length == 0">
      <h1>メッセージはまだありません</h1>
    </span>
    <div v-show="message_lists.length > 0" style="text-align: center">
      <div class="message_form">
        <h2 style="margin-bottom:50px;">メッセージ一覧</h2>
        <div v-for="(message_list, index) in message_lists" :key="index">
          <router-link
            style="color: black; text-decoration: none"
            :to="`message_group/${message_list.id}`"
          >
            <div class="message_box">
              <h4 class="party_theme">{{ message_list.party_theme }}</h4>
              <p class="latest_message">{{ message_list.latest_message }}</p>
              <p class="latest_message_time">{{ message_list.latest_message_time }}</p>
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
      }).catch((err) => {
      if (err.response.status === 500) {
        this.$router.push('/errors/error_500')
      }
    })
    this.show = true
    this.$nuxt.$loading.finish()
  },
}
</script>

<style scope>
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
    box-shadow: 0 3px 5px rgba(0, 0, 0, 0.3);
    text-align: left;
    padding: 5px;
  }
  .message_box:hover {
    background-color: #f5f5f5
  }

  .latest_message_time{
    font-size:10px;
    margin-bottom:0px !important;
  }
  @media (max-width: 600px) {
    .message_form {
      margin-left:5%;
      width:90%;
    }
    .latest_message_time{
      font-size:8px;
    }
  }
  @media (max-width: 400px) {
    .party_theme{
      font-size:12px;
    }
    .latest_message{
      font-size:12px;
    }
    .latest_message_time{
      font-size:8px;
    }
  }
</style>
