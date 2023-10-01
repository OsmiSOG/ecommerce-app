<script setup>
import { useForm } from "@inertiajs/vue3";

const props = defineProps({
    service: Object,
    currencies: Array,
    frequencies: Array,
    discountTypes: Array,
})

const form = useForm({
    name: '',
    description: '',
    price: 0,
    currency: null,
    trial_period: 0,
    trial_interval: null,
    invoice_period: 1,
    invoice_interval: 'month',
    grace_period: 3,
    grace_interval: 'day',
    discount_period: 0,
    discount_interval: null,
    discount_subscribers_limit: null,
    discount_type_amount: null,
    discount_amount: null,
    sort_order: null,
    active_subscribers_limit: null,
});

const emits = defineEmits(['submited'])

const submit = () => {
    form.post(route('sell.service.plan.store', props.service.id), {
        onError: () => {},
        onSuccess: () => {
            form.reset()
            emits('submited');
        },
    });
};

</script>

<style scoped>
:deep() {
    --vs-controls-color: #7189c9;

    --vs-dropdown-bg: #282c34;
    --vs-dropdown-color: #eeeeee;
    --vs-dropdown-option-color: #eeeeee;

    --vs-selected-bg: #4296db;
    --vs-selected-color: #eeeeee;

    --vs-search-input-color: #eeeeee;
    --vs-search-input-bg: rgb(255, 255, 255);

    --vs-dropdown-option--active-bg: #4d537e;
    --vs-dropdown-option--active-color: #eeeeee;
}
</style>

