<script setup>
import AdminLayout from "@/Layouts/AdminLayout.vue";
import { Link, Head, router } from "@inertiajs/vue3";
import { onMounted, ref } from "vue";

const props = defineProps({
    subscriptions: Object,
})

const tableFields = [
    { label: 'Servicio', field: 'plan_id', sort: true},
    { label: 'Trial Ends (First Payment)', field: 'trial_ends_at', sort: true},
    { label: 'Starts', field: 'starts_at', sort: true},
    { label: 'Ends (Next Payment)', field: 'ends_at', sort: true},
    { label: 'Grace Ends', field: 'grace_ends_at', sort: true},
    { label: 'Discount Ends', field: 'discount_ends_at', sort: true},
    { label: 'Canceled At', field: 'canceled_at', sort: true},
]

const filter = ref(null);
const sort = ref(null);

const handleSort = (field) => {
    if (sort.value === field) {
        sort.value = sort.value.startsWith('-') ? field : '-'+field
    } else {
        sort.value = field
    }
    searchQuery()
}

const searchQuery = () => {
    router.get(route('subscription.index', {
        _query: {
            page: filter.value ? 1 : props.subscriptions.current_page,
            sort: sort.value,
            filter: {
                search: filter.value
            }
        },
    }), {}, { preserveState: true, replace: true });
}

</script>

<template>
    <Head title="Admin - My Subscriptions"></Head>
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
                        </tr>
                    </thead>
                    <tbody>
                        <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700" v-for="subscription in subscriptions.data" :key="subscription.id">
                            <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                {{ subscription.plan.service_plan.service.name }} {{'('}} {{ subscription.plan.name }} {{')'}}
                            </th>
                            <td class="px-6 py-4">
                                {{ subscription.trial_ends_at }}
                            </td>
                            <td class="px-6 py-4">
                                {{ subscription.starts_at }}
                            </td>
                            <td class="px-6 py-4">
                                {{ subscription.ends_at }}
                            </td>
                            <td class="px-6 py-4">
                                {{ subscription.grace_ends_at }}
                            </td>
                            <td class="px-6 py-4">
                                {{ subscription.discount_ends_at }}
                            </td>
                            <td class="px-6 py-4">
                                <div v-if="!subscription.canceled_at">
                                    <Link :href="route('subscription.cancel', subscription.id)" as="button" method="post" class="font-medium text-blue-600 dark:text-red-500 hover:underline"> Cancel </Link>
                                </div>
                                <div v-else>
                                    {{ subscription.canceled_at }}
                                </div>
                            </td>
                        </tr>
                        <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700" v-if="subscriptions.data.length === 0">
                            <td colspan="6" class="text-lg text-center">There is no subscriptions yet</td>
                        </tr>
                    </tbody>
                </table>
                <nav class="flex items-center justify-between pt-4" aria-label="Table navigation">
                    <span class="text-sm font-normal text-gray-500 dark:text-gray-400">Showing <span class="font-semibold text-gray-900 dark:text-white">{{subscriptions.from}}-{{subscriptions.to}}</span> of <span class="font-semibold text-gray-900 dark:text-white">{{subscriptions.total}}</span></span>
                    <ul class="inline-flex -space-x-px text-sm h-8">
                        <li>
                            <Link
                                :href="subscriptions.first_page_url"
                                class="flex items-center justify-center px-3 h-8 ml-0 leading-tight text-gray-500 bg-white border border-gray-300 rounded-l-lg hover:bg-gray-100 hover:text-gray-700 dark:bg-gray-800 dark:border-gray-700 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-white">
                                &laquo;&laquo;
                            </Link>
                        </li>

                        <li v-for="(link, key) in subscriptions.links" :key="key">
                            <a v-if="!link.url" aria-disabled="true" class="flex items-center justify-center px-3 h-8 leading-tight text-gray-500 bg-white border border-gray-300 hover:bg-gray-100 hover:text-gray-700 dark:bg-gray-800 dark:border-gray-700 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-white">
                                <span v-html="link.label"></span>
                            </a>
                            <Link v-else :href="link.url" :aria-current="link.active ? 'page': ''" class="flex items-center justify-center px-3 h-8 leading-tight text-gray-500 bg-white border border-gray-300 hover:bg-gray-100 hover:text-gray-700 dark:bg-gray-800 dark:border-gray-700 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-white" :class="link.active ? 'bg-gray-100 text-gray-700 dark:bg-gray-700 dark:text-white' : ''">
                                <span v-html="link.label"></span>
                            </Link>
                        </li>

                        <li>
                            <Link :href="subscriptions.last_page_url" class="flex items-center justify-center px-3 h-8 leading-tight text-gray-500 bg-white border border-gray-300 rounded-r-lg hover:bg-gray-100 hover:text-gray-700 dark:bg-gray-800 dark:border-gray-700 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-white">
                                &raquo;&raquo;
                            </Link>
                        </li>
                    </ul>
                </nav>
            </div>

        </div>
    </AdminLayout>
</template>
