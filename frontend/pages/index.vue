<template>
  <div v-show="show">
    <div class="search_box">
      <v-row>
        <v-col cols="12" sm="6" md="6" lg="6" xl="6" class="pref_form">
          <h3>都道府県</h3>
          <select v-model="pref_id">
            <option value="">選択してください。</option>
            <option
              v-for="(pref, index) in prefs"
              :key="index"
              class="input_form"
              :value="pref.id"
            >
              {{ pref.name }}
            </option>
          </select>
        </v-col>
        <v-col cols="12" sm="6" md="6" lg="6" xl="6" class="tag_form">
          <h3>タグ</h3>
          <select v-model="tag_id">
            <option value="">選択してください。</option>
            <option
              v-for="(tag, index) in tags"
              :key="index"
              class="input_form"
              :value="tag.id"
            >
              {{ tag.name }}
            </option>
          </select>
        </v-col>
        <v-col cols="12" class="keyword_form">
          <h3>キーワード</h3>
          <input v-model="keyword" type="text" />
        </v-col>
      </v-row>

      <div style="text-align: center; margin: 10px 0px">
        <v-btn class="search_btn" @click="searchParty"
          >検索する</v-btn
        >
      </div>
    </div>

    <h2 style="margin-bottom: 20px">もくもく会一覧</h2>
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
              :to="`party/${party.id}`"
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
      parties: '',
      prefs: '',
      tags: '',
      pref_id: '',
      tag_id: '',
      keyword: null,
    }
  },
  async created() {

    this.parties = await this.$axios.get('/api/party/index').then((res) => {
      return res.data
    }).catch((err) => {
      if (err.response.status === 500) {
        this.$router.push('/errors/error_500')
      }
    })

    this.prefs = this.$PREF

    this.tags = this.$TAG
    this.show = true
    this.$nuxt.$loading.finish()
  },
  methods: {
    async searchParty() {
      this.parties = await this.$axios
        .get('/api/party/search', {
          params: {
            keyword: this.keyword,
            pref_id: this.pref_id,
            tag_id: this.tag_id,
          },
        })
        .then((res) => {
          return res.data
        })
        .catch((err) => {
          if (err.response.status === 500) {
            this.$router.push('/errors/error_500')
          }
        })
    },
  },
}
</script>

<style scope>
.search_box {
  box-shadow: 0 3px 5px rgba(0, 0, 0, 0.5);
  padding:5px;
  margin-bottom: 50px;
  height: auto;
}

.pref_form {
  padding: 20px;
}
.pref_form > select {
  width: 100%;
  height: 50px;
  font-size: 20px;
  border-radius: 4px;
  border: 1px solid black;
}

.tag_form {
  padding: 20px;
}
.tag_form > select {
  width: 100%;
  height: 50px;
  font-size: 20px;
  border-radius: 4px;
  border: 1px solid black;
}

.keyword_form {
  padding: 20px;
}
.keyword_form > input {
  width: 100%;
  height: 40px;
  border-radius: 4px;
  border: 1px solid black;
}

.search_btn {
  background-color: red !important;
  color: white !important;
}

.party_box {
  box-shadow: 0 2px 4px rgba(0, 0, 0, 0.5);
  margin-bottom: 50px;
  padding: 15px;
  min-height: 200px;
}
.party_img{
  display: block;
  width: 180px;
  height: 180px;
  margin:10px;
}
.content_box {
  height: 100%;
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

@media (max-width: 600px) {
  .pref_form, .tag_form, .keyword_form {
    padding-bottom:10px;
  }
  .pref_form > select {
    height: 30px;
    font-size: 12px;
  }
  .tag_form > select {
    height: 30px;
    font-size: 12px;
  }
  .keyword_form > input {
    height: 30px;
    font-size: 12px;
  }
  .search_btn {
    font-size: 12px !important;
  }
}
@media (max-width: 400px) {
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
