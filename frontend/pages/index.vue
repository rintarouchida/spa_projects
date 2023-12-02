<template>
  <div>
    <div class="search_box">
        <div class="pref_form">
          <h2>都道府県</h2>
          <select v-model="pref_id">
            <option value="">選択してください。</option>
            <option class="input_form" v-for="(pref, index) in prefs" :key="index" v-bind:value="pref.id">
            {{ pref.name }}
            </option>
          </select>
        </div>

        <div class="tag_form">
          <h2>タグ</h2>
          <select v-model="tag_id">
            <option value="">選択してください。</option>
            <option class="input_form" v-for="(tag, index) in tags" :key="index" v-bind:value="tag.id">
            {{ tag.name }}
            </option>
          </select>
        </div>

      <div class="keyword_form">
        <h2>キーワード</h2>
        <input type="text" v-model="keyword">
      </div>
      <div style="text-align: center; margin-bottom:20px;">
      <v-btn color="red" style="color:white;" @click="searchParty">検索する</v-btn>
      </div>
    </div>

    <h1 style="margin-bottom:20px;">もくもく会一覧</h1>

    <div v-for="(party, index) in parties" :key="index" class="party_box" v-show="party.due_max > 0">
      <div class="picture_box">写真</div>
      <div class="content_box">
        <h1 class="theme"><router-link :to="`party/${party.id}`" style="text-decoration: none;">{{party.theme}}</router-link></h1>
        <p>
          関連タグ: <span v-for="(tag, index) in party.tags" :key="index" class="tag">
            {{tag.name}}
          </span>
        </p>
        <p>開催場所: {{party.place}}</p>
        <p>定員: 残り{{party.due_max}}人</p>
      </div>
      <span class="clear"></span>
    </div>
  </div>
</template>

<script>
export default {
  data() {
    return {
      parties: '',
      prefs: '',
      tags: '',
      pref_id: '',
      tag_id: '',
      keyword: null,
    }
  },
  async created() {
    setTimeout(() => this.$nuxt.$loading.start(), 500);

    this.parties = await this.$axios.get('/api/get_parties').then(res => {
      return res.data;
    });

    this.prefs = await this.$axios.get('/api/get_prefs').then(res => {
      return res.data;
    });
    this.tags = await this.$axios.get('/api/get_tags').then(res => {
      return res.data;
    });
    this.$nuxt.$loading.finish();
  },
  methods:{
    async searchParty(){
      this.$nuxt.$loading.start();
      this.parties = await this.$axios.get('/api/search',{
          params: {
            keyword: this.keyword,
            pref_id: this.pref_id,
            tag_id: this.tag_id,
          }
      }).then((res) => {
        this.$nuxt.$loading.finish();
        return res.data;
      }).catch(err => {
        console.log(err);
        this.$nuxt.$loading.finish();
      });
    },
  }
}
</script>

<style scope>
  .search_box{
    border: 2px solid black;
    margin-bottom:50px;
    height:auto;
  }

  .pref_form{
    width:50%;
    padding:20px;
    float:left;
  }
  .pref_form>select{
    width:80%;
    height:50px;
    font-size:20px;
    border:1px solid black;
  }

  .tag_form{
    width:50%;
    padding:20px;
    float:right;
  }
  .tag_form>select{
    width:80%;
    height:50px;
    font-size:20px;
    border:1px solid black;
  }

  .keyword_form{
    padding:20px;
  }
  .keyword_form>input{
    width:100%;
    height:40px;
    border:1px solid black;
  }

  .party_box{
    border: 2px solid black;
    margin-bottom:50px;
    padding: 15px;
    height:200px;
  }
  .picture_box{
    background-color: #D9D9D9;
    width:25%;
    height:100%;
    float:left;
  }
  .content_box{
    width:75%;
    height:100%;
    float:right;
    padding-left:40px;
  }
  .theme {
    color:#066AFF;
  }
  .tag {
    padding:3px;
    border: 1px solid #0ABCF4;
    width: fit-content;
    margin-right:20px;
  }
  .clear{
    clear:both;
  }
</style>
