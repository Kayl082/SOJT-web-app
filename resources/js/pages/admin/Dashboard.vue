<script setup lang="ts">
import { Head } from '@inertiajs/vue3';
import { computed } from 'vue';
import Header from '@/components/Header.vue';
import Sidebar from '@/components/Sidebar.vue';
import AdminNav from '@/components/navigation/AdminNav.vue';
import AdminNavIcons from '@/components/navigation/AdminNavIcons.vue';
import { useSidebar } from '@/composables/useSidebar';

const { isCollapsed } = useSidebar();
const contentClass = computed(() => ({
    'dashboard-content': true,
    'sidebar-collapsed': isCollapsed.value
}));
</script>

<template>
    <Head title="Admin Dashboard" />

    <div class="dashboard-wrapper">
        <Header />
        <Sidebar role="admin">
            <AdminNav />
            <template #icons>
                <AdminNavIcons />
            </template>
        </Sidebar>

        <main :class="contentClass">
            <div class="container">
                <h1>Admin Dashboard</h1>
                <p>Platform overview and management</p>
            </div>
        </main>
    </div>
</template>
