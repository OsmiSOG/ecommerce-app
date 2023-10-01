<script setup>
import { Link, useForm } from "@inertiajs/vue3";
import { onMounted, ref } from "vue";

const props = defineProps({
    service: Object,
})

const plans = ref([]);

const getPlans = async () => {
    const {data} = await axios.get(route('sell.service.plan.get', props.service.id))
    plans.value = data
}

onMounted(async () => {
    await getPlans()
});

</script>


<template>
    <div class="p-2 flex flex-wrap justify-center">
        <div v-for="plan in plans" :key="plan.id" class="w-full max-w-sm m-3 p-4 bg-white border border-gray-200 rounded-lg shadow sm:p-8 dark:bg-gray-800 dark:border-gray-700">
            <h5 class="mb-4 text-xl font-medium text-gray-500 dark:text-gray-400">{{plan.plan.name}}</h5>
            <p class="mb-4 text-md font-medium text-gray-400 dark:text-gray-300">
                {{ plan.plan.description }}
            </p>
            <div class="flex items-baseline text-gray-900 dark:text-white">
                <span class="text-3xl font-semibold">$</span>
                <span class="text-5xl font-extrabold tracking-tight">{{plan.plan.price}}</span>
                <span class="ml-1 text-xl font-normal text-gray-500 dark:text-gray-400">Every {{ plan.plan.invoice_period }} {{plan.plan.invoice_interval}}</span>
            </div>

            <ul class="mb-3 text-sm font-medium text-gray-900 bg-white border border-gray-200 rounded-lg dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                <li v-if="plan.plan.trial_period" class="w-full px-4 py-2 border-b border-gray-200 rounded-t-lg dark:border-gray-600">Trial Time {{plan.plan.trial_period}} {{ plan.plan.trial_interval }}</li>
                <li v-if="plan.plan.grace_period" class="w-full px-4 py-2 border-b border-gray-200 dark:border-gray-600">Grace Time {{plan.plan.grace_period}} {{ plan.plan.grace_interval }}</li>
                <li v-if="plan.plan.discount_period" class="w-full px-4 py-2 border-b border-gray-200 dark:border-gray-600">Discount
                    {{plan.plan.discount_period}}
                    {{ plan.plan.discount_interval }}
                    for -{{ discount_type_amount === 'value' ? '$' : ''}} {{ plan.plan.discount_type_amount }} {{ discount_type_amount === 'percentage' ? '%' : ''}}
                    {{ plan.plan.discount_subscribers_limit ? `Limite : ${plan.plan.discount_subscribers_limit}` : '' }}
                </li>
                <li v-if="plan.plan.active_subscribers_limit" class="w-full px-4 py-2 rounded-b-lg">Limit {{ plan.plan.active_subscribers_limit }} users</li>
            </ul>

            <Link
                :href="route('sell.service.plan.toggle-active', plan.id)"
                method="put"
                as="button"
                @success="getPlans"
                class="text-white mb-3 bg-blue-600 hover:bg-blue-700 focus:ring-4 focus:outline-none focus:ring-blue-200 dark:focus:ring-blue-900 font-medium rounded-lg text-sm px-5 py-2.5 inline-flex justify-center w-full text-center">
                {{ plan.plan.active ? 'Deactive' : 'Active' }}
            </Link>

            <Link
                :href="route('sell.service.plan.delete', plan.id)"
                method="delete"
                as="button"
                @success="getPlans"
                class="text-white bg-red-600 hover:bg-red-700 focus:ring-4 focus:outline-none focus:ring-red-200 dark:focus:ring-red-900 font-medium rounded-lg text-sm px-5 py-2.5 inline-flex justify-center w-full text-center">
                Eliminar
            </Link>
        </div>
    </div>

</template>
