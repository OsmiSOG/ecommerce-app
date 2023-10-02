<script setup>
import ApplicationLogo from '@/Components/ApplicationLogo.vue';
import { Dialog, DialogPanel } from '@headlessui/vue'
import { Link } from '@inertiajs/vue3';
import { ref } from 'vue'

const mobileMenuOpen = ref(false)

const navigation = [
  { name: 'Home', href: route('home'), current: route().current('home') },
  { name: 'Products', href: route('products'), current: route().current('products') },
  { name: 'Services', href: route('services'), current: route().current('services') },
]

</script>

<template>
    <div class="bg-white">
        <header class="absolute inset-x-0 top-0 z-50">
            <nav class="flex items-center justify-between p-6 lg:px-8" aria-label="Global">
                <div class="flex lg:flex-1">
                    <Link :href="route('home')" class="-m-1.5 p-1.5">
                        <span class="sr-only">Developer Sr</span>
                        <ApplicationLogo
                            class="block h-9 w-auto fill-current text-gray-800 dark:text-gray-200"
                        />
                    </Link>
                </div>
                <div class="flex lg:hidden">
                    <button type="button" class="-m-2.5 inline-flex items-center justify-center rounded-md p-2.5 text-gray-700" @click="mobileMenuOpen = true">
                        <span class="sr-only">Open main menu</span>
                        <i class="fa-solid fa-bars" aria-hidden="true"></i>
                    </button>
                </div>
                <div class="hidden lg:flex lg:gap-x-12">
                    <Link v-for="item in navigation" :key="item.name" :href="item.href" class="text-sm font-semibold leading-6 text-gray-900" :class="item.current ? 'underline underline-offset-8' : ''">
                        {{ item.name }}
                    </Link>
                </div>
                <div class="hidden lg:flex lg:flex-1 lg:justify-end">
                    <Link :href="route('welcome')" class="text-sm font-semibold leading-6 text-gray-900" v-if="$page.props.auth.user">My Space <span aria-hidden="true">&rarr;</span></Link>
                    <Link :href="route('login')" class="text-sm font-semibold leading-6 text-gray-900" v-else>Log in <span aria-hidden="true">&rarr;</span></Link>
                </div>
            </nav>
            <Dialog as="div" class="lg:hidden" @close="mobileMenuOpen = false" :open="mobileMenuOpen">
                <div class="fixed inset-0 z-50" />
                <DialogPanel class="fixed inset-y-0 right-0 z-50 w-full overflow-y-auto bg-white px-6 py-6 sm:max-w-sm sm:ring-1 sm:ring-gray-900/10">
                    <div class="flex items-center justify-between">
                        <a href="#" class="-m-1.5 p-1.5">
                            <span class="sr-only">Your Company</span>
                            <ApplicationLogo
                                class="block h-9 w-auto fill-current text-gray-800 dark:text-gray-200"
                            />
                        </a>
                        <button type="button" class="-m-2.5 rounded-md p-2.5 text-gray-700" @click="mobileMenuOpen = false">
                            <span class="sr-only">Close menu</span>
                            <i class="fa-solid fa-xmark" aria-hidden="true"></i>
                        </button>
                    </div>
                    <div class="mt-6 flow-root">
                        <div class="-my-6 divide-y divide-gray-500/10">
                            <div class="space-y-2 py-6">
                                <Link v-for="item in navigation" :key="item.name" :href="item.href" class="-mx-3 block rounded-lg px-3 py-2 text-base font-semibold leading-7 text-gray-900 hover:bg-gray-50" :class="item.current ? 'underline underline-offset-8' : ''">{{ item.name }}</Link>
                            </div>
                            <div v-if="$page.props.auth.user">
                                <Link :href="route('welcome')" class="-mx-3 block rounded-lg px-3 py-2.5 text-base font-semibold leading-7 text-gray-900 hover:bg-gray-50">My Space</Link>
                            </div>
                            <div class="py-6" v-else>
                                <Link :href="route('login')" class="-mx-3 block rounded-lg px-3 py-2.5 text-base font-semibold leading-7 text-gray-900 hover:bg-gray-50">Log in</Link>
                            </div>
                        </div>
                    </div>
                </DialogPanel>
            </Dialog>
        </header>

        <div class="relative isolate px-6 pt-14 lg:px-8">
            <div class="absolute inset-x-0 -top-40 -z-10 transform-gpu overflow-hidden blur-3xl sm:-top-80" aria-hidden="true">
                <div class="relative left-[calc(50%-11rem)] aspect-[1155/678] w-[36.125rem] -translate-x-1/2 rotate-[30deg] bg-gradient-to-tr from-[#ff80b5] to-[#9089fc] opacity-30 sm:left-[calc(50%-30rem)] sm:w-[72.1875rem]" style="clip-path: polygon(74.1% 44.1%, 100% 61.6%, 97.5% 26.9%, 85.5% 0.1%, 80.7% 2%, 72.5% 32.5%, 60.2% 62.4%, 52.4% 68.1%, 47.5% 58.3%, 45.2% 34.5%, 27.5% 76.7%, 0.1% 64.9%, 17.9% 100%, 27.6% 76.8%, 76.1% 97.7%, 74.1% 44.1%)" />
            </div>
            <slot />
        </div>
    </div>
</template>
