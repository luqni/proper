<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, useForm, router } from '@inertiajs/vue3';
import Card from '@/Components/Card.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import InputLabel from '@/Components/InputLabel.vue';
import TextInput from '@/Components/TextInput.vue';
import { ref } from 'vue';

const props = defineProps({
    tasks: Array,
    users: Array
});

const isAdding = ref(false);
const editingTask = ref(null);

const form = useForm({
    title: '',
    description: '',
    due_date: new Date().toISOString().split('T')[0],
    assigned_to: ''
});

const startAdd = () => {
    isAdding.value = true;
    editingTask.value = null;
    form.reset();
    form.assigned_to = '';
};

const startEdit = (task) => {
    isAdding.value = true;
    editingTask.value = task;
    form.title = task.title;
    form.description = task.description || '';
    form.due_date = task.due_date ? task.due_date.split('T')[0] : '';
    form.assigned_to = task.assigned_to;
};

const submit = () => {
    if (editingTask.value) {
        form.put(route('tasks.update', editingTask.value.id), {
            onSuccess: () => {
                isAdding.value = false;
                editingTask.value = null;
                form.reset();
            }
        });
    } else {
        form.post(route('tasks.store'), {
            onSuccess: () => {
                isAdding.value = false;
                form.reset();
            }
        });
    }
};

const updateStatus = (task, status) => {
    router.put(route('tasks.update', task.id), {
        status: status
    });
};

const deleteTask = (id) => {
    if (confirm(__('Delete this task?'))) {
        form.delete(route('tasks.destroy', id));
    }
};

const getStatusColor = (status) => {
    if (status === 'completed') return 'bg-green-100 text-green-800 border-green-200';
    if (status === 'in_progress') return 'bg-blue-100 text-blue-800 border-blue-200';
    return 'bg-gray-100 text-gray-800 border-gray-200';
};
</script>

