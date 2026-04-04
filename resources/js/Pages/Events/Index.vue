<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, useForm } from '@inertiajs/vue3';
import Card from '@/Components/Card.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import InputLabel from '@/Components/InputLabel.vue';
import TextInput from '@/Components/TextInput.vue';
import { ref } from 'vue';

const props = defineProps({
    events: Array
});

const isAdding = ref(false);

const form = useForm({
    title: '',
    description: '',
    start_time: new Date().toISOString().slice(0, 16),
    end_time: '',
    type: 'general'
});

const submit = () => {
    form.post(route('events.store'), {
        onSuccess: () => {
            isAdding.value = false;
            form.reset();
        }
    });
};

const deleteEvent = (id) => {
    if (confirm(__('Delete this event?'))) {
        form.delete(route('events.destroy', id));
    }
};
</script>

<template>
    <Head :title="__('Calendar Events')" />

    <AuthenticatedLayout>
        <template #header>{{ __('Farm Calendar') }}</template>

        <div class="flex justify-between items-center mb-6">
            <h2 class="text-2xl font-bold text-gray-800">{{ __('Events & Schedule') }}</h2>
            <PrimaryButton @click="isAdding = true">{{ __('Add Event') }}</PrimaryButton>
        </div>

        <div v-if="isAdding" class="mb-8">
            <Card class="p-6">
                <form @submit.prevent="submit" class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="md:col-span-2">
                        <InputLabel for="title" :value="__('Event Title')" />
                        <TextInput id="title" v-model="form.title" type="text" class="mt-1 block w-full" required />
                    </div>
                    <div>
                        <InputLabel for="start_time" :value="__('Start Time')" />
                        <TextInput id="start_time" v-model="form.start_time" type="datetime-local" class="mt-1 block w-full" required />
                    </div>
                    <div>
                        <InputLabel for="end_time" :value="__('End Time (Optional)')" />
                        <TextInput id="end_time" v-model="form.end_time" type="datetime-local" class="mt-1 block w-full" />
                    </div>
                    <div>
                        <InputLabel for="type" :value="__('Type')" />
                        <select id="type" v-model="form.type" class="mt-1 block w-full border-gray-300 rounded-lg shadow-sm focus:border-farm-500 focus:ring-farm-500">
                            <option value="general">{{ __('General') }}</option>
                            <option value="breeding">{{ __('Breeding') }}</option>
                            <option value="health">{{ __('Health Check') }}</option>
                            <option value="holiday">{{ __('Farm Holiday') }}</option>
                            <option value="appointment">{{ __('Appointment') }}</option>
                        </select>
                    </div>
                    <div class="md:col-span-2">
                        <InputLabel for="description" :value="__('Description')" />
                        <TextInput id="description" v-model="form.description" type="text" class="mt-1 block w-full" />
                    </div>
                    <div class="md:col-span-2 flex justify-end space-x-3 mt-4">
                        <button type="button" @click="isAdding = false" class="text-gray-500 font-bold hover:text-gray-700">{{ __('Cancel') }}</button>
                        <PrimaryButton :disabled="form.processing">{{ __('Save Event') }}</PrimaryButton>
                    </div>
                </form>
            </Card>
        </div>

        <div class="space-y-4">
            <Card v-for="event in events" :key="event.id" class="p-4 border-l-4" :class="[
                event.type === 'breeding' ? 'border-pink-500' : 
                event.type === 'health' ? 'border-red-500' : 'border-farm-500'
            ]">
                <div class="flex justify-between items-start">
                    <div>
                        <h3 class="text-lg font-bold text-gray-900">{{ event.title }}</h3>
                        <p class="text-sm text-gray-500 font-medium">
                            {{ new Date(event.start_time).toLocaleDateString('id-ID') }} 
                            <span v-if="event.end_time"> - {{ new Date(event.end_time).toLocaleDateString('id-ID') }}</span>
                        </p>
                        <p v-if="event.description" class="text-sm text-gray-700 mt-2">{{ event.description }}</p>
                    </div>
                    <button @click="deleteEvent(event.id)" class="text-gray-400 hover:text-red-500 transition">
                        <i class="fas fa-trash-can"></i>
                    </button>
                </div>
            </Card>
            <div v-if="events.length === 0" class="text-center py-12 text-gray-500 bg-earth-50 rounded-2xl border-2 border-dashed border-earth-200">
                {{ __('No upcoming events.') }}
            </div>
        </div>
    </AuthenticatedLayout>
</template>
