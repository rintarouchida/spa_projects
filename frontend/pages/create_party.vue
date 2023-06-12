<template>
  <div style="text-align:center;">
    <div class="register_form">
      <h2>もくもく会作成</h2>

      <h3 class="register_items">題名<span class="required">必須</span></h3>
      <input class="input_form" type="text" placeholder="題名" v-model="theme"><br>
      <p v-show="validation.theme.length" v-for="(theme, index) in validation.theme" :key="index" class="validation_error">・{{theme}}</p>

      <h3 class="register_items">詳細<span class="required">必須</span></h3>
      <textarea class="input_form" type="text" placeholder="詳細" v-model="introduction">
      </textarea>
      <p v-show="validation.introduction.length" v-for="(introduction, index) in validation.introduction" :key="index" class="validation_error">・{{introduction}}</p>

      <h3 class="register_items">開催する都道府県<span class="required">必須</span></h3>
      <select class="input_form" v-model="pref_id">
      <option class="input_form" selected v-bind:value="null">都道府県を選択</option>
      <option class="input_form" v-for="(pref, index) in prefs" :key="index" v-bind:value="pref.id">
        {{ pref.name }}
      </option>
      </select><br>
      <p v-show="validation.pref_id.length" v-for="(pref_id, index) in validation.pref_id" :key="index" class="validation_error">・{{pref_id}}</p>

      <h3 class="register_items">開催場所<span class="required">必須</span></h3>
      <input class="input_form" type="text" placeholder="開催場所" v-model="place"><br>
      <p v-show="validation.place.length" v-for="(place, index) in validation.place" :key="index" class="validation_error">・{{place}}</p>

      <h3 class="register_items">定員<span class="required">必須</span></h3>
      <select class="input_form" v-model="due_max">
      <option class="input_form" selected v-bind:value="null">定員を選択</option>
      <option class="input_form" v-for="(number, index) in numbers" :key="index" v-bind:value="number">
        {{ number }}
      </option>
      </select><br>
      <p v-show="validation.due_max.length" v-for="(due_max, index) in validation.due_max" :key="index" class="validation_error">・{{due_max}}</p>

      <h3 class="register_items">開催日時<span class="required">必須</span></h3>
      <input class="input_form" type="date" placeholder="開催日時" v-model="due_date"><br>
      <p v-show="validation.due_date.length" v-for="(due_date, index) in validation.due_date" :key="index" class="validation_error">・{{due_date}}</p>

      <h3 class="register_items">紐付けるタグ</h3>
      <div v-for="(tag, index) in tags" :key="index" class="register_items">
        <input
          :id="tag.id"
          type="checkbox"
          :value="tag.id"
          v-model="tag_ids"
        >
        <label :for="tag.id">{{tag.name}}</label>
      </div>
      <v-btn color="primary" @click="register">登録する</v-btn>
    </div>
  </div>
</template>

<script>
export default {
  data(){
    return {
    theme: '',
    prefs: '',
    pref_id: '',
    introduction: '',
    tags: '',
    tag_ids: [],
    numbers: [1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19,20],
    due_max: '',
    due_date: '',
    err: null,
      validation: {
        errors: [],
        theme: [],
        introduction: [],
        pref_id: [],
        place: [],
        due_max: [],
        due_date: [],
      },
    }
  },
  async mounted() {
    this.prefs = await this.$axios.get('/api/get_prefs').then(res => {
      return res.data;
    });
    this.tags = await this.$axios.get('/api/get_tags').then(res => {
      return res.data;
    });
  },
  methods:{
    async register(){
      await this.$axios.post('/api/party/register',
        {
          theme: this.theme,
          pref_id: this.pref_id,
          place: this.place,
          due_max: this.due_max,
          tag_ids: this.tag_ids,
          due_date: this.due_date,
          introduction: this.introduction,
        }
      ).then((res) => {
        console.log(res);
        window.alert(res.data.message);
        this.$router.push("/");
      }).catch(err => {
        console.log('エラー発生');
        if (err.response.status === 422) {
          this.validation.errors = err.response.data.errors;
          if ("theme" in this.validation.errors) {
            this.validation.theme = this.validation.errors.theme;
          } else { this.validation.theme = []; }
          if ("introduction" in this.validation.errors) {
            this.validation.introduction = this.validation.errors.introduction;
          } else { this.validation.introduction = []; }
          if ("pref_id" in this.validation.errors) {
            this.validation.pref_id = this.validation.errors.pref_id;
          } else { this.validation.pref_id = []; }
          if ("place" in this.validation.errors) {
            this.validation.place = this.validation.errors.place;
          } else { this.validation.place = []; }
          if ("due_max" in this.validation.errors) {
            this.validation.due_max = this.validation.errors.due_max;
          } else { this.validation.due_max = []; }
          if ("due_date" in this.validation.errors) {
            this.validation.due_date = this.validation.errors.due_date;
          } else { this.validation.due_date = []; }
        }
      })
    }
  },
}
</script>

<style scope>
.register_form{
    margin-top:20px;
    margin-left:15%;
    width:70%;
    padding-left:5%;
    padding-right:5%;
    background-color:#E8E6E6;
    padding-bottom: 20px;
  }
  .register_items{
    text-align:left;
  }
  .input_form{
    border: 1px solid black;
    background-color: white;
    width:100%;
    height:40px;
    margin-bottom:20px;
  }
  .required{
    font-size:12px;
    margin-left:5px;
    color:white;
    background-color:red;
    padding:2px;
  }

  .validation_error{
    color:red;
    text-align:left;
  }
</style>
