<template>
  <div>
    <v-btn color="primary"
          @click="openModal()">登録する</v-btn>
    <div
      class="overlay"
      v-show="modalContent"
    >
      <div class="content">
        <h3 style="color:red; margin-bottom:20px;">以下の内容でもくもく会を登録します。よろしいですか?
        </h3>
        <span style="text-align: left;">
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
        </span>
        <v-btn style="margin-right:100px; color:white" color="red" class="btn btn-secondary" @click="createPartyAndCloseModal">はい</v-btn>
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
    width:50%;
    padding: 1em;
    background:#fff;
  }
</style>
