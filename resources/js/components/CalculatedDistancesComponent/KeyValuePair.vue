<template>
    <div class="text-value-container">
        <div class="text-container" v-for="(value, key) in pairs" :key="key">
            {{ key }}:
            <span class="value">{{ value }}</span>
        </div>
    </div>
</template>
  
<script>
    export default {
        name: 'KeyValuePair',
        props: {
            pairs: {
                type: Object,
                required: true
            }
        }
    };
</script>
  
<style lang="scss" scoped>
    .text-value-container {
        width: 100%;
        display: flex;
        flex-direction: row;
        justify-content: space-between;

        .text-container {
            gap: 6px;
            width: 130px;
            display: flex;
            align-items: center;
            flex-direction: column;
            justify-content: center;

            color: #000;
            font-weight: normal;

            .value {
                font-weight: bold;
            }
        }
    }
</style>
  