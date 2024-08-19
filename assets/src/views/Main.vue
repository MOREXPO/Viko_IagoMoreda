<script setup lang="ts">
import { RouterLink, RouterView } from 'vue-router'
</script>
<template>
  <div id="main">
    <RouterView></RouterView>
  </div>
</template>
<style scoped>
#main {
  position: relative;
  left: 2vw;
  top: 1vw;
  height: calc(100% - 2vw);
  width: 100%;
  background: var(--navbar-dark-primary);
  border-radius: 16px;
  display: flex;
  flex-direction: column;
  color: var(--navbar-light-primary);
  font-family: Verdana, Geneva, Tahoma, sans-serif;
  overflow: hidden;
  user-select: none;
}
@media (max-width: 768px) { /* Ajusta el tamaño según sea necesario */
  #main {
    left: 0%;
  }
}
</style>