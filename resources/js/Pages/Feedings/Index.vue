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
    feedings: Array,
    animals: Array,
    rations: Array
});

const isAdding = ref(false);

const form = useForm({
    animal_id: '',
    ration_id: '',
    quantity: '',
    fed_at: new Date().toISOString().slice(0, 16),
    notes: ''
});

const submit = () => {
    form.post(route('feedings.store'), {
        onSuccess: () => {
            isAdding.value = false;
            form.reset();
        }
    });
};

const deleteFeeding = (id) => {
    if (confirm('Delete this feeding record?')) {
        form.delete(route('feedings.destroy', id));
    }
};
</script>

<template>
    <Head title="Feeding Records" />

    <AuthenticatedLayout>
        <template #header>Feeding Records</template>

        <div class="flex justify-between items-center mb-6">
            <h2 class="text-2xl font-bold text-gray-800">Feeding Logs</h2>
            <PrimaryButton @click="isAdding = true">Log Feeding</PrimaryButton>
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
                        <InputLabel for="ration_id" value="Ration (Pakan)" />
                        <select id="ration_id" v-model="form.ration_id" class="mt-1 block w-full border-gray-300 rounded-lg shadow-sm focus:border-farm-500 focus:ring-farm-500">
                            <option value="">Select Ration</option>
                            <option v-for="ration in rations" :key="ration.id" :value="ration.id">{{ ration.name }}</option>
                        </select>
                    </div>
                    <div>
                        <InputLabel for="quantity" value="Quantity (kg)" />
                        <TextInput id="quantity" v-model="form.quantity" type="number" step="0.01" class="mt-1 block w-full" required />
                    </div>
                    <div>
                        <InputLabel for="fed_at" value="Date & Time" />
                        <TextInput id="fed_at" v-model="form.fed_at" type="datetime-local" class="mt-1 block w-full" required />
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
                        <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Ration</th>
                        <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Qty</th>
                        <th class="px-6 py-3 text-right text-xs font-bold text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </template>
                <template #body>
                    <tr v-for="feeding in feedings" :key="feeding.id" class="hover:bg-gray-50">
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 font-medium">
                            {{ new Date(feeding.fed_at).toLocaleDateString('id-ID') }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            {{ feeding.animal?.name_or_tag }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">
                            {{ feeding.ration?.name || 'Manual Feed' }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-farm-600">
                            {{ feeding.quantity }} kg
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm">
                            <button @click="deleteFeeding(feeding.id)" class="text-red-600 hover:text-red-900 font-bold">Delete</button>
                        </td>
                    </tr>
                    <tr v-if="feedings.length === 0">
                        <td colspan="5" class="px-6 py-12 text-center text-gray-500 italic">No feeding records found.</td>
                    </tr>
                </template>
            </Table>
        </Card>
    </AuthenticatedLayout>
</template>
