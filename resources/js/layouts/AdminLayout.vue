<script setup lang="ts">
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
    <div class="dashboard-wrapper">
        <Header />
        <Sidebar role="admin">
            <AdminNav />
            <template #icons>
                <AdminNavIcons />
            </template>
        </Sidebar>
        
        <main :class="contentClass">
            <slot />
        </main>
    </div>
</template>
