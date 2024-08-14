<template>
  <div class="main-container">
    <header class="header-container">
      <span class="title" @click="navigateHome">Cep Distance</span>
      <Button @click="handleClick">Distâncias já calculadas</Button>
    </header>
    <div class="children-container">
      <div class="children">
        <slot></slot>
      </div>
    </div>
  </div>
</template>

<script>
import Button from '@/components/Button/Button.vue';
import Input from '@/components/Input/Input.vue';
import { useRouter } from 'vue-router';

export default {
  name: 'AboutComponent',
  components: {
    Button,
    Input
  },
  data() {
    return {
      username: ''
    }
  },
  methods: {
    handleClick() {
      this.$router.push('/calculate-distance');
    },
    navigateHome() {
      this.$router.push('/');
    }
  }
};

</script>

<style lang="scss" scoped>
  @import './MainTemplate.module.scss';
</style>
