<script setup>
import NumberFormat from "@/Helpers/NumberFormat";
import MarketLayout from "@/Layouts/MarketLayout.vue";
import { Head, Link } from '@inertiajs/vue3';
import { onMounted } from 'vue';
import { initFlowbite } from 'flowbite'

defineProps({
    cart: Object,
})



onMounted(() => {
    initFlowbite();
})

</script>

<template>
    <Head :title="`Sr Developer - Cart Shopping Overview`" />

    <MarketLayout>
        <div>
            <div class="mx-auto max-w-2xl pt-16 pb-16 sm:pb-24 lg:pb-28">

                <h1 class="text-4xl font-bold tracking-tight text-gray-900 sm:text-2xl text-center pb-16">Shopping Cart</h1>

                <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
                    <table class="w-full text-sm text-left text-gray-500">
                        <thead class="text-xs text-gray-700 uppercase bg-gray-200">
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
                                <th scope="col" class="px-6 py-3">
                                    Action
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr class="bg-gray-100 border-b hover:bg-gray-300" v-for="product in cart.products" :key="product.id">
                                <td class="w-32 p-4">
                                    <img :src="product.front_picture.url" :alt="product.name">
                                </td>
                                <td class="px-6 py-4 font-semibold text-gray-900">
                                    Apple Watch
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex items-center space-x-3">
                                        <Link :href="route('cart.substract', product.id)" as="button" method="put" preserve-scroll class="inline-flex items-center justify-center p-1 text-sm font-medium h-6 w-6 text-gray-500 bg-white border border-gray-300 rounded-full focus:outline-none hover:bg-gray-100 focus:ring-4 focus:ring-gray-200" type="button">
                                            <span class="sr-only">Quantity button</span>
                                            <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 18 2">
                                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M1 1h16"/>
                                            </svg>
                                        </Link>
                                        <div>
                                            <input disabled v-model="product.pivot.product_qty" type="number" id="first_product" class="bg-gray-50 w-14 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block px-2.5 py-1" placeholder="1" required>
                                        </div>
                                        <Link :href="route('cart.add', product.id)" as="button" method="post" preserve-scroll class="inline-flex items-center justify-center h-6 w-6 p-1 text-sm font-medium text-gray-500 bg-white border border-gray-300 rounded-full focus:outline-none hover:bg-gray-100 focus:ring-4 focus:ring-gray-200" type="button">
                                            <span class="sr-only">Quantity button</span>
                                            <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 18 18">
                                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 1v16M1 9h16"/>
                                            </svg>
                                        </Link>
                                    </div>
                                </td>
                                <td class="px-6 py-4 font-semibold text-gray-900">
                                    {{ NumberFormat(product.price) }}
                                </td>
                                <td class="px-6 py-4">
                                    <Link :href="route('cart.remove', product.id)" as="button" method="delete" class="font-medium text-red-600 hover:underline">Remove</Link>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <div class="border-t border-gray-200 px-4 py-6 sm:px-6">
                    <div class="flex justify-between text-base font-medium text-gray-900">
                        <p>Subtotal</p>
                        <p>{{ NumberFormat(cart.total) }}</p>
                    </div>
                    <p class="mt-0.5 text-sm text-gray-500">Shipping and taxes calculated at checkout.</p>
                    <div class="mt-6">
                        <a href="#" class="flex items-center justify-center rounded-md border border-transparent bg-indigo-600 px-6 py-3 text-base font-medium text-white shadow-sm hover:bg-indigo-700">Checkout</a>
                    </div>
                    <div class="mt-6 flex justify-center text-center text-sm text-gray-500">
                        <p>
                        or
                        <button type="button" class="font-medium text-indigo-600 hover:text-indigo-500">
                            Continue Shopping
                            <span aria-hidden="true"> &rarr;</span>
                        </button>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </MarketLayout>

</template>

