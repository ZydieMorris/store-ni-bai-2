<script setup lang="ts">
import CashierLayout from '@/layouts/cashier/Cashier-Layout.vue';





interface Products {
    id: number
    product_name: string
    price: number
    stock_available: number
    product_category_id: number
    image: string
}

interface Order {
    id: number
    user_id: number
    total_amount: number
    change_amount: number
    status: string
}

interface CartItem {
    id: number
    product_id: number
    product_quantity: number
    unit_price: number
    product: Products

}

const { products, order, cartItem } = defineProps<{ products: Products[], order: Order[], cartItem: CartItem[] }>();



</script>

<template>
    <CashierLayout>
        <div>Hello</div>
    </CashierLayout>
</template>
