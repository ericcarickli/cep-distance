<template>
    <button class="button-container" :disabled="disabled" @click="$emit('click')">
        <slot></slot>
    </button>
</template>
  
<script>
    export default {
        name: 'Button',
        props: {
            disabled: {
                type: Boolean,
                default: false
            }
        },
        methods: {
            handleClick(event) {
                if (!this.disabled) {
                    this.$emit('click', event);
                }
            }
        }
    }
</script>
  
<style lang="scss" scoped>
@import './Button.module.scss';
</style>