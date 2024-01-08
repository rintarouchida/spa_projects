<template>
  <div>
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
            <button class="link" @click="logout" style="margin:5px; padding-bottom:8px;">ログアウト</button>
          </div>
        </div>
      </nav>
    </header>
    <header class="header_sp">
      <div class="hamburger-menu" @click="toggleMenu">
        <div class="bar"></div>
        <div class="bar"></div>
        <div class="bar"></div>
      </div>
    </header>
        <!-- ハンバーガーメニューのコンテンツ -->
    <div class="menu-content" id="menuContent">
      <!-- メニューアイテム -->
      <p></p>
      <p></p>
      <p><a class="link" @click="toRoute('/')">一覧画面へ</a></p>
      <p><a class="link" @click="toRoute('/create_party')">もくもく会を作成</a></p>
      <p><a class="link" @click="toRoute('/party/created_list')">作成したもくもく会一覧</a></p>
      <p><a class="link" @click="toRoute('/party/participated_list')">参加したもくもく会一覧</a></p>
      <p><a class="link" @click="toRoute('/messages')">メッセージ(参加者側)</a></p>
      <p><a class="link" @click="toRoute('/messages_for_leader')">メッセージ(主催者側)</a></p>
      <p><a class="link" @click="toRoute('/user/show')">ユーザー情報</a></p>
      <p><a class="link" @click="logout">ログアウト</a></p>
    </div>
  </div>
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
    toggleMenu() {
      const menuContent = document.getElementById('menuContent');
      if (menuContent.style.left === '0px') {
        menuContent.style.left = '-100%'; // メニューを隠す
      } else {
        menuContent.style.left = '0px'; // メニューを表示する
      }
    },
    toRoute(url) {
      const menuContent = document.getElementById('menuContent');
      menuContent.style.left = '-100%';
      this.$router.push(url);
    }
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
  z-index: 2;
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
  color: #333;
  text-decoration: none;
  transition: color ease;
  font-family: 'Helvetica', 'Arial', sans-serif;
  font-size: 14px;
  font-weight: bold;
}
.link:hover{
  color: #066aff;
}
button {
  background: none;
  cursor: pointer;
}

.menu {
  position: absolute;
  padding:8px 8px 0px 8px;
  width: 200px;
  left: 0;
  top: 100%;
  z-index: 100;
  background-color: white;
  border: 1px solid black;
}

/* header_sp用デザイン */
.header_sp {
  position: fixed;
  top: 0;
  width: 100%;
  height: 100px;
  background-color: white;
  border-bottom: 1px solid black;
  margin-bottom: 100px;
  z-index: 2;
  display: flex;
  align-items: center;
  padding: 0 20px;
}

.hamburger-menu {
  display: flex;
  flex-direction: column;
  justify-content: space-between;
  width: 30px;
  height: 25px;
  cursor: pointer;
}

/* ハンバーガーメニューのバー */
.hamburger-menu .bar {
  height: 3px;
  background-color: black;
}

/* メニューコンテンツスタイル */
.menu-content {
  position: fixed;
  top: 0;
  left: -100%;
  width: 100%;
  height: 100%;
  background-color: white;
  z-index: 1;
  overflow:scroll;
  transition: left 0.3s;
}

/* メニューアイテムスタイル */
.menu-content p {
  height:50px;
  padding: 10px;
  border-bottom: 1px solid black;
  text-decoration: none;
  color: black;
  z-index:1;
}

@media (min-width: 601px) {
  .header_sp {
    display: none;
  }
}

@media (max-width: 600px) {
  .header {
    display: none;
  }
}
</style>
