<template>
  <div>
    <v-btn color="primary"
          @click="openModal()">更新する</v-btn>
    <div
      class="overlay"
      v-show="modalContent"
    >
      <div class="content">
        <h3 class="alert" style="color:red; margin-bottom:20px;">以下の内容でログインユーザー情報を更新します。よろしいですか?
        </h3>
        <p v-show="name === ''">名前: <span style="color:red;">未入力です</span></p>
        <p v-show="name !== ''">名前: {{name}}</p>
        <p v-show="email === ''">メールアドレス: <span style="color:red;">未入力です</span></p>
        <p v-show="email !== ''">メールアドレス: {{email}}</p>
        <p v-show="birthday !== ''">生年月日: {{birthday}}</p>
        <p v-show="pref_name === ''">都道府県名: <span style="color:red;">未選択です</span></p>
        <p v-show="pref_name !== ''">都道府県名: {{pref_name}}</p>
        <p v-show="introduction !== ''">自己紹介: {{introduction}}</p>
        <p v-show="twitter_url !== ''">TwiiterのURL: {{twitter_url}}</p>
        <div style="text-align:center;">
          <v-btn style="color:white" color="red" class="btn btn-secondary btn-ok" @click="updateUserAndCloseModal">はい</v-btn>
          <v-btn color="primary" class="btn btn-back"
            @click="closeModal"
          >いいえ</v-btn>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  props: {
    name:
    {
      type: String,
      default: '',
      required: true
    },
    email:
    {
      type: String,
      default: '',
      required: true
    },
    birthday:
    {
      type: String,
      default: '',
      required: true
    },
    pref_name:
    {
      type: String,
      default: '',
      required: true
    },
    introduction:
    {
      type: String,
      default: '',
      required: true
    },
    twitter_url:
    {
      type: String,
      default: '',
      required: true
    },
  },
  data(){
    return{
      modalContent:false,
    }
  },
  methods:{
    openModal(){
      this.modalContent = true;
    },
    closeModal(){
      this.modalContent = false;
    },
    updateUserAndCloseModal() {
      this.modalContent = false;
      this.$emit('close-modal')
    }
  }
}
</script>

<style scope>
  li{
    list-style:none;
  }
  .overlay{
    z-index:1;
    position:fixed;
    top:0;
    left:0;
    width:100%;
    height:100%;
    background-color:rgba(0,0,0,0.5);
    display: flex;
    align-items: center;
    justify-content: center;
  }
  .content{
    z-index:2;
    width:70%;
    padding: 1em;
    background:#fff;
    text-align:left;
  }
  .btn-ok{
    margin-right:40px;
  }
  .btn-back{
    margin-left:40px;
  }

  @media (max-width: 600px) {
    .alert {
      font-size:18px;
    }
    .content{
      width:90%;
    }
    .content>p{
      font-size:14px;
    }
    .btn-ok{
    margin-right:25px;
    }
    .btn-back{
      margin-left:25px;
    }
  }
  @media (max-width: 400px) {
    .alert {
      font-size:14px;
    }
    .btn{
      font-size:10px !important;
    }
    .content>p{
      font-size:12px;
    }
    .btn_span{
      width:25px;
    }
    .btn-ok{
    margin-right:15px;
    }
    .btn-back{
      margin-left:15px;
    }
  }
</style>
