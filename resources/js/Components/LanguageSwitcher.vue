<script setup>
import { router } from '@inertiajs/vue3';

const props = defineProps({
    variant: {
        type: String,
        default: 'default' // 'default' (green bg) or 'outline' or 'white'
    }
});

const switchLanguage = (locale) => {
    router.post('/locale', { locale }, {
        preserveScroll: true,
    });
};
</script>

<template>
    <div :class="[
        variant === 'white' ? 'bg-white/20 backdrop-blur-md border-white/20' : 'bg-emerald-50 border-emerald-100/50',
        'flex items-center rounded-xl p-1 border shadow-sm'
    ]">
        <button @click="switchLanguage('id')" 
            :class="[
                $page.props.locale === 'id' 
                    ? (variant === 'white' ? 'bg-white text-emerald-900 shadow-md' : 'bg-white text-emerald-700 shadow-sm') 
                    : (variant === 'white' ? 'text-white/60 hover:text-white' : 'text-emerald-900/30 hover:text-emerald-700'),
                'px-2.5 py-1.5 text-[10px] font-black rounded-lg transition-all uppercase leading-none'
            ]">
            ID
        </button>
        <button @click="switchLanguage('en')" 
            :class="[
                $page.props.locale === 'en' 
                    ? (variant === 'white' ? 'bg-white text-emerald-900 shadow-md' : 'bg-white text-emerald-700 shadow-sm') 
                    : (variant === 'white' ? 'text-white/60 hover:text-white' : 'text-emerald-900/30 hover:text-emerald-700'),
                'px-2.5 py-1.5 text-[10px] font-black rounded-lg transition-all uppercase leading-none'
            ]">
            EN
        </button>
    </div>
</template>
