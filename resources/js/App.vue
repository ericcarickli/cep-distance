<template>
  <div>
    <MainTemplate>
      <router-view></router-view>
    </MainTemplate>
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
