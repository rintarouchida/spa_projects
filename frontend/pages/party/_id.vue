<template>
  <div>
    <div style="width:50%; float:left;">
      <p class="picture"></p>
    </div>
    <div style="width:50%; float:right;">
     <h2 class="theme">{{party.theme}}</h2>
     <span class="tag" v-for="(tag, index) in party.tags" :key="index">{{ tag }}</span>
    <p style="margin-top:15px;">主催者: {{ party.user_name }}</p>
    <p style="margin-top:15px;">開催場所: {{ party.place }}</p>
    <p style="margin-top:15px;">定員: {{ party.due_max }}名</p>
    <p style="margin-top:15px;">締切: {{ party.due_date }}</p>
    </div>
    <div class="clear"></div>
    <p>{{ party.introduction }}</p>
    <div style="text-align: center;"><v-btn color="red" style="color:white">参加する</v-btn></div>
  </div>
</template>

<script>
export default {
   data(){
    return {
      party: '',
      id: 10,
    }
  },
  async mounted() {
    this.party = await this.$axios.get(`api/party/get/${this.$route.params.id}`).then(res => {
      return res.data;
    });
  },
}
</script>

<style scope>
  .picture{
    background-color: yellow;
    width:100%;
    height:400px;
  }
  .clear{
    clear: both;
  }
  .theme {
    color:#066AFF;
    margin-bottom:10px;
  }
  .tag {
    padding:3px;
    border: 1px solid #0ABCF4;
    width: fit-content;
    margin-right:20px;
  }
</style>
