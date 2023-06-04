<template>
  <div>
    <h1 style="margin-bottom:20px;">おすすめのもくもく会一覧</h1>
    <div v-for="(test, index) in tests" :key="index" class="party_box">
      <div class="picture_box">写真</div>
      <div class="content_box">
        <h1 class="theme">{{test.name}}</h1>
        <p>
          関連タグ: <span v-for="(tag, index) in test.tags" :key="index" class="tag">
            {{tag.name}}
          </span>
        </p>
        <p>開催場所: {{test.place}}</p>
        <p>定員: 残り{{test.due}}</p>
      </div>
      <span class="clear"></span>
    </div>
  </div>
</template>

<script>
export default {
  data() {
    return {
      tests: {
        test1:{
          name: 'test1',
          place: '渋谷',
          due: '3人',
          tags: {
            tag1:{name: 'Ruby'},
            tag2:{name: 'Laravel'},
          },
        },
        test2:{
          name: 'test2',
          place: '渋谷',
          due: '3人',
          tags: {
            tag1:{name: 'Ruby'},
            tag2:{name: 'Laravel'},
          },
        },
        test3:{
          name: 'test3',
          place: '渋谷',
          due: '3人',
          tags: {
            tag1:{name: 'Ruby'},
            tag2:{name: 'Laravel'},
          },
        },
      },
    }
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
