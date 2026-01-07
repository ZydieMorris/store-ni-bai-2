<template>
    <div>
        <Header/>
        <slot />
        <Sidebar/>
    </div>
</template>

<script setup lang="ts">
import Header from '@/components/admin/Header.vue';
import Sidebar from '@/components/admin/Sidebar.vue';


</script>

