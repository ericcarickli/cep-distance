<template>
    <div>
        <div class="distances-container" :class="{ 'for-mass-calculation': forMassCalculation }" v-if="distances.length">
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
        <div v-else-if="!forMassCalculation">
            <p class="no-elements">Nenhuma disância calculada no momento.</p>
        </div>
    </div>
</template>

<script>
    import KeyValuePair from './KeyValuePair.vue';

    export default {
        name: 'CalculatedElementsComponent',
        components: {
            KeyValuePair
        },
        props: {
            forMassCalculation: {
                type: Boolean,
                default: false
            },
            distances: {
                type: Array,
                default: () => []
            },
        },
        methods: {
            formatDate(dateString) {
                const date = new Date(dateString);
                const year = date.getFullYear();
                const month = String(date.getMonth() + 1).padStart(2, '0');
                const day = String(date.getDate()).padStart(2, '0');

                return `${day}/${month}/${year}`;
            },
            formatDistance(distance) {
                const numericDistance = parseFloat(distance);
                return numericDistance.toFixed(2);
            }
        },
    };
</script>
  
  
<style lang="scss" scoped>
    @import './CalculatedDistancesComponent.module.scss';
</style>