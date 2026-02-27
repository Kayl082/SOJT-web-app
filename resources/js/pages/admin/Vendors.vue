<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';
import { ref, computed } from 'vue';
import Header from '@/components/Header.vue';
import Sidebar from '@/components/Sidebar.vue';
import AdminNav from '@/components/navigation/AdminNav.vue';
import AdminNavIcons from '@/components/navigation/AdminNavIcons.vue';
import PlaceholderPage from '@/components/PlaceholderPage.vue';
import { useSidebar } from '@/composables/useSidebar';
import CustomerNav from '@/components/navigation/CustomerNav.vue';
import CustomerNavIcons from '@/components/navigation/CustomerNavIcons.vue';
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card'
import { Input } from '@/components/ui/input'
import { Button } from '@/components/ui/button'
import {
  DropdownMenu,
  DropdownMenuTrigger,
  DropdownMenuContent,
  DropdownMenuItem,
  DropdownMenuSeparator,
} from '@/components/ui/dropdown-menu'
import { ChevronDown, User, Store, Power, Trash2, Circle, } from 'lucide-vue-next'
import {
  Avatar,
  AvatarImage,
  AvatarFallback
} from '@/components/ui/avatar'
import { Dialog, DialogContent, DialogHeader, DialogTitle, DialogFooter } from '@/components/ui/dialog'
import { MoreVertical } from 'lucide-vue-next'

const { isCollapsed } = useSidebar();
const contentClass = computed(() => ({
    'dashboard-content': true,
    'sidebar-collapsed': isCollapsed.value
}));


interface User {
  id: number
  name: string
  email: string
  phone?: string
  is_active: boolean
  created_at: string
}

const Users: User[] = [
  {
    id: 1,
    name: "Maria Santos",
    email: "maria.santos@example.com",
    phone: "+63 917 123 4567",
    is_active: true,
    created_at: "2025-11-12T09:15:00Z",
  },
  {
    id: 2,
    name: "Daniel Cruz",
    email: "daniel.cruz@example.com",
    phone: "+63 905 987 6543",
    is_active: false,
    created_at: "2025-10-03T14:42:00Z",
  },
  {
    id: 3,
    name: "Angela Reyes",
    email: "angela.reyes@example.com",
    phone: "+63 928 456 7890",
    is_active: true,
    created_at: "2026-01-20T11:30:00Z",
  },
]

interface Store {
  id: number
  name: string
  address: string
  phone: string
  hours: string
  isOpen: boolean
  is_active: boolean
  logo?: string
  cover?: string
  owner: User
}

// Mock Data
const stores = ref<Store[]>([
  {
    id: 1,
    name: 'Emerald Fresh Market',
    address: '123 Green Valley Rd, NY',
    phone: '(123) 456-7890',
    hours: 'Mon - Fri: 8AM - 8PM',
    isOpen: true,
    is_active: true,
    logo: '',
    cover: 'https://picsum.photos/600/400?random=1',
    owner: Users[1],
  },
  {
    id: 2,
    name: 'Golden Harvest Grocery',
    address: '45 Sunset Blvd, CA',
    phone: '(987) 654-3210',
    hours: 'Daily: 9AM - 6PM',
    isOpen: false,
    is_active: true,
    logo: '',
    cover: 'https://picsum.photos/600/400?random=2',
    owner: Users[2],
  },
  {
    id: 3,
    name: 'Urban Organic Hub',
    address: '78 City Center Ave, TX',
    phone: '(555) 234-5678',
    hours: 'Mon - Sat: 7AM - 9PM',
    isOpen: true,
    is_active: false,
    logo: '',
    cover: 'https://picsum.photos/600/400?random=3',
    owner: Users[3],
  },
])

const search = ref('')
const searchBy = ref<'name' | 'address'>('name')

const filterStatus = ref<'all' | 'open'>('all')

