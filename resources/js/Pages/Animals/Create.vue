<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';
import Card from '@/Components/Card.vue';
import InputLabel from '@/Components/InputLabel.vue';
import TextInput from '@/Components/TextInput.vue';
import InputError from '@/Components/InputError.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';

const form = useForm({
    registration_number: '',
    name_or_tag: '',
    species: 'Sheep',
    breed: '',
    sex: 'male',
    birth_date: '',
    entry_date: new Date().toISOString().split('T')[0],
    weight: '',
    initial_weight: '',
    status: 'active',
    condition_notes: '',
});

const submit = () => {
    form.post(route('animals.store'));
};
</script>

<template>
    <Head title="Add New Animal" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex items-center space-x-3">
                <Link :href="route('animals.index')" class="text-farm-600 hover:text-farm-800 transition bg-farm-50 p-1.5 rounded-lg border border-transparent hover:border-farm-200">
                    <i class="fas fa-arrow-left"></i>
                </Link>
                <span class="text-xl font-bold">Add New Animal</span>
            </div>
        </template>

        <div class="max-w-4xl mx-auto py-6">
            <Card class="shadow-sm border-earth-200">
                <form @submit.prevent="submit" class="p-8 space-y-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Basic Info -->
                        <div class="space-y-4">
                            <h3 class="text-sm font-bold text-gray-500 uppercase tracking-widest border-b border-earth-100 pb-2">Identification</h3>
                            
                            <div>
                                <InputLabel for="registration_number" value="Registration Number / Barcode" />
                                <TextInput id="registration_number" type="text" class="mt-1 block w-full" v-model="form.registration_number" />
                                <InputError class="mt-2" :message="form.errors.registration_number" />
                            </div>

                            <div>
                                <InputLabel for="name_or_tag" value="Name or tag" />
                                <TextInput id="name_or_tag" type="text" class="mt-1 block w-full" v-model="form.name_or_tag" required />
                                <InputError class="mt-2" :message="form.errors.name_or_tag" />
                            </div>

                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <InputLabel for="species" value="Species" />
                                    <select id="species" v-model="form.species" class="mt-1 block w-full border-gray-300 rounded-lg shadow-sm focus:border-farm-500 focus:ring-farm-500">
                                        <option value="Sheep">Sheep (Domba)</option>
                                        <option value="Goat">Goat (Kambing)</option>
                                        <option value="Other">Other</option>
                                    </select>
                                    <InputError class="mt-2" :message="form.errors.species" />
                                </div>
                                <div>
                                    <InputLabel for="sex" value="Sex" />
                                    <select id="sex" v-model="form.sex" class="mt-1 block w-full border-gray-300 rounded-lg shadow-sm focus:border-farm-500 focus:ring-farm-500">
                                        <option value="male">Male (Jantan)</option>
                                        <option value="female">Female (Betina)</option>
                                    </select>
                                    <InputError class="mt-2" :message="form.errors.sex" />
                                </div>
                            </div>
                        </div>

                        <!-- Dates & Weight -->
                        <div class="space-y-4">
                            <h3 class="text-sm font-bold text-gray-500 uppercase tracking-widest border-b border-earth-100 pb-2">Records & Weights</h3>
                            
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <InputLabel for="birth_date" value="Birth Date" />
                                    <TextInput id="birth_date" type="date" class="mt-1 block w-full" v-model="form.birth_date" />
                                    <InputError class="mt-2" :message="form.errors.birth_date" />
                                </div>
                                <div>
                                    <InputLabel for="entry_date" value="Entry Date" />
                                    <TextInput id="entry_date" type="date" class="mt-1 block w-full" v-model="form.entry_date" />
                                    <InputError class="mt-2" :message="form.errors.entry_date" />
                                </div>
                            </div>

                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <InputLabel for="initial_weight" value="Weight on Entry (kg)" />
                                    <TextInput id="initial_weight" type="number" step="0.1" class="mt-1 block w-full" v-model="form.initial_weight" />
                                    <InputError class="mt-2" :message="form.errors.initial_weight" />
                                </div>
                                <div>
                                    <InputLabel for="weight" value="Current Weight (kg)" />
                                    <TextInput id="weight" type="number" step="0.1" class="mt-1 block w-full" v-model="form.weight" />
                                    <InputError class="mt-2" :message="form.errors.weight" />
                                </div>
                            </div>

                            <div>
                                <InputLabel for="status" value="Status" />
                                <select id="status" v-model="form.status" class="mt-1 block w-full border-gray-300 rounded-lg shadow-sm focus:border-farm-500 focus:ring-farm-500">
                                    <option value="active">Active</option>
                                    <option value="sold">Sold</option>
                                    <option value="dead">Dead</option>
                                </select>
                                <InputError class="mt-2" :message="form.errors.status" />
                            </div>
                        </div>
                    </div>

                    <div>
                        <InputLabel for="condition_notes" value="Condition Notes" />
                        <textarea id="condition_notes" v-model="form.condition_notes" rows="4" class="mt-1 block w-full border-gray-300 rounded-lg shadow-sm focus:border-farm-500 focus:ring-farm-500" placeholder="Describe the animal's current health or condition..."></textarea>
                        <InputError class="mt-2" :message="form.errors.condition_notes" />
                    </div>

                    <div class="flex items-center justify-end pt-6 border-t border-earth-100">
                        <PrimaryButton :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                            Save Animal
                        </PrimaryButton>
                    </div>
                </form>
            </Card>
        </div>
    </AuthenticatedLayout>
</template>
