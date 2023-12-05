<template>
  <div style="text-align: center">
    <div class="register_form">
      <h2>もくもく会作成</h2>

      <h3 class="register_items">題名<span class="required">必須</span></h3>
      <input
        v-model="theme"
        class="input_form"
        type="text"
        placeholder="題名"
      /><br />
      <p
        v-for="(theme, index) in validation.theme"
        v-show="validation.theme.length"
        :key="index"
        class="validation_error"
      >
        ・{{ theme }}
      </p>

      <h3 class="register_items">詳細<span class="required">必須</span></h3>
      <textarea
        v-model="introduction"
        class="input_form"
        type="text"
        placeholder="詳細"
      >
      </textarea>
      <p
        v-for="(introduction, index) in validation.introduction"
        v-show="validation.introduction.length"
        :key="index"
        class="validation_error"
      >
        ・{{ introduction }}
      </p>

      <h3 class="register_items">
        開催する都道府県<span class="required">必須</span>
      </h3>
      <select
        v-model="pref_id"
        class="input_form"
        @change="getPrefName(pref_id)"
      >
        <option class="input_form" selected :value="null">
          都道府県を選択
        </option>
        <option
          v-for="(pref, index) in prefs"
          :key="index"
          class="input_form"
          :value="pref.id"
        >
          {{ pref.name }}
        </option></select
      ><br />
      <p
        v-for="(pref_id, index) in validation.pref_id"
        v-show="validation.pref_id.length"
        :key="index"
        class="validation_error"
      >
        ・{{ pref_id }}
      </p>

      <h3 class="register_items">開催場所<span class="required">必須</span></h3>
      <input
        v-model="place"
        class="input_form"
        type="text"
        placeholder="開催場所"
      /><br />
      <p
        v-for="(place, index) in validation.place"
        v-show="validation.place.length"
        :key="index"
        class="validation_error"
      >
        ・{{ place }}
      </p>

      <h3 class="register_items">定員<span class="required">必須</span></h3>
      <select v-model="due_max" class="input_form">
        <option class="input_form" selected :value="''">定員を選択</option>
        <option
          v-for="(number, index) in numbers"
          :key="index"
          class="input_form"
          :value="number"
        >
          {{ number }}
        </option></select
      ><br />
      <p
        v-for="(due_max, index) in validation.due_max"
        v-show="validation.due_max.length"
        :key="index"
        class="validation_error"
      >
        ・{{ due_max }}
      </p>

      <h3 class="register_items">開催日時<span class="required">必須</span></h3>
      <input
        v-model="due_date"
        class="input_form"
        type="date"
        placeholder="開催日時"
      /><br />
      <p
        v-for="(due_date, index) in validation.due_date"
        v-show="validation.due_date.length"
        :key="index"
        class="validation_error"
      >
        ・{{ due_date }}
      </p>

      <h3 class="register_items">紐付けるタグ</h3>
      <v-container>
        <v-row>
          <v-col
            v-for="(tag, index) in tags"
            :key="index"
            cols="12"
            sm="6"
            md="4"
            lg="3"
            xl="3"
            class="register_items"
          >
            <input
              :id="tag.id"
              v-model="tag_ids"
              type="checkbox"
              :value="tag.id"
            />
            <label :for="tag.id">{{ tag.name }}</label>
          </v-col>
        </v-row>
      </v-container>
      <p
        v-for="(tag_ids, index) in validation.tag_ids"
        v-show="validation.tag_ids.length"
        :key="index"
        class="validation_error"
      >
        ・{{ tag_ids }}
      </p>

      <CreatePartyModal
        :theme="theme"
        :introduction="introduction"
        :pref_name="pref_name"
        :place="place"
        :due_max="due_max"
        :due_date="due_date"
        @close-modal="register"
      />
    </div>
  </div>
</template>

<script>
import CreatePartyModal from '~/components/Modal/CreatePartyModal.vue'
export default {
  components: {
    CreatePartyModal,
  },
  data() {
    return {
      theme: '',
      prefs: '',
      pref_id: '',
      pref_name: '',
      introduction: '',
      tags: '',
      tag_ids: [],
      numbers: [
        1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18, 19, 20,
      ],
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
  created() {
    this.prefs = this.$PREF
    this.tags = this.$TAG
  },
  methods: {
    async register() {
      await this.$axios
        .post('/api/party/register', {
          theme: this.theme,
          pref_id: this.pref_id,
          place: this.place,
          due_max: this.due_max,
          tag_ids: this.tag_ids,
          due_date: this.due_date,
          introduction: this.introduction,
        })
        .then((res) => {
          console.log(res)
          window.alert(res.data.message)
          this.$router.push('/')
        })
        .catch((err) => {
          console.log('エラー発生')
          if (err.response.status === 422) {
            this.validation.errors = err.response.data.errors
            if ('theme' in this.validation.errors) {
              this.validation.theme = this.validation.errors.theme
            } else {
              this.validation.theme = []
            }
            if ('introduction' in this.validation.errors) {
              this.validation.introduction = this.validation.errors.introduction
            } else {
              this.validation.introduction = []
            }
            if ('pref_id' in this.validation.errors) {
              this.validation.pref_id = this.validation.errors.pref_id
            } else {
              this.validation.pref_id = []
            }
            if ('place' in this.validation.errors) {
              this.validation.place = this.validation.errors.place
            } else {
              this.validation.place = []
            }
            if ('due_max' in this.validation.errors) {
              this.validation.due_max = this.validation.errors.due_max
            } else {
              this.validation.due_max = []
            }
            if ('due_date' in this.validation.errors) {
              this.validation.due_date = this.validation.errors.due_date
            } else {
              this.validation.due_date = []
            }
            if ('tag_ids' in this.validation.errors) {
              this.validation.tag_ids = this.validation.errors.tag_ids
            } else {
              this.validation.tag_ids = []
            }
          }
        })
    },
    getPrefName(prefId) {
      if (prefId !== null) {
        console.log(prefId)
        this.pref_name = this.prefs[prefId - 1].name
      } else {
        this.pref_name = ''
      }
      return null
    },
  },
}
</script>

<style scope>
.register_form {
  margin-top: 20px;
  margin-left: 15%;
  width: 70%;
  padding-left: 5%;
  padding-right: 5%;
  background-color: #e8e6e6;
  padding-bottom: 20px;
}
.register_items {
  text-align: left;
}
.input_form {
  border: 1px solid black;
  background-color: white;
  width: 100%;
  height: 40px;
  margin-bottom: 20px;
}
.required {
  font-size: 12px;
  margin-left: 5px;
  color: white;
  background-color: red;
  padding: 2px;
}

.validation_error {
  color: red;
  text-align: left;
}
</style>
