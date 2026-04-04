<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, useForm } from '@inertiajs/vue3';
import Card from '@/Components/Card.vue';
import Table from '@/Components/Table.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import InputLabel from '@/Components/InputLabel.vue';
import TextInput from '@/Components/TextInput.vue';
import { ref } from 'vue';

const props = defineProps({
    locations: Array
});

const isAdding = ref(false);

const form = useForm({
    name: '',
    type: 'pasture',
    area_size: '',
    notes: ''
});

const submit = () => {
    form.post(route('locations.store'), {
        onSuccess: () => {
            isAdding.value = false;
            form.reset();
        }
    });
};
</script>

<template>
    <Head :title="__('Farm Locations')" />

    <AuthenticatedLayout>
        <template #header>{{ __('Farm Map (Areas)') }}</template>

        <div class="flex justify-between items-center mb-6">
            <h2 class="text-2xl font-bold text-gray-800">{{ __('Farm Areas & Locations') }}</h2>
            <PrimaryButton @click="isAdding = true">{{ __('Add Area') }}</PrimaryButton>
        </div>

        <div v-if="isAdding" class="mb-8">
            <Card class="p-6">
                <form @submit.prevent="submit" class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <InputLabel for="name" :value="__('Area Name')" />
                        <TextInput id="name" v-model="form.name" type="text" class="mt-1 block w-full" required />
                    </div>
                    <div>
                        <InputLabel for="type" :value="__('Type')" />
                        <select id="type" v-model="form.type" class="mt-1 block w-full border-gray-300 rounded-lg shadow-sm focus:border-farm-500 focus:ring-farm-500">
                            <option value="pasture">{{ __('Pasture (Lahan Rumput)') }}</option>
                            <option value="barn">{{ __('Barn (Kandang)') }}</option>
                            <option value="pen">{{ __('Pen (Sekat)') }}</option>
                            <option value="storage">{{ __('Storage (Gudang)') }}</option>
                        </select>
                    </div>
                    <div>
                        <InputLabel for="area_size" :value="__('Size (sqm/hectares)')" />
                        <TextInput id="area_size" v-model="form.area_size" type="number" step="0.1" class="mt-1 block w-full" />
                    </div>
                    <div>
                        <InputLabel for="notes" :value="__('Notes')" />
                        <TextInput id="notes" v-model="form.notes" type="text" class="mt-1 block w-full" />
                    </div>
                    <div class="md:col-span-2 flex justify-end space-x-3 mt-4">
                        <button type="button" @click="isAdding = false" class="text-gray-500 font-bold hover:text-gray-700">{{ __('Cancel') }}</button>
                        <PrimaryButton :disabled="form.processing">{{ __('Save Area') }}</PrimaryButton>
                    </div>
                </form>
            </Card>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <Card v-for="location in locations" :key="location.id" class="p-6 hover:shadow-md transition">
                <div class="flex items-start justify-between">
                    <div>
                        <h3 class="text-lg font-bold text-gray-900">{{ location.name }}</h3>
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-farm-100 text-farm-800 capitalize mt-1 outline outline-1 outline-farm-200">
                            {{ __(location.type) }}
                        </span>
                        <p v-if="location.area_size" class="text-sm text-gray-500 mt-2">{{ location.area_size }} units</p>
                        <p v-if="location.notes" class="text-sm text-gray-600 mt-2 italic">"{{ location.notes }}"</p>
                    </div>
                    <div class="text-farm-600">
                        <i class="fas fa-location-dot text-2xl"></i>
                    </div>
                </div>
            </Card>
            <div v-if="locations.length === 0" class="col-span-full text-center py-12 text-gray-500 bg-earth-50 rounded-2xl border-2 border-dashed border-earth-200">
                {{ __('No areas defined. Click "Add Area" to map your farm.') }}
            </div>
        </div>
    </AuthenticatedLayout>
</template>
