<script setup lang="ts">
import { Link } from '@inertiajs/vue3';
import Heading from '@/components/Heading.vue';
import { useCurrentUrl } from '@/composables/useCurrentUrl';
import { toUrl } from '@/lib/utils';
import { edit as editAppearance } from '@/routes/appearance';
import { edit as editProfile } from '@/routes/profile';
import { show } from '@/routes/two-factor';
import { edit as editPassword } from '@/routes/user-password';
import { type NavItem } from '@/types';

const sidebarNavItems: NavItem[] = [
    {
        title: 'Profile',
        href: editProfile(),
    },
    {
        title: 'Password',
        href: editPassword(),
    },
    {
        title: 'Two-Factor Auth',
        href: show(),
    },
    {
        title: 'Appearance',
        href: editAppearance(),
    },
];

const { isCurrentUrl } = useCurrentUrl();
</script>

<template>
    <div class="settings-container">
        <div class="settings-header">
            <h1 class="settings-title">Settings</h1>
            <p class="settings-description">Manage your profile and account settings</p>
        </div>

        <div class="settings-layout">
            <aside class="settings-sidebar">
                <nav class="settings-nav" aria-label="Settings">
                    <Link
                        v-for="item in sidebarNavItems"
                        :key="toUrl(item.href)"
                        :href="item.href"
                        :class="[
                            'settings-nav-item',
                            { 'active': isCurrentUrl(item.href) },
                        ]"
                    >
                        {{ item.title }}
                    </Link>
                </nav>
            </aside>

            <div class="settings-content">
                <slot />
            </div>
        </div>
    </div>
</template>

<style scoped>
.settings-container {
    max-width: 1200px;
    margin: 0 auto;
}

.settings-header {
    margin-bottom: 32px;
}

.settings-title {
    font-size: 1.875rem;
    font-weight: 700;
    color: #1B4D3E;
    margin-bottom: 8px;
    font-family: 'Montserrat', sans-serif;
}

.settings-description {
    color: #6b7280;
    font-size: 0.95rem;
}

.settings-layout {
    display: flex;
    gap: 48px;
}

.settings-sidebar {
    width: 200px;
    flex-shrink: 0;
}

.settings-nav {
    display: flex;
    flex-direction: column;
    gap: 4px;
    position: sticky;
    top: 100px;
}

.settings-nav-item {
    padding: 10px 16px;
    border-radius: 8px;
    color: #6b7280;
    font-size: 0.9rem;
    font-weight: 500;
    text-decoration: none;
    transition: all 0.2s;
}

.settings-nav-item:hover {
    background: rgba(27, 77, 62, 0.05);
    color: #1B4D3E;
}

.settings-nav-item.active {
    background: rgba(27, 77, 62, 0.1);
    color: #1B4D3E;
    font-weight: 600;
}

.settings-content {
    flex: 1;
    max-width: 640px;
}

@media (max-width: 1024px) {
    .settings-layout {
        flex-direction: column;
        gap: 24px;
    }

    .settings-sidebar {
        width: 100%;
    }

    .settings-nav {
        flex-direction: row;
        overflow-x: auto;
        position: static;
    }

    .settings-nav-item {
        white-space: nowrap;
    }
}
</style>
