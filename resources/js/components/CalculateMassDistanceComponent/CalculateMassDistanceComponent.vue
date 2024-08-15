<template>
    <div class="home">
        <div class="container">
            <div class="calculate-distance-container">
                <input
                    type="file"
                    @change="onFileChange"
                />
                <Button @click="importFile" :disabled="!csvFile || loading">
                    {{ loading ?  'Calculando...' : 'Calcular Distâncias' }}
                </Button>
            </div>
            <div v-if="errorMessage" class="error-message">
                {{ errorMessage }}
            </div>
        </div>
    </div>
  </template>
  
<script>
    import Button from '../Button/Button.vue';
  
    export default {
        name: 'CalculateMassDistanceComponent',
        components: {
            Button
        },
        data() {
            return {
                csvFile: null,
                loading: false,
                errorMessage: ''
            };
        },
        methods: {
            onFileChange(event) {
                const file = event.target.files[0];

                if (file && this.isValidCSV(file)) {
                    this.csvFile = file;
                    this.errorMessage = '';
                } else {
                    this.csvFile = null;
                    this.errorMessage = 'Por favor adicione um arquivo .csv.';
                }
            },
            isValidCSV(file) {
                console.log(file);
                return file.type === 'text/csv' || file.name.endsWith('.csv');
            },
            async importFile() {
                if (!this.csvFile) return;

                this.loading = true;
                try {
                    const formData = new FormData();
                    formData.append('file', this.csvFile);

                    const response = await axios.post('/api/calculate-mass', formData, {
                        headers: {
                            'Content-Type': 'multipart/form-data'
                        }
                    });

                    console.log('Distances calculated and saved:', response.data);
                } catch (error) {
                    console.error('Error calculating distances:', error);
                } finally {
                    this.loading = false;
                }
            }
        }
    };
</script>
  
<style lang="scss" scoped>
    @import './CalculateMassDistanceComponent.module.scss';
</style>
  