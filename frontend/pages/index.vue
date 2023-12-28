<template>
  <div v-show="show">
    <div class="search_box">
      <v-row>
        <v-col cols="12" sm="6" md="6" lg="6" xl="6" class="pref_form">
          <h2>都道府県</h2>
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
          <h2>タグ</h2>
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
          <h2>キーワード</h2>
          <input v-model="keyword" type="text" />
        </v-col>
      </v-row>

      <div style="text-align: center; margin-bottom: 20px">
        <v-btn color="red" style="color: white" @click="searchParty"
          >検索する</v-btn
        >
      </div>
    </div>

    <h1 style="margin-bottom: 20px">もくもく会一覧</h1>

    <div
      v-for="(party, index) in parties"
      v-show="party.due_max > 0"
      :key="index"
      class="party_box"
    >
      <v-row>
        <v-col cols="3" sm="3" md="3" lg="3" xl="3" class="picture_box">
          <img
            :src="party.image"
            alt=""
            style="display: block; width: 100%; height: 100%"
        /></v-col>
        <v-col cols="9" sm="9" md="9" lg="9" xl="9" class="content_box">
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
              {{ tag.name }}
            </span>
          </p>
          <p>開催場所: {{ party.place }}</p>
          <p>定員: 残り{{ party.due_max }}人</p>
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
    setTimeout(() => this.$nuxt.$loading.start(), 500)
    console.log(this.$PREF[0].name)

    this.parties = await this.$axios.get('/api/party/index').then((res) => {
      return res.data
    })

    this.prefs = this.$PREF

    this.tags = this.$TAG
    this.show = true
    this.$nuxt.$loading.finish()
  },
  methods: {
    async searchParty() {
      this.$nuxt.$loading.start()
      this.parties = await this.$axios
        .get('/api/party/search', {
          params: {
            keyword: this.keyword,
            pref_id: this.pref_id,
            tag_id: this.tag_id,
          },
        })
        .then((res) => {
          this.$nuxt.$loading.finish()
          return res.data
        })
        .catch((err) => {
          console.log(err)
          this.$nuxt.$loading.finish()
        })
    },
  },
}
</script>

<style scope>
.search_box {
  border: 2px solid black;
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
  border: 1px solid black;
}

.tag_form {
  padding: 20px;
}
.tag_form > select {
  width: 100%;
  height: 50px;
  font-size: 20px;
  border: 1px solid black;
}

.keyword_form {
  padding: 20px;
}
.keyword_form > input {
  width: 100%;
  height: 40px;
  border: 1px solid black;
}

.party_box {
  border: 2px solid black;
  margin-bottom: 50px;
  padding: 15px;
  min-height: 200px;
}
.picture_box {
  background-color: #d9d9d9;
  height: 200px;
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
</style>
