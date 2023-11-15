<template>
  <div>
    <div style="width:50%; float:left;">
      <p class="picture"></p>
    </div>
    <div style="width:50%; float:right;">
     <h2 class="theme">{{party.theme}}</h2>
     <span class="tag" v-for="(tag, index) in party.tags" :key="index">{{ tag }}</span>
    <p style="margin-top:15px;">主催者: <router-link :to="`../user/${party.user_id}`">{{ party.user_name }}</router-link>
    <p style="margin-top:15px;">開催場所: {{ party.place }}</p>
    <p style="margin-top:15px;">定員: {{ party.due_max }}名</p>
    <p style="margin-top:15px;">締切: {{ party.due_date }}</p>
    </div>
    <div class="clear"></div>
    <p>{{ party.introduction }}</p>
    <JoinPartyModal v-show="joinable" style="text-align: center;" @close-modal="join"/>
  </div>
</template>

<script>
import JoinPartyModal from '~/components/Modal/JoinPartyModal.vue';
export default {
  components: {
    JoinPartyModal
  },
   data(){
    return {
      party: '',
      id: 10,
      joinable: false,
    }
  },
  async mounted() {
    setTimeout(() => this.$nuxt.$loading.start(), 500);
    this.party = await this.$axios.get(`api/party/get/${this.$route.params.id}`).then(res => {
      return res.data;
    });
    this.joinable = await this.$axios.get(`api/party/check_if_joined/${this.$route.params.id}`).then(res => {
      return !res.data.result;
    });
    this.$nuxt.$loading.finish();
  },
  methods:{
    async join(){
      await this.$axios.post('/api/party/join',
        {
          party_id: this.$route.params.id,
        }
      ).then((res) => {
        console.log(res);
        window.alert(res.data.message);
        this.$router.push("/");
      });
    }
  }
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
