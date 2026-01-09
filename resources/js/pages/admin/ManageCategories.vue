<script setup lang="ts">
import Button from '@/components/ui/button/Button.vue';
import AdminLayout from '@/layouts/admin/Admin-Layout.vue';
import { ArrowLeft, Edit, Plus, Trash } from 'lucide-vue-next';
import {
    Dialog,
    DialogClose,
    DialogContent,
    DialogDescription,
    DialogFooter,
    DialogHeader,
    DialogTitle,
    DialogTrigger,
} from '@/components/ui/dialog'

import { Label } from '@/components/ui/label'
import { Link, router, useForm } from '@inertiajs/vue3';
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
import Input from '@/components/ui/input/Input.vue';
import { ref, onMounted } from 'vue';


const form = useForm({
    category_name: '',
})

function saveCategory() {
    form.post('/products/categories', {
        onSuccess: () => {
            window.location.reload();
        }
    }

    )
}

interface Category {
    id: number
    category_name: string
}


 defineProps<{ categories: Category[] }>() 


function deleteCategory(id: number) {
    router.delete(`/products/categories/${id}`)
}

function editCategory(id: number) {
    form.put(`/products/categories/${id}`, {
        onSuccess: () => {
           
            window.location.reload();
        }
    })
}



</script>

<template>
    <AdminLayout>
        <div class=" ml-100 w-350 ">

            <div class="flex justify-between pt-35">
                <div class=" text-3xl font-semibold">
                    Manage Category
                </div>
                <Link href="/products">
                    <Button class="bg-gray-400">
                        <ArrowLeft /> Return
                    </Button>
                </Link>
            </div>

            <div class="mt-7">
                <Dialog>
                    <!-- Trigger button to open dialog -->
                    <DialogTrigger as-child>
                        <Button class="bg-green-600">
                            <Plus /> Add New
                        </Button>
                    </DialogTrigger>

                    <!-- Dialog content -->
                    <DialogContent class="sm:max-w-md">
                        <DialogHeader>
                            <DialogTitle></DialogTitle>
                            <DialogDescription>
                            </DialogDescription>
                        </DialogHeader>

                        <!-- Form inside dialog content -->
                        <form @submit.prevent="saveCategory" class="mt-4 space-y-4">
                            <div class="grid gap-2">
                                <Label for="category_name">Category Name</Label>
                                <Input id="category_name" v-model="form.category_name" />
                            </div>

                            <DialogFooter class="flex justify-end gap-2">
                                <DialogClose as-child>
                                    <Button variant="outline">Cancel</Button>
                                </DialogClose>
                                <Button type="submit">Save</Button>
                            </DialogFooter>
                        </form>
                    </DialogContent>
                </Dialog>
            </div>

            <div class="flex justify-end mt-10">
                <Input placeholder="Search" class="w-60 " />

            </div>

            <div class="mt-5">
                <Table>
                    <TableCaption>

                    </TableCaption>
                    <TableHeader>
                        <TableRow>
                            <TableHead class="font-bold text-white text-lg bg-green-400 rounded">
                                Category Name
                            </TableHead>
                        </TableRow>
                    </TableHeader>
                    <TableBody>
                        <TableRow v-for="category in categories" :key="category.id">
                            <TableCell class="font-medium flex justify-between">
                                <p> {{ category.category_name }}</p>
                                <div class="space-x-3">
                                    <Dialog>
                                        <!-- Trigger button to open dialog -->
                                        <DialogTrigger as-child>
                                            <Button class="bg-green-600">
                                                <Edit /> Edit
                                            </Button>
                                        </DialogTrigger>

                                        <!-- Dialog content -->
                                        <DialogContent class="sm:max-w-md">
                                            <DialogHeader>
                                                <DialogTitle></DialogTitle>
                                                <DialogDescription>
                                                </DialogDescription>
                                            </DialogHeader>

                                            <!-- Form inside dialog content -->
                                            <form @submit.prevent="editCategory(category.id)" class="mt-4 space-y-4">
                                                <div class="grid gap-2">
                                                    <Label for="category_name">Category Name</Label>
                                                    <Input id="category_name" v-model="form.category_name" />
                                                </div>

                                                <DialogFooter class="flex justify-end gap-2">
                                                    <DialogClose as-child>
                                                        <Button variant="outline">Cancel</Button>
                                                    </DialogClose>
                                                    <Button type="submit">Save</Button>
                                                </DialogFooter>
                                            </form>
                                        </DialogContent>
                                    </Dialog>

                                    <Button @click="deleteCategory(category.id)" variant="destructive">
                                        <Trash /> Delete
                                    </Button>
                                </div>
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
