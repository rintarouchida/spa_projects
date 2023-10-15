<template>
<div>
  メッセージ一覧
  <div style="text-align:center;">
    <div class="message_form">
      <h2>メッセージ一覧</h2>
      <div v-for="(message_list, index) in message_lists" :key="index">
        <router-link style="color:black; text-decoration:none;" :to="`message_group/${message_list.id}`">
        <div class="message_box">
          <h4>タイトル: {{message_list.party_theme}}</h4>
          <p>{{message_list.latest_message}}</p>
          <p>{{message_list.latest_message_time}}</p>
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
      message_lists: [],
    }
  },
  async created() {
    this.message_lists = await this.$axios.get('/api/message/index').then(res => {
      return res.data;
    });
  }
}
</script>

<style scope>
.message_form{
    margin-top:20px;
    margin-left:15%;
    width:70%;
    padding-left:5%;
    padding-right:5%;
    background-color:#E8E6E6;
    padding-bottom: 20px;
  }
  .message_box{
    background-color: white;
    border: 1px solid black;
    text-align:left;
    padding:5px;
  }
</style>
