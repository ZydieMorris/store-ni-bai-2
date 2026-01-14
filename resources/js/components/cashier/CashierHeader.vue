<template>
     <div class="w-full   h-20 z-20  flex items-center bg-white justify-between px-10 shadow-xl">
      <div class="text-[#254F81] font-bold text-2xl">
        <p>Store ni Bai</p>
      </div>

      <div>
        <Button class="bg-[#254F81]" @click="logout">Logout</Button>
      </div>
    </div>
</template>

<script setup lang="ts">
import Button from '@/components/ui/button/Button.vue';
import { Link, router } from '@inertiajs/vue3';

function logout() {
  router.post('/logout');
}
</script>

