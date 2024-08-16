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
            <div v-if="error || rowError">
                <span v-if="error" class="error-message">
                    {{ error }}
                </span>
                <span v-if="rowError" class="error-message">
                    Seu CSV contém erro na linha: {{ rowError }}
                </span>
            </div>
        </div>
        <CalculatedElementsComponent :distances="response" :forMassCalculation="true"/>
    </div>
  </template>
  
<script>
    import Button from '../Button/Button.vue';
    import CalculatedElementsComponent from '../CalculatedDistancesComponent/CalculatedElementsComponent.vue';
  
    export default {
        name: 'CalculateMassDistanceComponent',
        components: {
            Button,
            CalculatedElementsComponent
        },
        data() {
            return {
                csvFile: null,
                response: [],
                loading: false,
                error: '',
                rowError: ''
            };
        },
        created() {
            this.response = [];
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
                return file.type === 'text/csv' || file.name.endsWith('.csv');
            },
            async importFile() {
                if(this.loading) return;
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
                    this.response = response.data.saved_distances;
                } catch (err) {
                    this.handleError(err.response?.data?.error || err.message);
                    this.rowError = err.response.data.csv_row;
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
        }
    };
</script>
  
<style lang="scss" scoped>
    @import './CalculateMassDistanceComponent.module.scss';
</style>
  