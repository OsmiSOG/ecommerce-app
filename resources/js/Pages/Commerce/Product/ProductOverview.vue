<script setup>
import NumberFormat from "@/Helpers/NumberFormat";
import MarketLayout from "@/Layouts/MarketLayout.vue";
import { Head, Link } from '@inertiajs/vue3';
import { onMounted } from 'vue';
import { initFlowbite } from 'flowbite'

defineProps({
    product: Object,
})

onMounted(() => {
    initFlowbite();
})

</script>

<template>
    <Head :title="`Sr Developer - Product ${product.name} Overview`" />

    <MarketLayout>
        <div>
            <div class="pt-6">

                <!-- Image gallery -->
                <div class="mx-auto mt-6 sm:px-6 lg:max-w-7xl lg:gap-x-8 lg:px-8">

                    <div id="default-carousel" class="relative w-full" data-carousel="slide">
                        <!-- Carousel wrapper -->
                        <div class="relative h-80 overflow-hidden rounded-lg md:h-96">
                            <!-- Item 1 -->
                            <div class="hidden duration-700 ease-in-out" data-carousel-item v-for="picture in product.pictures" :key="picture.id">
                                <img :src="picture.url" class="object-scale-down h-80 absolute block w-full -translate-x-1/2 -translate-y-1/2 top-1/2 left-1/2" :alt="`product picture ${picture.id}`">
                            </div>

                        </div>
                        <!-- Slider indicators -->
                        <div class="absolute z-30 flex space-x-3 -translate-x-1/2 bottom-5 left-1/2">
                            <button type="button" class="w-3 h-3 rounded-full" aria-current="true" :aria-label="`Slide ${key}`" :data-carousel-slide-to="key" v-for="(picture, key) in product.pictures" :key="key"></button>
                        </div>
                        <!-- Slider controls -->
                        <button type="button" class="absolute top-0 left-0 z-30 flex items-center justify-center h-full px-4 cursor-pointer group focus:outline-none" data-carousel-prev>
                            <span class="inline-flex items-center justify-center w-10 h-10 rounded-full bg-white/30 dark:bg-gray-800/30 group-hover:bg-white/50 dark:group-hover:bg-gray-800/60 group-focus:ring-4 group-focus:ring-white dark:group-focus:ring-gray-800/70 group-focus:outline-none">
                                <svg class="w-4 h-4 text-white dark:text-gray-800" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 6 10">
                                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 1 1 5l4 4"/>
                                </svg>
                                <span class="sr-only">Previous</span>
                            </span>
                        </button>
                        <button type="button" class="absolute top-0 right-0 z-30 flex items-center justify-center h-full px-4 cursor-pointer group focus:outline-none" data-carousel-next>
                            <span class="inline-flex items-center justify-center w-10 h-10 rounded-full bg-white/30 dark:bg-gray-800/30 group-hover:bg-white/50 dark:group-hover:bg-gray-800/60 group-focus:ring-4 group-focus:ring-white dark:group-focus:ring-gray-800/70 group-focus:outline-none">
                                <svg class="w-4 h-4 text-white dark:text-gray-800" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 6 10">
                                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 9 4-4-4-4"/>
                                </svg>
                                <span class="sr-only">Next</span>
                            </span>
                        </button>
                    </div>

                </div>

                <!-- Product info -->
                <div class="mx-auto max-w-2xl px-4 pb-16 pt-10 sm:px-6 lg:grid lg:max-w-7xl lg:grid-cols-3 lg:grid-rows-[auto,auto,1fr] lg:gap-x-8 lg:px-8 lg:pb-24 lg:pt-16">
                    <div class="lg:col-span-2 lg:border-r lg:border-gray-200 lg:pr-8">
                        <h1 class="text-2xl font-bold tracking-tight text-gray-900 sm:text-3xl">{{product.name}}</h1>
                    </div>

                    <!-- Options -->
                    <div class="mt-4 lg:row-span-3 lg:mt-0">
                        <h2 class="sr-only">Product information</h2>
                        <p class="text-3xl tracking-tight text-gray-900">{{NumberFormat(product.price)}}</p>

                        <form class="mt-10">
                            <Link :href="route('cart.add', product.id)" method="post" as="button" preserve-scroll class="mt-10 flex w-full items-center justify-center rounded-md border border-transparent bg-indigo-600 px-8 py-3 text-base font-medium text-white hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                                Add to bag
                            </Link>
                        </form>
                    </div>

                    <div class="py-10 lg:col-span-2 lg:col-start-1 lg:border-r lg:border-gray-200 lg:pb-16 lg:pr-8 lg:pt-6">
                        <!-- Description and details -->
                        <div>
                            <h3 class="sr-only">Description</h3>

                            <div class="space-y-6">
                                <p class="text-base text-gray-900">
                                    {{ product.description }}
                                </p>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </MarketLayout>

</template>

