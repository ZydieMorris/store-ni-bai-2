<script setup lang="ts">
import Button from '@/components/ui/button/Button.vue';
import AdminLayout from '@/layouts/admin/Admin-Layout.vue';
import { Plus, PrinterCheck } from 'lucide-vue-next';


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
import { Input } from '@/components/ui/input'
import { Label } from '@/components/ui/label'
import { Link, useForm } from '@inertiajs/vue3';
import { onMounted, ref } from 'vue';



interface Category {
  id: number
  category_name: string
}


const { categories } = defineProps<{ categories: Category[] }>()

const activeCategoryId = ref<number | null>(null);

onMounted(() => {
  if (categories.length) {
    activeCategoryId.value = categories[0].id
  }
});


const form = useForm({
    product_name: '',
    category_id: activeCategoryId.value,
    price: '',
    image: '',
})

</script>




<template>
  <AdminLayout>

    <div class="flex   ml-100 relative w-350  ">

      <!-- Tab Area -->
      <div class=" flex justify-between mt-35  space-x-15 w-full ">

        <div class="space-x-15">

          <button v-for="category in categories" :key="category.id" @click="activeCategoryId = category.id"
            class="pb-2 font-bold " :class="activeCategoryId === category.id
              ? 'border-b-2 border-black'
              : 'text-gray-400'">
            {{ category.category_name }}
          </button>



        </div>

        <div class=" ">
          <Link href="/manage-categories">
            <Button>
              <Plus /> Manage Categories
            </Button>

          </Link>
        </div>
      </div>



      <div class="w-full space-y-3 mt-5    bg-red-200 absolute top-50">

        <div v-if="activeCategoryId !== null" class="text-center font-semibold text-3xl   ">

              {{ categories.find(c => c.id === activeCategoryId)?.category_name }}

        </div>

        <div class="flex justify-center items-center">

            <Link href="/manage-stocks">
                                  <Button> <Plus/> Manage Stocks</Button>
            </Link>

        </div>

        <!-- Add Product -->
            <div class="flex justify-center items-center mt-10 ">
                <Dialog >
                <form>
                <DialogTrigger as-child>
                    <Button class="h-30 w-40">
                    <Plus/> Add Menu
                    </Button>
                </DialogTrigger>
                <DialogContent class="sm:max-w-[625px]">
                    <DialogHeader>
                    <DialogTitle>Add New Menu ( {{ categories.find(c => c.id === activeCategoryId)?.category_name }} )</DialogTitle>
                    <DialogDescription>

                    </DialogDescription>
                    </DialogHeader>
                    <div class="flex justify-between p-3">
                    <div class="grid gap-3">
                        <Label for="name-1">Product Name</Label>
                        <Input class="w-70 h-12" />
                    </div>
                    <div class="grid gap-3">
                        <Label >Price</Label>
                        <Input  class="h-12"/>
                    </div>
                    </div>

                    <div class="space-y-2">

                        <Label>Choose Image</Label>
                        <Input type="file" class="h-12"/>
                    </div>
                    <DialogFooter>
                    <DialogClose as-child>
                        <Button variant="outline">
                        Cancel
                        </Button>
                    </DialogClose>
                    <Button type="submit">
                        Save
                    </Button>
                    </DialogFooter>
                </DialogContent>
                </form>
            </Dialog>
          </div>

      </div>






    </div>





  </AdminLayout>
</template>
