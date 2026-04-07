<script setup>
import { ref } from 'vue';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';
import Card from '@/Components/Card.vue';
import InputLabel from '@/Components/InputLabel.vue';
import TextInput from '@/Components/TextInput.vue';
import InputError from '@/Components/InputError.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';

const form = useForm({
    name: '',
    type: 'Energi',
    protein: '',
    fat: '',
    fiber: '',
    tdn: '',
    dry_matter: '',
    price_per_kg: '',
    stock: '',
});

const submit = () => {
    form.post(route('feeds.store'));
};
</script>

<template>
    <Head :title="__('Add Feed Ingredient')" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex items-center space-x-3">
                <Link :href="route('feeds.index')" class="text-orange-600 hover:text-orange-800 transition bg-orange-50 p-1.5 rounded-lg border border-transparent hover:border-orange-200">
                    <i class="fas fa-arrow-left"></i>
                </Link>
                <span class="text-xl font-bold">{{ __('Add Feed Ingredient') }}</span>
            </div>
        </template>

        <div class="max-w-4xl mx-auto py-6">
            <Card class="shadow-sm border-orange-100">
                <form @submit.prevent="submit" class="p-8 space-y-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Basic Info -->
                        <div class="space-y-4">
                            <h3 class="text-sm font-bold text-gray-500 uppercase tracking-widest border-b border-orange-50 pb-2">{{ __('Basic Information') }}</h3>
                            
                            <div>
                                <InputLabel for="name" :value="__('Name')" />
                                <TextInput id="name" type="text" class="mt-1 block w-full" v-model="form.name" required />
                                <InputError class="mt-2" :message="form.errors.name" />
                            </div>

                            <div>
                                <InputLabel for="type" :value="__('Type')" />
                                <select id="type" v-model="form.type" class="mt-1 block w-full border-gray-300 rounded-lg shadow-sm focus:border-orange-500 focus:ring-orange-500">
                                    <option value="Energi">{{ __('Energi') }}</option>
                                    <option value="Protein">{{ __('Protein') }}</option>
                                    <option value="Mineral">{{ __('Mineral') }}</option>
                                    <option value="Lainnya">{{ __('Lainnya') }}</option>
                                </select>
                                <InputError class="mt-2" :message="form.errors.type" />
                            </div>

                            <div>
                                <InputLabel for="price_per_kg" :value="__('Price per kg')" />
                                <TextInput id="price_per_kg" type="number" step="1" class="mt-1 block w-full" v-model="form.price_per_kg" />
                                <InputError class="mt-2" :message="form.errors.price_per_kg" />
                            </div>

                            <div>
                                <InputLabel for="stock" :value="__('Initial Stock (kg)')" />
                                <TextInput id="stock" type="number" step="0.01" class="mt-1 block w-full" v-model="form.stock" />
                                <InputError class="mt-2" :message="form.errors.stock" />
                            </div>
                        </div>

                        <!-- Nutrition -->
                        <div class="space-y-4">
                            <h3 class="text-sm font-bold text-gray-500 uppercase tracking-widest border-b border-orange-50 pb-2">{{ __('Nutritional Content (%)') }}</h3>
                            
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <InputLabel for="protein" :value="__('Protein (PK)')" />
                                    <TextInput id="protein" type="number" step="0.01" class="mt-1 block w-full" v-model="form.protein" />
                                    <InputError class="mt-2" :message="form.errors.protein" />
                                </div>
                                <div>
                                    <InputLabel for="tdn" :value="__('TDN / Energi')" />
                                    <TextInput id="tdn" type="number" step="0.01" class="mt-1 block w-full" v-model="form.tdn" />
                                    <InputError class="mt-2" :message="form.errors.tdn" />
                                </div>
                            </div>

                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <InputLabel for="fat" :value="__('Lemak (Fat)')" />
                                    <TextInput id="fat" type="number" step="0.01" class="mt-1 block w-full" v-model="form.fat" />
                                    <InputError class="mt-2" :message="form.errors.fat" />
                                </div>
                                <div>
                                    <InputLabel for="fiber" :value="__('Serat (Fiber)')" />
                                    <TextInput id="fiber" type="number" step="0.01" class="mt-1 block w-full" v-model="form.fiber" />
                                    <InputError class="mt-2" :message="form.errors.fiber" />
                                </div>
                            </div>

                            <div>
                                <InputLabel for="dry_matter" :value="__('Bahan Kering (BK)')" />
                                <TextInput id="dry_matter" type="number" step="0.01" class="mt-1 block w-full" v-model="form.dry_matter" />
                                <InputError class="mt-2" :message="form.errors.dry_matter" />
                            </div>
                        </div>
                    </div>

                    <div class="flex items-center justify-end pt-6 border-t border-orange-50">
                        <PrimaryButton class="bg-orange-600 hover:bg-orange-700 focus:bg-orange-700 active:bg-orange-800" :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                            {{ __('Save Feed Ingredient') }}
                        </PrimaryButton>
                    </div>
                </form>
            </Card>
        </div>
    </AuthenticatedLayout>
</template>
