<script setup lang="ts">
import CashierLayout from '@/layouts/cashier/Cashier-Layout.vue';







interface Order {
    id: number
    user_id: number
    total_amount: number
    amount_paid: number
    change_amount: number
    status: string
    orderItems: OrderItem[]
}


interface OrderItem {
    id: number
    product_id: number
    product_name: string
    quantity: number
    unit_price: number
    total_price: number
}


const { order } = defineProps<{ order: Order }>();



</script>

<template>
    <CashierLayout>
        <div class="w-300 mx-auto bg-amber-300 mt-20 h-100 p-5">
            <h1 class="text-center text-2xl font-bold">Receipt</h1>

                

            <div v-for="item in order.orderItems" :key="item.id">
                <p> {{ item.product_name }}</p>
                
               
                
            </div>

        </div>
    </CashierLayout>
</template>
