<script setup>
import ApplicationLogo from '@/Components/ApplicationLogo.vue';
import AdminLayout from '@/Layouts/AdminLayout.vue';
import { Head } from '@inertiajs/vue3';
</script>

<template>
    <Head title="Dashboard" />

    <AdminLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Dashboard</h2>
        </template>

        <div class="py-12">
            <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
                <ApplicationLogo
                    class="w-auto fill-current text-gray-800 dark:text-gray-200"
                />
                <div class="text-center">
                    <h1 class="text-4xl font-bold tracking-tight text-gray-900 sm:text-6xl dark:text-gray-200"> Welcome </h1>
                    <p class="mt-6 text-lg leading-8 text-gray-600">Buy products or services from other verified commerces or sell your product and services. Start here and enjoy the best experience with us.</p>

                </div>
            </div>
        </div>
    </AdminLayout>
</template>
