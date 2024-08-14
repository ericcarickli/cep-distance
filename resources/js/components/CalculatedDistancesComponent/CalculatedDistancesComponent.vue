<template>
  <div>
    <div class="distances-container" v-if="distances.length">
      <div class="distance-container" v-for="distance in distances" :key="distance._id" >
          <span class="km-container">
            {{ formatDistance(distance.calculated_distance) }}
            <span class="unit">km</span>
          </span>
          <div class="values-container">
            <KeyValuePair
              :pairs="{
                'Cep de Origem': distance.cep_from,
                'Cep de Destino': distance.cep_to
              }"
            />
            <KeyValuePair
              :pairs="{
                'Criado em': formatDate(distance.created_at),
                'Atualizado em': formatDate(distance.updated_at)
              }"
            />
          </div>
      </div>
    </div>
    <div v-else>
      <p>No distances calculated yet.</p>
    </div>
  </div>
</template>

<script>
  import axios from 'axios';
  import KeyValuePair from './KeyValuePair.vue';

  export default {
    name: 'CalculatedDistancesComponent',
    components: {
      KeyValuePair
    },
    data() {
      return {
        distances: [],
      };
    },
    created() {
      this.fetchDistances();
    },
    methods: {
      async fetchDistances() {
        try {
          const response = await axios.get('/api/distances');

          console.log(response);
          this.distances = response.data;
        } catch (error) {
          console.error('Error fetching distances:', error);
        }
      },
      formatDate(dateString) {
        const date = new Date(dateString);
        const year = date.getFullYear();
        const month = String(date.getMonth() + 1).padStart(2, '0');
        const day = String(date.getDate()).padStart(2, '0');

        return `${day}/${month}/${year}`;
      },
      formatDistance(distance) {
        return distance.toFixed(2);
      }
    },
  };
</script>


<style lang="scss" scoped>
    @import './CalculatedDistancesComponent.module.scss';
</style>