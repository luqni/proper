<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';
import Card from '@/Components/Card.vue';
import { ref } from 'vue';

const props = defineProps({
    animal: Object
});

const activeTab = ref('weights');

const tabs = [
    { id: 'weights', name: __('Weight Tracking') },
    { id: 'health', name: __('Health Records') },
    { id: 'breeding', name: __('Breeding History') },
    { id: 'production', name: __('Production Logs') },
];

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
                        <span class="mt-2 bg-farm-100 text-farm-800 text-xs px-3 py-1 rounded-full font-bold border border-farm-200 shadow-sm capitalize">{{ animal.status }}</span>
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
                                <p class="text-sm font-semibold text-gray-900 mt-1">{{ animal.breed || '-' }}</p>
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
                            <p class="text-xs font-bold text-gray-500 uppercase tracking-wide">{{ __('Condition / Notes') }}</p>
                            <p class="text-sm text-gray-700 mt-2 italic">{{ animal.condition_notes || __('No condition notes recorded.') }}</p>
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
