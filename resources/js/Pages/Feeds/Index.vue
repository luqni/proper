<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link } from '@inertiajs/vue3';
import { ref, computed } from 'vue';
import LanguageSwitcher from '@/Components/LanguageSwitcher.vue';

const props = defineProps({
    feeds: Array
});

const searchQuery = ref('');

const filteredFeeds = computed(() => {
    return props.feeds.filter(feed => {
        return feed.name.toLowerCase().includes(searchQuery.value.toLowerCase()) ||
               feed.type.toLowerCase().includes(searchQuery.value.toLowerCase());
    });
});

const getTypeIcon = (type) => {
    switch (type.toLowerCase()) {
        case 'energi':
        case 'energy': return 'fas fa-bolt text-yellow-500';
        case 'protein': return 'fas fa-dna text-red-500';
        case 'mineral': return 'fas fa-gem text-blue-500';
        default: return 'fas fa-leaf text-emerald-500';
    }
};

const formatPrice = (price) => {
    return new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', minimumFractionDigits: 0 }).format(price);
};
</script>

<template>
    <Head :title="__('Pakan')" />

    <AuthenticatedLayout hideHeader>
        <div class="relative -mt-4 -mx-4 sm:-mx-6 lg:-mx-8 min-h-screen bg-gray-50 pb-24">
            <!-- Header Section -->
            <div class="bg-orange-600 text-white px-6 py-6 pb-20 rounded-b-[2.5rem] shadow-lg">
                <div class="flex justify-between items-center max-w-4xl mx-auto">
                    <h1 class="text-3xl font-extrabold tracking-tight">{{ __('Pakan') }}</h1>
                    <div class="flex items-center space-x-3">
                        <LanguageSwitcher variant="white" />
                        <Link :href="route('feeds.create')" class="bg-orange-500/50 hover:bg-orange-400 backdrop-blur-md px-4 py-2 rounded-full flex items-center shadow-sm border border-orange-400/30 transition">
                            <span class="text-lg font-bold mr-1">+</span>
                            <span class="font-bold text-sm">{{ __('New') }}</span>
                        </Link>
                    </div>
                </div>
            </div>

            <!-- Search Bar -->
            <div class="px-6 -mt-10 max-w-4xl mx-auto">
                <div class="bg-white rounded-3xl shadow-xl p-2 flex items-center border border-gray-100">
                    <div class="flex items-center px-4 py-2 flex-grow">
                        <i class="fas fa-magnifying-glass text-gray-400 mr-3"></i>
                        <input v-model="searchQuery" type="text" :placeholder="__('Search...')" class="border-none focus:ring-0 w-full text-gray-800 font-medium placeholder-gray-400" />
                    </div>
                </div>
            </div>

            <!-- Feed List -->
            <div class="px-6 py-8 space-y-4 max-w-4xl mx-auto">
                <div v-if="filteredFeeds.length > 0" class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <Link v-for="feed in filteredFeeds" :key="feed.id" :href="route('feeds.edit', feed.id)" 
                        class="bg-white p-5 rounded-3xl shadow-sm border border-gray-100 block hover:shadow-md transition group">
                        <div class="flex justify-between items-start mb-3">
                            <div class="flex items-center">
                                <div class="h-10 w-10 rounded-2xl bg-orange-50 flex items-center justify-center mr-3">
                                    <i :class="getTypeIcon(feed.type)" class="text-lg"></i>
                                </div>
                                <div>
                                    <h3 class="text-lg font-extrabold text-gray-900 leading-tight">{{ feed.name }}</h3>
                                    <p class="text-xs font-bold text-gray-400 uppercase tracking-wider">{{ feed.type }}</p>
                                </div>
                            </div>
                            <div class="text-right">
                                <p class="text-sm font-black text-orange-600">{{ formatPrice(feed.price_per_kg) }}<span class="text-[10px] text-gray-400 font-bold ml-0.5">/kg</span></p>
                            </div>
                        </div>

                        <!-- Nutrition Grid -->
                        <div class="grid grid-cols-3 gap-2 mt-4 pt-4 border-t border-gray-50">
                            <div class="bg-gray-50 p-2 rounded-2xl text-center">
                                <p class="text-[10px] font-bold text-gray-400 uppercase">PK</p>
                                <p class="text-xs font-black text-gray-700">{{ feed.protein }}%</p>
                            </div>
                            <div class="bg-gray-50 p-2 rounded-2xl text-center">
                                <p class="text-[10px] font-bold text-gray-400 uppercase">Energi</p>
                                <p class="text-xs font-black text-gray-700">{{ feed.tdn }}%</p>
                            </div>
                            <div class="bg-gray-50 p-2 rounded-2xl text-center">
                                <p class="text-[10px] font-bold text-gray-400 uppercase">BK</p>
                                <p class="text-xs font-black text-gray-700">{{ feed.dry_matter }}%</p>
                            </div>
                        </div>
                    </Link>
                </div>

                <div v-else class="text-center py-24">
                    <div class="bg-gray-100 h-20 w-20 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-leaf text-3xl text-gray-400"></i>
                    </div>
                    <h3 class="text-lg font-bold text-gray-400">{{ __('No feed ingredients found.') }}</h3>
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
