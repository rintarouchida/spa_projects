<template>
<div>
    <input @change="selectedFile" type="file" name="file">
    <button @click="upload" type="submit">アップロード</button>
    <h3>テキスト</h3>
    <input type="text" v-model="text">
</div>
</template>

<script>
export default {
  data() {
    return {
      text:'',
      uploadFile: null
    }
  },
  methods: {
    selectedFile(e) {
      // 選択された File の情報を保存しておく
      const files = e.target.files;
      this.uploadFile = files[0];
      console.log(this.uploadFile);
    },
    async upload() {
      const formData = new FormData();
      formData.append('image', this.uploadFile);
      formData.append('text', this.text);

      const config = {
        headers: {
          'content-type': 'multipart/form-data',
          'X-HTTP-Method-Override': 'PUT',
        }
      };
      await this.$axios.post('/api/post_image', formData, config)
          .then((res) => {
            console.log(this.uploadFile)
          console.log(res.data)
        }).catch((err) => {
          console.log(err)
        })
    },
  }
}
</script>

<style>

</style>
