<script setup>
import AdminLayout from "@/Layouts/AdminLayout.vue";
import Modal from "@/Components/Modal.vue";
import PlanForm from "./Partials/PlanForm.vue";
import Plans from "./Partials/Plans.vue";
import { Link, Head, router } from "@inertiajs/vue3";
import { onMounted, ref } from "vue";

const props = defineProps({
    services: Object,
    currencies: Array,
    frequencies: Array,
    discountTypes: Array
})

const tableFields = [
    { label: 'Service name', field: 'name', sort: true},
    { label: 'Limit', field: 'limit', sort: true},
    { label: 'Plans', field: 'model', sort: false},
    { label: 'Categoria', field: 'category_id', sort: true},
    { label: 'Subcategoria', field: 'subcategory_id', sort: true},
    { label: 'Created At', field: 'created_at', sort: true},
]

const filter = ref(null);
const sort = ref(null);
const planFormModal = ref(false)
const planModal = ref(false)
const selectedService = ref(null)

const handleSort = (field) => {
    if (sort.value === field) {
        sort.value = sort.value.startsWith('-') ? field : '-'+field
    } else {
        sort.value = field
    }
    searchQuery()
}

const handleFormPlan = (service) => {
    selectedService.value = service
    planFormModal.value = true
}
const handlePlan = (service) => {
    selectedService.value = service
    planModal.value = true
}

const searchQuery = () => {
    router.get(route('sell.service.index', {
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
    <Head title="Admin - Sell Service"></Head>
    <AdminLayout>
        <div class="p-12">

            <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
                <div class="flex justify-between">
                    <div>
                        <Link :href="route('sell.service.create')" type="button" class="text-white bg-gradient-to-r from-cyan-400 via-cyan-500 to-cyan-600 hover:bg-gradient-to-br focus:ring-4 focus:outline-none focus:ring-cyan-300 dark:focus:ring-cyan-800 font-medium rounded-lg text-sm px-5 py-2.5 text-center mr-2 mb-2">Sell Service</Link>
                    </div>
                    <div class="pb-4 bg-white dark:bg-gray-900">
                        <label for="table-search" class="sr-only">Search</label>
                        <div class="relative mt-1">
                            <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                                <svg class="w-4 h-4 text-gray-500 dark:text-gray-400" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 20">
                                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m19 19-4-4m0-7A7 7 0 1 1 1 8a7 7 0 0 1 14 0Z"/>
                                </svg>
                            </div>
                            <input @change.prevent="searchQuery" v-model="filter" type="text" id="table-search" class="block p-2 pl-10 text-sm text-gray-900 border border-gray-300 rounded-lg w-80 bg-gray-50 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="Search for items">
                        </div>
                    </div>
                </div>
                <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                    <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                        <tr>
                            <th scope="col" class="px-6 py-3" v-for="(field, key) in tableFields" :key="key">
                                <div class="flex items-center">
                                    {{ field.label }}
                                    <a href="#" @click.prevent="handleSort(field.field)" v-if="field.sort"><i class="fa-solid fa-sort w-3 h-3 ml-1.5"></i></a>
                                </div>
                            </th>
                            <th scope="col" class="px-6 py-3">
                                <span class="sr-only">Edit</span>
                                <span class="sr-only">Stock</span>
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700" v-for="service in services.data" :key="service.id">
                            <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                {{ service.name }}
                            </th>
                            <td class="px-6 py-4">
                                {{ service.limit }}
                            </td>
                            <td class="px-6 py-4">
                                <a href="#" @click.prevent="handlePlan(service)" class="mr-3">
                                    {{ service.service_plans_count }}
                                </a>
                                <a href="#" @click.prevent="handleFormPlan(service)" class="text-lime-600"><i class="fa-solid fa-circle-plus"></i></a>
                            </td>
                            <td class="px-6 py-4">
                                {{ service.category.name }}
                            </td>
                            <td class="px-6 py-4">
                                {{ service.subcategory.name }}
                            </td>
                            <td class="px-6 py-4">
                                {{ service.created_at }}
                            </td>
                            <td class="px-6 py-4 text-right">
                                <a href="#" class="font-medium text-blue-600 dark:text-blue-500 hover:underline">Edit</a>
                            </td>
                        </tr>
                        <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700" v-if="services.data.length === 0">
                            <td colspan="6" class="text-lg text-center">There is no services yet</td>
                        </tr>
                    </tbody>
                </table>
                <nav class="flex items-center justify-between pt-4" aria-label="Table navigation">
                    <span class="text-sm font-normal text-gray-500 dark:text-gray-400">Showing <span class="font-semibold text-gray-900 dark:text-white">{{services.from}}-{{services.to}}</span> of <span class="font-semibold text-gray-900 dark:text-white">{{services.total}}</span></span>
                    <ul class="inline-flex -space-x-px text-sm h-8">
                        <li>
                            <Link
                                :href="services.first_page_url"
                                class="flex items-center justify-center px-3 h-8 ml-0 leading-tight text-gray-500 bg-white border border-gray-300 rounded-l-lg hover:bg-gray-100 hover:text-gray-700 dark:bg-gray-800 dark:border-gray-700 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-white">
                                &laquo;&laquo;
                            </Link>
                        </li>

                        <li v-for="(link, key) in services.links" :key="key">
                            <a v-if="!link.url" aria-disabled="true" class="flex items-center justify-center px-3 h-8 leading-tight text-gray-500 bg-white border border-gray-300 hover:bg-gray-100 hover:text-gray-700 dark:bg-gray-800 dark:border-gray-700 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-white">
                                <span v-html="link.label"></span>
                            </a>
                            <Link v-else :href="link.url" :aria-current="link.active ? 'page': ''" class="flex items-center justify-center px-3 h-8 leading-tight text-gray-500 bg-white border border-gray-300 hover:bg-gray-100 hover:text-gray-700 dark:bg-gray-800 dark:border-gray-700 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-white" :class="link.active ? 'bg-gray-100 text-gray-700 dark:bg-gray-700 dark:text-white' : ''">
                                <span v-html="link.label"></span>
                            </Link>
                        </li>

                        <li>
                            <Link :href="services.last_page_url" class="flex items-center justify-center px-3 h-8 leading-tight text-gray-500 bg-white border border-gray-300 rounded-r-lg hover:bg-gray-100 hover:text-gray-700 dark:bg-gray-800 dark:border-gray-700 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-white">
                                &raquo;&raquo;
                            </Link>
                        </li>
                    </ul>
                </nav>
            </div>

        </div>
        <Modal :show="planFormModal" @close="planFormModal = false">
            <PlanForm
                :service="selectedService"
                :currencies="currencies"
                :frequencies="frequencies"
                :discount-types="discountTypes"
                @submited="planFormModal = false"
            ></PlanForm>
        </Modal>
        <Modal :show="planModal" max-width="4xl" @close="planModal = false">
            <Plans
                :service="selectedService"
            ></Plans>
        </Modal>
    </AdminLayout>
</template>
