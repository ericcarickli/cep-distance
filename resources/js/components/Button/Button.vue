<template>
    <button class="button-container" @click="$emit('click')">
        <slot></slot> <!-- This allows for flexible button content -->
    </button>
</template>
  
<script>
    export default {
        name: 'Button'
    }
</script>
  
<style lang="scss" scoped>
@import './Button.module.scss';
</style>