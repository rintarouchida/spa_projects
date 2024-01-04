<template>
  <div>
    <v-btn color="red" style="color:white;"
          @click="openModal()">参加する</v-btn>
    <div
      class="overlay"
      v-show="modalContent"
    >
      <div class="content">
        <h3 style="color:red; margin-bottom:20px;">こちらのもくもく会に参加します。よろしいですか?
        </h3>
        <v-btn style="margin-right:100px; color:white" color="red" class="btn btn-secondary" @click="joinPartyAndCloseModal">はい</v-btn>
        <v-btn color="primary"
          @click="closeModal"
        >いいえ</v-btn>
      </div>
    </div>
  </div>
</template>

<script>
export default {
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
    joinPartyAndCloseModal() {
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
    width:50%;
    padding: 1em;
    background:#fff;
  }
</style>
