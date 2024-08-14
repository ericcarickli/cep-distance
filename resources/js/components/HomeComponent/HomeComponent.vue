<template>
  <div class="home">
    <div class="container">
      <div class="calculate-distance-container">
        <Input 
          id="from" 
          label="CEP de origem"
          v-model="cepFrom"
        />
        <Input 
          id="to" 
          label="CEP de destino"
          v-model="cepTo"
        />
        <Button @click="calculateDistance" :disabled="loading">
          {{ loading ?  'Calculando...' : 'Calcular Distância' }}
        </Button>
      </div>
      <div class="result-container">
        <span v-if="distance" class="result">A distância é de: 
          <span class="result-value">{{ formatDistance(distance) }} km </span>
        </span>
        <span v-if="error" class="error">Erro: {{ error }}</span>
      </div>
    </div>
  </div>
</template>

<script>
  import Input from '../Input/Input.vue';
  import Button from '../Button/Button.vue';
  import axios from 'axios';

  export default {
    name: 'HomeComponent',
    components: {
      Input,
      Button
    },
    data() {
      return {
        cepFrom: '',
        cepTo: '',
        distance: null,
        error: null,
        loading: false
      };
    },
    methods: {
      async calculateDistance() {
        if (this.loading) return;

        this.loading = true;
        this.error = null;
        this.distance = null;

        try {
          this.error = null;
          const response = await axios.post('/api/calculate', {
            cep_from: this.cepFrom,
            cep_to: this.cepTo
          });

          if (response.data.distance) {
            this.distance = response.data.distance;
          } else {
            this.error = 'Invalid response from server.';
          }

          this.loading = false;
        } catch (err) {
          this.error = 'An error occurred: ' + (err.response?.data?.error || err.message);
        }
      },
      formatDistance(distance) {
        return distance.toFixed(2);
      }
  }
};
</script>

<style lang="scss" scoped>
    @import './HomeComponent.module.scss';
</style>
