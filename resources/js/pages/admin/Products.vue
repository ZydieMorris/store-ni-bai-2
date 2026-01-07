<script setup lang="ts">
import Button from '@/components/ui/button/Button.vue';
import AdminLayout from '@/layouts/admin/Admin-Layout.vue';
import { Plus } from 'lucide-vue-next';


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
import { useForm } from '@inertiajs/vue3';

const form = useForm({
  category_name: '',
})

function saveCategory() {
  form.post('/category')
}

</script>




<template>
  <AdminLayout>

    <!-- Your page content here -->
    <div class="flex  items-end justify-end pt-35 pr-20 pl-50 mx-auto bg-amber-500">


      <div>


   <Dialog>
        <!-- Trigger button -->
        <DialogTrigger as-child>
          <Button variant="outline">
            <Plus /> Manage Categories
          </Button>
        </DialogTrigger>

        <!-- Dialog content -->
        <DialogContent class="sm:max-w-md">
          <DialogHeader>
            <DialogTitle>Create Category</DialogTitle>
            <DialogDescription>
              Enter a name for your new product category.
            </DialogDescription>
          </DialogHeader>

          <!-- Form -->
          <form @submit.prevent="saveCategory" class="mt-4">
            <div class="grid gap-2">
              <Label for="category_name">Category Name</Label>
              <Input
                id="category_name"
                name="category_name"
                v-model="form.category_name"
              />
            
            </div>

            <DialogFooter class="mt-4 flex justify-end gap-2">
              <DialogClose as-child>
                <Button variant="outline">Cancel</Button>
              </DialogClose>
              <Button type="submit">Save</Button>
            </DialogFooter>
          </form>
        </DialogContent>
      </Dialog>

      </div>

    </div>

  </AdminLayout>
</template>
