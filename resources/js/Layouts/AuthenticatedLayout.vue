<script setup>
import { ref } from 'vue';
import { Link, usePage, useForm } from '@inertiajs/vue3';
import ApplicationLogo from '@/Components/ApplicationLogo.vue';
import Dropdown from '@/Components/Dropdown.vue';
import DropdownLink from '@/Components/DropdownLink.vue';
import OfflineIndicator from '@/Components/OfflineIndicator.vue';
import InstallPrompt from '@/Components/InstallPrompt.vue';

const navItems = [
    { name: 'Dashboard', route: 'dashboard', icon: 'fas fa-house' },
    { name: 'Animals', route: 'animals.index', icon: 'fas fa-cow', pattern: 'animals.*' }, 
    { name: 'Finances', route: 'finances.index', icon: 'fas fa-wallet', pattern: 'finances.*' },
    { name: 'Team', route: 'teams.index', icon: 'fas fa-users', pattern: 'teams.*' },
];

const props = defineProps({
    hideHeader: {
        type: Boolean,
        default: false
    },
    hideSidebar: {
        type: Boolean,
        default: false
    }
});

const isActive = (item) => {
    if (item.pattern) {
        return route().current(item.pattern);
    }
    return route().current(item.route);
};

const switchLanguage = (locale) => {
    const form = useForm({ locale });
    form.post(route('locale.update'), {
        preserveScroll: true,
        onSuccess: () => window.location.reload(),
    });
};
</script>

