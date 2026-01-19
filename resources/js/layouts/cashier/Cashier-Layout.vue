<template>
    <div class="max-w-[1920px] mx-auto">
    <cashier-header/>
        <slot />
    </div>
</template>

<script setup lang="ts">
import CashierHeader from '@/components/cashier/CashierHeader.vue';


</script>

