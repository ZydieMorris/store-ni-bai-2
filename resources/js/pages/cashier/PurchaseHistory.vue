<script setup lang="ts">
import Button from '@/components/ui/button/Button.vue';
import CashierLayout from '@/layouts/cashier/Cashier-Layout.vue';
import { Link } from '@inertiajs/vue3';
import { ArrowLeft, ChevronDown } from 'lucide-vue-next';
import {ref} from 'vue';


interface Product {
  id: number
  product_name: string
  price: number
}


interface Order {
  id: number
  user_id: number
  total_amount: number
  amount_paid: number
  change_amount: number
  status: string
  order_items: OrderItem[]
}


interface OrderItem {
  id: number
  product_id: number
  quantity: number
  unit_price: number
  total_price: number
  product: Product
}



const { orders } = defineProps<{ orders: Order[] }>();

const activeOrderId = ref<number | null>(null)

const toggleOrder = (orderId: number) => {
  activeOrderId.value = activeOrderId.value === orderId ? null : orderId
}


</script>

<template>
    <CashierLayout class=" min-h-screen bg-gray-300 text-white">
        <div class="w-300 mx-auto mt-15">

            <Link href="/cashier/dashboard"> <Button class="bg-[#254F81]"> <ArrowLeft /> Return</Button> </Link>
        </div>

            <div class="w-300 mx-auto mt-5 h-auto bg-white rounded-lg p-10 space-y-5">
                <div v-for="order in orders" :key="order.id" class="bg-[#254F81] rounded">
    <button @click="toggleOrder(order.id)" class="w-full flex justify-between p-5">
        <div class="text-start">
            <p class="font-bold"> Order ID: {{ order.id }}</p>
            <p class="font-bold">Status: {{ order.status }}</p>
            <p class="font-bold">Total Amount: {{ order.total_amount }}</p>
        </div>
    </button>

    <div v-if="activeOrderId === order.id">
        <div v-for="item in order.order_items" :key="item.id" class="p-5">
            {{ item.product_name }} x {{ item.quantity }}
        </div>
    </div>
</div>


            </div>
    </CashierLayout>
</template>




