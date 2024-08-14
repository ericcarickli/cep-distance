<!-- App.vue -->
<template>
  <div>
    <!-- <nav>
      <router-link to="/">Home</router-link>
      <router-link to="/calculate-distance">About</router-link>
    </nav>
    <router-view></router-view> Route components will be rendered here -->
    <Layout>
      <router-view></router-view> <!-- This is where the routed component will be displayed -->
    </Layout>
  </div>
</template>

<script>
import MainTemplate from './templates/MainTemplate/MainTemplate.vue';

export default {
  name: 'App',
  components: {
    MainTemplate
  }
};
</script>

<!-- <style>
nav {
  padding: 10px;
}
nav a {
  margin: 0 10px;
}
</style> -->
