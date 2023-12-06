<template>
  <v-app dark>
    <HeaderGuest />
    <v-main>
      <v-container>
        <Nuxt />
      </v-container>
    </v-main>
  </v-app>
</template>

<script>
export default {
  name: 'GuestLayout',
  data() {
    return {}
  },
}
</script>
