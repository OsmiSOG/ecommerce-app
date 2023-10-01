<script setup>
import AdminLayout from "@/Layouts/AdminLayout.vue";
import { Head, useForm } from "@inertiajs/vue3";
import { ref, watch } from 'vue';

const props = defineProps({
    categories: Array
})

const subcategories = ref([])
const categoryId = ref(null)

const form = useForm({
    category_id: null,
    subcategory_id: null,
    name: null,
    description: null,
    limit: null,
});

watch(categoryId, (data, prev) => {
    form.category_id = data
    if (data) {
        subcategories.value = props.categories.find(category => category.id === data).subcategories
    } else {
        subcategories.value = []
        form.subcategory_id = null
    }
})

const submit = () => {
    form.post(route('sell.service.store'), {
        onError: () => {},
        onSuccess: () => form.reset(),
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
    <Head title="Admin - Sell Product"></Head>
    <AdminLayout>
        <div class="relative overflow-x-auto sm:rounded-lg p-12 mx-12 shadow-lg">
            <h1 class="text-gray-900 dark:text-gray-100 text-3xl mb-7 font-semibold text-center">What are you going to sell ?</h1>
            <form @submit.prevent="submit">
                <div class="grid gap-6 mb-6 md:grid-cols-2">
                    <div>
                        <label for="name" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Category</label>
                        <v-select v-model="categoryId" :options="categories" label="name" :reduce="category => category.id" class="bg-gray-50 border border-gray-300 text-gray-900 rounded-lg dark:bg-gray-700 dark:border-gray-600 dark:text-white"></v-select>
                    </div>
                    <div>
                        <label for="name" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Subcategory</label>
                        <v-select v-model="form.subcategory_id" :options="subcategories" label="name" :reduce="subcategory => subcategory.id" class="bg-gray-50 border border-gray-300 text-gray-900 rounded-lg dark:bg-gray-700 dark:border-gray-600 dark:text-white"></v-select>
                    </div>
                </div>
                <div class="mb-6">
                    <label for="name" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Name</label>
                    <input v-model="form.name" type="text" id="name" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="product xyz" required>
                    <p v-if="form.errors.name" class="mt-2 text-sm text-red-600 dark:text-red-500"><span class="font-medium">Oh, snapp!</span> {{ form.errors.name }}</p>
                </div>
                <div class="mb-6">
                    <label for="description" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Description</label>
                    <textarea v-model="form.description"
                        id="description" rows="4" class="block p-2.5 w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                        placeholder="Write the product description..."
                    ></textarea>
                    <p v-if="form.errors.description" class="mt-2 text-sm text-red-600 dark:text-red-500"><span class="font-medium">Oh, snapp!</span> {{ form.errors.description }}</p>
                </div>
                <div class="grid gap-6 md:grid-cols-2">
                    <div class="mb-6">
                        <label for="limit" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Limit</label>
                        <input v-model="form.limit" type="number" id="limit" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                        <p v-if="form.errors.limit" class="mt-2 text-sm text-red-600 dark:text-red-500"><span class="font-medium">Oh, snapp!</span> {{ form.errors.limit }}</p>
                    </div>
                </div>

                <button type="submit" :disabled="form.processing" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm w-full sm:w-auto px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">Submit</button>
            </form>
        </div>
    </AdminLayout>
</template>
