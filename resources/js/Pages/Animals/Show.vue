<script setup>
import { ref, onMounted } from 'vue';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';
import Card from '@/Components/Card.vue';

const props = defineProps({
    animal: Object,
    qrCode: String
});

const activeTab = ref('weights');

const tabs = ref([
    { id: 'weights', name: __('Weight Tracking') },
    { id: 'health', name: __('Health Records') },
]);

onMounted(() => {
    if (props.animal.purpose === 'breeding') {
        tabs.value.push({ id: 'pedigree', name: __('Pedigree (Silsilah)') });
    } else if (props.animal.purpose === 'fattening') {
        tabs.value.push({ id: 'fattening', name: __('Fattening (ADG)') });
    } else if (props.animal.purpose === 'milking') {
        tabs.value.push({ id: 'milking', name: __('Milking Records') });
    }
    
    // Add these anyway but maybe lower priority?
    tabs.value.push({ id: 'breeding', name: __('Breeding History') });
    tabs.value.push({ id: 'production', name: __('Production Logs') });
});

const weightForm = useForm({
    weight: '',
    measured_at: new Date().toISOString().split('T')[0],
    notes: ''
});

const submitWeight = () => {
    // This would ideally point to a separate WeightController or a weight-specific route
    // For now, we can update via the main animal update if desired, 
    // but better to have a dedicated endpoint. 
    // I'll assume we can post to a weight recording route.
    weightForm.post(route('animals.weights.store', props.animal.id), {
        onSuccess: () => weightForm.reset(),
    });
};

const productionForm = useForm({
    type: 'milk',
    quantity: '',
    unit: 'Liters',
    date: new Date().toISOString().split('T')[0],
});

const submitProduction = () => {
    productionForm.post(route('animals.productions.store', props.animal.id), {
        onSuccess: () => productionForm.reset('quantity'),
    });
};
</script>

