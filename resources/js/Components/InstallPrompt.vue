<script setup>
import { ref, onMounted, onUnmounted } from 'vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';

const deferredPrompt = ref(null);
const showInstallPrompt = ref(false);

const handleBIP = (e) => {
    e.preventDefault();
    deferredPrompt.value = e;
    showInstallPrompt.value = true;
};

onMounted(() => {
    window.addEventListener('beforeinstallprompt', handleBIP);
});

onUnmounted(() => {
    window.removeEventListener('beforeinstallprompt', handleBIP);
});

const installApp = async () => {
    if (!deferredPrompt.value) return;
    deferredPrompt.value.prompt();
    const { outcome } = await deferredPrompt.value.userChoice;
    if (outcome === 'accepted') {
        showInstallPrompt.value = false;
    }
    deferredPrompt.value = null;
};
</script>

<template>
    <div v-if="showInstallPrompt" class="fixed bottom-4 right-4 z-50 bg-white p-4 rounded-lg shadow-lg border border-farm-200 max-w-sm flex items-start space-x-4">
        <div class="flex-1">
            <h3 class="text-sm font-semibold text-gray-900">Install PintarTernak</h3>
            <p class="text-xs text-gray-500 mt-1">Get the app on your device for offline use and faster access.</p>
        </div>
        <PrimaryButton @click="installApp" class="bg-farm-600 hover:bg-farm-700">Install</PrimaryButton>
        <button @click="showInstallPrompt = false" class="text-gray-400 hover:text-gray-600">
            <i class="fas fa-xmark h-4 w-4"></i>
        </button>
    </div>
</template>
