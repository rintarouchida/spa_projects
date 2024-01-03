<template>
  <div v-show="show">
    <div style="width:50%; float:left;">
      <p class="picture">
        <img
          :src="party.image"
          alt=""
          style="display: block; width: 100%; height: 100%"
        />
      </p>
    </div>
    <div style="width:50%; float:right;">
     <h2 class="theme">{{party.theme}}</h2>
     <span class="tag" v-for="(tag, index) in party.tags" :key="index">{{ tag }}</span>
    <p style="margin-top:15px;">主催者: <router-link :to="`../user/${party.user_id}`">{{ party.user_name }}</router-link>
    <p style="margin-top:15px;">開催場所: {{ party.place }}</p>
    <p style="margin-top:15px;">定員: {{ party.due_max }}名(残り{{party.due_max - party.now_participated_num}}名)</p>
    <p style="margin-top:15px;">締切: {{ party.event_date }}</p>
    </div>
    <div class="clear"></div>
    <p>{{ party.introduction }}</p>
    <CancelPartyModal v-show="party.cancelable" style="text-align: center;" @close-modal="cancel"/>
  </div>
</template>

<script>
import CancelPartyModal from '~/components/Modal/CancelPartyModal.vue'
export default {
  components: {
    CancelPartyModal
  },
   data(){
    return {
      show: false,
      party: '',
    }
  },
  async mounted() {
    setTimeout(() => this.$nuxt.$loading.start(), 500);
    this.party = await this.$axios.get(`api/party/get/${this.$route.params.id}`).then(res => {
      return res.data;
    }).catch((err) => {
      if (err.response.status === 500) {
        this.$router.push('/errors/error_500')
      }
    });
    this.show = true
    this.$nuxt.$loading.finish();
  },
  methods:{
    async cancel(){
      await this.$axios.delete(`api/party/cancel/${this.$route.params.id}`).then((res) => {
        console.log(res);
        window.alert(res.data.message);
        this.$router.push("/");
      }).catch((err) => {
      if (err.response.status === 500) {
        this.$router.push('/errors/error_500')
      }
    });
    }
  }
}
</script>

<style scope>
  .picture{
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
