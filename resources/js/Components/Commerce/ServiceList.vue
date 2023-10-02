<script setup>
import { Link } from '@inertiajs/vue3';


defineProps({
    label: String,
    services: Array,
    urlMore: String
})

</script>

<template>
    <div class="bg-white">
        <div class="mx-auto max-w-2xl px-4 py-16 sm:px-6 sm:py-24 lg:max-w-7xl lg:px-8">
            <div class="flex justify-between">
                <h2 class="text-2xl font-bold tracking-tight text-gray-900"> {{label}} </h2>
                <Link v-if="urlMore" :href="urlMore" class="font-medium text-blue-600 dark:text-blue-500 hover:underline">View more</Link>
            </div>

            <div class="mt-6 grid grid-cols-1 gap-x-6 gap-y-10 sm:grid-cols-2 lg:grid-cols-4 xl:gap-x-8">
                <div v-for="service in services" :key="service.id" class="group relative">
                    <div class="aspect-h-1 aspect-w-1 w-full overflow-hidden rounded-md bg-gray-200 lg:aspect-none group-hover:opacity-75 lg:h-80">
                        <img :src="service.front_picture.url" :alt="service.name" class="h-full w-full object-cover object-center lg:h-full lg:w-full" />
                    </div>
                    <div class="mt-4 flex justify-between">
                        <div>
                            <h3 class="text-sm text-gray-700">
                                <Link :href="route('services.overview', service.id)">
                                <span aria-hidden="true" class="absolute inset-0" />
                                {{ service.name }}
                                </Link>
                            </h3>
                            <p class="mt-1 text-sm text-gray-500">{{ service.description }}</p>
                        </div>
                        <p class="text-sm font-medium text-gray-900">{{ service.service_plans_count }} Available Plans </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