<template>
    <Head :title="__('Farm Tasks')" />

    <AuthenticatedLayout>
        <template #header>{{ __('Task Tracking') }}</template>

        <div class="flex justify-between items-center mb-6">
            <h2 class="text-2xl font-bold text-gray-800">{{ __('Operational Tasks') }}</h2>
            <PrimaryButton @click="startAdd">{{ __('Add Task') }}</PrimaryButton>
        </div>

        <div v-if="isAdding" class="mb-8 scale-in-center">
            <Card class="p-6 border-farm-100 shadow-lg">
                <h3 class="text-lg font-black text-gray-900 mb-4">{{ editingTask ? __('Edit Task') : __('Add New Task') }}</h3>
                <form @submit.prevent="submit" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5">
                    <div class="md:col-span-2">
                        <InputLabel for="title" :value="__('Task Title')" />
                        <TextInput id="title" v-model="form.title" type="text" class="mt-1 block w-full" required />
                    </div>
                    <div>
                        <InputLabel for="due_date" :value="__('Due Date')" />
                        <TextInput id="due_date" v-model="form.due_date" type="date" class="mt-1 block w-full" />
                    </div>
                    <div class="md:col-span-2">
                        <InputLabel for="description" :value="__('Description')" />
                        <TextInput id="description" v-model="form.description" type="text" class="mt-1 block w-full" />
                    </div>
                    <div>
                        <InputLabel for="assigned_to" :value="__('Assigned To')" />
                        <select id="assigned_to" v-model="form.assigned_to" class="mt-1 block w-full border-gray-300 rounded-lg shadow-sm focus:border-farm-500 focus:ring-farm-500">
                            <option value="">{{ __('Me / All') }}</option>
                            <option v-for="user in users" :key="user.id" :value="user.id">
                                {{ user.name }} ({{ user.role }})
                            </option>
                        </select>
                    </div>
                    <div class="md:col-span-3 flex justify-end space-x-3 mt-6 pt-4 border-t border-gray-100">
                        <button type="button" @click="isAdding = false; editingTask = null" class="text-gray-500 font-bold hover:text-gray-700 uppercase text-xs tracking-widest">{{ __('Cancel') }}</button>
                        <PrimaryButton :disabled="form.processing" class="shadow-farm-200">
                            {{ editingTask ? __('Update Task') : __('Save Task') }}
                        </PrimaryButton>
                    </div>
                </form>
            </Card>
        </div>

        <div class="space-y-4">
            <Card v-for="task in tasks" :key="task.id" class="p-4 flex items-center justify-between shadow-sm hover:shadow-md transition group overflow-hidden border-earth-100">
                <div class="flex items-center space-x-4 flex-1 min-w-0">
                    <button @click="updateStatus(task, task.status === 'completed' ? 'pending' : 'completed')" 
                        class="h-7 w-7 rounded-full border-2 flex items-center justify-center transition shrink-0 shadow-inner"
                        :class="task.status === 'completed' ? 'bg-farm-600 border-farm-600' : 'border-gray-300 hover:border-farm-500 bg-white'">
                        <i v-if="task.status === 'completed'" class="fas fa-check text-[10px] text-white"></i>
                    </button>
                    <div class="flex-1 min-w-0">
                        <h3 class="text-md font-bold truncate leading-tight" :class="task.status === 'completed' ? 'text-gray-400 line-through' : 'text-gray-900'">{{ task.title }}</h3>
                        <p v-if="task.description && task.status !== 'completed'" class="text-xs text-gray-500 mt-0.5 truncate">{{ task.description }}</p>
                        <div class="flex items-center flex-wrap gap-y-2 gap-x-3 mt-2">
                            <span v-if="task.due_date" class="text-[10px] font-black uppercase tracking-tight text-gray-400 flex items-center">
                                <i class="far fa-calendar-alt mr-1.5 text-farm-500"></i>
                                {{ new Date(task.due_date).toLocaleDateString($page.props.locale === 'id' ? 'id-ID' : 'en-US') }}
                            </span>
                            <span class="px-2 py-0.5 rounded-md text-[9px] font-black uppercase shadow-xs border inline-flex items-center" :class="getStatusColor(task.status)">
                                {{ __(task.status) }}
                            </span>
                            <div v-if="task.assignee" class="flex items-center space-x-1.5 px-2 py-0.5 bg-earth-50 rounded-md border border-earth-100" :title="__('Assigned to') + ': ' + task.assignee.name">
                                <div class="h-4 w-4 rounded-full bg-farm-600 flex items-center justify-center text-white text-[8px] font-black">
                                    {{ task.assignee.name.charAt(0) }}
                                </div>
                                <span class="text-[9px] font-black text-gray-600 uppercase">{{ task.assignee.name.split(' ')[0] }}</span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="flex items-center space-x-2 ml-4">
                    <button @click="startEdit(task)" class="text-earth-400 hover:text-earth-700 transition p-1 opacity-0 group-hover:opacity-100">
                        <i class="fas fa-pen-to-square"></i>
                    </button>
                    <button @click="deleteTask(task.id)" class="text-gray-400 hover:text-rose-600 transition p-1">
                        <i class="fas fa-trash-can"></i>
                    </button>
                </div>
            </Card>
            <div v-if="tasks.length === 0" class="text-center py-16 text-gray-400 bg-earth-50 rounded-[2rem] border-2 border-dashed border-earth-200">
                <i class="fas fa-clipboard-list text-3xl mb-3 opacity-20"></i>
                <p class="text-sm font-bold uppercase tracking-widest">{{ __('No tasks scheduled.') }}</p>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

<style scoped>
.scale-in-center {
    animation: scale-in-center 0.3s cubic-bezier(0.250, 0.460, 0.450, 0.940) both;
}

@keyframes scale-in-center {
  0% {
    transform: scale(0.95);
    opacity: 0;
  }
  100% {
    transform: scale(1);
    opacity: 1;
  }
}
</style>
