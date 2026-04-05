<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';
import { ref } from 'vue';
import LanguageSwitcher from '@/Components/LanguageSwitcher.vue';

const props = defineProps({
    stats: Object,
    farm: Object
});

const isEditingFarmName = ref(false);
const farmForm = useForm({
    name: props.farm.name
});

const coverForm = useForm({
    cover_photo: null
});

const uploadCover = (e) => {
    const file = e.target.files[0];
    if (!file) return;
    
    coverForm.cover_photo = file;
    coverForm.post(route('farms.updateCover', props.farm.id), {
        onSuccess: () => {
             // Success notice
        }
    });
};

const saveFarmName = () => {
    farmForm.put(route('farms.update', props.farm.id), {
        onSuccess: () => isEditingFarmName.value = false,
    });
};

const dashboardCards = [
    { 
        title: 'Farm Map', 
        subtitle: `${props.stats.locations_count} Areas`, 
        iconColor: 'text-amber-600',
        image: 'https://cdn-icons-png.flaticon.com/512/854/854878.png',
        link: route('locations.index')
    },
    { 
        title: 'Tasks', 
        subtitle: `${props.stats.tasks_overdue_count} Overdue`, 
        subtext: `${props.stats.tasks_active_count} Active`,
        iconColor: 'text-red-500',
        image: 'https://cdn-icons-png.flaticon.com/512/2666/2666505.png',
        link: route('tasks.index')
    },
    { 
        title: 'Notes', 
        subtitle: `${props.stats.notes_count} Notes`, 
        image: 'https://cdn-icons-png.flaticon.com/512/3209/3209265.png',
        link: route('notes.index')
    },
    { 
        title: 'Pakan', 
        subtitle: `${props.stats.feeds_count} Ingred.`, 
        image: 'https://cdn-icons-png.flaticon.com/512/1047/1047711.png',
        link: route('feeds.index')
    },
    { 
        title: 'Calendar', 
        subtitle: `${props.stats.events_today_count} Today`, 
        image: 'https://cdn-icons-png.flaticon.com/512/3652/3652191.png',
        link: route('events.index')
    },
    { 
        title: 'Feedings', 
        subtitle: `${props.stats.feedings_count} Records`, 
        image: 'https://cdn-icons-png.flaticon.com/512/3047/3047928.png',
        link: route('feedings.index')
    },
    { 
        title: 'Health', 
        subtitle: `${props.stats.health_records_count} Vaccines`, 
        image: 'https://cdn-icons-png.flaticon.com/512/2966/2966327.png',
        link: route('health.index')
    },
];

// Avatars are now derived from farm.users (team)
const vFocus = {
    mounted: (el) => el.focus()
};
</script>

