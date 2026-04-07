<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, useForm } from '@inertiajs/vue3';
import Card from '@/Components/Card.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import InputLabel from '@/Components/InputLabel.vue';
import TextInput from '@/Components/TextInput.vue';
import InputError from '@/Components/InputError.vue';
import { ref } from 'vue';

const props = defineProps({
    team: Array
});

const isAdding = ref(false);
const isEditing = ref(false);
const editingId = ref(null);

const form = useForm({
    name: '',
    email: '',
    password: '',
    role: 'staff'
});

const openAddModal = () => {
    isEditing.value = false;
    isAdding.value = true;
    form.reset();
    form.clearErrors();
};

const editMember = (member) => {
    isEditing.value = true;
    isAdding.value = true;
    editingId.value = member.id;
    form.name = member.name;
    form.email = member.email;
    form.role = member.role;
    form.password = '';
    form.clearErrors();
};

const submit = () => {
    if (isEditing.value) {
        form.put(route('teams.update', editingId.value), {
            onSuccess: () => {
                isAdding.value = false;
                isEditing.value = false;
                form.reset();
            }
        });
    } else {
        form.post(route('teams.store'), {
            onSuccess: () => {
                isAdding.value = false;
                form.reset();
            }
        });
    }
};

const removeMember = (id) => {
    if (confirm(__('Are you sure you want to remove this team member? they will lose access to the farm.'))) {
        form.delete(route('teams.destroy', id));
    }
};
</script>

<template>
    <Head :title="__('Team Management')" />

    <AuthenticatedLayout>
        <template #header>{{ __('Team Management') }}</template>

        <div class="flex justify-between items-center mb-6">
            <h2 class="text-2xl font-bold text-gray-800">{{ __('Farm Team') }}</h2>
            <PrimaryButton v-if="$page.props.auth.user.role === 'owner'" @click="openAddModal">{{ __('Add Member') }}</PrimaryButton>
        </div>

        <!-- Add/Edit Member Form -->
        <div v-if="isAdding" class="mb-8">
            <Card class="p-6 border-2 border-farm-200">
                <h3 class="text-lg font-bold mb-4 text-farm-700">
                    {{ isEditing ? __('Edit Member') : __('Add New Member') }}
                </h3>
                <form @submit.prevent="submit" class="space-y-4">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <InputLabel for="name" :value="__('Name')" />
                            <TextInput id="name" v-model="form.name" type="text" class="mt-1 block w-full" required />
                            <InputError :message="form.errors.name" class="mt-2" />
                        </div>
                        <div>
                            <InputLabel for="email" :value="__('Email Address')" />
                            <TextInput id="email" v-model="form.email" type="email" class="mt-1 block w-full" required />
                            <InputError :message="form.errors.email" class="mt-2" />
                        </div>
                        <div>
                            <InputLabel for="password" :value="__('Password')" />
                            <TextInput id="password" v-model="form.password" type="password" class="mt-1 block w-full" :required="!isEditing" :placeholder="isEditing ? __('Leave blank to keep current') : ''" />
                            <InputError :message="form.errors.password" class="mt-2" />
                        </div>
                        <div>
                            <InputLabel for="role" :value="__('Role')" />
                            <select id="role" v-model="form.role" class="mt-1 block w-full border-gray-300 rounded-lg shadow-sm focus:border-farm-500 focus:ring-farm-500">
                                <option value="staff">{{ __('Staff (Observer/Editor)') }}</option>
                                <option value="owner">{{ __('Co-Owner (Full Access)') }}</option>
                            </select>
                            <InputError :message="form.errors.role" class="mt-2" />
                        </div>
                    </div>
                    <div class="flex justify-end space-x-3 mt-6">
                        <button type="button" @click="isAdding = false" class="text-gray-500 font-bold hover:text-gray-700">{{ __('Cancel') }}</button>
                        <PrimaryButton :disabled="form.processing">
                            {{ isEditing ? __('Update Member') : __('Invite Member') }}
                        </PrimaryButton>
                    </div>
                </form>
            </Card>
        </div>

        <!-- Team List -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <Card v-for="member in team" :key="member.id" class="p-5 flex flex-col items-center text-center relative overflow-hidden group">
                <div v-if="member.role === 'owner'" class="absolute top-0 right-0 p-1 bg-amber-100 text-amber-600 rounded-bl-lg">
                    <i class="fas fa-star text-xs"></i>
                </div>
                
                <div class="h-20 w-20 rounded-full bg-farm-100 flex items-center justify-center text-farm-700 text-2xl font-black mb-4 border-4 border-white shadow-md">
                    {{ member.name.charAt(0) }}
                </div>
                <h3 class="text-xl font-bold text-gray-900">{{ member.name }}</h3>
                <p class="text-sm text-gray-500 mb-2">{{ member.email }}</p>
                <div class="flex items-center mt-2">
                    <span :class="member.role === 'owner' ? 'bg-amber-100 text-amber-700' : 'bg-farm-100 text-farm-600'" class="px-3 py-1 rounded-full text-xs font-black uppercase tracking-widest">
                        {{ __(member.role === 'owner' ? 'Owner' : 'Staff') }}
                    </span>
                </div>

                <div v-if="$page.props.auth.user.role === 'owner'" class="mt-6 flex space-x-4 opacity-0 group-hover:opacity-100 transition">
                    <button @click="editMember(member)" class="text-farm-600 text-sm font-bold hover:underline">
                        {{ __('Edit') }}
                    </button>
                    <button v-if="member.id !== $page.props.auth.user.id" 
                        @click="removeMember(member.id)"
                        class="text-red-500 text-sm font-bold hover:underline">
                        {{ __('Remove') }}
                    </button>
                </div>
            </Card>
        </div>
    </AuthenticatedLayout>
</template>
