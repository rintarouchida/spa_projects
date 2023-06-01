<template>
  <v-app dark>
  <Header />
    <v-main>
      <v-container>
        ログイン前
        <Nuxt />
      </v-container>
    </v-main>
  </v-app>
</template>

<script>
export default {
  name: 'GuestLayout',
  data() {
    return {
    }
  },
}
</script>
