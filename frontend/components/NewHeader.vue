<template>
  <header class="header" @mouseleave="menu1 = false; menu2 = false; menu3 = false">
    <nav>
      <div class="dropdown">
        <button @mouseover="menu1 = !menu1; menu2 = false; menu3 = false">もくもくMAP</button>
        <div v-if="menu1" class="menu">
          <p><router-link class="link" to="/">一覧画面へ</router-link></p>
          <p><router-link class="link" to="/create_party">もくもく会を作成</router-link></p>
          <p><router-link class="link" to="/party/created_list">作成したもくもく会一覧</router-link></p>
          <p><router-link class="link" to="/party/participated_list">参加したもくもく会一覧</router-link></p>
        </div>
      </div>
      <div class="dropdown">
        <button @mouseover="menu2 = !menu2; menu1 = false; menu3 = false">メッセージ</button>
        <div v-if="menu2" class="menu">
          <p><router-link class="link" to="/messages">メッセージ(参加者側)</router-link></p>
          <p><router-link class="link" to="/messages_for_leader">メッセージ(主催者側)</router-link></p>
        </div>
      </div>
      <div class="dropdown">
        <button @mouseover="menu3 = !menu3; menu1 = false; menu2 = false">ユーザー</button>
        <div v-if="menu3" class="menu">
          <p><router-link class="link" to="/user/show">ユーザー情報</router-link></p>
          <button class="link" @click="logout" style="margin-top:5px;">ログアウト</button>
        </div>
      </div>
    </nav>
  </header>
</template>

<script>
export default {
  data() {
    return {
      menu1: false,
      menu2: false,
      menu3: false,
    }
  },
  methods: {
    async logout() {
      await this.$auth
        .logout()
        .then(() => {
          this.$router.push('/auth/login')
        })
        .catch((e) => console.log(e))
    },
  },
}
</script>

<style scoped>
.header {
  position: fixed;
  top: 0;
  width: 100%;
  height: 100px;
  background-color: white;
  border-bottom: 1px solid black;
  margin-bottom: 100px;
  z-index: 1;
  display: flex;
  align-items: center;
}

nav {
  width: 100%;
  display: flex;
  justify-content: space-around;
}

.dropdown {
  position: relative;
}

.link {
  color: #333; /* 色を黒っぽく設定 */
  text-decoration: none; /* 下線を削除 */
  transition: color ease; /* 色の遷移効果を追加 */
  font-family: 'Helvetica', 'Arial', sans-serif; /* 任意のフォントを設定。適宜変更すること */
  font-size: 18px; /* フォントサイズを設定。適宜変更すること */
  font-weight: bold; /* テキストをボールドに設定 */
}
.link:hover{
  color: #066aff; /* ホバー時の色を薄い黒に設定 */
}
button {
  background: none;
  cursor: pointer;
}

.menu {
  position: absolute;
  width: 200px;
  left: 0;
  top: 100%;
  z-index: 100;
  background-color: white;
  border: 1px solid black;
}
</style>