<template>
    <div class="min-h-screen bg-earth-50 flex pb-16 md:pb-0">
        <!-- Desktop Sidebar Navigation -->
        <nav v-if="!hideSidebar" class="hidden md:flex flex-col inset-y-0 left-0 z-50 w-64 bg-emerald-900 text-white flex-shrink-0 shadow-lg">
            <div class="flex items-center justify-between h-16 px-4 bg-emerald-950 border-b border-emerald-800">
                <Link :href="route('dashboard')" class="flex items-center space-x-2.5">
                    <div class="h-9 w-9 p-1 bg-white rounded-lg flex items-center justify-center">
                        <ApplicationLogo />
                    </div>
                    <span class="text-lg font-black tracking-tight text-white">Pintar<span class="text-emerald-400">Ternak</span></span>
                </Link>
            </div>
            
            <div class="px-3 py-4 space-y-2 flex-1 overflow-y-auto bg-emerald-900">
                <Link v-for="item in navItems" :key="item.name" :href="route(item.route)" :class="[isActive(item) ? 'bg-emerald-800 text-white shadow-sm' : 'text-emerald-200 hover:bg-emerald-800 hover:text-white', 'group flex items-center px-3 py-2.5 text-sm font-medium rounded-xl transition-colors']">
                    <i :class="[item.icon, isActive(item) ? 'text-white' : 'text-emerald-400 group-hover:text-white', 'mr-3 text-lg w-6 flex-shrink-0 text-center']"></i>
                    {{ __(item.name) }}
                </Link>
            </div>
            
            <!-- Sidebar Footer (User Logout) -->
            <div class="bg-emerald-950 px-4 py-4 flex items-center shrink-0 border-t border-emerald-800">
                 <div class="flex-shrink-0">
                   <div class="h-10 w-10 rounded-full bg-emerald-700 flex items-center justify-center text-white font-bold border-2 border-emerald-600 shadow-sm">{{ $page.props.auth.user.name.charAt(0) }}</div>
                 </div>
                 <div class="ml-3 truncate">
                   <p class="text-sm font-medium text-white truncate">{{ $page.props.auth.user.name }}</p>
                   <Link :href="route('logout')" method="post" as="button" class="text-xs font-medium text-emerald-400 hover:text-white transition ease-in-out duration-150 inline-block mt-0.5">{{ __('Log Out') }}</Link>
                 </div>
            </div>
        </nav>

        <!-- Main Content Wrapper -->
        <div class="flex-1 flex flex-col min-w-0 bg-emerald-50/30">
            <!-- Top Header -->
            <header v-if="!hideHeader" class="bg-white/80 backdrop-blur-md shadow-sm border-b border-emerald-100 shrink-0 sticky top-0 z-30">
                <div class="flex items-center justify-between min-h-[4rem] py-2 px-4 md:px-8">
                    <div class="flex items-center min-w-0 flex-1 mr-4">
                        <!-- Mobile Logo (visible only on mobile) -->
                        <div class="md:hidden mr-3">
                            <Link :href="route('dashboard')" class="h-9 w-9 p-1 bg-white rounded-lg shadow-sm border border-emerald-100 flex items-center justify-center">
                                <ApplicationLogo />
                            </Link>
                        </div>
                        <h1 class="text-xl font-black text-emerald-950 tracking-tight w-full truncate"><slot name="header"></slot></h1>
                    </div>
                    <div class="flex items-center space-x-4">
                        <!-- Language Switcher -->
                        <div class="flex items-center bg-earth-100 rounded-lg p-1 border border-earth-200">
                            <button @click="switchLanguage('id')" 
                                :class="[$page.props.locale === 'id' ? 'bg-white text-farm-700 shadow-sm' : 'text-gray-500 hover:text-gray-700']"
                                class="px-2 py-1 text-[10px] font-black rounded-md transition-all uppercase leading-none">
                                ID
                            </button>
                            <button @click="switchLanguage('en')" 
                                :class="[$page.props.locale === 'en' ? 'bg-white text-farm-700 shadow-sm' : 'text-gray-500 hover:text-gray-700']"
                                class="px-2 py-1 text-[10px] font-black rounded-md transition-all uppercase leading-none">
                                EN
                            </button>
                        </div>
                        
                        <OfflineIndicator />
                        <Dropdown align="right" width="48">
                            <template #trigger>
                                <button class="flex items-center text-sm font-medium text-emerald-900/60 hover:text-emerald-900 focus:outline-none transition py-1 px-1.5 rounded-xl hover:bg-emerald-50 border border-transparent hover:border-emerald-100">
                                    <div class="h-8 w-8 rounded-full bg-emerald-100 flex items-center justify-center text-emerald-700 border border-emerald-200 font-bold overflow-hidden shadow-sm">
                                        {{ $page.props.auth.user.name.charAt(0) }}
                                    </div>
                                    <div class="ml-2 hidden sm:block font-bold">{{ $page.props.auth.user.name }}</div>
                                    <i class="fas fa-chevron-down ml-1.5 text-[10px] text-emerald-300"></i>
                                </button>
                            </template>
                            <template #content>
                                <div class="p-1 px-2 mb-1 bg-emerald-50 rounded-lg mx-1 mt-1">
                                    <p class="text-[10px] font-black text-emerald-800 uppercase tracking-widest">{{ __('Active Account') }}</p>
                                    <p class="text-xs font-bold text-emerald-600 truncate">{{ $page.props.auth.user.email }}</p>
                                </div>
                                <DropdownLink :href="route('profile.edit')">
                                    <i class="fas fa-user-circle mr-2 text-emerald-500"></i>
                                    {{ __('Profile') }}
                                </DropdownLink>
                                <div class="border-t border-emerald-50 my-1"></div>
                                <DropdownLink :href="route('logout')" method="post" as="button" class="text-rose-600">
                                    <i class="fas fa-sign-out-alt mr-2 text-rose-500"></i>
                                    {{ __('Log Out') }}
                                </DropdownLink>
                            </template>
                        </Dropdown>
                    </div>
                </div>
            </header>

            <!-- Main view container -->
            <main class="flex-1 overflow-y-auto p-4 sm:p-6 lg:p-8">
                <div class="max-w-7xl mx-auto space-y-6">
                    <slot />
                </div>
            </main>
        </div>

        <!-- Mobile Bottom Navigation -->
        <div class="md:hidden fixed bottom-0 left-0 right-0 z-40 bg-white/95 backdrop-blur-md border-t border-emerald-100 shadow-[0_-8px_30px_rgba(6,78,59,0.08)] flex justify-around items-center px-4 py-3 pb-safe w-full rounded-t-[2.5rem]">
            <Link v-for="item in navItems" :key="item.name" :href="route(item.route)" :class="[isActive(item) ? 'text-emerald-700 bg-emerald-50 scale-110 shadow-sm' : 'text-emerald-900/40 hover:text-emerald-900', 'flex flex-col items-center justify-center p-3 rounded-2xl transition-all duration-300 origin-bottom']">
                <i :class="[item.icon, isActive(item) ? 'text-emerald-700' : 'text-emerald-900/30', 'text-xl mb-1 transition-colors']"></i>
                <span class="text-[9px] font-black tracking-widest uppercase transition-colors" :class="[isActive(item) ? 'text-emerald-700' : 'text-emerald-950/40']">{{ __(item.name) }}</span>
            </Link>
        </div>

        <InstallPrompt />
    </div>
</template>
