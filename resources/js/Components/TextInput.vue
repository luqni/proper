<script setup>
import { onMounted, ref } from 'vue';

const model = defineModel({
    type: String,
    required: true,
});

const input = ref(null);

onMounted(() => {
    if (input.value.hasAttribute('autofocus')) {
        input.value.focus();
    }
});

defineExpose({ focus: () => input.value.focus() });
</script>

<template>
    <input
        class="rounded-xl border-emerald-100 shadow-sm focus:border-emerald-500 focus:ring-emerald-500 bg-white/50 backdrop-blur-sm transition-all"
        v-model="model"
        ref="input"
    />
</template>