<template>
    <Head :title="`Animal: ${animal.name_or_tag}`" />

    <AuthenticatedLayout>
        <template #header>
             <div class="flex items-center justify-between w-full gap-2">
                <div class="flex items-center space-x-2 min-w-0">
                    <Link :href="route('animals.index')" class="text-emerald-700 hover:text-emerald-900 transition bg-emerald-50 p-1.5 rounded-lg border border-transparent hover:border-emerald-200 shrink-0">
                        <i class="fas fa-arrow-left"></i>
                    </Link>
                    <span class="text-lg md:text-xl font-bold truncate">{{ animal.name_or_tag }} {{ __('Profile') }}</span>
                </div>
                <Link :href="route('animals.edit', animal.id)" class="bg-emerald-700 text-white px-3 py-1.5 md:px-4 md:py-2 rounded-xl text-xs md:text-sm font-bold hover:bg-emerald-800 transition shadow-sm shrink-0 whitespace-nowrap">
                    {{ __('Edit') }} <span class="hidden sm:inline">{{ __('Animals') }}</span>
                </Link>
             </div>
        </template>

        <div class="flex flex-col md:flex-row gap-6 mt-2">
            <!-- Left Pane: Profile Summary -->
            <div class="w-full md:w-1/3">
                <Card class="shadow-sm border-earth-200">
                    <div class="p-6 flex flex-col items-center border-b border-earth-100 bg-earth-50 rounded-t-lg">
                        <img :src="animal.photo_path || `https://ui-avatars.com/api/?name=${animal.name_or_tag}&background=random`" alt="Photo" class="h-24 w-24 rounded-full border-4 border-white shadow-md mb-4" />
                        <h2 class="text-xl font-bold text-gray-900">{{ animal.name_or_tag }}</h2>
                        <div class="flex items-center space-x-2 mt-2">
                            <span class="bg-farm-100 text-farm-800 text-xs px-3 py-1 rounded-full font-bold border border-farm-200 shadow-sm capitalize">{{ animal.status }}</span>
                            <span v-if="animal.inbreeding_risk" class="bg-red-100 text-red-800 text-[10px] px-2 py-1 rounded-full font-black border border-red-200 shadow-sm uppercase animate-pulse">
                                <i class="fas fa-triangle-exclamation mr-1"></i>
                                {{ __('Inbred') }}
                            </span>
                        </div>
                    </div>
                    <div class="p-6 space-y-5">
                        <div class="pb-4 border-b border-earth-100">
                            <p class="text-xs font-bold text-gray-500 uppercase tracking-wide">{{ __('Registration / ID') }}</p>
                            <p class="text-sm font-semibold text-gray-900 mt-1">{{ animal.registration_number || 'N/A' }}</p>
                        </div>
                        <div class="grid grid-cols-2 gap-y-5 gap-x-4">
                            <div>
                                <p class="text-xs font-bold text-gray-500 uppercase tracking-wide">{{ __('Species') }}</p>
                                <p class="text-sm font-semibold text-gray-900 mt-1">{{ animal.species }}</p>
                            </div>
                            <div>
                                <p class="text-xs font-bold text-gray-500 uppercase tracking-wide">{{ __('Breed') }}</p>
                                <p class="text-sm font-semibold text-gray-900 mt-1">{{ animal.breed || __('Not Recorded') }}</p>
                            </div>
                            <div>
                                <p class="text-xs font-bold text-gray-500 uppercase tracking-wide">{{ __('Current Weight') }}</p>
                                <p class="text-sm font-semibold text-gray-900 mt-1">{{ animal.weight }} kg</p>
                            </div>
                            <div>
                                <p class="text-xs font-bold text-gray-500 uppercase tracking-wide">{{ __('Sex') }}</p>
                                <p class="text-sm font-semibold text-gray-900 mt-1 capitalize">{{ animal.sex }}</p>
                            </div>
                            <div>
                                <p class="text-xs font-bold text-gray-500 uppercase tracking-wide">{{ __('Birth Date') }}</p>
                                <p class="text-sm font-semibold text-gray-900 mt-1 uppercase">
                                    {{ animal.birth_date ? new Date(animal.birth_date).toLocaleDateString('id-ID') : '-' }}
                                </p>
                            </div>
                            <div>
                                <p class="text-xs font-bold text-gray-500 uppercase tracking-wide">{{ __('Entry Date') }}</p>
                                <p class="text-sm font-semibold text-gray-900 mt-1 uppercase">
                                    {{ animal.entry_date ? new Date(animal.entry_date).toLocaleDateString('id-ID') : '-' }}
                                </p>
                            </div>
                        </div>
                        <div class="pt-4 border-t border-earth-100">
                            <p class="text-xs font-bold text-gray-500 uppercase tracking-wide">{{ __('Purpose') }}</p>
                            <p class="text-sm font-semibold text-gray-900 mt-1 capitalize">{{ animal.purpose }}</p>
                        </div>
                        <div class="pt-4 border-t border-earth-100">
                            <p class="text-xs font-bold text-gray-500 uppercase tracking-wide">{{ __('Condition / Notes') }}</p>
                            <p class="text-sm text-gray-700 mt-2 italic">{{ animal.condition_notes || __('No condition notes recorded.') }}</p>
                        </div>
                        
                        <!-- QR Code Section -->
                        <div class="pt-6 border-t border-earth-100 flex flex-col items-center">
                            <p class="text-xs font-bold text-gray-500 uppercase tracking-wide w-full mb-4">{{ __('Animal QR Code') }}</p>
                            <div class="bg-white p-3 rounded-2xl border border-earth-100 shadow-sm mb-4">
                                <img :src="`data:image/svg+xml;base64,${qrCode}`" alt="QR Code" class="h-32 w-32" />
                            </div>
                            <a :href="`data:image/svg+xml;base64,${qrCode}`" :download="`qr-${animal.name_or_tag}.svg`" class="text-xs font-bold text-emerald-700 hover:text-emerald-900 transition flex items-center">
                                <i class="fas fa-download mr-1.5"></i>
                                {{ __('Download QR Code') }}
                            </a>
                        </div>
                    </div>
                </Card>
            </div>

            <!-- Right Pane: Tabs -->
            <div class="w-full md:w-2/3">
                <Card class="shadow-sm border-earth-200 h-full flex flex-col">
                    <div class="border-b border-earth-200 bg-white rounded-t-lg shrink-0 overflow-x-auto">
                        <nav class="-mb-px flex space-x-8 px-6" aria-label="Tabs">
                            <button v-for="tab in tabs" :key="tab.id" @click="activeTab = tab.id"
                                :class="[activeTab === tab.id ? 'border-farm-500 text-farm-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300', 'whitespace-nowrap py-4 px-1 border-b-2 font-bold text-sm transition-colors']">
                                {{ tab.name }}
                            </button>
                        </nav>
                    </div>
                    
                    <div class="p-6 flex-1 bg-white rounded-b-lg">
                        <div v-if="activeTab === 'weights'">
                            <div class="flex justify-between items-center mb-6">
                                <h3 class="text-lg font-bold text-gray-900">{{ __('Weight History') }}</h3>
                                <p class="text-sm text-gray-500">{{ __('Initial Weight') }}: {{ animal.initial_weight || '-' }} kg</p>
                            </div>
                            
                            <!-- Add Weight Form -->
                            <form @submit.prevent="submitWeight" class="mb-8 p-4 bg-earth-50 rounded-xl border border-earth-200 grid grid-cols-1 md:grid-cols-3 gap-4 items-end">
                                <div>
                                    <label class="block text-xs font-bold text-gray-700 uppercase mb-1">{{ __('New Weight (kg)') }}</label>
                                    <input v-model="weightForm.weight" type="number" step="0.1" required class="w-full border-gray-300 rounded-lg text-sm" />
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-gray-700 uppercase mb-1">{{ __('Date') }}</label>
                                    <input v-model="weightForm.measured_at" type="date" required class="w-full border-gray-300 rounded-lg text-sm" />
                                </div>
                                <button type="submit" :disabled="weightForm.processing" class="h-[38px] bg-farm-600 text-white rounded-lg text-xs font-bold hover:bg-farm-700 transition">
                                    {{ __('Record Weight') }}
                                </button>
                            </form>

                            <div v-if="animal.weights && animal.weights.length > 0" class="overflow-x-auto">
                                <table class="min-w-full divide-y divide-earth-200">
                                    <thead class="bg-gray-50">
                                        <tr>
                                            <th class="px-4 py-3 text-left text-xs font-bold text-gray-500 uppercase">{{ __('Date') }}</th>
                                            <th class="px-4 py-3 text-left text-xs font-bold text-gray-500 uppercase">{{ __('Weight') }}</th>
                                            <th class="px-4 py-3 text-left text-xs font-bold text-gray-500 uppercase">{{ __('Change') }}</th>
                                            <th class="px-4 py-3 text-left text-xs font-bold text-gray-500 uppercase">{{ __('Notes') }}</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-earth-100">
                                        <tr v-for="(w, index) in animal.weights" :key="w.id">
                                            <td class="px-4 py-3 text-sm text-gray-900">{{ new Date(w.measured_at).toLocaleDateString('id-ID') }}</td>
                                            <td class="px-4 py-3 text-sm font-bold text-gray-900">{{ w.weight }} kg</td>
                                            <td class="px-4 py-3 text-sm text-gray-600">
                                                <span v-if="index > 0" :class="w.weight - animal.weights[index-1].weight >= 0 ? 'text-green-600' : 'text-red-600'">
                                                    {{ (w.weight - animal.weights[index-1].weight).toFixed(2) }}
                                                </span>
                                                <span v-else>-</span>
                                            </td>
                                            <td class="px-4 py-3 text-xs text-gray-500">{{ w.notes }}</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            <div v-else class="flex flex-col items-center justify-center text-sm font-medium text-gray-500 bg-earth-50 py-12 px-6 rounded-xl border border-dashed border-earth-300">
                                {{ __('No weight records found.') }}
                            </div>
                        </div>

                        <div v-if="activeTab === 'pedigree'">
                            <h3 class="text-lg font-bold text-gray-900 mb-6">{{ __('Animal Pedigree (Silsilah)') }}</h3>
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                                <div class="space-y-4">
                                    <h4 class="text-sm font-bold text-gray-500 uppercase tracking-widest">{{ __('Parents') }}</h4>
                                    <div class="p-4 bg-earth-50 rounded-2xl border border-earth-200">
                                        <div class="flex items-center justify-between mb-4 pb-2 border-b border-earth-100">
                                            <span class="text-xs font-bold text-gray-500 uppercase">{{ __('Sire (Bapak)') }}</span>
                                            <Link v-if="animal.sire" :href="route('animals.show', animal.sire.id)" class="text-sm font-bold text-emerald-700 hover:underline">
                                                {{ animal.sire.name_or_tag }}
                                            </Link>
                                            <span v-else class="text-sm text-gray-400 italic">{{ __('Not Recorded') }}</span>
                                        </div>
                                        <div class="flex items-center justify-between">
                                            <span class="text-xs font-bold text-gray-500 uppercase">{{ __('Dam (Induk)') }}</span>
                                            <Link v-if="animal.dam" :href="route('animals.show', animal.dam.id)" class="text-sm font-bold text-emerald-700 hover:underline">
                                                {{ animal.dam.name_or_tag }}
                                            </Link>
                                            <span v-else class="text-sm text-gray-400 italic">{{ __('Not Recorded') }}</span>
                                        </div>
                                    </div>
                                </div>

                                <div class="space-y-4">
                                    <h4 class="text-sm font-bold text-gray-500 uppercase tracking-widest">{{ __('Offspring (Anak)') }}</h4>
                                    <div v-if="animal.offspring && animal.offspring.length > 0" class="space-y-2">
                                        <div v-for="child in animal.offspring" :key="child.id" class="p-3 bg-white border border-earth-100 rounded-xl flex items-center justify-between shadow-sm">
                                            <Link :href="route('animals.show', child.id)" class="text-sm font-bold text-gray-900 hover:text-emerald-700">
                                                {{ child.name_or_tag }}
                                            </Link>
                                            <span class="text-xs text-gray-500 bg-earth-50 px-2 py-0.5 rounded-full">{{ child.species }}</span>
                                        </div>
                                    </div>
                                    <div v-else class="p-8 bg-earth-50 rounded-2xl border border-dashed border-earth-200 flex flex-col items-center justify-center text-gray-400">
                                        <i class="fas fa-baby text-2xl mb-2"></i>
                                        <p class="text-xs">{{ __('No offspring recorded.') }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div v-if="activeTab === 'fattening'">
                            <div class="flex justify-between items-center mb-6">
                                <h3 class="text-lg font-bold text-gray-900">{{ __('Fattening Progress') }}</h3>
                                <div class="bg-emerald-100 text-emerald-800 px-4 py-2 rounded-xl border border-emerald-200">
                                    <p class="text-[10px] font-black uppercase tracking-widest">{{ __('Daily Gain (ADG)') }}</p>
                                    <p class="text-xl font-black">{{ animal.average_daily_gain }} <span class="text-xs font-bold">kg/day</span></p>
                                </div>
                            </div>
                            
                            <div class="p-8 bg-earth-50 rounded-2xl border border-earth-200 mb-6">
                                <h4 class="text-xs font-black text-gray-400 uppercase tracking-widest mb-4">{{ __('Growth Summary') }}</h4>
                                <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                                    <div class="p-3 bg-white rounded-xl shadow-sm border border-earth-100 text-center">
                                        <p class="text-[10px] font-bold text-gray-400 uppercase">{{ __('Initial') }}</p>
                                        <p class="text-lg font-bold text-gray-900">{{ animal.initial_weight || '-' }} <span class="text-[10px]">kg</span></p>
                                    </div>
                                    <div class="p-3 bg-white rounded-xl shadow-sm border border-earth-100 text-center">
                                        <p class="text-[10px] font-bold text-gray-400 uppercase">{{ __('Current') }}</p>
                                        <p class="text-lg font-bold text-gray-900">{{ animal.weight }} <span class="text-[10px]">kg</span></p>
                                    </div>
                                    <div class="p-3 bg-white rounded-xl shadow-sm border border-earth-100 text-center">
                                        <p class="text-[10px] font-bold text-gray-400 uppercase">{{ __('Total Gain') }}</p>
                                        <p class="text-lg font-bold text-emerald-600">{{ (animal.weight - (animal.initial_weight || 0)).toFixed(2) }} <span class="text-[10px]">kg</span></p>
                                    </div>
                                    <div class="p-3 bg-white rounded-xl shadow-sm border border-earth-100 text-center">
                                        <p class="text-[10px] font-bold text-gray-400 uppercase">{{ __('Days on Feed') }}</p>
                                        <p class="text-lg font-bold text-gray-900">
                                            {{ animal.entry_date ? Math.floor((new Date() - new Date(animal.entry_date)) / (1000 * 60 * 60 * 24)) : '-' }}
                                        </p>
                                    </div>
                                </div>
                            </div>
                            
                            <p class="text-center text-xs text-gray-400 italic">
                                {{ __('Note: ADG is calculated based on the two most recent weight records.') }}
                            </p>
                        </div>

                        <div v-if="activeTab === 'milking'">
                            <h3 class="text-lg font-bold text-gray-900 mb-6">{{ __('Milk Production History') }}</h3>
                            
                            <!-- Add Milking Record Form -->
                            <form @submit.prevent="submitProduction" class="mb-8 p-4 bg-earth-50 rounded-xl border border-earth-200 grid grid-cols-1 md:grid-cols-4 gap-4 items-end">
                                <div>
                                    <label class="block text-xs font-bold text-gray-700 uppercase mb-1">{{ __('Quantity') }}</label>
                                    <input v-model="productionForm.quantity" type="number" step="0.01" required class="w-full border-gray-300 rounded-lg text-sm" />
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-gray-700 uppercase mb-1">{{ __('Unit') }}</label>
                                    <select v-model="productionForm.unit" class="w-full border-gray-300 rounded-lg text-sm">
                                        <option value="Liters">Liters</option>
                                        <option value="kg">kg</option>
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-gray-700 uppercase mb-1">{{ __('Date') }}</label>
                                    <input v-model="productionForm.date" type="date" required class="w-full border-gray-300 rounded-lg text-sm" />
                                </div>
                                <button type="submit" :disabled="productionForm.processing" class="h-[38px] bg-emerald-600 text-white rounded-lg text-xs font-bold hover:bg-emerald-700 transition">
                                    {{ __('Record Milk') }}
                                </button>
                            </form>

                            <div v-if="animal.productions && animal.productions.filter(i => i.type === 'milk').length > 0" class="space-y-4">
                                <div class="overflow-x-auto">
                                    <table class="min-w-full divide-y divide-earth-200">
                                        <thead class="bg-gray-50">
                                            <tr>
                                                <th class="px-4 py-3 text-left text-xs font-bold text-gray-500 uppercase">{{ __('Date') }}</th>
                                                <th class="px-4 py-3 text-left text-xs font-bold text-gray-500 uppercase">{{ __('Production') }}</th>
                                                <th class="px-4 py-3 text-left text-xs font-bold text-gray-500 uppercase">{{ __('Unit') }}</th>
                                            </tr>
                                        </thead>
                                        <tbody class="divide-y divide-earth-100">
                                            <tr v-for="p in animal.productions.filter(i => i.type === 'milk')" :key="p.id">
                                                <td class="px-4 py-3 text-sm text-gray-900">{{ new Date(p.date).toLocaleDateString('id-ID') }}</td>
                                                <td class="px-4 py-3 text-sm font-bold text-emerald-600 font-mono">{{ p.quantity }}</td>
                                                <td class="px-4 py-3 text-sm text-gray-500 uppercase">{{ p.unit }}</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <div v-else class="flex flex-col items-center justify-center text-sm font-medium text-gray-500 bg-earth-50 py-12 px-6 rounded-xl border border-dashed border-earth-300">
                                <i class="fas fa-glass-water text-3xl text-earth-300 mb-3"></i>
                                {{ __('No milking records found for this animal.') }}
                            </div>
                        </div>

                        <div v-if="activeTab === 'health'">
                            <h3 class="text-lg font-bold text-gray-900 mb-4">{{ __('Health Records') }}</h3>
                            <div class="flex flex-col items-center justify-center text-sm font-medium text-gray-500 bg-earth-50 py-12 px-6 rounded-xl border border-dashed border-earth-300">
                                <i class="fas fa-file-medical text-3xl text-earth-300 mb-3"></i>
                                {{ __('No health records found for this animal.') }}
                            </div>
                        </div>
                        <div v-else-if="activeTab === 'breeding'">
                            <h3 class="text-lg font-bold text-gray-900 mb-4">{{ __('Breeding History') }}</h3>
                            <div class="flex flex-col items-center justify-center text-sm font-medium text-gray-500 bg-earth-50 py-12 px-6 rounded-xl border border-dashed border-earth-300">
                                <i class="fas fa-heart text-3xl text-earth-300 mb-3"></i>
                                {{ __('No breeding history recorded.') }}
                            </div>
                        </div>
                        <div v-else-if="activeTab === 'production'">
                            <h3 class="text-lg font-bold text-gray-900 mb-4">{{ __('Production Logs') }}</h3>
                            <div class="flex flex-col items-center justify-center text-sm font-medium text-gray-500 bg-earth-50 py-12 px-6 rounded-xl border border-dashed border-earth-300">
                                <i class="fas fa-glass-water text-3xl text-earth-300 mb-3"></i>
                                {{ __('No production data available.') }}
                            </div>
                        </div>
                    </div>
                </Card>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
