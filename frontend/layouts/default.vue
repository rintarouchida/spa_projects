<template>
  <v-app dark>
  <Header />
    <v-main>
      <v-container>
        ログイン中
        <Nuxt />
      </v-container>
    </v-main>
  </v-app>
</template>

<script>
export default {
  name: 'LoginedLayout',
  data() {
    return {
    }
  },
}
</script>
