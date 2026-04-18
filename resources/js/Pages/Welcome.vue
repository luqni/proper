<script setup>
import { Head, Link } from '@inertiajs/vue3';
import ApplicationLogo from '@/Components/ApplicationLogo.vue';

defineProps({
    canLogin: Boolean,
    canRegister: Boolean,
});
</script>

<template>
    <Head :title="__('Pintar Ternak - Kelola Peternakan Lebih Cerdas')" />

    <div class="min-h-screen bg-emerald-50 selection:bg-emerald-600 selection:text-white overflow-x-hidden">
        <!-- Navigation -->
        <nav class="relative z-50 max-w-7xl mx-auto px-4 sm:px-6 py-4 flex justify-between items-center bg-white/70 backdrop-blur-lg border-b border-emerald-100/50 sticky top-0 shadow-sm">
            <div class="flex items-center space-x-2">
                <div class="h-10 w-10 flex items-center justify-center p-1 bg-white rounded-xl shadow-sm border border-emerald-100">
                    <ApplicationLogo />
                </div>
                <span class="text-xl font-black text-emerald-900 tracking-tight">Pintar<span class="text-emerald-600">Ternak</span></span>
            </div>
            
            <div v-if="canLogin" class="flex items-center space-x-3">
                <Link v-if="$page.props.auth.user" :href="route('dashboard')" class="px-5 py-2 bg-emerald-700 text-white text-sm font-bold rounded-xl hover:bg-emerald-800 transition shadow-lg shadow-emerald-200">
                    {{ __('Dashboard') }}
                </Link>

                <template v-else>
                    <Link :href="route('login')" class="text-emerald-900 text-sm font-bold hover:text-emerald-700 transition">
                        {{ __('Masuk') }}
                    </Link>

                    <Link v-if="canRegister" :href="route('register')" class="px-5 py-2 bg-emerald-700 text-white text-sm font-bold rounded-xl hover:bg-emerald-800 transition shadow-lg shadow-emerald-200">
                        {{ __('Daftar') }}
                    </Link>
                </template>
            </div>
        </nav>

        <!-- Hero Section -->
        <div class="relative pt-12 pb-20 sm:pt-20 sm:pb-32 px-4 sm:px-6">
            <!-- Background Decorative Elements -->
            <div class="absolute top-0 right-0 -mr-20 -mt-20 w-64 h-64 sm:w-96 sm:h-96 bg-emerald-200/50 rounded-full blur-3xl opacity-30"></div>
            <div class="absolute bottom-0 left-0 -ml-20 -mb-20 w-64 h-64 sm:w-80 sm:h-80 bg-emerald-300/30 rounded-full blur-3xl opacity-20"></div>

            <div class="max-w-7xl mx-auto grid lg:grid-cols-2 gap-12 sm:gap-16 items-center">
                <div class="relative z-10 text-center lg:text-left">
                    <div class="inline-flex items-center px-4 py-1.5 rounded-full bg-emerald-100 text-emerald-700 text-[10px] sm:text-xs font-black uppercase tracking-widest mb-6 border border-emerald-200/50 mx-auto lg:mx-0">
                        🚀 {{ __('Solusi Peternakan Modern') }}
                    </div>
                    <h1 class="text-4xl sm:text-5xl lg:text-7xl font-black text-emerald-950 leading-[1.1] mb-6 sm:mb-8">
                        {{ __('Kelola Ternak Anda') }} <br class="hidden sm:block">
                        <span class="text-transparent bg-clip-text bg-gradient-to-r from-emerald-600 to-emerald-800">{{ __('Lebih Cerdas') }}</span>.
                    </h1>
                    <p class="text-lg sm:text-xl text-emerald-800/80 mb-8 sm:mb-10 leading-relaxed font-medium max-w-2xl mx-auto lg:mx-0">
                        {{ __('Pintar Ternak adalah sistem manajemen lengkap untuk peternakan modern. Pantau hewan, keuangan, dan tim Anda dalam satu aplikasi yang mudah digunakan.') }}
                    </p>
                    <div class="flex flex-col sm:flex-row space-y-4 sm:space-y-0 sm:space-x-4 justify-center lg:justify-start">
                        <Link :href="route('register')" class="w-full sm:w-auto px-8 py-4 sm:py-5 bg-emerald-700 text-white font-black text-lg rounded-2xl sm:rounded-3xl hover:bg-emerald-800 transition-all transform hover:-translate-y-1 shadow-xl shadow-emerald-200 flex items-center justify-center">
                            {{ __('Mulai Sekarang') }}
                            <i class="fas fa-arrow-right ml-3 text-sm"></i>
                        </Link>
                        <Link :href="route('login')" class="w-full sm:w-auto px-8 py-4 sm:py-5 bg-white text-emerald-900 font-bold text-lg rounded-2xl sm:rounded-3xl border-2 border-emerald-100 hover:border-emerald-200 transition-all flex items-center justify-center">
                            {{ __('Coba Demo') }}
                        </Link>
                    </div>
                    
                    <!-- Stats - Hide on small mobile, show on sm up -->
                    <div class="mt-12 sm:mt-16 grid grid-cols-3 gap-4 sm:gap-8">
                        <div>
                            <div class="text-2xl sm:text-3xl font-black text-emerald-900">100%</div>
                            <div class="text-[9px] sm:text-xs font-bold text-emerald-700/60 uppercase">{{ __('Digital') }}</div>
                        </div>
                        <div>
                            <div class="text-2xl sm:text-3xl font-black text-emerald-900">24/7</div>
                            <div class="text-[9px] sm:text-xs font-bold text-emerald-700/60 uppercase">{{ __('Monitoring') }}</div>
                        </div>
                        <div>
                            <div class="text-2xl sm:text-3xl font-black text-emerald-900">∞</div>
                            <div class="text-[9px] sm:text-xs font-bold text-emerald-700/60 uppercase">{{ __('Efisien') }}</div>
                        </div>
                    </div>
                </div>

                <div class="relative px-4 sm:px-0">
                    <!-- Dashboard Mockup/Visual - Simplified for mobile -->
                    <div class="relative rounded-[2rem] sm:rounded-[3rem] overflow-hidden shadow-2xl border-4 sm:border-8 border-white bg-emerald-100 transform rotate-2 hover:rotate-0 transition-transform duration-500 max-w-md mx-auto lg:max-w-none">
                        <img src="/images/logo.png" class="w-full opacity-10 absolute inset-0 scale-150 rotate-12" alt="">
                        <div class="relative p-6 sm:p-12 space-y-4 sm:space-y-6">
                            <div class="bg-white/80 backdrop-blur p-4 sm:p-6 rounded-2xl sm:rounded-3xl shadow-sm border border-white/50">
                                <div class="flex items-center space-x-3 mb-3 sm:mb-4">
                                    <div class="h-8 w-8 sm:h-10 sm:w-10 bg-emerald-100 rounded-xl flex items-center justify-center text-emerald-600">
                                        <i class="fas fa-cow"></i>
                                    </div>
                                    <h4 class="font-black text-emerald-900 tracking-tight text-sm sm:text-base">{{ __('Status Kesehatan Ternak') }}</h4>
                                </div>
                                <div class="h-2 sm:h-3 w-full bg-emerald-50 rounded-full overflow-hidden">
                                    <div class="h-full bg-emerald-500 w-[92%] rounded-full shadow-sm"></div>
                                </div>
                                <div class="flex justify-between mt-2 sm:mt-3 text-[10px] font-black text-emerald-700/60 uppercase">
                                    <span>{{ __('Sehat') }}</span>
                                    <span>92%</span>
                                </div>
                            </div>

                            <div class="grid grid-cols-2 gap-3 sm:gap-4">
                                <div class="bg-emerald-600 p-4 sm:p-6 rounded-2xl sm:rounded-3xl shadow-lg border border-emerald-500 text-white">
                                    <div class="text-lg sm:text-2xl font-black mb-1">+25.5jt</div>
                                    <div class="text-[8px] sm:text-xs font-black opacity-80 uppercase tracking-widest">{{ __('Profit Bulan Ini') }}</div>
                                </div>
                                <div class="bg-white/80 backdrop-blur p-4 sm:p-6 rounded-2xl sm:rounded-3xl shadow-sm border border-white/50 flex flex-col justify-center">
                                    <div class="flex -space-x-1 sm:-space-x-2">
                                        <div v-for="i in 3" :key="i" class="h-6 w-6 sm:h-8 sm:w-8 rounded-full bg-emerald-50 border border-white flex items-center justify-center text-[8px] sm:text-[10px] font-black text-emerald-900">
                                            PT{{ i }}
                                        </div>
                                    </div>
                                    <div class="text-[8px] sm:text-xs font-black text-emerald-700/60 mt-2 sm:mt-3 uppercase tracking-widest">{{ __('Tim Aktif') }}</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Features Grid -->
        <div class="max-w-7xl mx-auto px-4 sm:px-6 py-20 sm:py-32 border-t border-emerald-100 bg-white/50">
            <div class="text-center mb-16 sm:mb-20">
                <h2 class="text-3xl sm:text-4xl font-black text-emerald-950 mb-4">{{ __('Kenapa Memilih Pintar Ternak?') }}</h2>
                <p class="text-emerald-800/60 font-bold max-w-2xl mx-auto px-4">
                    {{ __('Segala yang Anda butuhkan untuk memodernisasi alur kerja peternakan Anda dalam satu aplikasi PWA yang canggih.') }}
                </p>
            </div>

            <div class="grid sm:md:grid-cols-2 lg:grid-cols-3 gap-4 sm:gap-8">
                <!-- Feature 1 -->
                <div class="bg-white p-8 sm:p-10 rounded-[2rem] sm:rounded-[3rem] shadow-sm hover:shadow-xl hover:shadow-emerald-100/50 border border-emerald-50 transition duration-300 group">
                    <div class="h-14 w-14 sm:h-16 sm:w-16 bg-emerald-50 rounded-2xl sm:rounded-3xl flex items-center justify-center text-emerald-600 text-xl sm:text-2xl mb-6 sm:mb-8 group-hover:bg-emerald-600 group-hover:text-white transition duration-300">
                        <i class="fas fa-cow"></i>
                    </div>
                    <h3 class="text-xl sm:text-2xl font-black text-emerald-900 mb-3 sm:mb-4">{{ __('Catatan Hewan Digital') }}</h3>
                    <p class="text-emerald-800/70 font-medium leading-relaxed text-sm sm:text-base">
                        {{ __('Pantau ternak secara menyeluruh dari lahir hingga dijual. Monitor berat badan, riwayat medis, dan silsilah dengan mudah.') }}
                    </p>
                </div>

                <!-- Feature 2 -->
                <div class="bg-white p-8 sm:p-10 rounded-[2rem] sm:rounded-[3rem] shadow-sm hover:shadow-xl hover:shadow-emerald-100/50 border border-emerald-50 transition duration-300 group">
                    <div class="h-14 w-14 sm:h-16 sm:w-16 bg-amber-50 rounded-2xl sm:rounded-3xl flex items-center justify-center text-amber-600 text-xl sm:text-2xl mb-6 sm:mb-8 group-hover:bg-amber-500 group-hover:text-white transition duration-300">
                        <i class="fas fa-list-check"></i>
                    </div>
                    <h3 class="text-xl sm:text-2xl font-black text-emerald-900 mb-3 sm:mb-4">{{ __('Manajemen Tugas Pintar') }}</h3>
                    <p class="text-emerald-800/70 font-medium leading-relaxed text-sm sm:text-base">
                        {{ __('Jadwalkan vaksin, pemberian pakan, dan perawatan. Tim Anda mendapatkan pembaruan real-time tentang tugas selanjutnya.') }}
                    </p>
                </div>

                <!-- Feature 3 -->
                <div class="bg-white p-8 sm:p-10 rounded-[2rem] sm:rounded-[3rem] shadow-sm hover:shadow-xl hover:shadow-emerald-100/50 border border-emerald-50 transition duration-300 group">
                    <div class="h-14 w-14 sm:h-16 sm:w-16 bg-blue-50 rounded-2xl sm:rounded-3xl flex items-center justify-center text-blue-600 text-xl sm:text-2xl mb-6 sm:mb-8 group-hover:bg-blue-600 group-hover:text-white transition duration-300">
                        <i class="fas fa-wallet"></i>
                    </div>
                    <h3 class="text-xl sm:text-2xl font-black text-emerald-900 mb-3 sm:mb-4">{{ __('Pelacakan Keuangan') }}</h3>
                    <p class="text-emerald-800/70 font-medium leading-relaxed text-sm sm:text-base">
                        {{ __('Pencatatan pendapatan dan pengeluaran otomatis. Dapatkan wawasan tentang margin laba dan ROI peternakan Anda.') }}
                    </p>
                </div>

                <!-- Feature 4 -->
                <div class="bg-white p-8 sm:p-10 rounded-[2rem] sm:rounded-[3rem] shadow-sm hover:shadow-xl hover:shadow-emerald-100/50 border border-emerald-50 transition duration-300 group">
                    <div class="h-14 w-14 sm:h-16 sm:w-16 bg-purple-50 rounded-2xl sm:rounded-3xl flex items-center justify-center text-purple-600 text-xl sm:text-2xl mb-6 sm:mb-8 group-hover:bg-purple-600 group-hover:text-white transition duration-300">
                        <i class="fas fa-users"></i>
                    </div>
                    <h3 class="text-xl sm:text-2xl font-black text-emerald-900 mb-3 sm:mb-4">{{ __('Kolaborasi Tim') }}</h3>
                    <p class="text-emerald-800/70 font-medium leading-relaxed text-sm sm:text-base">
                        {{ __('Akses berbasis peran untuk pemilik dan staf. Log audit memastikan semua orang bertanggung jawab atas data yang diinput.') }}
                    </p>
                </div>

                <!-- Feature 5 -->
                <div class="bg-white p-8 sm:p-10 rounded-[2rem] sm:rounded-[3rem] shadow-sm hover:shadow-xl hover:shadow-emerald-100/50 border border-emerald-50 transition duration-300 group">
                    <div class="h-14 w-14 sm:h-16 sm:w-16 bg-rose-50 rounded-2xl sm:rounded-3xl flex items-center justify-center text-rose-600 text-xl sm:text-2xl mb-6 sm:mb-8 group-hover:bg-rose-600 group-hover:text-white transition duration-300">
                        <i class="fas fa-map-location-dot"></i>
                    </div>
                    <h3 class="text-xl sm:text-2xl font-black text-emerald-900 mb-3 sm:mb-4">{{ __('Pemetaan Area') }}</h3>
                    <p class="text-emerald-800/70 font-medium leading-relaxed text-sm sm:text-base">
                        {{ __('Kelola kandang, padang rumput, dan area penyimpanan. Peta visual memastikan penggunaan lahan dan aset yang efisien.') }}
                    </p>
                </div>

                <!-- Feature 6 -->
                <div class="bg-white p-8 sm:p-10 rounded-[2rem] sm:rounded-[3rem] shadow-sm hover:shadow-xl hover:shadow-emerald-100/50 border border-emerald-50 transition duration-300 group">
                    <div class="h-14 w-14 sm:h-16 sm:w-16 bg-orange-50 rounded-2xl sm:rounded-3xl flex items-center justify-center text-orange-600 text-xl sm:text-2xl mb-6 sm:mb-8 group-hover:bg-orange-600 group-hover:text-white transition duration-300">
                        <i class="fas fa-signal-slash"></i>
                    </div>
                    <h3 class="text-xl sm:text-2xl font-black text-emerald-900 mb-3 sm:mb-4">{{ __('Akses Offline (PWA)') }}</h3>
                    <p class="text-emerald-800/70 font-medium leading-relaxed text-sm sm:text-base">
                        {{ __('Tidak ada internet? Tidak masalah. Catat data di kandang dan sinkronkan nanti. Instal seperti aplikasi native di Android/iOS.') }}
                    </p>
                </div>
            </div>
        </div>

        <!-- CTA Section -->
        <div class="max-w-7xl mx-auto px-4 sm:px-6 py-12 sm:py-20 mb-10">
            <div class="bg-emerald-900 rounded-[3rem] sm:rounded-[4rem] p-8 sm:p-16 lg:p-24 text-center relative overflow-hidden shadow-2xl">
                <div class="absolute inset-0 opacity-10 bg-[radial-gradient(circle_at_center,_var(--tw-gradient-stops))] from-emerald-100 to-transparent"></div>
                <div class="relative z-10 max-w-3xl mx-auto">
                    <h2 class="text-3xl sm:text-4xl lg:text-6xl font-black text-white mb-6 sm:mb-8">{{ __('Siap mentransformasi operasional ternak Anda?') }}</h2>
                    <p class="text-emerald-100/70 text-base sm:text-xl font-medium mb-8 sm:mb-12">
                        {{ __('Bergabunglah dengan ratusan peternak modern yang menggunakan Pintar Ternak untuk meningkatkan produktivitas dan keuntungan.') }}
                    </p>
                    <div class="flex flex-col sm:flex-row space-y-4 sm:space-y-0 sm:space-x-4 justify-center">
                        <Link :href="route('register')" class="w-full sm:w-auto px-8 py-5 bg-white text-emerald-900 font-black text-xl rounded-2xl sm:rounded-[2.5rem] hover:bg-emerald-50 transition-all shadow-xl flex items-center justify-center">
                            {{ __('Mulai Gratis Sekarang') }}
                        </Link>
                    </div>
                </div>
            </div>
        </div>

        <footer class="max-w-7xl mx-auto px-4 sm:px-6 py-12 sm:py-20 border-t border-emerald-100 flex flex-col md:flex-row justify-between items-center text-emerald-800/40 text-xs font-bold uppercase tracking-widest text-center md:text-left space-y-6 md:space-y-0">
            <div>&copy; 2026 Pintar Ternak. Hak Cipta Dilindungi.</div>
            <div class="flex flex-wrap justify-center items-center gap-4 sm:gap-6">
                <a href="#" class="hover:text-emerald-600 transition">{{ __('Kebijakan Privasi') }}</a>
                <a href="#" class="hover:text-emerald-600 transition">{{ __('Ketentuan Layanan') }}</a>
                <span class="hidden sm:inline">|</span>
                <span>Laravel v{{ $page.props.laravelVersion }} (PHP v{{ $page.props.phpVersion }})</span>
            </div>
        </footer>
    </div>
</template>

<style scoped>
.blur-3xl {
    filter: blur(64px);
}
.pb-safe {
    padding-bottom: env(safe-area-inset-bottom);
}
</style>
