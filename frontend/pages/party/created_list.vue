<template>
  <div v-show="show">
    <h2 v-show="parties.length > 0" style="margin-bottom: 20px">
      作成したもくもく会一覧
    </h2>
      <p class="alert" style="color:red;">※開催日が過ぎたもくもく会は表示されません</p>
    <span v-show="parties.length == 0">
      <h1>作成したもくもく会はまだありません</h1>
      <router-link to="../create_party">もくもく会作成はこちらから</router-link>
    </span>
    <div
      v-for="(party, index) in parties"
      v-show="party.due_max > 0"
      :key="index"
      class="party_box"
    >
      <v-row>
        <v-col cols="12" sm="4" md="4" lg="4" xl="4">
          <img class="party_img"
            :src="party.image"
            alt=""
        /></v-col>
        <v-col cols="12" sm="8" md="8" lg="8" xl="8" class="content_box">
          <h1 class="theme">
            <router-link
              :to="`../party/created/${party.id}`"
              style="text-decoration: none"
              >{{ party.theme }}</router-link
            >
          </h1>
          <p>
            関連タグ:
            <span v-for="(tag, index) in party.tags" :key="index" class="tag">
              {{ tag }}
            </span>
          </p>
          <p>開催場所: {{ party.place }}</p>
          <p>定員: 残り{{ party.due_max }}人</p>
          <p>開催日: {{ party.event_date }}</p>
        </v-col>
      </v-row>
    </div>
  </div>
</template>

<script>
export default {
  data() {
    return {
      show: false,
      parties: [],
    }
  },
  async created() {
    setTimeout(() => this.$nuxt.$loading.start(), 500)

    this.parties = await this.$axios
      .get('/api/party/index_created')
      .then((res) => {
        return res.data
      }).catch((err) => {
      if (err.response.status === 500) {
        this.$router.push('/errors/error_500')
      }
    })
    this.show = true
    this.$nuxt.$loading.finish()
  },
}
</script>

<style scope>

.party_box {
  box-shadow: 0 2px 4px rgba(0, 0, 0, 0.5);
  margin-bottom: 50px;
  padding: 15px;
  min-height: 200px;
}

.party_img {
  display: block;
  width: 180px;
  height: 180px;
  margin:10px;
}

.content_box {
  width: 75%;
  height: 100%;
  float: right;
}

.theme {
  color: #066aff;
}
.tag {
  padding: 3px;
  border: 1px solid #0abcf4;
  width: fit-content;
  margin-right: 20px;
}

.clear {
  clear: both;
}

@media (max-width: 400px) {
  .alert {
    font-size:12px;
  }
  .theme{
    font-size:22px;
  }
  .party_img{
    width: 120px;
    height: 120px;
    margin:30px 30px 30px 0px;
  }
  .content_box>p{
    font-size:12px;
  }
  .tag{
    font-size:8px;
    margin-right: 5px;
  }
}
</style>
