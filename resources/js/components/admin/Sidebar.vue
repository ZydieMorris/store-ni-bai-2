<script setup lang="ts">
import { Link, router } from '@inertiajs/vue3';
import { ChartNoAxesCombined, House, Package, ShoppingCart, Warehouse } from 'lucide-vue-next';
import Button from '../ui/button/Button.vue';

function logout() {
    router.post('/logout')
}


</script>
<template>
    <div class="min-h-screen bg-[#254F81] w-70 text-white  fixed top-0 z-100">

        <h1 class="text-center pt-10 text-2xl font-semibold">Hello Admin</h1>

        <div class="flex flex-col justify-center items-center mt-20 font-medium">

            <Link href="/dashboard" class="p-5 hover:bg-[#2e77d1] w-full text-center text-lg">
                <div class="flex justify-center gap-3">
                    <House />
                    Dashboard
                </div>
            </Link>

            <Link href="/products" class="p-5 hover:bg-[#2e77d1] w-full text-center text-lg">
                <div class="flex justify-center gap-3">
                    <Package />
                    Products
                </div>
            </Link>

            <Link class="p-5 hover:bg-[#2e77d1] w-full text-center text-lg">
                <div class="flex justify-center gap-3">
                    <ShoppingCart />
                    Sales
                </div>
            </Link>

            <Link class="p-5 hover:bg-[#2e77d1] w-full text-center text-lg">
                <div class="flex justify-center gap-3">
                    <ChartNoAxesCombined />
                    Report
                </div>
            </Link>

        </div>

        <div class="absolute right-5 bottom-20">

            <Button @click="logout" class="bg-[#2e77d1] hover:bg-blue-400">Logout</Button>
        </div>

    </div>
</template>
