<script setup>
import pluralize from "pluralize";
import NumberFormat from "@/Helpers/NumberFormat";
import MarketLayout from "@/Layouts/MarketLayout.vue";
import Modal from "@/Components/Modal.vue";
import SubscriptionForm from "./Partials/SubscriptionForm.vue";
import { Head, Link } from '@inertiajs/vue3';
import { onMounted, ref } from 'vue';
import { initFlowbite } from 'flowbite'

defineProps({
    service: Object,
})

onMounted(() => {
    initFlowbite();
})

const selectedPlan = ref(null);
const showModal = ref(false);

const handleModalSubscription = (plan) => {
    selectedPlan.value = plan
    showModal.value = true
}

</script>

<template>
    <Head :title="`Sr Developer - Service ${service.name} Overview`" />

    <MarketLayout>
        <div class="pb-20">
            <div class="pt-6 mx-auto">
                <div class="text-center text-4xl font-bold">
                    {{ service.name }}
                </div>
                <!-- Image gallery -->
                <div class="mx-auto mt-6 sm:px-6 lg:max-w-7xl lg:gap-x-8 lg:px-8">

                    <div id="default-carousel" class="relative w-full" data-carousel="slide">
                        <!-- Carousel wrapper -->
                        <div class="relative h-80 overflow-hidden rounded-lg md:h-96">
                            <!-- Item 1 -->
                            <div class="hidden duration-700 ease-in-out" data-carousel-item v-for="picture in service.pictures" :key="picture.id">
                                <img :src="picture.url" class="object-scale-down h-80 absolute block w-full -translate-x-1/2 -translate-y-1/2 top-1/2 left-1/2" :alt="`service picture ${picture.id}`">
                            </div>

                        </div>
                        <!-- Slider indicators -->
                        <div class="absolute z-30 flex space-x-3 -translate-x-1/2 bottom-5 left-1/2">
                            <button type="button" class="w-3 h-3 rounded-full" aria-current="true" :aria-label="`Slide ${key}`" :data-carousel-slide-to="key" v-for="(picture, key) in service.pictures" :key="key"></button>
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

                <div class="w-full bg-white shadow rounded-md m-4 p-4">
                    <h4 class="font-semibold text-lg">Description:</h4>
                    <div class="text-lg flex justify-center">
                        {{ service.description }}
                    </div>
                </div>


                <div class="flex flex-wrap justify-center gap-6">
                    <div class="w-full max-w-sm p-4 bg-white border border-gray-200 rounded-lg shadow sm:p-8 dark:bg-gray-800 dark:border-gray-700" v-for="service in service.service_plans" :key="service.id">
                        <h5 class="mb-4 text-xl font-medium text-gray-500 dark:text-gray-400">{{ service.plan.name }}</h5>
                        <div class="flex items-baseline text-gray-900 dark:text-white">
                            <span class="text-5xl font-extrabold tracking-tight">{{ NumberFormat(service.plan.price) }}</span>
                            <span class="ml-1 text-xl font-normal text-gray-500 dark:text-gray-400">/{{pluralize(service.plan.invoice_interval, service.plan.invoice_period, true)}}</span>
                        </div>
                        <p class="text-gray-500 dark:text-gray-400 my-7">{{ service.plan.description }}</p>
                        <ul role="list" class="space-y-5 my-7">
                            <li class="flex space-x-3 items-center" v-if="service.plan.active_subscribers_limit">
                                <i class="fa-solid fa-circle-check flex-shrink-0 w-4 h-4 text-blue-600 dark:text-blue-500"></i>
                                <span class="text-base font-normal leading-tight text-gray-500 dark:text-gray-400">Limit {{ pluralize('Subscriber', service.plan.active_subscribers_limit, true) }}</span>
                            </li>
                        </ul>
                        <ul role="list" class="space-y-5 my-7">
                            <li class="flex space-x-3 items-center" v-if="service.plan.trial_period">
                                <i class="fa-solid fa-circle-check flex-shrink-0 w-4 h-4 text-blue-600 dark:text-blue-500"></i>
                                <span class="text-base font-normal leading-tight text-gray-500 dark:text-gray-400">Trial Period for {{ pluralize(service.plan.trial_interval, service.plan.trial_period, true) }}</span>
                            </li>
                        </ul>
                        <ul role="list" class="space-y-5 my-7">
                            <li class="flex space-x-3 items-center" v-if="service.plan.discount_period">
                                <b class="font-semibold"> - {{ service.plan.discount_type_amount === 'value' ? NumberFormat(service.plan.discount_amount) : `${service.plan.discount_amount} %` }}</b>
                                <span class="text-base font-normal leading-tight text-gray-500 dark:text-gray-400">Discount for {{ pluralize(service.plan.discount_interval, service.plan.discount_period, true) }} </span>
                                <span class="text-base font-normal leading-tight text-gray-500 dark:text-gray-400" v-if="service.plan.discount_subscribers_limit">Only first {{ service.plan.discount_subscribers_limit }} subscribers </span>
                            </li>
                        </ul>
                        <button @click.prevent="handleModalSubscription(service.plan)" type="button" class="text-white bg-blue-600 hover:bg-blue-700 focus:ring-4 focus:outline-none focus:ring-blue-200 dark:focus:ring-blue-900 font-medium rounded-lg text-sm px-5 py-2.5 inline-flex justify-center w-full text-center">Choose plan</button>
                    </div>

                </div>
            </div>
        </div>
        <Modal :show="showModal" @close="showModal = false">
            <SubscriptionForm
                :plan="selectedPlan"
                @submited="showModal = false"
            ></SubscriptionForm>
        </Modal>
    </MarketLayout>
</template>

