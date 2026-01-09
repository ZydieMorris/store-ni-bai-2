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

interface Products {
  id: number
  product_name: string
  price: number
  image: string
  product_category_id: number
}

interface Category {
  id: number
  category_name: string
}
const { categories, products } = defineProps<{
  categories: Category[],
  products: Products[]
}>()



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
  image: null as File | null,
})

const onImageChange = (e: Event) => {
  const target = e.target as HTMLInputElement
  form.image = target.files?.[0] ?? null
}


function saveProduct() {
  form.post('/add-menu', {
    onSuccess: () => {
      window.location.reload()
    }
  }

  )
}

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
            <Button class="bg-[#254F81]">
              <Plus /> Manage Categories
            </Button>

          </Link>
        </div>
      </div>

      <!-- Catgeroy Content -->
      <div class="w-full space-y-3 mt-5 absolute top-50">

        <div v-if="activeCategoryId !== null" class="text-center font-semibold text-3xl   ">
          {{categories.find(c => c.id === activeCategoryId)?.category_name}}



        </div>

        <!-- Manage Stocks -->
        <div class="flex justify-center items-center">

          <Link :href="`/manage-stocks?category_id=${activeCategoryId}`">
            <Button class="bg-[#254F81]">
              <Plus /> Manage Stocks
            </Button>
          </Link>




        </div>

        <!-- Add Product -->
        <div class="flex justify-end items-end mt-10 ">
          <Dialog>

            <DialogTrigger as-child>
              <Button class="h-30 w-40 bg-[#254F81]" @click="form.category_id = activeCategoryId">
                <Plus /> Add Menu
              </Button>
            </DialogTrigger>
            <DialogContent class="sm:max-w-[625px]">
              <DialogHeader>
                <DialogTitle>Add New Menu ( {{categories.find(c => c.id === activeCategoryId)?.category_name}} )
                </DialogTitle>
                <DialogDescription>

                </DialogDescription>
              </DialogHeader>
              <form @submit.prevent="saveProduct">
                <div class="flex justify-between p-3">
                  <div class="grid gap-3">
                    <Label for="name-1">Product Name</Label>
                    <Input v-model="form.product_name" class="w-70 h-12" />
                  </div>
                  <div class="grid gap-3">
                    <Label>Price</Label>
                    <Input v-model="form.price" class="h-12" />
                  </div>
                </div>

                <div class="space-y-2">

                  <Label>Choose Image</Label>
                  <Input type="file" name="image" accept="image/*" @change="onImageChange" class="h-12" />
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
              </form>
            </DialogContent>

          </Dialog>

          <div v-for="product in products" :key="product.id">
            <p>
              {{ product.product_name }}
            </p>
          </div>
        </div>

       


      </div>



    </div>
  </AdminLayout>
</template>
