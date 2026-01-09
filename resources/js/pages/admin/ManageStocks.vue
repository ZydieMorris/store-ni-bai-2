<script setup lang="ts">
import AdminLayout from '@/layouts/admin/Admin-Layout.vue';
import Button from '@/components/ui/button/Button.vue';
import { Link, router, useForm } from '@inertiajs/vue3';
import { ArrowLeft, Plus, Trash } from 'lucide-vue-next';
import {
    Table,
    TableBody,
    TableCaption,
    TableCell,
    TableFooter,
    TableHead,
    TableHeader,
    TableRow,
} from '@/components/ui/table'
import { ref } from 'vue';
import {
    Dialog,
    DialogContent,
    DialogDescription,
    DialogHeader,
    DialogTitle,
    DialogTrigger,
} from "@/components/ui/dialog"




interface Product {
    id: number
    product_name: string
    price: number
}
const { products } = defineProps<{ products: Product[] }>();

const form = useForm({
    stock_available: '',
})

function saveStock(productID: number) {
    form.post(`/product/add-stock/${productID}`, {
        onSuccess: () => {
            window.location.reload();
        }
    })
}

const stocksAvailable = ref('0');

function addStock(productID: number) {
    stocksAvailable.value = '0';
}


function deleteStock(productID: number) {
    router.delete(`/product/${productID}`)
}

</script>

<template>
    <AdminLayout>
        <div class="ml-100 w-350">
            <div class="flex justify-between pt-35">
                <div class="text-3xl font-semibold">
                    Manage Stocks
                </div>

                <Link href="/products">
                    <Button class="bg-gray-400">
                        <ArrowLeft /> Return
                    </Button>
                </Link>
            </div>

            <div class="mt-20">
                <Table>
                    <TableCaption></TableCaption>
                    <TableHeader>
                        <TableRow>

                            <TableHead>Product Name</TableHead>
                            <TableHead>Price</TableHead>
                            <TableHead>
                                Quantity of Serving
                            </TableHead>
                        </TableRow>
                    </TableHeader>
                    <TableBody>
                        <TableRow v-for="product in products" :key="product.id">
                            <TableCell class="font-medium">
                                {{ product.product_name }}
                            </TableCell>
                            <TableCell>
                                {{ product.price }}
                            </TableCell>
                            {{ }}
                            <TableCell>

                            </TableCell>
                            <TableCell class="flex gap-5">
                                <Dialog>

                                    <DialogTrigger asChild>
                                        <Button variant="outline">
                                            <Plus /> Add Stock
                                        </Button>
                                    </DialogTrigger>
                                    <DialogContent className="sm:max-w-[625px] ">
                                        <form @submit.prevent="saveStock(product.id)">
                                            <DialogHeader>

                                                <DialogTitle>Add Stock ({{ product.product_name }})</DialogTitle>
                                                <DialogDescription class="border-b">

                                                </DialogDescription>
                                            </DialogHeader>

                                            <div class="flex flex-col space-y-2 mb-10 mt-10">
                                                <Label>Stock</Label>
                                                <Input placeholder="0" class="h-10" v-model="form.stock_available" />
                                            </div>


                                            <DialogFooter class=" space-x-5">
                                                <DialogClose asChild>
                                                    <Button variant="outline">Cancel</Button>
                                                </DialogClose>
                                                <Button type="submit">Save changes</Button>
                                            </DialogFooter>
                                        </form>
                                    </DialogContent>

                                </Dialog>

                                <Button @click="deleteStock(product.id)" variant="destructive">
                                    <Trash />
                                </Button>
                            </TableCell>


                        </TableRow>
                    </TableBody>
                    <TableFooter>

                    </TableFooter>
                </Table>
            </div>
        </div>
    </AdminLayout>
</template>
