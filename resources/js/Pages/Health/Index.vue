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
    records: Array,
    animals: Array
});

const isAdding = ref(false);

const form = useForm({
    animal_id: '',
    type: 'vaccine',
    name: '',
    date: new Date().toISOString().split('T')[0],
    notes: ''
});

const submit = () => {
    form.post(route('health.store'), {
        onSuccess: () => {
            isAdding.value = false;
            form.reset();
        }
    });
};

const deleteRecord = (id) => {
    if (confirm('Delete this health record?')) {
        form.delete(route('health.destroy', id));
    }
};
</script>

<template>
    <Head title="Health Records" />

    <AuthenticatedLayout>
        <template #header>Health & Vaccines</template>

        <div class="flex justify-between items-center mb-6">
            <h2 class="text-2xl font-bold text-gray-800">Health Logs</h2>
            <PrimaryButton @click="isAdding = true">Add Record</PrimaryButton>
        </div>

        <div v-if="isAdding" class="mb-8">
            <Card class="p-6">
                <form @submit.prevent="submit" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                    <div>
                        <InputLabel for="animal_id" value="Animal" />
                        <select id="animal_id" v-model="form.animal_id" class="mt-1 block w-full border-gray-300 rounded-lg shadow-sm focus:border-farm-500 focus:ring-farm-500" required>
                            <option value="">Select Animal</option>
                            <option v-for="animal in animals" :key="animal.id" :value="animal.id">{{ animal.name_or_tag }} ({{ animal.registration_number }})</option>
                        </select>
                    </div>
                    <div>
                        <InputLabel for="type" value="Treatment Type" />
                        <select id="type" v-model="form.type" class="mt-1 block w-full border-gray-300 rounded-lg shadow-sm focus:border-farm-500 focus:ring-farm-500">
                            <option value="vaccine">Vaccine (Vaksin)</option>
                            <option value="medication">Medication (Obat)</option>
                            <option value="checkup">Checkup (Cek Rutin)</option>
                            <option value="treatment">Treatment (Pengobatan)</option>
                            <option value="vitamin">Vitamin</option>
                        </select>
                    </div>
                    <div>
                        <InputLabel for="name" value="Product/Treatment Name" />
                        <TextInput id="name" v-model="form.name" type="text" class="mt-1 block w-full" placeholder="e.g. Vitamin B12" required />
                    </div>
                    <div>
                        <InputLabel for="date" value="Date" />
                        <TextInput id="date" v-model="form.date" type="date" class="mt-1 block w-full" required />
                    </div>
                    <div class="lg:col-span-2">
                        <InputLabel for="notes" value="Notes" />
                        <TextInput id="notes" v-model="form.notes" type="text" class="mt-1 block w-full" />
                    </div>
                    <div class="lg:col-span-3 flex justify-end space-x-3 mt-4">
                        <button type="button" @click="isAdding = false" class="text-gray-500 font-bold hover:text-gray-700">Cancel</button>
                        <PrimaryButton :disabled="form.processing">Save Record</PrimaryButton>
                    </div>
                </form>
            </Card>
        </div>

        <Card class="overflow-hidden">
            <Table>
                <template #head>
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Date</th>
                        <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Animal</th>
                        <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Type</th>
                        <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Treatment</th>
                        <th class="px-6 py-3 text-right text-xs font-bold text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </template>
                <template #body>
                    <tr v-for="record in records" :key="record.id" class="hover:bg-gray-50">
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            {{ new Date(record.date).toLocaleDateString() }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 font-medium">
                            {{ record.animal?.name_or_tag }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-2.5 py-0.5 rounded-full text-xs font-bold bg-blue-100 text-blue-800 uppercase outline outline-1 outline-blue-200">
                                {{ record.type }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">
                            {{ record.name }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm">
                            <button @click="deleteRecord(record.id)" class="text-red-600 hover:text-red-900 font-bold">Delete</button>
                        </td>
                    </tr>
                    <tr v-if="records.length === 0">
                        <td colspan="5" class="px-6 py-12 text-center text-gray-500 italic">No health records found.</td>
                    </tr>
                </template>
            </Table>
        </Card>
    </AuthenticatedLayout>
</template>
