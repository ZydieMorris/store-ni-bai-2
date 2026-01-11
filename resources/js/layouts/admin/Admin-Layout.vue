<template>
    <div class="max-w-[1920px]">
        <Sidebar/>
        <Header/>
        <slot />

    </div>
</template>

<script setup lang="ts">
import Header from '@/components/admin/Header.vue';
import Sidebar from '@/components/admin/Sidebar.vue';


</script>

