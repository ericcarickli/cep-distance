<template>
  <div class="home">
    <div class="container">
      <div class="calculate-container">
        <div class="router-link-container">
          <router-link class="link" to="/calculate-mass">Calcular distâncias em massa</router-link>
        </div>
        <div class="calculate-distance-container">
          <Input 
            id="from" 
            :required="true"
            v-model="cepFrom"
            label="CEP de origem"
          />
          <Input 
            id="to" 
            v-model="cepTo"
            :required="true"
            label="CEP de destino"
          />
          <Button @click="calculateDistance" :disabled="!isCepValid || loading">
            {{ loading ?  'Calculando...' : 'Calcular Distância' }}
          </Button>
        </div>
      </div>
      <div class="result-container">
        <span v-if="distance !== null" class="result">
          A distância é de: 
          <span class="result-value">{{ formatDistance(distance) }} km </span>
        </span>
        <span v-if="error" class="error">{{ error }}</span>
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
    computed: {
      isCepValid() {
        const cepPattern = /^\d{8}$/;
        return cepPattern.test(this.cepFrom) && cepPattern.test(this.cepTo);
      }
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

          console.log(response);

          if (response.data.distance !== undefined) {
            this.distance = response.data.distance;
          } else {
            this.error = 'Invalid response from server.';
          }
        } catch (err) {
          this.handleError(err.response?.data?.error || err.message);
        } finally {
          this.loading = false;
        }
      },
      handleError(errorCode) {
        const errorMessages = {
          from_cep_invalid: 'CEP de origem inválido.',
          to_cep_invalid: 'CEP de destino inválido.',
          from_cep_coordinates_not_available: 'Coordenadas do CEP de origem não estão disponíveis na Brasil API.',
          to_cep_coordinates_not_available: 'Coordenadas do CEP de destino não estão disponíveis na Brasil API.'
        };

        this.error = errorMessages[errorCode] || 'Ocorreu um erro inesperado. Tente novamente mais tarde.';
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
