<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, router, usePage } from '@inertiajs/vue3';
import { onMounted, onUnmounted, ref } from 'vue';
import { Html5QrcodeScanner } from 'html5-qrcode';
import Card from '@/Components/Card.vue';

const scanner = ref(null);
const scanError = ref(null);
const isScanning = ref(true);

const onScanSuccess = (decodedText, decodedResult) => {
    // Stop scanning once we have a result
    if (scanner.value) {
        scanner.value.clear();
        isScanning.value = false;
    }

    // Check if the URL is from our application
    // Simple check: if it contains '/animals/' and is a URL
    if (decodedText.includes('/animals/')) {
        router.visit(decodedText);
    } else {
        scanError.value = "Invalid QR code. Please scan an animal QR code from Pintar Ternak.";
        // Restart after a delay if error
        setTimeout(() => {
            startScanner();
            scanError.value = null;
        }, 3000);
    }
};

const onScanFailure = (error) => {
    // We don't necessarily want to show every failure (it fails many times per second while looking)
    // console.warn(`Code scan error = ${error}`);
};

const startScanner = () => {
    isScanning.value = true;
    scanner.value = new Html5QrcodeScanner(
        "qr-reader",
        { 
            fps: 10, 
            qrbox: { width: 250, height: 250 },
            aspectRatio: 1.0
        },
        /* verbose= */ false
    );
    scanner.value.render(onScanSuccess, onScanFailure);
};

onMounted(() => {
    startScanner();

    // Simple observer to translate library buttons
    const observer = new MutationObserver(() => {
        const isId = usePage().props.locale === 'id';
        const buttons = document.querySelectorAll('#qr-reader button');
        
        buttons.forEach(btn => {
            if (btn.innerText.includes('Request Camera Permissions')) {
                if (isId) btn.innerText = 'Minta Izin Kamera';
            } else if (btn.innerText.includes('Minta Izin Kamera')) {
                if (!isId) btn.innerText = 'Request Camera Permissions';
                
            } else if (btn.innerText.includes('Scan an Image File')) {
                if (isId) btn.innerText = 'Pindai File Gambar';
            } else if (btn.innerText.includes('Pindai File Gambar')) {
                if (!isId) btn.innerText = 'Scan an Image File';
                
            } else if (btn.innerText.includes('Stop Scanning')) {
                if (isId) btn.innerText = 'Berhenti Memindai';
            } else if (btn.innerText.includes('Berhenti Memindai')) {
                if (!isId) btn.innerText = 'Stop Scanning';
            }
        });
    });
    observer.observe(document.body, { childList: true, subtree: true });
});

onUnmounted(() => {
    if (scanner.value) {
        scanner.value.clear();
    }
});
</script>

<template>
    <Head title="Scan Animal QR" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex items-center space-x-2">
                <i class="fas fa-qrcode text-emerald-600"></i>
                <h2 class="font-bold text-xl text-gray-800 leading-tight">
                    {{ __('Scan Animal QR') }}
                </h2>
            </div>
        </template>

        <div class="max-w-xl mx-auto mt-4">
            <Card class="overflow-hidden border-earth-200">
                <div class="p-6 bg-earth-50 border-b border-earth-100 flex flex-col items-center">
                    <div class="h-12 w-12 bg-emerald-100 rounded-full flex items-center justify-center text-emerald-600 mb-3">
                        <i class="fas fa-camera text-xl"></i>
                    </div>
                    <h3 class="text-lg font-bold text-gray-900">{{ __('Ready to Scan') }}</h3>
                    <p class="text-xs text-gray-500 mt-1 text-center">{{ __('Point your camera at the animal\'s QR code to view their profile.') }}</p>
                </div>

                <div class="p-4 md:p-8">
                    <div v-if="scanError" class="mb-4 p-3 bg-red-50 border border-red-100 rounded-xl text-red-600 text-xs font-bold flex items-center">
                        <i class="fas fa-exclamation-circle mr-2"></i>
                        {{ scanError }}
                    </div>

                    <div id="qr-reader" class="rounded-2xl overflow-hidden border-2 border-emerald-100 shadow-inner"></div>
                    
                    <div class="mt-8 flex flex-col items-center">
                        <div class="flex items-center space-x-3 text-gray-400">
                            <span class="flex h-2 w-2 rounded-full bg-emerald-500 animate-pulse"></span>
                            <span class="text-[10px] font-black uppercase tracking-widest">{{ __('Scanner Active') }}</span>
                        </div>
                    </div>
                </div>
            </Card>
            
            <div class="mt-6 p-4 bg-white/50 backdrop-blur-sm rounded-2xl border border-emerald-100/50 flex items-start space-x-4">
                <div class="mt-0.5 h-8 w-8 shrink-0 bg-emerald-50 rounded-lg flex items-center justify-center text-emerald-600 text-sm">
                    <i class="fas fa-lightbulb"></i>
                </div>
                <div>
                    <h4 class="text-xs font-bold text-emerald-900 uppercase tracking-wide">{{ __('Tips') }}</h4>
                    <p class="text-xs text-emerald-800/70 mt-1 leading-relaxed">
                        {{ __('Keep the camera steady and ensure there is enough light. The scanner will automatically redirect you once it recognizes a valid code.') }}
                    </p>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

<style>
#qr-reader {
    border: none !important;
}
#qr-reader__scan_region {
    background: white;
}
#qr-reader__dashboard_section_csr button {
    @apply bg-emerald-600 text-white px-4 py-2 rounded-xl text-xs font-bold hover:bg-emerald-700 transition shadow-sm mt-4;
}
#qr-reader__status_span {
    @apply hidden;
}
</style>
