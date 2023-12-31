<template>
  <div>
    <v-btn color="primary"
          @click="openModal()">参加をキャンセルする</v-btn>
    <div
      class="overlay"
      v-show="modalContent"
    >
      <div class="content">
        <h3 style="color:red; margin-bottom:20px;">こちらのもくもく会の参加をキャンセルします。よろしいですか?
        </h3>
        <v-btn style="margin-right:100px; color:white" color="red" class="btn btn-secondary" @click="cancelAndCloseModal">はい</v-btn>
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
    due_date:
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
    cancelAndCloseModal() {
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
