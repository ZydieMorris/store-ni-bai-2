<script setup lang="ts">
import AdminLayout from '@/layouts/admin/Admin-Layout.vue';
import Button from '@/components/ui/button/Button.vue';
import { Link, router, useForm } from '@inertiajs/vue3';
import { ArrowLeft, Edit, Plus, Trash } from 'lucide-vue-next';

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
    DialogFooter,    DialogClose,
} from "@/components/ui/dialog"
import {onMounted} from "vue";
import Input from '@/components/ui/input/Input.vue';
import Label from '@/components/ui/label/Label.vue';




interface Product {
    id: number
    product_name: string
    price: number
    stock_available: number
}

interface Categories {
    id: number
    category_name: string
}


const { products, categories } = defineProps<{ products: Product[], categories: Categories[] }>();

const form = useForm({
    stock_to_add: 0,
     product_name: '',
    price: '',
    image: null as File | null,
})

function saveStock(productID: number) {
    form.post(`/product/add-stock/${productID}`, {
        onSuccess: () => {
            window.location.reload();
        }
    })
}


const onImageChange = (e: Event) => {
  const target = e.target as HTMLInputElement
  form.image = target.files?.[0] ?? null
}

function editProduct(productID :number) {
        form.put(`/product/edit/${productID}`,
            {
                onSuccess : () => {
                        window.location.reload();
                }
            }
        )
}




function deleteStock(productID: number) {
    router.delete(`/product/${productID}`)
}

const activeCategoryId = ref<number | null>(null);

    onMounted(() => {
  if (categories.length) {
    activeCategoryId.value = categories[0].id
  }
});

</script>

<template>
    <AdminLayout>
        <div class="ml-100 w-350">
            <div class="flex justify-between pt-35">
                <div class="text-3xl font-semibold" >
                    Manage Stocks
                </div>

                  <div v-if="activeCategoryId !== null" class="text-center font-semibold text-3xl   ">
          {{categories.find(c => c.id === activeCategoryId)?.category_name}}



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

                            <TableCell >
                                 {{ product.stock_available}}
                            </TableCell>
                            <TableCell class="flex gap-5">
                              <Dialog>
                        <DialogTrigger asChild>
                            <Button variant="outline" @click="form.stock_to_add = 0">
                                <Plus /> Add Stock
                            </Button>
                        </DialogTrigger>

                        <DialogContent class="sm:max-w-[625px]">
                            <form @submit.prevent="saveStock(product.id)">
                                <DialogHeader>
                                    <DialogTitle>
                                        Add Stock ({{ product.product_name }})
                                    </DialogTitle>
                                    <DialogDescription class="border-b" />
                                </DialogHeader>

                                <div class="flex flex-col space-y-2 mb-10 mt-10">
                                    <Label>Stock</Label>
                                    <Input
                                        type="number"
                                        min="1"
                                        class="h-10"
                                        v-model.number="form.stock_to_add"
                                    />
                                </div>

                                <DialogFooter class="space-x-5">
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


                                <!-- Edit Product -->
                                  <Dialog>
                        <DialogTrigger asChild>
                            <Button class="bg-green-600">Edit</Button>
                        </DialogTrigger>

                        <DialogContent class="sm:max-w-[625px]">
                            <form @submit.prevent="editProduct(product.id)">
                                <DialogHeader>
                                    <DialogTitle>
                                        Edit Product ({{ product.product_name }})
                                    </DialogTitle>
                                    <DialogDescription class="border-b" />
                                </DialogHeader>

                                <div class="flex flex-col space-y-2 mb-10 mt-10">
                                   <div>
                                    <Label>Product Name</Label>
                                    <Input
                                       placeholder="Enter new product name"
                                       v-model="form.product_name"
                                    />
                                   </div>

                                   <div>
                                    <Label>Price</Label>
                                    <Input placeholder=" Enter new price" v-model="form.price"/>
                                   </div>

                                   <div>
                                    <Label>Image</Label>
                                    <Input type="file" name="image" accept="image/*" @change="onImageChange" class="h-12"  />

                                   </div>


                                </div>

                                <DialogFooter class="space-x-5">
                                    <DialogClose asChild>
                                        <Button variant="outline">Cancel</Button>
                                    </DialogClose>
                                    <Button type="submit">Save changes</Button>
                                </DialogFooter>
                            </form>
                        </DialogContent>

                    </Dialog>
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
