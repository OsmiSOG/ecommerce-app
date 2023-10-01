<script setup>
import { useForm } from "@inertiajs/vue3";

const props = defineProps({
    product: Object,
})

const form = useForm({
    stock: 0,
    serials: [],
    automatic: false,
});

const emits = defineEmits(['submited'])

const submit = () => {
    form.post(route('sell.product.stock.store', props.product.id), {
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

    --vs-selected-bg: #43637e;
    --vs-selected-color: #272019;

    --vs-search-input-color: #eeeeee;
    --vs-search-input-bg: rgb(255, 255, 255);

    --vs-dropdown-option--active-bg: #4d537e;
    --vs-dropdown-option--active-color: #eeeeee;
}
</style>

<template>
    <form @submit.prevent="submit" class="p-10">
        <h4 class="text-center font-semibold text-xl text-gray-700 dark:text-gray-200">Add stock for {{ product.name }}</h4>
        <div class="mb-6">
            <label for="stock" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">How many products would you like to add?</label>
            <input v-model="form.stock" type="number" id="name" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="Basic Plan" required>
            <p v-if="form.errors.stock" class="mt-2 text-sm text-red-600 dark:text-red-500"><span class="font-medium">Oh, snapp!</span> {{ form.errors.stock }}</p>
        </div>
        <div class="mb-6">
            <label for="serials" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Add the references of your products</label>
            <v-select v-model="form.serials" taggable multiple :disabled="form.serials.length >= form.stock" :deselectFromDropdown="true" class="bg-gray-50 border border-gray-300 text-gray-900 rounded-lg dark:bg-gray-700 dark:border-gray-600 dark:text-white"></v-select>
            <p v-if="form.errors.serials" class="mt-2 text-sm text-red-600 dark:text-red-500"><span class="font-medium">Oh, snapp!</span> {{ form.errors.serials }}</p>
        </div>

        <div class="flex items-center mb-4" v-if="form.stock - form.serials.length > 1">
            <input v-model="form.automatic" id="automatic" type="checkbox" value="" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
            <label for="automatic" class="ml-2 text-sm font-medium text-gray-900 dark:text-gray-300">Would you like that we asign them the references?</label>
            <p v-if="form.errors.automatic" class="mt-2 text-sm text-red-600 dark:text-red-500"><span class="font-medium">Oh, snapp!</span> {{ form.errors.automatic }}</p>
        </div>

        <button type="submit" :disabled="form.processing" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm w-full sm:w-auto px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">Submit</button>
    </form>
</template>