<template>
    <Head title="Dashboard" />

    <AuthenticatedLayout hideHeader>
        <div class="relative -mt-4 -mx-4 sm:-mx-6 lg:-mx-8">
            <!-- Hero Section -->
            <div class="relative h-80 sm:h-96 w-full overflow-hidden rounded-b-[3rem] shadow-2xl">
                <img :src="farm.cover_photo ? `/storage/${farm.cover_photo}` : '/images/farm-default-banner.jpg'" alt="Farm Hero" class="w-full h-full object-cover grayscale-[0.1] brightness-[0.85]" />
                
                <!-- Top Floating Bar -->
                <div class="absolute top-6 left-4 right-4 flex items-center justify-between">
                    <div class="bg-white/90 backdrop-blur-md rounded-full px-5 py-2.5 flex items-center shadow-lg border border-white/20 w-full max-w-sm">
                        <div class="bg-farm-100 p-1.5 rounded-full mr-3 text-farm-600">
                            <i class="fas fa-location-dot"></i>
                        </div>
                        <div @click="isEditingFarmName = true" class="flex-1 flex items-center cursor-pointer group/name min-w-0">
                            <input v-if="isEditingFarmName" 
                                v-model="farmForm.name" 
                                @blur="saveFarmName" 
                                @keyup.enter="saveFarmName"
                                v-focus
                                class="bg-transparent border-none p-0 font-bold text-gray-800 focus:ring-0 w-full" 
                            />
                            <span v-else class="font-bold text-gray-800 truncate">{{ farm.name }}</span>
                            <i v-if="!isEditingFarmName" class="fas fa-pen ml-2 text-[10px] text-gray-400 opacity-0 group-hover/name:opacity-100 transition"></i>
                        </div>
                    </div>
                    <div class="ml-3 flex items-center">
                        <LanguageSwitcher variant="white" />
                        <img :src="`https://ui-avatars.com/api/?name=${$page.props.auth.user.name}&background=ffcc00`" class="h-10 w-10 rounded-full border-2 border-white shadow-lg ml-3 hidden sm:block" />
                    </div>
                </div>

                <!-- Overlays bottom -->
                <div class="absolute bottom-8 left-6 right-6 flex items-end justify-between">
                    <!-- Avatars -->
                    <div class="flex items-center">
                        <Link :href="route('teams.index')" class="bg-farm-600 text-white p-2.5 rounded-full z-10 shadow-lg border-2 border-white cursor-pointer hover:scale-110 transition flex items-center justify-center">
                            <i class="fas fa-plus"></i>
                        </Link>
                        <div class="flex -ml-3 items-center">
                            <img v-for="(member, i) in farm.users.slice(0, 5)" :key="member.id" 
                                 :src="`https://ui-avatars.com/api/?name=${member.name}&background=random&color=fff`" 
                                 :title="member.name"
                                 :class="['h-10 w-10 rounded-full border-2 border-white shadow-md -ml-2 hover:z-20 transition-all cursor-help', i === 0 ? 'z-0' : '']" 
                                 :style="{ zIndex: 10 - i }" />
                            <div v-if="farm.users.length > 5" class="h-10 w-10 rounded-full border-2 border-white shadow-md -ml-2 bg-gray-200 flex items-center justify-center text-[10px] font-bold text-gray-600 z-0">
                                +{{ farm.users.length - 5 }}
                            </div>
                        </div>
                    </div>
                    
                    <!-- Camera Button -->
                    <div v-if="$page.props.auth.user.role === 'owner'" class="relative">
                        <input type="file" @change="uploadCover" class="hidden" ref="coverInput" accept="image/*" />
                        <div @click="$refs.coverInput.click()" 
                            class="bg-black/30 backdrop-blur-md p-3 rounded-2xl text-white shadow-lg border border-white/20 cursor-pointer hover:bg-black/50 transition">
                            <i class="fas fa-camera text-xl" :class="{'animate-pulse': coverForm.processing}"></i>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Content Grid -->
            <div class="px-4 py-8 sm:px-6 lg:px-8">
                <div class="grid grid-cols-2 gap-4 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4">
                    <Link v-for="card in dashboardCards" :key="card.title" :href="card.link" 
                        class="bg-white p-5 rounded-[2rem] shadow-sm border border-earth-100 hover:shadow-md transition-all group flex flex-col justify-between aspect-square cursor-pointer">
                        <div class="space-y-1">
                            <h3 class="text-lg font-extrabold text-gray-900 leading-tight">{{ __(card.title) }}</h3>
                            <p class="text-xs font-bold text-gray-400" :class="card.iconColor || ''">
                                {{ card.subtitle.split(' ')[0] }} {{ __(card.subtitle.split(' ')[1]) }}
                            </p>
                            <p v-if="card.subtext" class="text-xs font-bold text-gray-600">
                                {{ card.subtext.split(' ')[0] }} {{ __(card.subtext.split(' ')[1]) }}
                            </p>
                        </div>
                        <div class="flex justify-end mt-auto">
                            <img :src="card.image" :alt="card.title" class="h-16 w-16 opacity-90 group-hover:scale-110 transition duration-300" />
                        </div>
                    </Link>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

<style scoped>
.rounded-b-\[3rem\] {
    border-bottom-left-radius: 3rem;
    border-bottom-right-radius: 3rem;
}
.rounded-\[2rem\] {
    border-radius: 2rem;
}
</style>
