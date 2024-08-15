import { createRouter, createWebHistory } from 'vue-router';
import HomeComponent from '../components/HomeComponent/HomeComponent.vue';
import CalculatedDistances from '../components/CalculatedDistancesComponent/CalculatedDistancesComponent.vue'
import CalculateMassDistance from '../components/CalculateMassDistanceComponent/CalculateMassDistanceComponent.vue'

const routes = [
  {
    path: '/', component: HomeComponent,
  },
  {
    path: '/calculated-distances', component: CalculatedDistances,
  },
  {
    path: '/calculate-mass', component: CalculateMassDistance,
  },
];

const router = createRouter({
  history: createWebHistory(),
  routes,
});

export default router;
