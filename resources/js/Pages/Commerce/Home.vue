<script setup>
import { Head, Link } from '@inertiajs/vue3';
import { Dialog, DialogPanel } from '@headlessui/vue'
import MarketLayout from '@/Layouts/MarketLayout.vue';
import ApplicationLogo from '@/Components/ApplicationLogo.vue';
import ProductList from '@/Components/Commerce/ProductList.vue';
import ServiceList from '@/Components/Commerce/ServiceList.vue';

defineProps({
    products: Array,
    services: Array
})



</script>

<template>
    <Head title="Sr Developer - Home" />

    <MarketLayout>
        <div class="mx-auto max-w-2xl py-16 sm:py-24 lg:py-28">
            <ApplicationLogo
                    class="w-auto fill-current text-gray-800 dark:text-gray-200"
                />
            <div class="text-center">
                <h1 class="text-4xl font-bold tracking-tight text-gray-900 sm:text-6xl">Marketplace For Everyone Buy and Sell Products and Services</h1>
                <p class="mt-6 text-lg leading-8 text-gray-600">Buy products or services from other verified commerces or sell your product and services. Start here and enjoy the best experience with us.</p>
                <div class="mt-10 flex items-center justify-center gap-x-6">
                    <a :href="route('register')" class="rounded-md bg-indigo-600 px-3.5 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">I wanna sell</a>
                    <a href="#market" class="text-sm font-semibold leading-6 text-gray-900">Let's to buy <span aria-hidden="true">→</span></a>
                </div>
            </div>
        </div>
        <div class="absolute inset-x-0 top-[calc(100%-13rem)] -z-10 transform-gpu overflow-hidden blur-3xl sm:top-[calc(100%-30rem)]" aria-hidden="true">
            <div class="relative left-[calc(50%+3rem)] aspect-[1155/678] w-[36.125rem] -translate-x-1/2 bg-gradient-to-tr from-[#ff80b5] to-[#9089fc] opacity-30 sm:left-[calc(50%+36rem)] sm:w-[72.1875rem]" style="clip-path: polygon(74.1% 44.1%, 100% 61.6%, 97.5% 26.9%, 85.5% 0.1%, 80.7% 2%, 72.5% 32.5%, 60.2% 62.4%, 52.4% 68.1%, 47.5% 58.3%, 45.2% 34.5%, 27.5% 76.7%, 0.1% 64.9%, 17.9% 100%, 27.6% 76.8%, 76.1% 97.7%, 74.1% 44.1%)" />
        </div>
        <div id="market">
            <ProductList label="The latest products added" :products="products" :url-more="route('products')"/>
            <ServiceList label="The latest services added" :services="services" :url-more="route('services')"/>
        </div>
    </MarketLayout>

</template>

