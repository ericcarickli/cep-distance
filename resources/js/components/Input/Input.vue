<template>
    <div class="main-container">
        <label class="label" :for="id">
            {{ label }}
            <span v-if="required">*</span>
        </label>
        <div class="input-container">
            <input
                :id="id"
                :type="type"
                :value="modelValue"
                class="input"
                @input="$emit('update:modelValue', $event.target.value)"
            />
        </div>
    </div>
  </template>
  
<script>
    export default {
        name: 'Input',
        props: {
            id: String,
            label: String,
            type: {
                type: String,
                default: 'text'
            },
            modelValue: String,
            required: Boolean
        }
    }
</script>
  
<style lang="scss" scoped>
    @import './Input.module.scss';
</style>
  