const filteredStores = computed(() => {
  return stores.value.filter(store => {

    const valueToSearch =
      searchBy.value === 'name'
        ? store.name
        : store.address

    const matchesSearch = valueToSearch
      .toLowerCase()
      .includes(search.value.toLowerCase())

    const matchesOpen =
      filterStatus.value === 'open'
        ? store.isOpen
        : true

    return matchesSearch && matchesOpen
  })
})

const selectedVendor = ref<Store | null>(null)
const showDeleteDialog = ref(false)
const showOwnerDialog = ref(false)

const toggleStoreStatus = (vendor: any) => {
  console.log('Toggle store open status:', vendor.id)
}

const toggleOwnerStatus = (vendor: any) => {
  console.log('Toggle owner active status:', vendor.owner.id)
}

const deleteStore = () => {
  console.log('Delete store:', selectedVendor.value?.id)
  showDeleteDialog.value = false
}

const viewOwner = (vendor: any) => {
  selectedVendor.value = vendor
  showOwnerDialog.value = true
}
</script>

<template>
    <Head title="Manage Vendors" />

    <div class="dashboard-wrapper">
        <Header />
        <Sidebar role="admin">
            <AdminNav />
            <template #icons>
                <AdminNavIcons />
            </template>
        </Sidebar>

        <main :class="contentClass">

            <div class="p-6 space-y-6">
                <!-- Page Title -->
                <div class="space-y-1">
                <h1 class="text-2xl font-semibold text-[#245c4a]">Manage Vendors</h1>
                <p class="text-muted-foreground">
                    View and manage all vendor accounts, approve new vendors, and monitor vendor activity.
                </p>
                </div>

                <!-- Search + Filter -->
                <div class="flex flex-col sm:flex-row gap-4">
                    <div class="flex w-full max-w-1xl gap-3">

                    <DropdownMenu>
                        <div class="relative w-full max-w-3xl cursor-pointer">
                        
                            <!-- Input -->
                            <Input
                                v-model="search"
                                :placeholder="`Search by ${searchBy}...`"
                                class="pr-10"
                            />
                            <DropdownMenuTrigger as-child>
                                <button
                                    type="button"
                                    class="absolute right-2 top-1/2 -translate-y-1/2 p-1 rounded-md hover:bg-muted transition"
                                >
                                    <ChevronDown class="w-4 h-4 opacity-60" />
                                </button>
                            </DropdownMenuTrigger>
                        </div>
                    

                    <DropdownMenuContent align="end" class="w-40">
                        <DropdownMenuItem @click="searchBy = 'name'">
                        Search by Name
                        </DropdownMenuItem>

                        <DropdownMenuItem @click="searchBy = 'address'">
                        Search by Address
                        </DropdownMenuItem>
                    </DropdownMenuContent>
                    </DropdownMenu>

                    </div>
                </div>

                <!-- Store Grid -->
                <div
                class="relative grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6"
                >
                <Card
                    v-for="store in filteredStores"
                    :key="store.id"
                    class="p-0 pb-5 rounded-xl shadow-sm hover:shadow-md transition-all duration-200 border border-border"
                >

                    <!-- Cover Image -->
                    <div class="relative h-40 w-full overflow-hidden">
                        <img
                        :src="store.cover"
                        alt="Store cover"
                        class="h-full w-full object-cover"
                        />

                        <div class="absolute top-2 right-2 z-10">
                            <DropdownMenu>
                                <DropdownMenuTrigger as-child>
                                <Button
                                size="icon"
                                class="bg-white/70 hover:bg-white shadow-sm rounded-md transition-colors"
                                >
                                <MoreVertical class="w-4 h-4 text-foreground" />
                                </Button>
                                </DropdownMenuTrigger>

                                <DropdownMenuContent
                                align="end"
                                class="w-56 rounded-xl border border-border shadow-md p-2"
                                >
                                <!-- View Owner -->
                                <DropdownMenuItem
                                    class="flex items-center gap-2 rounded-md px-2 py-2 text-sm cursor-pointer hover:bg-accent transition-colors"
                                    @click="viewOwner(store)"
                                >
                                    <User class="w-4 h-4 text-muted-foreground" />
                                    <span>View Owner Info</span>
                                </DropdownMenuItem>

                                <!-- Toggle Store -->
                                <DropdownMenuItem
                                    class="flex items-center gap-2 rounded-md px-2 py-2 text-sm cursor-pointer hover:bg-accent transition-colors"
                                    @click="toggleStoreStatus(store)"
                                >
                                    <Store class="w-4 h-4 text-muted-foreground" />
                                    <span>
                                    {{ store.isOpen ? 'Mark as Closed' : 'Mark as Open' }}
                                    </span>
                                </DropdownMenuItem>

                                <!-- Toggle Owner -->
                                <DropdownMenuItem
                                    class="flex items-center gap-2 rounded-md px-2 py-2 text-sm cursor-pointer hover:bg-accent transition-colors"
                                    @click="toggleOwnerStatus(store)"
                                >
                                    <Power
                                    class="w-4 h-4"
                                    :class="store.owner?.is_active
                                        ? 'text-emerald-600'
                                        : 'text-muted-foreground'"
                                    />
                                    <span>
                                    {{ store.owner?.is_active ? 'Deactivate Owner' : 'Activate Owner' }}
                                    </span>
                                </DropdownMenuItem>

                                <DropdownMenuSeparator class="my-2" />

                                <!-- Delete -->
                                <DropdownMenuItem
                                    class="flex items-center gap-2 rounded-md px-2 py-2 text-sm cursor-pointer text-destructive hover:bg-destructive/10 transition-colors"
                                    @click="() => { selectedVendor = store; showDeleteDialog = true }"
                                >
                                    <Trash2 class="w-4 h-4" />
                                    <span>Delete Store</span>
                                </DropdownMenuItem>
                                </DropdownMenuContent>
                            </DropdownMenu>
                        </div>


                        <!-- Store Status Indicators -->
                        <div class="absolute top-2 left-2 z-10 flex flex-col gap-2">

                        <!-- Store Active / Inactive -->
                        <span
                        class="flex items-center gap-1 px-3 py-1 text-xs font-medium rounded-full shadow-sm backdrop-blur-sm"
                        :class="store.is_active
                            ? 'bg-emerald-500/90 text-white'
                            : 'bg-gray-400/90 text-white'"
                        >
                        <Circle class="w-3 h-3 fill-white" />
                        {{ store.is_active ? 'Active' : 'Inactive' }}
                        </span>

                        <!-- Open / Closed -->
                        <span
                        class="flex items-center gap-1 px-3 py-1 text-xs font-medium rounded-full shadow-sm backdrop-blur-sm"
                        :class="store.isOpen
                            ? 'bg-[#C5A059]/95 text-white'
                            : 'bg-black/70 text-white'"
                        >
                        <Store class="w-3 h-3" />
                        {{ store.isOpen ? 'Open Now' : 'Closed' }}
                        </span>

                        </div>

                        <!-- gradient overlay -->
                        <div class="absolute inset-0 bg-gradient-to-t from-black/40 to-transparent"></div>
                    </div>

                    <CardHeader>
                        <div class="flex items-center gap-3">
                            
                            <!-- Logo -->
                            <Avatar class="h-10 w-10">
                            <AvatarImage v-if="store.logo" :src="store.logo" />
                            <AvatarFallback class="bg-[#245c4a] text-white text-sm font-medium">
                                {{ store.name.slice(0, 2).toUpperCase() }}
                            </AvatarFallback>
                            </Avatar>

                            <!-- Store Name -->
                            <CardTitle class="text-lg text-[#245c4a]">
                            {{ store.name }}
                            </CardTitle>

                        </div>
                    </CardHeader>

                    <CardContent class="space-y-2 text-sm">
                    <p>
                        <span class="font-medium">Address:</span>
                        {{ store.address }}
                    </p>

                    <p>
                        <span class="font-medium">Phone:</span>
                        {{ store.phone }}
                    </p>

                    <p>
                        <span class="font-medium">Hours:</span>
                        {{ store.hours }}
                    </p>
                    
                    <div class="flex justify-end pt-4">
                        <Link 
                        class="text-sm font-medium text-[#C5A059] hover:underline"
                        @click="$inertia.visit(`/admin/stores/${store.id}`)"
                        >
                        Visit Store →
                        </Link>
                    </div>
                    </CardContent>
                </Card>
                </div>

                <!-- Empty State -->
                <div
                v-if="filteredStores.length === 0"
                class="text-center py-12 text-muted-foreground"
                >
                No stores found.
                </div>
            </div>

            <Dialog v-model:open="showOwnerDialog">
            <DialogContent class="sm:max-w-md rounded-xl border border-border shadow-md p-6">
                
                <DialogHeader class="space-y-2">
                <DialogTitle class="text-xl font-semibold text-[#245c4a]">
                    Owner Information
                </DialogTitle>
                </DialogHeader>

                <div class="mt-4 space-y-4 text-sm">
                
                <div class="flex justify-between items-center border-b border-border pb-2">
                    <span class="text-muted-foreground font-medium">Name</span>
                    <span class="text-foreground font-medium">
                    {{ selectedVendor?.owner?.name }}
                    </span>
                </div>

                <div class="flex justify-between items-center border-b border-border pb-2">
                    <span class="text-muted-foreground font-medium">Email</span>
                    <span class="text-foreground">
                    {{ selectedVendor?.owner?.email }}
                    </span>
                </div>

                <div class="flex justify-between items-center border-b border-border pb-2">
                    <span class="text-muted-foreground font-medium">Phone</span>
                    <span class="text-foreground">
                    {{ selectedVendor?.owner?.phone || 'N/A' }}
                    </span>
                </div>

                <div class="flex justify-between items-center pt-2">
                    <span class="text-muted-foreground font-medium">Status</span>
                    
                    <span
                    class="px-3 py-1 text-xs font-medium rounded-full"
                    :class="selectedVendor?.owner?.is_active
                        ? 'bg-emerald-100 text-emerald-700'
                        : 'bg-red-100 text-red-600'"
                    >
                    {{ selectedVendor?.owner?.is_active ? 'Active' : 'Inactive' }}
                    </span>
                </div>

                </div>

                <DialogFooter class="mt-6">
                <Button 
                    variant="secondary" 
                    class="rounded-md"
                    @click="showOwnerDialog = false"
                >
                    Close
                </Button>
                </DialogFooter>

            </DialogContent>
            </Dialog>


            <Dialog v-model:open="showDeleteDialog">
            <DialogContent class="sm:max-w-md rounded-xl border border-border shadow-md p-6">
                
                <DialogHeader class="space-y-2">
                <DialogTitle class="text-xl font-semibold text-destructive">
                    Delete Store
                </DialogTitle>
                </DialogHeader>

                <div class="mt-4 space-y-4">
                
                <p class="text-sm text-muted-foreground leading-relaxed">
                    Are you sure you want to delete
                    <span class="font-semibold text-foreground">
                    {{ selectedVendor?.name }}
                    </span>?
                </p>

                <div class="rounded-lg border border-destructive/20 bg-destructive/5 p-3">
                    <p class="text-xs text-destructive font-medium">
                    This action cannot be undone.
                    </p>
                </div>

                </div>

                <DialogFooter class="mt-6 flex gap-2 sm:justify-end">
                <Button
                    variant="secondary"
                    class="rounded-md"
                    @click="showDeleteDialog = false"
                >
                    Cancel
                </Button>

                <Button
                    variant="destructive"
                    class="rounded-md"
                    @click="deleteStore"
                >
                    Delete Store
                </Button>
                </DialogFooter>

            </DialogContent>
            </Dialog>
        </main>
    </div>
</template>
