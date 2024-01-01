<template>
  <div>
    <v-btn color="primary"
          @click="openModal()">更新する</v-btn>
    <div
      class="overlay"
      v-show="modalContent"
    >
      <div class="content">
        <h3 style="color:red; margin-bottom:20px;">以下の内容でログインユーザー情報を更新します。よろしいですか?
        </h3>
        <span style="text-align: left;">
          <p v-show="name === ''">名前: <span style="color:red;">未入力です</span></p>
          <p v-show="name !== ''">名前: {{name}}</p>
          <p v-show="email === ''">メールアドレス: <span style="color:red;">未入力です</span></p>
          <p v-show="email !== ''">メールアドレス: {{email}}</p>
          <p v-show="birthday !== ''">生年月日: {{birthday}}</p>
          <p v-show="pref_name === ''">都道府県名: <span style="color:red;">未選択です</span></p>
          <p v-show="pref_name !== ''">都道府県名: {{pref_name}}</p>
          <p v-show="introduction !== ''">自己紹介: {{introduction}}</p>
          <p v-show="twitter_url !== ''">TwiiterのURL: {{twitter_url}}</p>
        </span>
        <v-btn style="margin-right:100px; color:white" color="red" class="btn btn-secondary" @click="updateUserAndCloseModal">はい</v-btn>
        <v-btn color="primary"
          @click="closeModal"
        >いいえ</v-btn>
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

<style>
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
    width:50%;
    padding: 1em;
    background:#fff;
  }
</style>
