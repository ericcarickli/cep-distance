import { createRouter, createWebHistory } from 'vue-router';
import HomeComponent from '../components/HomeComponent/HomeComponent.vue';
import AboutComponent from '../components/AboutComponent/AboutComponent.vue';

const routes = [
  {
    path: '/',
    name: 'Home',
    component: HomeComponent,
  },
  {
    path: '/about',
    name: 'About',
    component: AboutComponent,
  },
];

const router = createRouter({
  history: createWebHistory(),
  routes,
});

export default router;
