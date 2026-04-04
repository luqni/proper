<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, useForm } from '@inertiajs/vue3';
import Card from '@/Components/Card.vue';
import Table from '@/Components/Table.vue';
import InputLabel from '@/Components/InputLabel.vue';
import TextInput from '@/Components/TextInput.vue';
import InputError from '@/Components/InputError.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import { ref } from 'vue';

const props = defineProps({
    rations: Array
});

const isCreating = ref(false);
const editingRation = ref(null);

const form = useForm({
    name: '',
    price_per_kg: '',
    weight_kg: '',
    notes: ''
});

const startCreate = () => {
    isCreating.value = true;
    editingRation.value = null;
    form.reset();
};

const startEdit = (ration) => {
    isCreating.value = false;
    editingRation.value = ration;
    form.name = ration.name;
    form.price_per_kg = ration.price_per_kg;
    form.weight_kg = ration.weight_kg;
    form.notes = ration.notes;
};

const submit = () => {
    if (editingRation.value) {
        form.put(route('rations.update', editingRation.value.id), {
            onSuccess: () => {
                editingRation.value = null;
                form.reset();
            }
        });
    } else {
        form.post(route('rations.store'), {
            onSuccess: () => {
                isCreating.value = false;
                form.reset();
            }
        });
    }
};

const deleteRation = (id) => {
    if (confirm('Are you sure you want to delete this ration?')) {
        form.delete(route('rations.destroy', id));
    }
};
</script>

<template>
    <Head title="Ration Management (Ransum)" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex justify-between items-center w-full">
                <span class="text-xl font-bold">Ration Management (Ransum)</span>
                <PrimaryButton @click="startCreate" class="bg-farm-600 hover:bg-farm-700 font-semibold shadow-sm text-xs py-2 px-4 shadow">Add New Ration</PrimaryButton>
            </div>
        </template>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mt-2">
            <!-- Left Side: List -->
            <div class="lg:col-span-2">
                <Card class="shadow-sm border-earth-200 overflow-hidden">
                    <Table v-if="rations && rations.length > 0">
                        <template #header>
                            <th scope="col" class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider bg-farm-50">Ration Name</th>
                            <th scope="col" class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider bg-farm-50">Price / kg</th>
                            <th scope="col" class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider bg-farm-50">Stock (kg)</th>
                            <th scope="col" class="relative px-6 py-4 bg-farm-50"><span class="sr-only">Actions</span></th>
                        </template>
                        <tr v-for="ration in rations" :key="ration.id" class="hover:bg-earth-50 transition border-t border-earth-100">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-bold text-gray-900">{{ ration.name }}</div>
                                <div class="text-xs text-gray-500 mt-0.5 truncate max-w-[200px]">{{ ration.notes }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700 font-medium">
                                Rp {{ Number(ration.price_per_kg).toLocaleString() }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">
                                {{ ration.weight_kg }} kg
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium space-x-2">
                                <button @click="startEdit(ration)" class="text-farm-600 hover:text-farm-900 font-bold">Edit</button>
                                <button @click="deleteRation(ration.id)" class="text-red-600 hover:text-red-900 font-bold">Delete</button>
                            </td>
                        </tr>
                    </Table>
                    <div v-else class="p-12 text-center text-gray-500 bg-earth-50">
                        No rations recorded yet. Add one to get started!
                    </div>
                </Card>
            </div>

            <!-- Right Side: Form (Create/Edit) -->
            <div class="lg:col-span-1">
                <Card v-if="isCreating || editingRation" class="shadow-sm border-earth-200">
                    <div class="p-6 border-b border-earth-100 bg-earth-50 rounded-t-lg">
                        <h3 class="text-lg font-bold text-gray-900">{{ editingRation ? 'Edit Ration' : 'Add New Ration' }}</h3>
                    </div>
                    <form @submit.prevent="submit" class="p-6 space-y-4">
                        <div>
                            <InputLabel for="name" value="Ration Name (e.g. Rumput, Silase)" />
                            <TextInput id="name" v-model="form.name" type="text" class="mt-1 block w-full" required placeholder="Rumput / Silase / Konsentrat" />
                            <InputError :message="form.errors.name" class="mt-1" />
                        </div>
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <InputLabel for="price_per_kg" value="Price per kg" />
                                <TextInput id="price_per_kg" v-model="form.price_per_kg" type="number" step="0.01" class="mt-1 block w-full" required />
                                <InputError :message="form.errors.price_per_kg" class="mt-1" />
                            </div>
                            <div>
                                <InputLabel for="weight_kg" value="Weight / Stock (kg)" />
                                <TextInput id="weight_kg" v-model="form.weight_kg" type="number" step="0.1" class="mt-1 block w-full" required />
                                <InputError :message="form.errors.weight_kg" class="mt-1" />
                            </div>
                        </div>
                        <div>
                            <InputLabel for="notes" value="Notes" />
                            <textarea id="notes" v-model="form.notes" rows="3" class="mt-1 block w-full border-gray-300 rounded-lg shadow-sm focus:border-farm-500 focus:ring-farm-500 text-sm" placeholder="Additional details..."></textarea>
                            <InputError :message="form.errors.notes" class="mt-1" />
                        </div>
                        <div class="flex items-center justify-end space-x-3 pt-2">
                            <button type="button" @click="isCreating = false; editingRation = null" class="text-gray-500 text-sm font-bold hover:text-gray-700">Cancel</button>
                            <PrimaryButton :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                                {{ editingRation ? 'Update Ration' : 'Save Ration' }}
                            </PrimaryButton>
                        </div>
                    </form>
                </Card>
                <div v-else class="bg-earth-50 border border-dashed border-earth-300 rounded-xl p-8 text-center text-gray-500 text-sm font-medium">
                    Select a ration to edit or click "Add New Ration" to register a new feed type.
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