<template>
    <form @submit.prevent="submit" class="p-10">
        <h4 class="text-center font-semibold text-xl text-gray-700 dark:text-gray-200">Create new plan</h4>
        <div class="mb-6">
            <label for="name" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Name</label>
            <input v-model="form.name" type="text" id="name" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="Basic Plan" required>
            <p v-if="form.errors.name" class="mt-2 text-sm text-red-600 dark:text-red-500"><span class="font-medium">Oh, snapp!</span> {{ form.errors.name }}</p>
        </div>
        <div class="mb-6">
            <label for="description" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Description</label>
            <textarea v-model="form.description"
                id="description" rows="4" class="block p-2.5 w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                placeholder="Write the plan description..."
            ></textarea>
            <p v-if="form.errors.description" class="mt-2 text-sm text-red-600 dark:text-red-500"><span class="font-medium">Oh, snapp!</span> {{ form.errors.description }}</p>
        </div>

        <!-- Price -->
        <div class="grid gap-6 mb-6 md:grid-cols-2">
            <div>
                <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Currency</label>
                <v-select v-model="form.currency" :options="currencies" class="bg-gray-50 border border-gray-300 text-gray-900 rounded-lg dark:bg-gray-700 dark:border-gray-600 dark:text-white"></v-select>
                <p v-if="form.errors.currency" class="mt-2 text-sm text-red-600 dark:text-red-500"><span class="font-medium">Oh, snapp!</span> {{ form.errors.currency }}</p>
            </div>
            <div>
                <label for="price" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Price</label>
                <input v-model="form.price" type="text" id="price" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="1" required>
                <p v-if="form.errors.price" class="mt-2 text-sm text-red-600 dark:text-red-500"><span class="font-medium">Oh, snapp!</span> {{ form.errors.price }}</p>
            </div>
        </div>
        <hr class="mb-6">

        <!-- Interval -->
        <div class="grid gap-6 mb-6 md:grid-cols-2">
            <div>
                <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Trial Interval</label>
                <v-select v-model="form.trial_interval" :options="frequencies" label="trial" class="bg-gray-50 border border-gray-300 text-gray-900 rounded-lg dark:bg-gray-700 dark:border-gray-600 dark:text-white"></v-select>
                <p v-if="form.errors.trial_interval" class="mt-2 text-sm text-red-600 dark:text-red-500"><span class="font-medium">Oh, snapp!</span> {{ form.errors.trial_interval }}</p>
            </div>
            <div>
                <label for="trialPeriod" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Trial Period</label>
                <input v-model="form.trial_period" type="number" id="trialPeriod" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="1">
                <p v-if="form.errors.trial_period" class="mt-2 text-sm text-red-600 dark:text-red-500"><span class="font-medium">Oh, snapp!</span> {{ form.errors.trial_period }}</p>
            </div>
        </div>
        <hr class="mb-6">

        <!-- Invoice -->
        <div class="grid gap-6 mb-6 md:grid-cols-2">
            <div>
                <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Invoice Interval</label>
                <v-select v-model="form.invoice_interval" :options="frequencies" label="trial" class="bg-gray-50 border border-gray-300 text-gray-900 rounded-lg dark:bg-gray-700 dark:border-gray-600 dark:text-white"></v-select>
                <p v-if="form.errors.invoice_interval" class="mt-2 text-sm text-red-600 dark:text-red-500"><span class="font-medium">Oh, snapp!</span> {{ form.errors.invoice_interval }}</p>
            </div>
            <div>
                <label for="invoicePeriod" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">invoice Period</label>
                <input v-model="form.invoice_period" type="number" id="invoicePeriod" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="100">
                <p v-if="form.errors.invoice_period" class="mt-2 text-sm text-red-600 dark:text-red-500"><span class="font-medium">Oh, snapp!</span> {{ form.errors.invoice_period }}</p>
            </div>
        </div>
        <hr class="mb-6">

        <!-- Grace -->
        <div class="grid gap-6 mb-6 md:grid-cols-2">
            <div>
                <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Grace Interval</label>
                <v-select v-model="form.grace_interval" :options="frequencies" label="trial" class="bg-gray-50 border border-gray-300 text-gray-900 rounded-lg dark:bg-gray-700 dark:border-gray-600 dark:text-white"></v-select>
                <p v-if="form.errors.grace_interval" class="mt-2 text-sm text-red-600 dark:text-red-500"><span class="font-medium">Oh, snapp!</span> {{ form.errors.grace_interval }}</p>
            </div>
            <div>
                <label for="gracePeriod" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Grace Period</label>
                <input v-model="form.grace_period" type="number" id="gracePeriod" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="1">
                <p v-if="form.errors.grace_period" class="mt-2 text-sm text-red-600 dark:text-red-500"><span class="font-medium">Oh, snapp!</span> {{ form.errors.grace_period }}</p>
            </div>
        </div>
        <hr class="mb-6">

        <!-- Discount -->
        <div class="grid gap-6 mb-6 md:grid-cols-2">
            <div>
                <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Discount Interval</label>
                <v-select v-model="form.discount_interval" :options="frequencies" label="discountInterval" class="bg-gray-50 border border-gray-300 text-gray-900 rounded-lg dark:bg-gray-700 dark:border-gray-600 dark:text-white"></v-select>
                <p v-if="form.errors.discount_interval" class="mt-2 text-sm text-red-600 dark:text-red-500"><span class="font-medium">Oh, snapp!</span> {{ form.errors.discount_interval }}</p>
            </div>
            <div>
                <label for="discountPeriod" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Discount Period</label>
                <input v-model="form.discount_period" type="number" id="discountPeriod" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="1">
                <p v-if="form.errors.discount_period" class="mt-2 text-sm text-red-600 dark:text-red-500"><span class="font-medium">Oh, snapp!</span> {{ form.errors.discount_period }}</p>
            </div>
        </div>
        <div class="grid gap-6 mb-6 md:grid-cols-3">
            <div>
                <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Discount Type</label>
                <v-select v-model="form.discount_type_amount" :options="discountTypes" label="discountType" class="bg-gray-50 border border-gray-300 text-gray-900 rounded-lg dark:bg-gray-700 dark:border-gray-600 dark:text-white"></v-select>
                <p v-if="form.errors.discount_type_amount" class="mt-2 text-sm text-red-600 dark:text-red-500"><span class="font-medium">Oh, snapp!</span> {{ form.errors.discount_type_amount }}</p>
            </div>
            <div>
                <label for="discountValue" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Discount Value</label>
                <input v-model="form.discount_amount" type="number" id="discountValue" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="1">
                <p v-if="form.errors.discount_amount" class="mt-2 text-sm text-red-600 dark:text-red-500"><span class="font-medium">Oh, snapp!</span> {{ form.errors.discount_amount }}</p>
            </div>
            <div>
                <label for="discountLimit" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Discount Limit</label>
                <input v-model="form.discount_subscribers_limit" type="number" id="discountLimit" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                <p v-if="form.errors.discount_subscribers_limit" class="mt-2 text-sm text-red-600 dark:text-red-500"><span class="font-medium">Oh, snapp!</span> {{ form.errors.discount_subscribers_limit }}</p>
            </div>
        </div>
        <hr class="mb-6">

        <div class="grid gap-6 md:grid-cols-2">
            <div>
                <label for="subscribersLimit" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Subscribers Limit</label>
                <input v-model="form.active_subscribers_limit" type="number" id="subscribersLimit" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                <p v-if="form.errors.active_subscribers_limit" class="mt-2 text-sm text-red-600 dark:text-red-500"><span class="font-medium">Oh, snapp!</span> {{ form.errors.active_subscribers_limit }}</p>
            </div>
            <div class="mb-6">
                <label for="sortOrder" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Sort Order</label>
                <input v-model="form.sort_order" type="number" id="sortOrder" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                <p v-if="form.errors.sort_order" class="mt-2 text-sm text-red-600 dark:text-red-500"><span class="font-medium">Oh, snapp!</span> {{ form.errors.sort_order }}</p>
            </div>
        </div>

        <button type="submit" :disabled="form.processing" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm w-full sm:w-auto px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">Submit</button>
    </form>
</template>
