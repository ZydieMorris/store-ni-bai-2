<script setup lang="ts">
import AdminLayout from '@/layouts/admin/Admin-Layout.vue';
import {
  Table,
  TableBody,
  TableCaption,
  TableCell,
  TableFooter,
  TableHead,
  TableHeader,
  TableRow,
} from "@/components/ui/table"

interface Product {
  id: number
  product_name: string
  price: number
}

interface OrderItem {
  id: number
  product_id: number
  quantity: number
  unit_price: number
  total_price: number
  product: Product | null
}

interface Order {
  id: number
  user_id: number
  status: string
  total_amount: number
  created_at: string
  order_items: OrderItem[]
}


const {orders}= defineProps<{
    total_products : number,
    total_sold_today : number,
    product_low_stock : number,
    total_sales : number,
    orders : Order[],
}>();


</script>
<template>
    <AdminLayout >
    <div class="ml-78 pt-40 h-100 ">
        <div class=" flex justify-evenly">
            <div class="w-80 h-50 bg-white text-black rounded-xl shadow-xl text-center p-10 text-2xl font-bold flex flex-col space-y-5">
                <h1>Total Products</h1>
                    <span class="text-4xl">{{ total_products }}</span>

            </div>

            <div class="w-80 h-50 bg-white text-black rounded-xl shadow-xl text-center p-10 text-2xl font-bold flex flex-col space-y-5">
                <h1>Products Sold Today</h1>
                    <span class="text-4xl">{{ total_sold_today }}</span>

            </div>

            <div class="w-80 h-50 bg-white text-black rounded-xl shadow-xl text-center p-10 text-2xl font-bold flex flex-col space-y-5">
                <h1>Total Out-of-Stock</h1>
                    <span class="text-4xl">{{ product_low_stock }}</span>

            </div>

            <div class="w-80 h-50 bg-white text-black rounded-xl shadow-xl text-center p-10 text-2xl font-bold flex flex-col space-y-5">
                <h1>Total Sales Today</h1>
                    <span class="text-4xl">₱ {{ total_sales}}</span>

            </div>

        </div>

        <!-- Tbale -->
            <div class="mt-15 space-y-3 px-10">
                <h1>Recently Sold Items</h1>
                <Table>
                    <TableCaption></TableCaption>
                    <TableHeader>
                        <TableRow>
                        <TableHead className="text-xl text-left">Product Name</TableHead>
                        <TableHead className="text-xl text-center ">Quantity</TableHead>
                        <TableHead className="text-xl text-right ">Total Amount</TableHead>


                        </TableRow>
                    </TableHeader>
                   <TableBody>
                        <TableRow v-for="item in orders.flatMap(o => o.order_items)" :key="item.id">
                            <TableCell className="font-medium h-10" >{{ item.product_name }}</TableCell>
                            <TableCell className="text-center h-10">{{ item.quantity }}</TableCell>
                            <TableCell className="text-right h-10">₱ {{ item.total_price }}</TableCell>
                        </TableRow>
                    </TableBody>

                </Table>

            </div>
    </div>

    </AdminLayout>
</template>


