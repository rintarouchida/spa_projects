<template>
  <div>
<!--  todo:アイコン入れられるようにしたら再度デザイン調整 -->
<!-- todo:メッセージ送信部分のデザイン -->
  <div style="text-align:center; ">
    <div class="message_form">
      <div class="party_theme">
        <h2>タイトル: {{party_theme}}</h2>
      </div>

        <div v-for="(message, index) in messages" :key="index" class="message_list">
          <span v-if="message.is_users_message" >
            <div class="message_box">
              <p class="message">{{message.content}}<br><span style="font-size:10px; float:right;">{{message.created_at}}</span></p>
            </div>
          </span>
          <span v-if="!message.is_users_message && (message.created_at !== null)">
            <div class="icon">
              <img
                :src="message.user_image"
                alt=""
                style="display: block; width: 100%; height: 100%"
              />
            </div>
            <div class="others_message_box">
              <p class="message">{{message.user_name}}:{{message.content}}<br><span style="font-size:10px;">{{message.created_at}}</span></p>
            </div>
          </span>
          <p style="clear:both;"></p>
        </div>
        <input type="text" v-model="content">
        <v-btn color="red" style="color:white" @click="sendMessage">メッセージ送信</v-btn>
        <p v-show="validation.content" v-for="(content, index) in validation.content" :key="index" class="validation_error">・{{content}}</p>
      </div>
    </div>
  </div>
</div>
  </div>
</template>

<script>
export default {
  data(){
    return {
      party: '',
      id: this.$route.params.id,
      data: [],
      messages: [],
      party_theme: '',
      content: '',
      validation: {
        errors: [],
        content: [],
      },
    }
  },
  async created() {
    setTimeout(() => this.$nuxt.$loading.start(), 500);
    this.data = await this.$axios.get(`api/message/get/${this.$route.params.id}`).then(res => {
      return res.data;
    }).catch((err) => {
      if (err.response.status === 500) {
        this.$router.push('/errors/error_500')
      }
    });

    this.party_theme = this.data.theme;
    this.messages = this.data.messages;
    setInterval(() => {
      this.getMessages();
      console.log('メッセージを更新')
    },10000);
    this.$nuxt.$loading.finish();
  },
  methods:{
    async getMessages(){
      this.messages = await this.$axios.get(`api/message/get/${this.$route.params.id}`).then(res => {
        return res.data.messages;
      }).catch((err) => {
      if (err.response.status === 500) {
        this.$router.push('/errors/error_500')
      }
      });
    },
    async sendMessage(){
      this.validation.content = [];
      await this.$axios.post('/api/message/send_message',{
          content: this.content,
          message_group_id: this.$route.params.id,
        },
      ).then((res) => {
        this.getMessages();
        this.content=null;
      }).catch(err => {
        console.log('エラー発生');
        if (err.response.status === 422) {
          this.validation.errors = err.response.data.errors;
          if ("content" in this.validation.errors) {
            this.validation.content = this.validation.errors.content;
          }
        }
        if (err.response.status === 500) {
          this.$router.push('/errors/error_500')
        }
      });
    },
  },
}
</script>

<style scoped>
.party_theme{
  background-color: #EEEDED;
}
.message_form{
    margin-top:20px;
    margin-left:15%;
    width:70%;
    background-color:#F5F5F5;
    border:1px solid black;
    padding-bottom: 20px;
  }
.message_list{
  padding-left:5%;
  padding-right:5%;
}
.icon{
  border-radius:50%;
  height:40px;
  width:40px;
}
.others_message_box{
    padding: 0.5em 1em;
    float:left;
    max-width:50%;
    height:auto;
    background-color:#03F9AF;
    border-radius: 9px;
}
.message{
  text-align:left;
}

.message_box{
    padding: 0.5em 1em;
    float:right;
    max-width:50%;
    height:auto;
    background-color:#EEA332;
    border-radius: 9px;
}

.validation_error{
    color:red;
    text-align:left;
  }

</style>

