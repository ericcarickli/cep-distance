import { createRouter, createWebHistory } from 'vue-router';
import HomeComponent from '../components/HomeComponent/HomeComponent.vue';
import CalculateDistance from '../components/CalculateDistanceComponent/CalculateDistanceComponent.vue'

const routes = [
  {
    path: '/',
    name: 'Home',
    component: HomeComponent,
  },
  {
    path: '/calculate-distance',
    name: 'CalculateDistance',
    component: CalculateDistance,
  },
];

const router = createRouter({
  history: createWebHistory(),
  routes,
});

export default router;
