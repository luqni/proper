<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, useForm } from '@inertiajs/vue3';
import Card from '@/Components/Card.vue';
import Table from '@/Components/Table.vue';
import InputLabel from '@/Components/InputLabel.vue';
import TextInput from '@/Components/TextInput.vue';
import InputError from '@/Components/InputError.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import LanguageSwitcher from '@/Components/LanguageSwitcher.vue';
import { ref, watch } from 'vue';

const props = defineProps({
    rations: Array,
    feeds: Array
});

const isCreating = ref(false);
const editingRation = ref(null);
const isMixing = ref(false);
const mixingRation = ref(null);

const form = useForm({
    name: '',
    price_per_kg: '',
    weight_kg: '',
    notes: '',
    ingredients: []
});

const mixForm = useForm({
    quantity: ''
});

const startCreate = () => {
    isCreating.value = true;
    editingRation.value = null;
    form.reset();
    form.ingredients = [];
};

const startEdit = (ration) => {
    isCreating.value = false;
    editingRation.value = ration;
    form.name = ration.name;
    form.price_per_kg = ration.price_per_kg;
    form.weight_kg = ration.weight_kg;
    form.notes = ration.notes;
    form.ingredients = ration.feeds ? ration.feeds.map(f => ({
        feed_id: f.id,
        quantity: f.pivot.quantity
    })) : [];
};

const addIngredient = () => {
    form.ingredients.push({
        feed_id: '',
        quantity: 1
    });
};

const removeIngredient = (index) => {
    form.ingredients.splice(index, 1);
};

const calculateAutoPrice = () => {
    if (form.ingredients.length === 0) return;
    
    let totalCost = 0;
    let totalWeight = 0;
    
    form.ingredients.forEach(ing => {
        const feed = props.feeds.find(f => f.id === ing.feed_id);
        if (feed) {
            totalCost += feed.price_per_kg * ing.quantity;
            totalWeight += Number(ing.quantity);
        }
    });
    
    if (totalWeight > 0) {
        form.price_per_kg = (totalCost / totalWeight).toFixed(2);
    }
};

watch(() => form.ingredients, () => {
    calculateAutoPrice();
}, { deep: true });

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

const startMix = (ration) => {
    mixingRation.value = ration;
    isMixing.value = true;
    mixForm.reset();
};

const submitMix = () => {
    mixForm.post(route('rations.mix', mixingRation.value.id), {
        onSuccess: () => {
            isMixing.value = false;
            mixingRation.value = null;
        }
    });
};

const deleteRation = (id) => {
    if (confirm(__('Are you sure you want to delete this ration?'))) {
        form.delete(route('rations.destroy', id));
    }
};
</script>

