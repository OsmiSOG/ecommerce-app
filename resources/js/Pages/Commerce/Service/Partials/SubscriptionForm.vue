<script setup>
import { useForm } from "@inertiajs/vue3";
import { onMounted, ref } from "vue";
import Swal from "sweetalert2";

const props = defineProps({
    plan: Object,
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
    plan_id: props.plan.id,
    card_holder: '',
    card_number: '',
    card_datetime: '',
    card_cvv: '',
    identification_type: '',
    identification_number: '',
    number_phone: '',
});

const emits = defineEmits(['submited'])

const submit = () => {
    form.transform((data) => ({
        ...data,
        card_datetime: data.card_datetime.replaceAll(' ', '').replace('/', '-'),
    })).post(route('subscription.store'), {
        onError: () => {
            Swal.fire('Ops', 'Something went wrong', 'error');
        },
        onSuccess: () => {
            form.reset()
            Swal.fire({
                title: 'Approved Payment',
                text: 'Successfull purcharse',
                icon: 'success',
                timer: 2000,
            });
            emits('submited');
        },
    });
};

</script>

<template>
    <form @submit.prevent="submit" class="p-10">
        <h4 class="text-center font-semibold text-xl text-gray-700 dark:text-gray-200 mb-6">Subscription to {{ plan.name }}</h4>
        <div class="grid grid-cols-3 gap-4">
            <div class="mb-6">
                <label for="identification_type" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Identification Type</label>
                <select v-model="form.identification_type" id="identification_type" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                    <option v-for="(type, key) in identificationTypes" :key="key" :value="type">{{ type }}</option>
                </select>
                <p v-if="form.errors.identification_type" class="mt-2 text-sm text-red-600 dark:text-red-500"><span class="font-medium">Oh, snapp!</span> {{ form.errors.identification_type }}</p>
            </div>
            <div class="mb-6 col-span-2">
                <label for="identification_number" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Identification Number</label>
                <input v-model="form.identification_number" type="number" id="identification_number" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="00000000" required>
                <p v-if="form.errors.identification_number" class="mt-2 text-sm text-red-600 dark:text-red-500"><span class="font-medium">Oh, snapp!</span> {{ form.errors.identification_number }}</p>
            </div>
        </div>
        <div class="mb-6">
            <label for="number_phone" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Number Phone</label>
            <input v-model="form.number_phone" type="number" id="number_phone" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="3000000" required>
            <p v-if="form.errors.number_phone" class="mt-2 text-sm text-red-600 dark:text-red-500"><span class="font-medium">Oh, snapp!</span> {{ form.errors.number_phone }}</p>
        </div>
        <div class="mb-6">
            <label for="card_holder" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Card Holder</label>
            <input v-model="form.card_holder" type="text" id="card_holder" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" required>
            <p v-if="form.errors.card_holder" class="mt-2 text-sm text-red-600 dark:text-red-500"><span class="font-medium">Oh, snapp!</span> {{ form.errors.card_holder }}</p>
        </div>
        <div class="mb-6 col-span-3">
            <label for="card_number" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Card Number</label>
            <div class="flex">
                <span class="inline-flex items-center px-3 text-sm text-gray-900 bg-gray-200 border border-r-0 border-gray-300 rounded-l-md dark:bg-gray-600 dark:text-gray-400 dark:border-gray-600">
                    <i :class="cardBrandToClass[cardBrand]"></i>
                </span>
                <input v-cardformat:formatCardNumber v-model="form.card_number" @change="setCardbrand" type="text" id="card_number" class="rounded-none rounded-r-lg bg-gray-50 border text-gray-900 focus:ring-blue-500 focus:border-blue-500 block flex-1 min-w-0 w-full text-sm border-gray-300 p-2.5  dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" required>
            </div>
            <p v-if="form.errors.card_number" class="mt-2 text-sm text-red-600 dark:text-red-500"><span class="font-medium">Oh, snapp!</span> {{ form.errors.card_number }}</p>
        </div>
        <div class="grid grid-cols-2 gap-4">
            <div class="mb-6">
                <label for="card_cvv" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Card CVV</label>
                <input v-cardformat:formatCardCVC v-model="form.card_cvv" type="number" id="card_cvv" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" required>
                <p v-if="form.errors.card_cvv" class="mt-2 text-sm text-red-600 dark:text-red-500"><span class="font-medium">Oh, snapp!</span> {{ form.errors.card_cvv }}</p>
            </div>
            <div class="mb-6">
                <label for="card_datetime" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Card Expiry</label>
                <input v-cardformat:formatCardExpiry v-model="form.card_datetime" type="text" id="card_datetime" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="mm / yy" required>
                <p v-if="form.errors.card_datetime" class="mt-2 text-sm text-red-600 dark:text-red-500"><span class="font-medium">Oh, snapp!</span> {{ form.errors.card_datetime }}</p>
            </div>
        </div>

        <button type="submit" :disabled="form.processing" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm w-full sm:w-auto px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
            <span v-if="!form.processing">Submit</span>
            <div v-else role="status">
                <svg aria-hidden="true" class="inline w-4 h-4 mr-2 text-gray-200 animate-spin dark:text-gray-600 fill-gray-600 dark:fill-gray-300" viewBox="0 0 100 101" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M100 50.5908C100 78.2051 77.6142 100.591 50 100.591C22.3858 100.591 0 78.2051 0 50.5908C0 22.9766 22.3858 0.59082 50 0.59082C77.6142 0.59082 100 22.9766 100 50.5908ZM9.08144 50.5908C9.08144 73.1895 27.4013 91.5094 50 91.5094C72.5987 91.5094 90.9186 73.1895 90.9186 50.5908C90.9186 27.9921 72.5987 9.67226 50 9.67226C27.4013 9.67226 9.08144 27.9921 9.08144 50.5908Z" fill="currentColor"/>
                    <path d="M93.9676 39.0409C96.393 38.4038 97.8624 35.9116 97.0079 33.5539C95.2932 28.8227 92.871 24.3692 89.8167 20.348C85.8452 15.1192 80.8826 10.7238 75.2124 7.41289C69.5422 4.10194 63.2754 1.94025 56.7698 1.05124C51.7666 0.367541 46.6976 0.446843 41.7345 1.27873C39.2613 1.69328 37.813 4.19778 38.4501 6.62326C39.0873 9.04874 41.5694 10.4717 44.0505 10.1071C47.8511 9.54855 51.7191 9.52689 55.5402 10.0491C60.8642 10.7766 65.9928 12.5457 70.6331 15.2552C75.2735 17.9648 79.3347 21.5619 82.5849 25.841C84.9175 28.9121 86.7997 32.2913 88.1811 35.8758C89.083 38.2158 91.5421 39.6781 93.9676 39.0409Z" fill="currentFill"/>
                </svg>
                <span class="sr-only">Loading...</span>
            </div>
        </button>
    </form>
</template>
