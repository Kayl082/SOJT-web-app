<script setup lang="ts">
import { Head } from '@inertiajs/vue3';
import { computed } from 'vue';
import Header from '@/components/Header.vue';
import Sidebar from '@/components/Sidebar.vue';
import VendorNav from '@/components/navigation/VendorNav.vue';
import VendorNavIcons from '@/components/navigation/VendorNavIcons.vue';
import { useSidebar } from '@/composables/useSidebar';

const { isCollapsed } = useSidebar();
const contentClass = computed(() => ({
    'dashboard-content': true,
    'sidebar-collapsed': isCollapsed.value
}));
</script>

<template>
    <Head title="Vendor Dashboard" />

    <div class="dashboard-wrapper">
        <Header />
        <Sidebar role="vendor">
            <!-- Full navigation with text -->
            <VendorNav />
            <!-- Icon-only navigation -->
            <template #icons>
                <VendorNavIcons />
            </template>
        </Sidebar>
        
        <main :class="contentClass">
            <div class="container">
                <h1>Vendor Dashboard</h1>
                <p>Store management and analytics</p>
            </div>
        </main>
    </div>
</template>
