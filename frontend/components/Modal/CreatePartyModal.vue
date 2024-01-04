<template>
  <div>
    <v-btn color="primary"
          @click="openModal()">登録する</v-btn>
    <div
      class="overlay"
      v-show="modalContent"
    >
      <div class="content">
        <h3 class="alert" style="color:red; margin-bottom:20px;">以下の内容でもくもく会を登録します。よろしいですか?
        </h3>
        <p v-show="theme === ''">題名: <span style="color:red;">未入力です</span></p>
        <p v-show="theme !== ''">題名: {{theme}}</p>
        <p v-show="introduction === ''">詳細: <span style="color:red;">未入力です</span></p>
        <p v-show="introduction !== ''">詳細: {{introduction}}</p>
        <p v-show="pref_name === ''">開催する都道府県: <span style="color:red;">未選択です</span></p>
        <p v-show="pref_name !== ''">開催する都道府県: {{pref_name}}</p>
        <p v-show="place === ''">開催場所: <span style="color:red;">未入力です</span></p>
        <p v-show="place !== ''">開催場所: {{place}}</p>
        <p v-show="due_max === ''">定員: <span style="color:red;">未選択です</span></p>
        <p v-show="due_max !== ''">定員: {{due_max}}人</p>
        <p v-show="event_date === ''">開催日時: <span style="color:red;">未入力です</span></p>
        <p v-show="event_date !== ''">開催日時: {{event_date}}</p>
        <p v-show="tag_ids !== '[]'">
          タグ: <span v-for="(tag_id, index) in tag_ids" :key="index">・{{tags[tag_id - 1]['name']}}</span>
        </p>
        <div style="text-align:center;">
          <v-btn style="color:white" color="red" class="btn btn-secondary btn-ok" @click="createPartyAndCloseModal">はい</v-btn>
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
    theme:
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
    pref_name:
    {
      type: String,
      default: '',
      required: true
    },
    place:
    {
      type: String,
      default: '',
      required: true
    },
    due_max:
    {
      type: String,
      default: '',
      required: true
    },
    event_date:
    {
      type: String,
      default: '',
      required: true
    },
    tag_ids:
    {
      type: Array,
      default: () => [],
      required: true
    },
  },
  data(){
    return{
      modalContent:false,
      tags: this.$TAG,
    }
  },
  methods:{
    openModal(){
      this.modalContent = true;
    },
    closeModal(){
      this.modalContent = false;
    },
    createPartyAndCloseModal() {
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
      width:80%;
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
    .content{
      width:90%;
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
