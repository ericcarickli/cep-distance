import { createRouter, createWebHistory } from 'vue-router';
import HomeComponent from '../components/HomeComponent/HomeComponent.vue';
import CalculatedDistances from '../components/CalculatedDistancesComponent/CalculatedDistancesComponent.vue'

const routes = [
  {
    path: '/', component: HomeComponent,
  },
  {
    path: '/calculated-distances', component: CalculatedDistances,
  },
];

const router = createRouter({
  history: createWebHistory(),
  routes,
});

export default router;
