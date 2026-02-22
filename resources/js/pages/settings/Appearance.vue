<script setup lang="ts">
import { Head, usePage } from '@inertiajs/vue3';
import { computed } from 'vue';
import AppearanceTabs from '@/components/AppearanceTabs.vue';
import Heading from '@/components/Heading.vue';
import VendorLayout from '@/layouts/VendorLayout.vue';
import CustomerLayout from '@/layouts/CustomerLayout.vue';
import AdminLayout from '@/layouts/AdminLayout.vue';
import SettingsLayout from '@/layouts/settings/Layout.vue';

const page = usePage();
const user = page.props.auth.user;
const userRole = computed(() => {
    // Try direct role property first
    if (user?.role) {
        return user.role;
    }
    // Fallback to roles array
    if (user?.roles && user.roles.length > 0) {
        return user.roles[0].name;
    }
    return 'customer';
});

const LayoutComponent = computed(() => {
    switch (userRole.value) {
        case 'admin':
            return AdminLayout;
        case 'vendor':
            return VendorLayout;
        default:
            return CustomerLayout;
    }
});
</script>

<template>
    <component :is="LayoutComponent">
        <Head title="Appearance settings" />

        <h1 class="sr-only">Appearance Settings</h1>

        <SettingsLayout>
            <div class="space-y-6">
                <Heading
                    variant="small"
                    title="Appearance settings"
                    description="Update your account's appearance settings"
                />
                <AppearanceTabs />
            </div>
        </SettingsLayout>
    </component>
</template>
