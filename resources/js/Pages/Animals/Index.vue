<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link } from '@inertiajs/vue3';
import { ref, computed } from 'vue';

const props = defineProps({
    groupedAnimals: Object,
    speciesList: Array,
    totalCount: Number
});

const searchQuery = ref('');
const selectedSpecies = ref('');

const filteredGrouped = computed(() => {
    let result = {};
    
    // Filter by species if selected
    const speciesToIterate = selectedSpecies.value 
        ? [selectedSpecies.value] 
        : Object.keys(props.groupedAnimals);

    speciesToIterate.forEach(species => {
        const animals = props.groupedAnimals[species] || [];
        const filtered = animals.filter(animal => {
            const matchesSearch = animal.name_or_tag.toLowerCase().includes(searchQuery.value.toLowerCase()) ||
                                (animal.breed && animal.breed.toLowerCase().includes(searchQuery.value.toLowerCase()));
            return matchesSearch;
        });
        
        if (filtered.length > 0) {
            result[species] = filtered;
        }
    });
    
    return result;
});

const getGenderIcon = (sex) => {
    if (sex === 'male') return '♂';
    if (sex === 'female') return '♀';
    return '⚥';
};

const getSpeciesIcon = (species) => {
    return 'fas fa-cow';
};
</script>

<template>
    <Head :title="__('Animals')" />

    <AuthenticatedLayout hideHeader>
        <div class="relative -mt-4 -mx-4 sm:-mx-6 lg:-mx-8 min-h-screen bg-gray-50 pb-24">
            <!-- Header Section -->
            <div class="bg-emerald-800 text-white px-6 py-6 pb-20 rounded-b-[2.5rem] shadow-lg">
                <div class="flex justify-between items-center max-w-4xl mx-auto">
                    <h1 class="text-3xl font-extrabold tracking-tight">{{ __('Animals') }}</h1>
                    <div class="flex items-center space-x-3">
                        <Link :href="route('animals.create')" class="bg-emerald-700/50 hover:bg-emerald-600 backdrop-blur-md px-4 py-2 rounded-full flex items-center shadow-sm border border-emerald-500/30 transition">
                            <span class="text-lg font-bold mr-1">+</span>
                            <span class="font-bold text-sm">{{ __('New') }}</span>
                        </Link>
                        <button class="p-2 hover:bg-emerald-700 rounded-full transition">
                            <i class="fas fa-ellipsis-vertical"></i>
                        </button>
                    </div>
                </div>
            </div>

            <!-- Search & Filter Bar -->
            <div class="px-6 -mt-10 max-w-4xl mx-auto">
                <div class="bg-white rounded-3xl shadow-xl p-2 flex items-center border border-gray-100">
                    <div class="flex-1 flex items-center px-4 py-2">
                        <i class="fas fa-filter text-gray-400 mr-3"></i>
                        <select v-model="selectedSpecies" class="text-gray-800 font-bold border-none focus:ring-0 bg-transparent py-1 w-full cursor-pointer">
                            <option value="">{{ __('All Species') }}</option>
                            <option v-for="s in speciesList" :key="s" :value="s">{{ s }}</option>
                        </select>
                    </div>
                    <div class="w-px h-8 bg-gray-100"></div>
                    <div class="flex items-center px-4 py-2 flex-grow">
                        <input v-model="searchQuery" type="text" :placeholder="__('Search...')" class="border-none focus:ring-0 w-full text-gray-800 font-medium placeholder-gray-400" />
                        <i class="fas fa-magnifying-glass text-gray-400 ml-2"></i>
                    </div>
                </div>
            </div>

            <!-- Animal Groups -->
            <div class="px-6 py-8 space-y-10 max-w-4xl mx-auto">
                <div v-for="(animals, species) in filteredGrouped" :key="species" class="space-y-4">
                    <div class="flex justify-between items-end px-2">
                        <h2 class="text-xl font-extrabold text-gray-700 flex items-center">
                            <span class="mr-2 text-xl text-emerald-600"><i :class="getSpeciesIcon(species)"></i></span>
                            {{ species }}
                        </h2>
                        <span class="text-sm font-bold text-gray-400">{{ animals.length }} {{ __('animals') }}</span>
                    </div>

                    <div class="space-y-4">
                        <Link v-for="animal in animals" :key="animal.id" :href="route('animals.show', animal.id)" 
                            class="bg-white p-4 rounded-3xl shadow-sm border border-gray-100 flex items-center hover:shadow-md transition group overflow-hidden">
                            <!-- Image Section -->
                            <div class="relative h-20 w-20 flex-shrink-0">
                                <img :src="animal.photo_path || `https://ui-avatars.com/api/?name=${animal.name_or_tag}&background=random`" 
                                     class="h-full w-full object-cover rounded-2xl group-hover:scale-105 transition duration-300" />
                                <div class="absolute -bottom-1 -left-1 bg-black/50 backdrop-blur-sm text-white h-7 w-7 rounded-full flex items-center justify-center border-2 border-white text-sm font-bold">
                                    {{ getGenderIcon(animal.sex) }}
                                </div>
                            </div>

                            <!-- Details Section -->
                            <div class="ml-5 flex-grow">
                                <div class="flex justify-between items-start">
                                    <h3 class="text-lg font-extrabold text-gray-900 leading-tight">{{ animal.name_or_tag }}</h3>
                                </div>
                                <div class="space-y-0.5 mt-1">
                                    <p class="text-xs font-bold text-gray-400 uppercase tracking-tight">
                                        {{ species }}<span v-if="animal.breed">: {{ animal.breed }}</span>
                                    </p>
                                    <p class="text-xs font-bold text-gray-600">{{ __('Age') }}: {{ animal.age_display }}</p>
                                    <div class="flex items-center pt-1">
                                        <div class="bg-gray-100 p-1 rounded-md mr-1.5 flex items-center justify-center h-5 w-5">
                                            <i class="fas fa-location-dot text-[10px] text-gray-500"></i>
                                        </div>
                                        <span class="text-xs font-extrabold text-gray-500">{{ animal.location?.name || __('Unassigned') }}</span>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Arrow -->
                            <div class="ml-2 text-gray-300 group-hover:text-emerald-500 transition">
                                <i class="fas fa-chevron-right text-lg"></i>
                            </div>
                        </Link>
                    </div>
                </div>

                <div v-if="Object.keys(filteredGrouped).length === 0" class="text-center py-24">
                    <div class="bg-gray-100 h-20 w-20 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-magnifying-glass text-3xl text-gray-400"></i>
                    </div>
                    <h3 class="text-lg font-bold text-gray-400">{{ __('No animals found matching your criteria.') }}</h3>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

<style scoped>
.rounded-b-\[2\.5rem\] {
    border-bottom-left-radius: 2.5rem;
    border-bottom-right-radius: 2.5rem;
}
</style>
