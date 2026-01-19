<script setup lang="ts">
import { computed, ref } from 'vue'
import { Link , useForm, router } from '@inertiajs/vue3'
import Button from '@/components/ui/button/Button.vue'
import Input from '@/components/ui/input/Input.vue'
import { NativeSelect, NativeSelectOption } from '@/components/ui/native-select'
import {
  Dialog,
  DialogContent,
  DialogHeader,
  DialogTitle,
  DialogDescription,
  DialogFooter,
  DialogTrigger,
    DialogClose,
} from '@/components/ui/dialog'
import Label from '@/components/ui/label/Label.vue'
import { Trash } from 'lucide-vue-next'
import CashierLayout from '@/layouts/cashier/Cashier-Layout.vue'


function logout() {
  router.post('/logout')
}

interface Product {
  id: number
  product_name: string
  price: number
  stock_available: number
  product_category_id: number
  image: string
}

interface Category {
  id: number
  category_name: string
  products: Product[]
}

interface CartItem {
  id: number
  product_id: number
  product_quantity: number
  unit_price: number
  product: Product
}

const props = defineProps<{ categories: Category[], cartItems: CartItem[],  searchTerm: string | null;}>()

const { categories, cartItems } = props

const form = useForm({
  product_id: 0,
  unit_price: 0,
  product_quantity: 1,
  amount_paid: 0,
})

const noOrder = computed(() => cartItems.length === 0)

function addToCart(product: Product) {
  form.product_id = product.id
  form.unit_price = product.price

  form.post('/cart/add', {
    onSuccess: () => window.location.reload(),
  })
}

const totalAmount = computed(() => {
  return cartItems.reduce((total, item) => {
    return total + item.unit_price * item.product_quantity
  }, 0)
})

function cancelOrder(cartItemId: number) {
  router.delete(`/cart/delete/${cartItemId}`)
}

function deleteOrder() {
  router.delete('/cart/clear')
}

function cashierPay() {
  router.post('/cashier/pay', {
    amount_paid: form.amount_paid,
  })
}

const selectedCategory = ref<null | number>(null)

const filteredCategory = computed(() => {
  if (!selectedCategory.value) return categories

  return categories.filter((category: Category) => category.id === selectedCategory.value)
})

import { watchDebounced } from '@vueuse/core';

const searchTerm = ref(props.searchTerm || '');

watchDebounced(
  searchTerm,
  (newValue) => {
    router.get('/cashier/dashboard', {
      search: newValue,
    }, {
      replace: true,
      preserveState: true,
    })
  },
  { debounce: 1000 }
);



</script>

<template>
  <CashierLayout class="max-w-[1920px] mx-auto min-h-screen bg-gray-300">
    <!-- Header -->

    <!-- Search bar -->
    <div class="flex gap-5 mt-20 px-20">
      <div>
        <Input v-model="searchTerm"  placeholder="Search Item" class="bg-white w-100" />
      </div>

      <div class="bg-white rounded">
       <NativeSelect v-model="selectedCategory">
  <NativeSelectOption value="">
    All Categories
  </NativeSelectOption>

  <NativeSelectOption
    v-for="category in categories"
    :key="category.id"
    :value="category.id"
  >
    {{ category.category_name }}
  </NativeSelectOption>
