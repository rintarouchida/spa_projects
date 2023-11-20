<template>
  <div>
    <h1 style="margin-bottom:20px;">参加済みのもくもく会一覧</h1>

    <div v-for="(party, index) in parties" :key="index" class="party_box" v-show="party.due_max > 0">
      <div class="picture_box">写真</div>
      <div class="content_box">
        <h1 class="theme"><router-link :to="`../party/${party.id}`" style="text-decoration: none;">{{party.theme}}</router-link></h1>
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
    }
  },
  async created() {
    setTimeout(() => this.$nuxt.$loading.start(), 500);

    this.parties = await this.$axios.get('/api/get_participated_parties').then(res => {
      return res.data;
    });
    this.$nuxt.$loading.finish();
  },
}
</script>

<style scope>
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

