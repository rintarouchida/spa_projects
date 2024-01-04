<template>
  <div>
    <v-btn color="primary"
          @click="openModal()">参加をキャンセルする</v-btn>
    <div
      class="overlay"
      v-show="modalContent"
    >
      <div class="content">
        <h3 class="alert" style="color:red; margin-bottom:20px;">こちらのもくもく会の参加をキャンセルします。よろしいですか?
        </h3>
        <div style="text-align:center;">
          <v-btn style="color:white" color="red" class="btn btn-secondary btn-ok" @click="cancelPartyAndCloseModal">はい</v-btn>
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
    cancelPartyAndCloseModal() {
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
    .btn-ok{
    margin-right:15px;
    }
    .btn-back{
      margin-left:15px;
    }
  }
</style>
