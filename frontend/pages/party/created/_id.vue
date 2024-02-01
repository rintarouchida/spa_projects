<template>
  <div v-show="show">
    <v-row>
      <v-col cols="12" sm="12" md="3" lg="3" xl="3">
        <img
          :src="party.image"
          alt=""
          class="picture"
        />
      </v-col>
      <v-col cols="12" sm="12" md="9" lg="9" xl="9">
        <h1 class="theme">{{party.theme}}</h1>
        <p style="margin-top: 10px"><span style="font-weight:bold;">タグ  </span> <span class="tag" v-for="(tag, index) in party.tags" :key="index">{{ tag }}</span><p>
        <p style="margin-top:10px;"><span style="font-weight:bold;">主催者</span> <router-link :to="`../../user/${party.user_id}`">{{ party.user_name }}</router-link>
        <p style="margin-top:10px;"><span style="font-weight:bold;">開催場所</span> {{ party.place }}</p>
        <p style="margin-top:10px;"><span style="font-weight:bold;">定員</span> {{ party.due_max }}名(残り{{party.due_max - party.now_participated_num}}名)</p>
        <p style="margin-top:10px;"><span style="font-weight:bold;">開催日</span> {{ party.event_date }}</p>
      </v-col>
    </v-row>
    <p>{{ party.introduction }}</p>
    <v-row class="justify-center">
      <v-btn v-show="editable" color="primary" @click="edit">編集する</v-btn>
    </v-row>
  </div>
</template>

<script>
export default {
   data(){
    return {
      show: false,
      party: '',
      id: 10,
      editble: false,
    }
  },
  async mounted() {
    //setTimeout(() => this.$nuxt.$loading.start(), 500);
    this.party = await this.$axios.get(`api/party/get/${this.$route.params.id}`).then(res => {
      return res.data;
    }).catch((err) => {
      if (err.response.status === 500) {
        this.$router.push('/errors/error_500')
      }
    });
    this.editable = await this.$axios.get(`api/party/check_if_editable/${this.$route.params.id}`).then(res => {
      return res.data.result;
    });
    this.show = true
    //this.$nuxt.$loading.finish();
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
      }).catch((err) => {
      if (err.response.status === 500) {
        this.$router.push('/errors/error_500')
      }
    });
    },
    edit() {
      this.$router.push(`../edit/${this.party.id}`)
    },
  }
}
</script>

<style scope>
  .picture {
    display: block;
    width: 200px;
    height: 200px;
    margin-top:15px;
  }

  .theme {
    color:#066AFF;
    margin-bottom:15px;
  }
  .tag {
    padding:3px;
    border: 1px solid #0ABCF4;
    width: fit-content;
    margin-right:20px;
  }
  @media (max-width: 600px) {
    .picture{
      height: 150px;
      width: 150px;
    }
  }
</style>