</NativeSelect>



      </div>

    </div>
    <!-- Main -->
    <div class="px-20 mt-5 space-y-5 flex space-x-20">

        <!-- <div v-for="items in filteredCategory" :key="items.id">
                {{ items.products }}
        </div> -->


      <!-- Products -->
      <div class="w-full max-w-5xl px-10 py-6  rounded-lg  bg-white">

        <div v-for="category in filteredCategory" :key="category.id" class="flex flex-col   gap-4 mb-8">
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
                  class="shrink-0 w-50 h-55 relative bg-white shadow-2xl rounded-md cursor-pointer text-sm font-medium">
                  <img :src="product.image" class="h-40 object-cover " />

                  <div class="absolute right-0 top-0 bg-[#254F81] p-2 px-5 rounded-b text-white font-semibold">
                    ₱ {{ product.price }}.00
                  </div>

                  <p class="font-semibold px-2">{{ product.product_name }}</p>
                  <p class="text-[#254F81] px-2">{{ product.stock_available }} serving(s)</p>
                </div>
              </DialogTrigger>

              <DialogContent class="sm:max-w-md">
                <form @submit.prevent="addToCart(product)">
                  <DialogHeader>
                    <DialogTitle>Add to Cart</DialogTitle>
                    <DialogDescription class="space-y-5">

                      <div>
                        <Label>Product Name</Label>
                        <!-- Show product name but bind product_id in form -->
                        <Input type="text" class="mt-1 block w-full" readonly :default-value='product.product_name' />
                      </div>

                      <div>
                        <Label>Price</Label>
                        <Input type="text" class="mt-1 block w-full" readonly :default-value="product.price" />
                      </div>

                      <p class="text-[#254F81]">Stock(s) remaining: {{ product.stock_available }}</p>

                      <div>
                        <Label>Quantity</Label>
                        <Input type="number" min="1" :max="product.stock_available" v-model="form.product_quantity" />
                      </div>

                    </DialogDescription>
                  </DialogHeader>

                  <div class="mt-4">
                    <img :src="product.image" class="w-full h-48 object-cover rounded-md" />
                  </div>

                  <DialogFooter>
                    <Button type="button" variant="outline" class="mr-2">Cancel</Button>
                    <Button type="submit">Add to Cart</Button>
                  </DialogFooter>
                </form>
              </DialogContent>


            </Dialog>
          </div>

        </div>

      </div>









      <!-- Bill Summary -->
      <div class="w-100 h-auto bg-white rounded-lg shadow-xl p-5">
        <h1 class="text-center font-bold text-2xl">Cart Summary</h1>

        <div>
          <table class="mt-5 border-b">
            <thead>
              <tr class="font-thin border-b w-full bg-[#254F81] text-white">
                <th class="font-semibold text-sm w-30 ">Product</th>
                <th class="font-semibold text-sm w-30">Quantity</th>
                <th class="font-semibold text-sm w-30">Price</th>
                <th class="font-semibold text-sm w-30">Action</th>

              </tr>
            </thead>

            <tbody>
              <tr v-if="noOrder">
                <td colspan="4" class="text-center p-5">No order yet. Please add items to the cart.</td>
              </tr>

              <tr v-else v-for="cartItem in cartItems" :key="cartItem.id" class="h-12 align-middle ">

                <td class="text-center ">{{ cartItem.product.product_name }}</td>
                <td class="text-center">{{ cartItem.product_quantity }}</td>
                <td class="text-center ">₱ {{ cartItem.product.price * cartItem.product_quantity }}</td>
                <td class="text-center "><Button variant="destructive" @click="cancelOrder(cartItem.id)">
                    <Trash />
                  </Button></td>
              </tr>


            </tbody>
          </table>

          <div class="flex justify-between pr-10 p-1 bg-[#254F81] text-white">
            <p class="font-bold">Total</p>
            <p>₱ {{ totalAmount }}</p>
          </div>

          <!-- <div>
            <h1>Mode of Payment</h1>
          </div> -->

          <div class="flex flex-col mt-5 space-y-2">

            <Dialog>

              <DialogTrigger asChild>
                <Button class="w-full">Pay Order</Button>
              </DialogTrigger>
              <DialogContent className="sm:max-w-[425px]">
                <form @submit.prevent="cashierPay()">
                  <DialogHeader>
                    <DialogTitle></DialogTitle>
                    <DialogDescription>

                    </DialogDescription>
                  </DialogHeader>


                  <div class="space-y-2">
                    <Label htmlFor="username-1">Enter Amount</Label>
                    <Input type="number" v-model="form.amount_paid" />
                  </div>



                  <DialogFooter class="mt-5">
                    <DialogClose asChild>
                      <Button variant="outline">Cancel</Button>
                    </DialogClose>
                    <Button type="submit">Pay</Button>
                  </DialogFooter>
                </form>
              </DialogContent>

            </Dialog>



            <Button @click="deleteOrder">Cancel Order</Button>



          </div>


        </div>


      </div>



    </div>


  </CashierLayout>
</template>
