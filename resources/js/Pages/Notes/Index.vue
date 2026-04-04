<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, useForm } from '@inertiajs/vue3';
import Card from '@/Components/Card.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import InputLabel from '@/Components/InputLabel.vue';
import TextInput from '@/Components/TextInput.vue';
import { ref } from 'vue';

const props = defineProps({
    notes: Array
});

const isAdding = ref(false);

const form = useForm({
    title: '',
    content: ''
});

const submit = () => {
    form.post(route('notes.store'), {
        onSuccess: () => {
            isAdding.value = false;
            form.reset();
        }
    });
};

const deleteNote = (id) => {
    if (confirm(__('Are you sure you want to delete this note?'))) {
        form.delete(route('notes.destroy', id));
    }
};
</script>

<template>
    <Head :title="__('Farm Notes')" />

    <AuthenticatedLayout>
        <template #header>{{ __('Farm Notes') }}</template>

        <div class="flex justify-between items-center mb-6">
            <h2 class="text-2xl font-bold text-gray-800">{{ __('Quick Notes') }}</h2>
            <PrimaryButton @click="isAdding = true">{{ __('New Note') }}</PrimaryButton>
        </div>

        <div v-if="isAdding" class="mb-8 max-w-2xl mx-auto">
            <Card class="p-6">
                <form @submit.prevent="submit" class="space-y-4">
                    <div>
                        <InputLabel for="title" :value="__('Title')" />
                        <TextInput id="title" v-model="form.title" type="text" class="mt-1 block w-full" required />
                    </div>
                    <div>
                        <InputLabel for="content" :value="__('Content')" />
                        <textarea id="content" v-model="form.content" rows="4" class="mt-1 block w-full border-gray-300 rounded-lg shadow-sm focus:border-farm-500 focus:ring-farm-500" required></textarea>
                    </div>
                    <div class="flex justify-end space-x-3 mt-4">
                        <button type="button" @click="isAdding = false" class="text-gray-500 font-bold hover:text-gray-700">{{ __('Cancel') }}</button>
                        <PrimaryButton :disabled="form.processing">{{ __('Save Note') }}</PrimaryButton>
                    </div>
                </form>
            </Card>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <Card v-for="note in notes" :key="note.id" class="p-6 flex flex-col h-full bg-amber-50 border-amber-200">
                <div class="flex justify-between items-start mb-3">
                    <h3 class="text-lg font-bold text-gray-900">{{ note.title }}</h3>
                    <button @click="deleteNote(note.id)" class="text-gray-400 hover:text-red-500 transition">
                        <i class="fas fa-trash-can"></i>
                    </button>
                </div>
                <p class="text-sm text-gray-700 flex-grow whitespace-pre-wrap">{{ note.content }}</p>
                <div class="mt-4 pt-3 border-t border-amber-100 text-[10px] uppercase tracking-wider font-bold text-amber-600">
                    {{ new Date(note.created_at).toLocaleDateString() }}
                </div>
            </Card>
            <div v-if="notes.length === 0" class="col-span-full text-center py-12 text-gray-500 bg-earth-50 rounded-2xl border-2 border-dashed border-earth-200">
                {{ __('No notes found. Keep track of important farm details here.') }}
            </div>
        </div>
    </AuthenticatedLayout>
</template>
