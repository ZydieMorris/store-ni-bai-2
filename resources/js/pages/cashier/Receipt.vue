<script setup lang="ts">
import Button from '@/components/ui/button/Button.vue';
import CashierLayout from '@/layouts/cashier/Cashier-Layout.vue';
import { Link } from '@inertiajs/vue3';

import { Input } from '@/components/ui/input'
import { Label } from '@/components/ui/label'






interface Order {
    id: number
    user_id: number
    total_amount: number
    amount_paid: number
    change_amount: number
    status: string
    order_items: Array<OrderItem & { product: any }>
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
    <CashierLayout class=" min-h-screen bg-gray-300">
        <div class="w-300 mx-auto bg-[#FEFEFE] shadow-lg mt-20 h-auto p-5 relative">
            <h1 class="text-center text-2xl font-bold">Receipt</h1>

           <div>
  <table class="w-full border-collapse">
    <thead>
      <tr>
        <th class="w-100  text-left px-2">
          Product Name
        </th>
        <th class="w-50  text-center px-2">
          Qty
        </th>
        <th class="w-50  text-center px-2">
          Price
        </th>
      </tr>
    </thead>

    <tbody>
      <tr
        v-for="item in order.order_items"
        :key="item.id"
      >
        <td class="px-2">
          {{ item.product_name }}
        </td>
        <td class="text-center px-2">
          {{ item.quantity }}
        </td>
        <td class="text-center px-2">
          {{ item.unit_price }}
        </td>
      </tr>
    </tbody>
  </table>

        <div class="w-full bg-black h-1">
        </div>

        <div class="w-50 h-auto space-y-3  mt-5">
            <div>
                Total Amount : {{ order.total_amount }}
            </div>
            <div>   
                Amount Paid : {{ order.amount_paid }}
            </div>
            <div>
                Change:{{ order.change_amount }}
            </div>

        </div>

        <div class="flex justify-end">
           <Link href="/cashier/dashboard"><Button>Make Another Purchase</Button> </Link>
        </div>


</div>


        </div>
    </CashierLayout>
</template>
