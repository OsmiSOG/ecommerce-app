<script setup>
import MarketLayout from "@/Layouts/MarketLayout.vue";
import ServiceList from "@/Components/Commerce/ServiceList.vue";
import { Head, Link, router } from '@inertiajs/vue3';
import { ref } from 'vue'

const props = defineProps({
    categories: Array,
    services: Object,
})

const filter = ref(null);
const sort = ref(null);

const searchQuery = () => {
    router.get(route('services', {
        _query: {
            page: filter.value ? 1 : props.services.current_page,
            sort: sort.value,
            filter: {
                search: filter.value
            }
        },
    }), {}, { preserveState: true, replace: true });
}

</script>

<template>
    <Head title="Sr Developer - services" />

    <MarketLayout>
        <h4 class="text-center mt-12 text-2xl font-bold"> Find your service </h4>
        <div class="flex justify-end">
            <div class="pb-4">
                <label for="table-search" class="sr-only">Search</label>
            </div>
            <div class="relative mt-1">
                <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                    <svg class="w-4 h-4 text-gray-500" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 20">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m19 19-4-4m0-7A7 7 0 1 1 1 8a7 7 0 0 1 14 0Z"/>
                    </svg>
                </div>
                <input @change.prevent="searchQuery" v-model="filter" type="text" id="table-search" class="block p-2 pl-10 text-sm text-gray-900 border border-gray-300 rounded-lg w-80 bg-gray-50 focus:ring-blue-500 focus:border-blue-500" placeholder="Search for items">
            </div>
        </div>

        <ServiceList :services="services.data"></ServiceList>

        <nav class="pt-4" aria-label="Table navigation">
            <div class="flex items-center justify-center">
                <ul class="inline-flex -space-x-px text-sm h-8">
                    <li>
                        <Link
                            :href="services.first_page_url"
                            class="flex items-center justify-center px-3 h-8 ml-0 leading-tight text-gray-500 bg-white border border-gray-300 rounded-l-lg hover:bg-gray-100 hover:text-gray-700">
                            &laquo;&laquo;
                        </Link>
                    </li>

                    <li v-for="(link, key) in services.links" :key="key">
                        <a v-if="!link.url" aria-disabled="true" class="flex items-center justify-center px-3 h-8 leading-tight text-gray-500 bg-white border border-gray-300 hover:bg-gray-100 hover:text-gray-700">
                            <span v-html="link.label"></span>
                        </a>
                        <Link v-else :href="link.url" class="flex items-center justify-center px-3 h-8 leading-tight text-gray-500 bg-white border border-gray-300 hover:bg-gray-100 hover:text-gray-700">
                            <span v-html="link.label"></span>
                        </Link>
                    </li>

                    <li>
                        <Link :href="services.last_page_url" class="flex items-center justify-center px-3 h-8 leading-tight text-gray-500 bg-white border border-gray-300 rounded-r-lg hover:bg-gray-100 hover:text-gray-700">
                            &raquo;&raquo;
                        </Link>
                    </li>
                </ul>
            </div>
            <div class="flex items-center justify-center">
                <span class="text-sm font-normal text-gray-500">Showing <span class="font-semibold text-gray-900">{{services.from}}-{{services.to}}</span> of <span class="font-semibold text-gray-900">{{services.total}}</span></span>
            </div>
        </nav>
    </MarketLayout>

</template>

