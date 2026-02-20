<script setup lang="ts">
import { computed } from 'vue';
import { usePage, router } from '@inertiajs/vue3';

const page = usePage();
const auth = computed(() => page.props.auth as { user: any; hasStore: boolean });

const showBanner = computed(() => {
    const user = auth.value?.user;
    const hasStore = auth.value?.hasStore;
    
    return user?.role === 'vendor' && !hasStore;
});
</script>

<template>
    <div v-if="showBanner" class="bg-gradient-to-r from-amber-50 to-emerald-50 border-b border-amber-200">
        <div class="px-4 sm:px-6 py-3">
            <div class="flex items-start gap-3">
                <div class="flex-shrink-0">
                    <div class="w-10 h-10 bg-emerald-700 rounded-lg flex items-center justify-center">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                </div>
                <div class="flex-1 min-w-0">
                    <h3 class="text-sm font-bold text-gray-900">Complete Your Store Setup</h3>
                    <p class="text-xs text-gray-600 mt-0.5">
                        You're in preview mode. Complete the setup below to unlock all features.
                    </p>
                </div>
            </div>
        </div>
    </div>
</template>
