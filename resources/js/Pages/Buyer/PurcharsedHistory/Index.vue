<script setup>
import Modal from "@/Components/Modal.vue";
import DetailPurcharse from "./DetailPurcharse.vue";
import NumberFormat from "@/Helpers/NumberFormat";
import AdminLayout from "@/Layouts/AdminLayout.vue";
import { Link, Head, router } from "@inertiajs/vue3";
import { onMounted, ref } from "vue";

const props = defineProps({
    carts: Object,
})

const tableFields = [
    { label: 'Purcharsed At', field: 'created_at', sort: true},
    { label: 'Total', field: 'total', sort: true},
    { label: '# Products', field: 'salable_id', sort: false},
]

const filter = ref(null);
const sort = ref(null);
const showModal = ref(false);
const selectedCart = ref(null);

const handleDetails = cart => {
    selectedCart.value = cart
    showModal.value = true
}

const handleSort = (field) => {
    console.log(field);
    if (sort.value === field) {
        sort.value = sort.value.startsWith('-') ? field : '-'+field
    } else {
        sort.value = field
    }
    searchQuery()
}

const searchQuery = () => {
    router.get(route('shopping-history.index', {
        _query: {
            page: filter.value ? 1 : props.carts.current_page,
            sort: sort.value,
            filter: {
                search: filter.value
            }
        },
    }), {}, { preserveState: true, replace: true });
}

</script>

<template>
    <Head title="Admin - My Shopping history"></Head>
    <AdminLayout>
        <div class="p-12">

            <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
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
                                <span class="sr-only">Details</span>
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700" v-for="cart in carts.data" :key="cart.id">
                            <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                {{ cart.created_at }}
                            </th>
                            <td class="px-6 py-4">
                                {{ NumberFormat( cart.total )}}
                            </td>
                            <td class="px-6 py-4">
                                {{ cart.salable.products.length }}
                            </td>
                            <td class="px-6 py-4 text-right">
                                <a href="#" @click.prevent="handleDetails(cart)" class="font-medium text-blue-600 dark:text-blue-500 hover:underline">Details</a>
                            </td>
                        </tr>
                        <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700" v-if="carts.data.length === 0">
                            <td colspan="6" class="text-lg text-center">There is no carts yet</td>
                        </tr>
                    </tbody>
                </table>
                <nav class="flex items-center justify-between pt-4" aria-label="Table navigation">
                    <span class="text-sm font-normal text-gray-500 dark:text-gray-400">Showing <span class="font-semibold text-gray-900 dark:text-white">{{carts.from}}-{{carts.to}}</span> of <span class="font-semibold text-gray-900 dark:text-white">{{carts.total}}</span></span>
                    <ul class="inline-flex -space-x-px text-sm h-8">
                        <li>
                            <Link
                                :href="carts.first_page_url"
                                class="flex items-center justify-center px-3 h-8 ml-0 leading-tight text-gray-500 bg-white border border-gray-300 rounded-l-lg hover:bg-gray-100 hover:text-gray-700 dark:bg-gray-800 dark:border-gray-700 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-white">
                                &laquo;&laquo;
                            </Link>
                        </li>

                        <li v-for="(link, key) in carts.links" :key="key">
                            <a v-if="!link.url" aria-disabled="true" class="flex items-center justify-center px-3 h-8 leading-tight text-gray-500 bg-white border border-gray-300 hover:bg-gray-100 hover:text-gray-700 dark:bg-gray-800 dark:border-gray-700 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-white">
                                <span v-html="link.label"></span>
                            </a>
                            <Link v-else :href="link.url" :aria-current="link.active ? 'page': ''" class="flex items-center justify-center px-3 h-8 leading-tight text-gray-500 bg-white border border-gray-300 hover:bg-gray-100 hover:text-gray-700 dark:bg-gray-800 dark:border-gray-700 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-white" :class="link.active ? 'bg-gray-100 text-gray-700 dark:bg-gray-700 dark:text-white' : ''">
                                <span v-html="link.label"></span>
                            </Link>
                        </li>

                        <li>
                            <Link :href="carts.last_page_url" class="flex items-center justify-center px-3 h-8 leading-tight text-gray-500 bg-white border border-gray-300 rounded-r-lg hover:bg-gray-100 hover:text-gray-700 dark:bg-gray-800 dark:border-gray-700 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-white">
                                &raquo;&raquo;
                            </Link>
                        </li>
                    </ul>
                </nav>
            </div>
        </div>

        <Modal :show="showModal" @close="showModal = false">
            <DetailPurcharse :cart="selectedCart"></DetailPurcharse>
        </Modal>
    </AdminLayout>
</template>
