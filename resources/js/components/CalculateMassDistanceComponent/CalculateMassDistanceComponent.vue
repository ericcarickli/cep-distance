<template>
    <div class="home">
        <div class="container">
            <div class="calculate-distance-container">
                <input
                    type="file"
                    ref="fileInput"
                    @change="onFileChange"
                />
                <Button @click="importFile" :disabled="!csvFile || loading">
                    {{ loading ?  'Calculando...' : 'Calcular Distâncias' }}
                </Button>
            </div>
            <span v-if="response" class="response">
                {{ response }}
            </span>
            <span v-if="error" class="error-message">
                {{ error }}
            </span>
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
                response: '',
                loading: false,
                error: ''
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

                    const responseToMessage = {
                        'process_initiated': 'Processo de cálculo de massa iniciado. Aguarde o resultado.',
                    }

                    this.response = responseToMessage[response.data.message];
                    this.csvFile = null;
                    setTimeout(() => this.resetFileInput(), 3000);
                } catch (err) {
                    this.handleError();
                } finally {
                    this.loading = false;
                }
            },
            resetFileInput() {
                this.$refs.fileInput.value = '';
            },
            handleError() {
                this.error = 'Ocorreu um erro inesperado. Tente novamente mais tarde.';
            },
        }
    };
</script>
  
<style lang="scss" scoped>
    @import './CalculateMassDistanceComponent.module.scss';
</style>
  