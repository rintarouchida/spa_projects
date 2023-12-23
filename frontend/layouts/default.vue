<template>
  <v-app dark>
    <NewHeader />
    <!-- /todo:問題なければ元のHeader削除 -->
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
