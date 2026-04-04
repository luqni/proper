<script setup>
import { ref, onMounted, onUnmounted } from 'vue';

const isOnline = ref(navigator.onLine);

const updateOnlineStatus = () => {
    isOnline.value = navigator.onLine;
};

onMounted(() => {
    window.addEventListener('online', updateOnlineStatus);
    window.addEventListener('offline', updateOnlineStatus);
});

onUnmounted(() => {
    window.removeEventListener('online', updateOnlineStatus);
    window.removeEventListener('offline', updateOnlineStatus);
});
</script>

<template>
    <div v-if="!isOnline" class="flex items-center space-x-2 text-red-600 bg-red-50 px-3 py-1 rounded-full text-xs font-semibold">
        <span class="w-2 h-2 rounded-full bg-red-600 animate-pulse"></span>
        <span>Offline Mode</span>
    </div>
</template>
