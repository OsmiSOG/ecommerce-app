<script setup>
import Navbar from '@/Components/AdminLayout/Navbar.vue';
import Sidebar from '@/Components/AdminLayout/Sidebar.vue';
import Footer from '@/Components/AdminLayout/Footer.vue';
import { onMounted } from 'vue';
import { initFlowbite } from 'flowbite'

// initialize components based on data attribute selectors
onMounted(() => {
    initFlowbite();
})

</script>

<template>

      <div class="bg-white dark:bg-gray-900   bg-gray-50 dark:bg-gray-800">

        <main class="bg-gray-50 dark:bg-gray-900">

            <Navbar />

            <div class="flex pt-16 overflow-hidden bg-gray-50 dark:bg-gray-900">

                <Sidebar />

                <div id="main-content" class="flex flex-col min-h-screen relative w-full h-full overflow-y-auto bg-gray-50 lg:ml-64 dark:bg-gray-900">

                    <main>
                        <slot />
                    </main>

                    <Footer />

                </div>

            </div>

        </main>

      </div>

</template>
