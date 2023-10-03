<script setup>
import NumberFormat from '@/Helpers/NumberFormat';
import AdminLayout from '@/Layouts/AdminLayout.vue';
import { Head } from '@inertiajs/vue3';

defineProps({
    productsDetail: Object,
    subscriptionsDetail: Object,
})

</script>

<template>
    <Head title="Dashboard" />

    <AdminLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Dashboard</h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="grid grid-cols-2 gap-6">
                    <div class="p-6 bg-green-200 border border-green-200 rounded-lg shadow dark:bg-green-800 dark:border-green-700">
                        <a href="#">
                            <h5 class="mb-2 text-2xl font-bold tracking-tight text-gray-900 dark:text-white">Sold Unit Products</h5>
                        </a>
                        <p class="mb-3 font-bold text-4xl text-gray-100 dark:text-gray-100">{{ productsDetail.total }}</p>
                    </div>
                    <div class="p-6 bg-green-200 border border-green-200 rounded-lg shadow dark:bg-green-800 dark:border-green-700">
                        <a href="#">
                            <h5 class="mb-2 text-2xl font-bold tracking-tight text-gray-900 dark:text-white">Sold Products Amount</h5>
                        </a>
                        <p class="mb-3 font-bold text-4xl text-gray-100 dark:text-gray-100">{{ NumberFormat(productsDetail.amount) }}</p>
                    </div>

                    <div class="p-6 bg-yellow-200 border border-yellow-400 rounded-lg shadow dark:bg-yellow-400 dark:border-yellow-400">
                        <a href="#">
                            <h5 class="mb-2 text-2xl font-bold tracking-tight text-gray-900 dark:text-white">Subscriptions</h5>
                        </a>
                        <p class="mb-3 font-bold text-4xl text-gray-100 dark:text-gray-100">{{ subscriptionsDetail.total }}</p>
                    </div>
                    <div class="p-6 bg-yellow-200 border border-yellow-400 rounded-lg shadow dark:bg-yellow-400 dark:border-yellow-400">
                        <a href="#">
                            <h5 class="mb-2 text-2xl font-bold tracking-tight text-gray-900 dark:text-white">collected by subscriptions</h5>
                        </a>
                        <p class="mb-3 font-bold text-4xl text-gray-100 dark:text-gray-100">{{ NumberFormat(subscriptionsDetail.amount) }}</p>
                    </div>

                </div>
            </div>
        </div>
    </AdminLayout>
</template>
