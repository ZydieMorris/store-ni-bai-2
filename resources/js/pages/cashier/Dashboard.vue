<script setup lang="ts">
import Button from '@/components/ui/button/Button.vue';
import Input from '@/components/ui/input/Input.vue';
import { Link, router } from '@inertiajs/vue3';
import {
  NativeSelect,
  NativeSelectOption,
} from '@/components/ui/native-select'
import { onMounted, ref } from 'vue';
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



function logout() {
     router.post('/logout');
    }

  interface Products {
    id: number
    product_name: string
    price: number
    stock_available: number
    product_category_id: number
    image: string
}

interface Categories {
    id: number
    category_name: string
    products: Products[]
}


const { categories } = defineProps<{ categories: Categories[] }>();

const activeCategoryId = ref<number | null>(null);





</script>
<template>
    <div class="max-w-[1920px] mx-auto min-h-screen bg-gray-300">
        <!-- Header -->
        <div class="w-full  h-20 z-20  flex items-center bg-white justify-between px-10 shadow-xl">
            <div class="text-[#254F81] font-bold text-2xl">
                <p>Store ni Bai</p>
            </div>

            <div>
                <Button class="bg-[#254F81]" @click="logout">Logout</Button>
            </div>
        </div>
        <!-- Search bar -->
            <div class="flex gap-5 mt-20 px-20">
                <div>
                    <Input placeholder="Search Item" class="bg-white w-100"/>
                </div>

                <div class="bg-white rounded">
                    <NativeSelect>
                        <NativeSelectOption value="">
                        Filter Category
                        </NativeSelectOption>
                        <NativeSelectOption value="todo">
                        Todo
                        </NativeSelectOption>
                        <NativeSelectOption value="in-progress">
                        In Progress
                        </NativeSelectOption>
                        <NativeSelectOption value="done">
                        Done
                        </NativeSelectOption>
                        <NativeSelectOption value="cancelled">
                        Cancelled
                        </NativeSelectOption>
                    </NativeSelect>
                </div>

            </div>
        <!-- Main -->
        <div class="px-20 mt-5 space-y-5 flex space-x-20">


                        <!-- Products -->
        <div class="w-full max-w-5xl px-10 py-6  rounded-lg  bg-white">

            <div
                v-for="category in categories"
                :key="category.id"
                class="flex flex-col   gap-4 mb-8"
            >
                <div class="flex justify-between bg-blue-400 px-5">
                    <div class="text-lg text-white font-semibold">
                        {{ category.category_name }}
                    </div>

                    <div>
                        <Link class="text-white text-sm">View All</Link>
                    </div>


                </div>

                <!-- Bottom slide / horizontal scroll -->
              <div class="flex gap-4 overflow-x-auto">
  <Dialog v-for="product in category.products" :key="product.id">
    <!-- Trigger: the product box -->
    <DialogTrigger as-child>
      <div
        class="flex-shrink-0 w-50 h-55 relative bg-white shadow-2xl rounded-md cursor-pointer text-sm font-medium"
      >
        <img :src="product.image" class="h-40 object-cover "/>

        <div class="absolute right-0 top-0 bg-[#254F81] p-2 px-5 rounded-b text-white font-semibold">
          ₱  {{ product.price }}.00
        </div>

        <p class="font-semibold px-2">{{ product.product_name }}</p>
        <p class="text-[#254F81] px-2">{{ product.stock_available }} serving(s)</p>
      </div>
    </DialogTrigger>

    <!-- Content: what appears in the modal -->
    <DialogContent class="sm:max-w-md">
      <DialogHeader>
        <DialogTitle></DialogTitle>
        <DialogDescription class="space-y-5">

          <div>
            <Label>Product Name</Label>
            <Input
              type="text"
              class="mt-1 block w-full"
              :default-value="product.product_name"
              readonly
            />
          </div>

          <div>
            <Label>Price</Label>
            <Input
              type="text"
              class="mt-1 block w-full"
              :default-value="product.price"
              readonly
            />
          </div>

          <p class="text-[#254F81]">Stock(s) remaining: {{ product.stock_available }}</p>

          <div>
            <Label>Quantity</Label>
            <Input placeholder="0"/>

          </div>
        </DialogDescription>
      </DialogHeader>

      <div class="mt-4">
        <img :src="product.image" class="w-full h-48 object-cover rounded-md" />
      </div>

      <DialogFooter>
        <Button variant="outline" class="mr-2">Cancel</Button>
        <Button>Add to Cart</Button>
      </DialogFooter>
    </DialogContent>
  </Dialog>
</div>

            </div>

        </div>









                <!-- Bill Summary -->
                 <div class="w-100 h-100 bg-white rounded-lg shadow-xl">
                    hi
                </div>



        </div>


    </div>
</template>

