<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, useForm } from '@inertiajs/vue3';
import Card from '@/Components/Card.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import InputLabel from '@/Components/InputLabel.vue';
import TextInput from '@/Components/TextInput.vue';
import { ref, computed } from 'vue';

const props = defineProps({
    finances: Array
});

const isAdding = ref(false);

const form = useForm({
    category: '',
    amount: '',
    type: 'expense',
    date: new Date().toISOString().split('T')[0],
    description: ''
});

const submit = () => {
    form.post(route('finances.store'), {
        onSuccess: () => {
            isAdding.value = false;
            form.reset();
        }
    });
};

const deleteTransaction = (id) => {
    if (confirm(__('Delete this transaction?'))) {
        form.delete(route('finances.destroy', id));
    }
};

const totalIncome = computed(() => {
    return props.finances.filter(f => f.type === 'income').reduce((acc, f) => acc + parseFloat(f.amount), 0);
});

const totalExpense = computed(() => {
    return props.finances.filter(f => f.type === 'expense').reduce((acc, f) => acc + parseFloat(f.amount), 0);
});

const balance = computed(() => totalIncome.value - totalExpense.value);

const formatCurrency = (amount) => {
    return new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR' }).format(amount);
};
</script>

<template>
    <Head :title="__('Farm Finances')" />

    <AuthenticatedLayout>
        <template #header>{{ __('Financial Ledger') }}</template>

        <!-- Summary Cards -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
            <Card class="p-6 bg-green-50 border-green-100">
                <p class="text-xs font-bold text-green-600 uppercase tracking-wider mb-1">{{ __('Total Income') }}</p>
                <h3 class="text-2xl font-black text-green-900">{{ formatCurrency(totalIncome) }}</h3>
            </Card>
            <Card class="p-6 bg-red-50 border-red-100">
                <p class="text-xs font-bold text-red-600 uppercase tracking-wider mb-1">{{ __('Total Expenses') }}</p>
                <h3 class="text-2xl font-black text-red-900">{{ formatCurrency(totalExpense) }}</h3>
            </Card>
            <Card class="p-6 bg-farm-50 border-farm-100">
                <p class="text-xs font-bold text-farm-600 uppercase tracking-wider mb-1">{{ __('Net Balance') }}</p>
                <h3 class="text-2xl font-black text-farm-900">{{ formatCurrency(balance) }}</h3>
            </Card>
        </div>

        <div class="flex justify-between items-center mb-6">
            <h2 class="text-2xl font-bold text-gray-800">{{ __('Transactions') }}</h2>
            <PrimaryButton @click="isAdding = true">{{ __('Record Transaction') }}</PrimaryButton>
        </div>

        <div v-if="isAdding" class="mb-8">
            <Card class="p-6">
                <form @submit.prevent="submit" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                    <div class="md:col-span-2">
                        <InputLabel for="category" :value="__('Description')" />
                        <TextInput id="category" v-model="form.category" type="text" class="mt-1 block w-full" required />
                    </div>
                    <div>
                        <InputLabel for="type" :value="__('Type')" />
                        <select id="type" v-model="form.type" class="mt-1 block w-full border-gray-300 rounded-lg shadow-sm focus:border-farm-500 focus:ring-farm-500">
                            <option value="income">{{ __('Income (Pemasukan)') }}</option>
                            <option value="expense">{{ __('Expense (Pengeluaran)') }}</option>
                        </select>
                    </div>
                    <div>
                        <InputLabel for="amount" :value="__('Amount (Rp)')" />
                        <TextInput id="amount" v-model="form.amount" type="number" step="1" class="mt-1 block w-full" required />
                    </div>
                    <div>
                        <InputLabel for="date" :value="__('Date')" />
                        <TextInput id="date" v-model="form.date" type="date" class="mt-1 block w-full" required />
                    </div>
                    <div class="lg:col-span-3 flex justify-end space-x-3 mt-4">
                        <button type="button" @click="isAdding = false" class="text-gray-500 font-bold hover:text-gray-700">{{ __('Cancel') }}</button>
                        <PrimaryButton :disabled="form.processing">{{ __('Save Transaction') }}</PrimaryButton>
                    </div>
                </form>
            </Card>
        </div>

        <div class="space-y-3">
            <Card v-for="finance in finances" :key="finance.id" class="p-4 flex items-center justify-between hover:shadow-md transition">
                <div class="flex items-center space-x-4">
                    <div class="h-10 w-10 rounded-full flex items-center justify-center shrink-0" 
                        :class="finance.type === 'income' ? 'bg-green-100 text-green-600' : 'bg-red-100 text-red-600'">
                        <i v-if="finance.type === 'income'" class="fas fa-arrow-up"></i>
                        <i v-else class="fas fa-arrow-down"></i>
                    </div>
                    <div>
                        <h4 class="font-bold text-gray-900">{{ finance.category }}</h4>
                        <p class="text-xs text-gray-500">{{ new Date(finance.date).toLocaleDateString('id-ID') }}</p>
                    </div>
                </div>
                <div class="flex items-center space-x-6">
                    <span class="font-black text-lg" :class="finance.type === 'income' ? 'text-green-600' : 'text-red-600'">
                        {{ finance.type === 'income' ? '+' : '-' }} {{ formatCurrency(finance.amount) }}
                    </span>
                    <button @click="deleteTransaction(finance.id)" class="text-gray-300 hover:text-red-500 transition">
                        <i class="fas fa-trash-can"></i>
                    </button>
                </div>
            </Card>
            <div v-if="finances.length === 0" class="text-center py-12 text-gray-500 bg-earth-50 rounded-2xl border-2 border-dashed border-earth-200">
                {{ __('No financial records yet.') }}
            </div>
        </div>
    </AuthenticatedLayout>
</template>
