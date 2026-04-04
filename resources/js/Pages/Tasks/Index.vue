<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, useForm } from '@inertiajs/vue3';
import Card from '@/Components/Card.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import InputLabel from '@/Components/InputLabel.vue';
import TextInput from '@/Components/TextInput.vue';
import { ref } from 'vue';

const props = defineProps({
    tasks: Array
});

const isAdding = ref(false);

const form = useForm({
    title: '',
    description: '',
    due_date: new Date().toISOString().split('T')[0],
    assigned_to: ''
});

const submit = () => {
    form.post(route('tasks.store'), {
        onSuccess: () => {
            isAdding.value = false;
            form.reset();
        }
    });
};

const updateStatus = (task, status) => {
    form.put(route('tasks.update', task.id), {
        status: status
    });
};

const deleteTask = (id) => {
    if (confirm(__('Delete this task?'))) {
        form.delete(route('tasks.destroy', id));
    }
};

const getStatusColor = (status) => {
    if (status === 'completed') return 'bg-green-100 text-green-800';
    if (status === 'in_progress') return 'bg-blue-100 text-blue-800';
    return 'bg-gray-100 text-gray-800';
};
</script>

<template>
    <Head :title="__('Farm Tasks')" />

    <AuthenticatedLayout>
        <template #header>{{ __('Task Tracking') }}</template>

        <div class="flex justify-between items-center mb-6">
            <h2 class="text-2xl font-bold text-gray-800">{{ __('Operational Tasks') }}</h2>
            <PrimaryButton @click="isAdding = true">{{ __('Add Task') }}</PrimaryButton>
        </div>

        <div v-if="isAdding" class="mb-8">
            <Card class="p-6">
                <form @submit.prevent="submit" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                    <div class="md:col-span-2">
                        <InputLabel for="title" :value="__('Task Title')" />
                        <TextInput id="title" v-model="form.title" type="text" class="mt-1 block w-full" required />
                    </div>
                    <div>
                        <InputLabel for="due_date" :value="__('Due Date')" />
                        <TextInput id="due_date" v-model="form.due_date" type="date" class="mt-1 block w-full" />
                    </div>
                    <div class="md:col-span-3">
                        <InputLabel for="description" :value="__('Description')" />
                        <TextInput id="description" v-model="form.description" type="text" class="mt-1 block w-full" />
                    </div>
                    <div class="md:col-span-3 flex justify-end space-x-3 mt-4">
                        <button type="button" @click="isAdding = false" class="text-gray-500 font-bold hover:text-gray-700">{{ __('Cancel') }}</button>
                        <PrimaryButton :disabled="form.processing">{{ __('Save Task') }}</PrimaryButton>
                    </div>
                </form>
            </Card>
        </div>

        <div class="space-y-4">
            <Card v-for="task in tasks" :key="task.id" class="p-4 flex items-center justify-between shadow-sm">
                <div class="flex items-center space-x-4">
                    <button @click="updateStatus(task, task.status === 'completed' ? 'pending' : 'completed')" 
                        class="h-6 w-6 rounded-full border-2 flex items-center justify-center transition"
                        :class="task.status === 'completed' ? 'bg-farm-600 border-farm-600' : 'border-gray-300 hover:border-farm-500'">
                        <i v-if="task.status === 'completed'" class="fas fa-check text-xs text-white shadow-sm"></i>
                    </button>
                    <div>
                        <h3 class="text-lg font-bold" :class="task.status === 'completed' ? 'text-gray-400 line-through' : 'text-gray-900'">{{ task.title }}</h3>
                        <div class="flex items-center space-x-3 mt-1">
                            <span v-if="task.due_date" class="text-xs font-bold text-gray-500">
                                {{ __('Due') }}: {{ new Date(task.due_date).toLocaleDateString('id-ID') }}
                            </span>
                            <span class="px-2 py-0.5 rounded-full text-[10px] font-extrabold uppercase shadow-sm" :class="getStatusColor(task.status)">
                                {{ __(task.status) }}
                            </span>
                        </div>
                    </div>
                </div>
                <button @click="deleteTask(task.id)" class="text-gray-400 hover:text-red-500 transition">
                    <i class="fas fa-trash-can"></i>
                </button>
            </Card>
            <div v-if="tasks.length === 0" class="text-center py-12 text-gray-500 bg-earth-50 rounded-2xl border-2 border-dashed border-earth-200">
                {{ __('No tasks scheduled.') }}
            </div>
        </div>
    </AuthenticatedLayout>
</template>