<template>
    <Head :title="__('Ration Management (Ransum)')" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex justify-between items-center w-full">
                <span class="text-xl font-bold">{{ __('Ration Management (Ransum)') }}</span>
                <div class="flex items-center space-x-4">
                    <LanguageSwitcher />
                    <PrimaryButton @click="startCreate" class="bg-farm-600 hover:bg-farm-700 font-semibold shadow-sm text-xs py-2 px-4 shadow">{{ __('Add New Ration') }}</PrimaryButton>
                </div>
            </div>
        </template>

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 mt-2">
            <!-- Left Side: List -->
            <div class="lg:col-span-8">
                <Card class="shadow-sm border-earth-200 overflow-hidden">
                    <Table v-if="rations && rations.length > 0">
                        <template #head>
                            <th scope="col" class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider bg-farm-50">{{ __('Ration Name') }}</th>
                            <th scope="col" class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider bg-farm-50">{{ __('Ingredients') }}</th>
                            <th scope="col" class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider bg-farm-50">{{ __('Price / kg') }}</th>
                            <th scope="col" class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider bg-farm-50">{{ __('Stock (kg)') }}</th>
                            <th scope="col" class="relative px-6 py-4 bg-farm-50"><span class="sr-only">{{ __('Actions') }}</span></th>
                        </template>
                        <template #body>
                            <tr v-for="ration in rations" :key="ration.id" class="hover:bg-earth-50 transition border-t border-earth-100">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-bold text-gray-900">{{ ration.name }}</div>
                                    <div class="text-xs text-gray-500 mt-0.5 truncate max-w-[200px]">{{ ration.notes }}</div>
                                </td>
                                <td class="px-6 py-4">
                                    <div v-if="ration.feeds && ration.feeds.length > 0" class="flex flex-wrap gap-1">
                                        <span v-for="feed in ration.feeds" :key="feed.id" class="inline-flex items-center px-1.5 py-0.5 rounded text-[10px] font-medium bg-emerald-100 text-emerald-800">
                                            {{ feed.name }} ({{ feed.pivot.quantity }}kg)
                                        </span>
                                    </div>
                                    <span v-else class="text-xs text-gray-400 italic">No recipe</span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700 font-medium">
                                    Rp {{ Number(ration.price_per_kg).toLocaleString() }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700 font-bold">
                                    {{ ration.weight_kg }} kg
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium space-x-2">
                                    <button @click="startMix(ration)" class="text-emerald-600 hover:text-emerald-900 font-bold">Mix</button>
                                    <button @click="startEdit(ration)" class="text-farm-600 hover:text-farm-900 font-bold">Edit</button>
                                    <button @click="deleteRation(ration.id)" class="text-red-600 hover:text-red-900 font-bold">Delete</button>
                                </td>
                            </tr>
                        </template>
                    </Table>
                    <div v-else class="p-12 text-center text-gray-500 bg-earth-50">
                        {{ __('No rations recorded yet. Add one to get started!') }}
                    </div>
                </Card>
            </div>

            <!-- Right Side: Form (Create/Edit) -->
            <div class="lg:col-span-4">
                <Card v-if="isCreating || editingRation" class="shadow-sm border-earth-200 sticky top-4">
                    <div class="p-4 border-b border-earth-100 bg-earth-50 rounded-t-lg">
                        <h3 class="text-md font-bold text-gray-900">{{ editingRation ? __('Edit Ration') : __('Add New Ration') }}</h3>
                    </div>
                    <form @submit.prevent="submit" class="p-4 space-y-4">
                        <div>
                            <InputLabel for="name" :value="__('Ration Name')" />
                            <TextInput id="name" v-model="form.name" type="text" class="mt-1 block w-full" required />
                            <InputError :message="form.errors.name" class="mt-1" />
                        </div>
                        
                        <!-- Ingredients Section -->
                        <div class="border rounded-lg p-3 bg-gray-50">
                            <div class="flex justify-between items-center mb-2">
                                <span class="text-xs font-bold text-gray-700 uppercase tracking-tight">{{ __('Recipe Ingredients') }}</span>
                                <button type="button" @click="addIngredient" class="text-[10px] font-bold bg-farm-600 text-white px-2 py-1 rounded hover:bg-farm-700">
                                    + {{ __('Add') }}
                                </button>
                            </div>
                            <div v-for="(ing, index) in form.ingredients" :key="index" class="space-y-2 mb-3 pb-3 border-b border-gray-200 last:border-0 last:pb-0">
                                <div class="flex items-center space-x-2">
                                    <select v-model="ing.feed_id" class="text-xs block w-full border-gray-300 rounded-md shadow-sm focus:border-farm-500 focus:ring-farm-500 h-8" required>
                                        <option value="">{{ __('Select Ingredient') }}</option>
                                        <option v-for="feed in feeds" :key="feed.id" :value="feed.id">{{ feed.name }} (Rp {{ feed.price_per_kg }})</option>
                                    </select>
                                    <button type="button" @click="removeIngredient(index)" class="text-red-500 hover:text-red-700">
                                        <i class="fas fa-times"></i>
                                    </button>
                                </div>
                                <div class="flex items-center space-x-2">
                                    <TextInput v-model="ing.quantity" type="number" step="0.01" class="h-8 text-xs w-full" placeholder="Qty (kg)" required />
                                    <span class="text-[10px] text-gray-500">kg</span>
                                </div>
                            </div>
                            <div v-if="form.ingredients.length === 0" class="text-[10px] text-gray-400 italic text-center py-2">
                                {{ __('Add ingredients to calculate price automatically.') }}
                            </div>
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <InputLabel for="price_per_kg" :value="__('Price / kg')" />
                                <TextInput id="price_per_kg" v-model="form.price_per_kg" type="number" step="0.01" class="mt-1 block w-full" required />
                                <InputError :message="form.errors.price_per_kg" class="mt-1" />
                            </div>
                            <div>
                                <InputLabel for="weight_kg" :value="__('Current Stock (kg)')" />
                                <TextInput id="weight_kg" v-model="form.weight_kg" type="number" step="0.1" class="mt-1 block w-full" required />
                                <InputError :message="form.errors.weight_kg" class="mt-1" />
                            </div>
                        </div>
                        <div>
                            <InputLabel for="notes" :value="__('Notes')" />
                            <textarea id="notes" v-model="form.notes" rows="2" class="mt-1 block w-full border-gray-300 rounded-lg shadow-sm focus:border-farm-500 focus:ring-farm-500 text-sm"></textarea>
                            <InputError :message="form.errors.notes" class="mt-1" />
                        </div>
                        <div class="flex items-center justify-end space-x-3 pt-2">
                            <button type="button" @click="isCreating = false; editingRation = null" class="text-gray-500 text-sm font-bold hover:text-gray-700">{{ __('Cancel') }}</button>
                            <PrimaryButton :disabled="form.processing">
                                {{ editingRation ? __('Update') : __('Save') }}
                            </PrimaryButton>
                        </div>
                    </form>
                </Card>
                <div v-else class="bg-earth-50 border border-dashed border-earth-300 rounded-xl p-8 text-center text-gray-500 text-sm font-medium">
                    {{ __('Select a ration to edit or click "Add New Ration" to register a new mixture.') }}
                </div>
            </div>
        </div>

        <!-- Mix Modal -->
        <div v-if="isMixing" class="fixed inset-0 bg-black/50 flex items-center justify-center z-50 p-4">
            <Card class="max-w-md w-full shadow-2xl">
                <div class="p-6 border-b bg-emerald-50 rounded-t-xl">
                    <h3 class="text-lg font-bold text-emerald-900">{{ __('Mix Batch') }}: {{ mixingRation.name }}</h3>
                    <p class="text-xs text-emerald-700 mt-1">{{ __('Ingredients will be deducted from your stock based on the recipe.') }}</p>
                </div>
                <form @submit.prevent="submitMix" class="p-6 space-y-4">
                    <div>
                        <InputLabel :value="__('Total Batch Weight (kg)')" />
                        <TextInput v-model="mixForm.quantity" type="number" step="0.1" class="mt-1 block w-full" placeholder="e.g. 100" required autofocus />
                        <div class="mt-2 text-gray-600">
                            <p class="text-xs font-bold mb-1">{{ __('Estimated Ingredient Usage') }}:</p>
                            <ul v-if="mixingRation.feeds" class="text-[10px] space-y-0.5">
                                <li v-for="feed in mixingRation.feeds" :key="feed.id" :class="feed.stock < (feed.pivot.quantity * (mixForm.quantity / mixingRation.feeds.reduce((acc, f) => acc + Number(f.pivot.quantity), 0))) ? 'text-red-500 font-bold' : ''">
                                    - {{ feed.name }}: {{ (feed.pivot.quantity * (mixForm.quantity / mixingRation.feeds.reduce((acc, f) => acc + Number(f.pivot.quantity), 0)) || 0).toFixed(2) }} kg 
                                    <span v-if="feed.stock < (feed.pivot.quantity * (mixForm.quantity / mixingRation.feeds.reduce((acc, f) => acc + Number(f.pivot.quantity), 0)))">({{ __('Incomplete stock!') }})</span>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <div class="flex justify-end space-x-3 pt-4">
                        <button type="button" @click="isMixing = false" class="text-gray-500 font-bold hover:text-gray-700">{{ __('Cancel') }}</button>
                        <PrimaryButton class="bg-emerald-600 hover:bg-emerald-700" :disabled="mixForm.processing">
                            {{ __('Mix Now') }}
                        </PrimaryButton>
                    </div>
                </form>
            </Card>
        </div>
    </AuthenticatedLayout>
</template>

<style scoped>
.rounded-b-\[2\.5rem\] {
    border-bottom-left-radius: 2.5rem;
    border-bottom-right-radius: 2.5rem;
}
</style>
