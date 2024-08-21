import { createRouter, createWebHistory } from 'vue-router'
import TableView from '../views/TableView.vue';
import ChartView from '../views/ChartView.vue';

const router = createRouter({
  history: createWebHistory(import.meta.env.BASE_URL),
  routes: [
    {
      path: '/',
      redirect: '/tabla'
    },
    {
      path: '/tabla',
      name: 'tabla',
      component: TableView
    },
    {
      path: '/graficos',
      name: 'graficos',
      component: ChartView
    },
  ]
})

export default router
