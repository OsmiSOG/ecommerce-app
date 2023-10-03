<script setup>
import NumberFormat from "@/Helpers/NumberFormat";
import { onMounted, ref } from 'vue';
import { initFlowbite } from 'flowbite'

defineProps({
    cart: Object,
})

onMounted(() => {
    initFlowbite();
})

</script>

<template>
        <div>
            <div class="mx-auto max-w-2xl p-10">

                <h1 class="text-4xl font-bold tracking-tight text-gray-900 sm:text-2xl text-center pb-16 dark:text-gray-200">Purcharse Details</h1>

                <div class="relative overflow-x-auto shadow-md sm:rounded-lg" v-if="cart">
                    <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                        <thead class="text-xs text-gray-700 uppercase bg-gray-200 dark:bg-gray-700 dark:text-gray-400">
                            <tr>
                                <th scope="col" class="px-6 py-3">
                                    <span class="sr-only">Image</span>
                                </th>
                                <th scope="col" class="px-6 py-3">
                                    Product
                                </th>
                                <th scope="col" class="px-6 py-3">
                                    Qty
                                </th>
                                <th scope="col" class="px-6 py-3">
                                    Price
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr class="bg-gray-100 border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-300 dark:hover:bg-gray-600" v-for="product in cart.salable.products" :key="product.id">
                                <td class="w-32 p-4">
                                    <img :src="product.front_picture.url" :alt="product.name">
                                </td>
                                <td class="px-6 py-4 font-semibold text-gray-900 dark:text-white">
                                    {{ product.name }}
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex items-center space-x-3">
                                        <div>
                                            <input disabled v-model="product.pivot.product_qty" type="number" :id="product.name" class="bg-gray-50 w-14 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block px-2.5 py-1 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="1" required>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 font-semibold text-gray-900 dark:text-white">
                                    {{ NumberFormat(product.price) }}
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <div class="border-t border-gray-200 px-4 py-6 sm:px-6" v-if="cart">
                    <div class="flex justify-between text-base font-medium text-gray-900 dark:text-white">
                        <p>Total</p>
                        <p>{{ NumberFormat(cart.total) }}</p>
                    </div>
                </div>

            </div>
        </div>

</template>

