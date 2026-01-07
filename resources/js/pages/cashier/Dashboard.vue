<template>
    <div>
        Hello cashier

        <Button @click="logout">Logout</Button>
    </div>
</template>

<script setup lang="ts">
import Button from '@/components/ui/button/Button.vue';
import { router } from '@inertiajs/vue3';

function logout() {
    router.post('/logout');
}
</script>
