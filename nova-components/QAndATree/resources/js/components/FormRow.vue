<template>
    <div class="mb-3">
        <label :for="name" class="form-label" v-if="label">{{ label }}</label>
        <slot></slot>
        <p v-if="error" v-html="error" class="alert-danger"></p>
    </div>
</template>

<script setup>
const props = defineProps(['name', 'label', 'error']);
</script>
