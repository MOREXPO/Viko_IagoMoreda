<template>
    <v-container>
      <v-row align="center" justify="center">
        <v-col cols="12" md="8">
          <v-card class="pa-5" elevation="2">
            <v-card-title>
              <h2 class="text-center">Bienvenido a la Página de Inicio</h2>
            </v-card-title>
            <v-card-text>
              <p class="text-center">
                Esta es la página principal de nuestra aplicación. Explora nuestras características y disfruta de la experiencia.
              </p>
            </v-card-text>
            <v-card-actions>
              <v-spacer></v-spacer>
              <v-btn color="primary" @click="goToFeatures">Explorar Características</v-btn>
            </v-card-actions>
          </v-card>
        </v-col>
      </v-row>
    </v-container>
  </template>
  
  <script>
  export default {
    name: "Home",
    methods: {
      goToFeatures() {
        this.$router.push({ name: 'features' });
      },
    },
  };
  </script>
  
  <style scoped>
  .text-center {
    text-align: center;
  }
  </style>
  