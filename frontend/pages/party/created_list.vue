<template>
  <div>
    <h1 v-show="parties.length > 0" style="margin-bottom: 20px">
      作成したもくもく会一覧
    </h1>
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
      <div class="picture_box">写真</div>
      <div class="content_box">
        <h1 class="theme">
          <router-link
            :to="`../party/${party.id}`"
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
      </div>
      <span class="clear"></span>
    </div>
  </div>
</template>

<script>
export default {
  data() {
    return {
      parties: [],
    }
  },
  async created() {
    setTimeout(() => this.$nuxt.$loading.start(), 500)

    this.parties = await this.$axios
      .get('/api/get_created_parties')
      .then((res) => {
        return res.data
      })
    this.$nuxt.$loading.finish()
  },
}
</script>

<style scope>
.party_box {
  border: 2px solid black;
  margin-bottom: 50px;
  padding: 15px;
  height: 200px;
}
.picture_box {
  background-color: #d9d9d9;
  width: 25%;
  height: 100%;
  float: left;
}
.content_box {
  width: 75%;
  height: 100%;
  float: right;
  padding-left: 40px;
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
