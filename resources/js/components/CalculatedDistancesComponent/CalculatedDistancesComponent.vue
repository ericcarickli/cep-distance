<template>
  <CalculateElementsComponent :distances="response"/>
</template>

<script>
  import axios from 'axios';
  import KeyValuePair from './KeyValuePair.vue';
  import CalculateElementsComponent from './CalculatedElementsComponent.vue';

  export default {
    name: 'CalculatedDistancesComponent',
    components: {
      KeyValuePair,
      CalculateElementsComponent
    },
    data() {
      return {
        response: []
      };
    },
    created() {
      this.fetchDistances();
    },
    methods: {
      async fetchDistances() {
        try {
          const response = await axios.get('/api/distances');
          this.response = response.data;
        } catch (error) {
          console.error('Error fetching distances:', error);
        }
      },
    },
  };
</script>