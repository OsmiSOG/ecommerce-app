<script setup>
import { useForm } from "@inertiajs/vue3";
import { onMounted, ref } from "vue";

const props = defineProps({
    cart: Object,
})

const identificationTypes = [
    'CC', 'CE', 'TI'
];

const cardBrand = ref('unknown')

let cardBrandToClass = {
    'visa': 'fab fa-cc-visa fa-lg',
    'mastercard': 'fab fa-cc-mastercard fa-lg',
    'amex': 'fab fa-cc-amex fa-lg',
    'discover': 'fab fa-cc-discover fa-lg',
    'diners': 'fab fa-cc-diners-club fa-lg',
    'jcb': 'fab fa-cc-jcb fa-lg',
    'unknown': 'fa fa-credit-card fa-lg',
};

const setCardbrand = (e) => {
    cardBrand.value = e.target.getAttribute('data-card-brand');
}

const form = useForm({
    cart_id: props.cart.id,
    card_holder: '',
    card_number: '',
    card_datetime: '',
    card_cvv: '',
    card_installments: '',
    identification_type: '',
    identification_number: '',
    number_phone: '',
});

const emits = defineEmits(['submited'])

const submit = () => {
    form.transform((data) => ({
        ...data,
        card_datetime: data.card_datetime.replaceAll(' ', '').replace('/', '-'),
    })).post(route('cart.checkout'), {
        onError: () => {},
        onSuccess: () => {
            form.reset()
            emits('submited');
        },
    });
};

</script>

<template>
    <form @submit.prevent="submit" class="p-10">
        <h4 class="text-center font-semibold text-xl text-gray-700 dark:text-gray-200">Checkout For Shoping Cart</h4>
        <div class="mb-6">
            <label for="identification_type" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Identification Type</label>
            <select v-model="form.identification_type" id="identification_type" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                <option v-for="(type, key) in identificationTypes" :key="key" :value="type">{{ type }}</option>
            </select>
            <p v-if="form.errors.identification_type" class="mt-2 text-sm text-red-600 dark:text-red-500"><span class="font-medium">Oh, snapp!</span> {{ form.errors.identification_type }}</p>
        </div>
        <div class="mb-6">
            <label for="identification_number" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Identification Number</label>
            <input v-model="form.identification_number" type="number" id="identification_number" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="Basic Plan" required>
            <p v-if="form.errors.identification_number" class="mt-2 text-sm text-red-600 dark:text-red-500"><span class="font-medium">Oh, snapp!</span> {{ form.errors.identification_number }}</p>
        </div>
        <div class="mb-6">
            <label for="number_phone" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Number Phone</label>
            <input v-model="form.number_phone" type="number" id="number_phone" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="Basic Plan" required>
            <p v-if="form.errors.number_phone" class="mt-2 text-sm text-red-600 dark:text-red-500"><span class="font-medium">Oh, snapp!</span> {{ form.errors.number_phone }}</p>
        </div>
        <div class="mb-6">
            <label for="card_holder" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Card Holder</label>
            <input v-model="form.card_holder" type="text" id="card_holder" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="Basic Plan" required>
            <p v-if="form.errors.card_holder" class="mt-2 text-sm text-red-600 dark:text-red-500"><span class="font-medium">Oh, snapp!</span> {{ form.errors.card_holder }}</p>
        </div>
        <div class="mb-6">
            <label for="card_number" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Card Number</label>
            <div class="flex">
                <span class="inline-flex items-center px-3 text-sm text-gray-900 bg-gray-200 border border-r-0 border-gray-300 rounded-l-md dark:bg-gray-600 dark:text-gray-400 dark:border-gray-600">
                    <i :class="cardBrandToClass[cardBrand]"></i>
                </span>
                <input v-cardformat:formatCardNumber v-model="form.card_number" @change="setCardbrand" type="text" id="card_number" class="rounded-none rounded-r-lg bg-gray-50 border text-gray-900 focus:ring-blue-500 focus:border-blue-500 block flex-1 min-w-0 w-full text-sm border-gray-300 p-2.5  dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="Basic Plan" required>
            </div>
            <p v-if="form.errors.card_number" class="mt-2 text-sm text-red-600 dark:text-red-500"><span class="font-medium">Oh, snapp!</span> {{ form.errors.card_number }}</p>
        </div>
        <div class="mb-6">
            <label for="card_datetime" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Card Expiry</label>
            <input v-cardformat:formatCardExpiry v-model="form.card_datetime" type="text" id="card_datetime" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="Basic Plan" required>
            <p v-if="form.errors.card_datetime" class="mt-2 text-sm text-red-600 dark:text-red-500"><span class="font-medium">Oh, snapp!</span> {{ form.errors.card_datetime }}</p>
        </div>
        <div class="mb-6">
            <label for="card_cvv" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Card CVV</label>
            <input v-cardformat:formatCardCVC v-model="form.card_cvv" type="number" id="card_cvv" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="Basic Plan" required>
            <p v-if="form.errors.card_installments" class="mt-2 text-sm text-red-600 dark:text-red-500"><span class="font-medium">Oh, snapp!</span> {{ form.errors.card_cvv }}</p>
        </div>
        <div class="mb-6">
            <label for="card_installments" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Installments</label>
            <input v-cardformat:formatCardCVC v-model="form.card_installments" type="number" id="card_installments" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="Basic Plan" required>
            <p v-if="form.errors.card_installments" class="mt-2 text-sm text-red-600 dark:text-red-500"><span class="font-medium">Oh, snapp!</span> {{ form.errors.card_installments }}</p>
        </div>

        <button type="submit" :disabled="form.processing" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm w-full sm:w-auto px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">Submit</button>
    </form>
</template>
