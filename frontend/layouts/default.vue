<template>
  <v-app dark>
    <Header />
    <v-main>
      <v-container>
        <Nuxt style="margin-top:100px;"/>
      </v-container>
    </v-main>
  </v-app>
</template>

<script>
export default {
  name: 'LoginedLayout',
  data() {
    return {}
  },
}
</script>
