<script setup>
import AdminLayout from "@/Layouts/AdminLayout.vue";
import Modal from "@/Components/Modal.vue";
import { Link, Head, router } from "@inertiajs/vue3";
import { onMounted, ref } from "vue";

const props = defineProps({
    subscriptions: Object,
    service: Object,
})

const tableFields = [
    { label: 'Plan', field: 'plan_id', sort: true},
    { label: 'Name', field: 'subscriber_id', sort: true},
    { label: 'Email', field: 'subscriber_id', sort: true},
    { label: 'Trial Ends', field: 'trial_ends_at', sort: true},
    { label: 'Invoice Ends', field: 'ends_at', sort: true},
    { label: 'Subscribed at', field: 'created_at', sort: true},
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
    router.get(route('sell.service.subscriptions.subscribers', {
        service: props.service.id,
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
    <Head :title="`Admin - Subscribers Service ${service.name}`"></Head>
    <AdminLayout>
        <div class="p-12">
            <h4 class="pb-8 text-center text-gray-700 dark:text-gray-200 text-lg">Subscribers service {{ service.name }} </h4>

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
                                {{ subscription.plan.name }}
                            </th>
                            <td class="px-6 py-4">
                                {{ subscription.subscriber.name }}
                            </td>
                            <td class="px-6 py-4">
                                {{ subscription.subscriber.email }}
                            </td>
                            <td class="px-6 py-4">
                                {{ subscription.trial_ends_at }}
                            </td>
                            <td class="px-6 py-4">
                                {{ subscription.ends_at }}
                            </td>
                            <td class="px-6 py-4">
                                {{ subscription.created_at }}
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
