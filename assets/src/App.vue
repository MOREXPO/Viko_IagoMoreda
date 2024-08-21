<template>
  <v-app>
    <v-app-bar app>
      <v-toolbar-title>VIKO - Iago Moreda Expósito</v-toolbar-title>
      <v-spacer></v-spacer>
      <v-btn to="/tabla" text>Tabla</v-btn>
      <v-btn to="/graficos" text>Graficos</v-btn>
    </v-app-bar>
    <v-main>
      <router-view></router-view>
    </v-main>
  </v-app>
</template>

<script>
import { mapState, mapActions } from 'pinia';
import { tweetStore } from './stores/tweet';
export default {
  name: 'App',
  mounted() {
    this.fetchTweets();
  },
  methods: {
    ...mapActions(tweetStore, ["fetchTweets"]),
  },
};
</script>
