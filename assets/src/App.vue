<template>
  <div class="h-100">
    <notifications position="bottom right" />
    <v-row no-gutters class="w-100 h-100">
      <Head></Head>
      <v-col class="w-100 h-100 pe-10 col-ajustado">
        <Main></Main>
      </v-col>
    </v-row>
  </div>
</template>
<script lang="ts">
import { mapState, mapActions } from 'pinia';
import Main from './views/Main.vue';
import Head from './views/Head.vue';

export default {
  name: "App",
  components: {
    Main,
    Head
  },

  methods: {
  },
  mounted() {
    
  },
}
</script>
<style scoped>
.col-ajustado {
  padding-inline-end: 40px !important;
}

@media (max-width: 768px) {

  /* Ajusta el tamaño según sea necesario */
  .col-ajustado {
    padding-inline-end: 0 !important;
    /* Sobrescribe el padding en móviles */
  }
}
</style>