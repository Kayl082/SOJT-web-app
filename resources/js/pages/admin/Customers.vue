<script setup lang="ts">
import { Head } from '@inertiajs/vue3';
import { ref, computed } from 'vue';
import Header from '@/components/Header.vue';
import Sidebar from '@/components/Sidebar.vue';
import AdminNav from '@/components/navigation/AdminNav.vue';
import AdminNavIcons from '@/components/navigation/AdminNavIcons.vue';
import PlaceholderPage from '@/components/PlaceholderPage.vue';
import { useSidebar } from '@/composables/useSidebar';
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card'
import { Button } from '@/components/ui/button'
import { Input } from '@/components/ui/input'
import { Badge } from '@/components/ui/badge'
import {
  DropdownMenu,
  DropdownMenuContent,
  DropdownMenuItem,
  DropdownMenuTrigger,
} from '@/components/ui/dropdown-menu'
import {
  Dialog,
  DialogContent,
  DialogHeader,
  DialogTitle,
  DialogFooter,
} from '@/components/ui/dialog'

import { ChevronDown, MoreHorizontal, Plus, Pencil, Trash2, Power, PowerOff } from 'lucide-vue-next'

const { isCollapsed } = useSidebar();
const contentClass = computed(() => ({
    'dashboard-content': true,
    'sidebar-collapsed': isCollapsed.value
}));

/* ================================
   TYPES
================================ */

interface User {
  id: number
  name: string
  email: string
  phone?: string
  is_active: boolean
  created_at: string
}

/* ================================
   PROPS (Backend Ready)
================================ */

const props = defineProps<{
  customers?: User[]
}>()

/* ================================
   MOCK DATA (Temporary)
================================ */

const mockCustomers = ref<User[]>([
  {
    id: 1,
    name: 'John Doe',
    email: 'john@example.com',
    phone: '09123456789',
    is_active: true,
    created_at: '2026-01-10',
  },
  {
    id: 2,
    name: 'Jane Smith',
    email: 'jane@example.com',
    phone: '09987654321',
    is_active: false,
    created_at: '2026-02-01',
  },
  {
    id: 3,
    name: 'Michael Reyes',
    email: 'michael@example.com',
    phone: '09000000000',
    is_active: true,
    created_at: '2026-02-15',
  },
])

/* If backend provides data, use it. Otherwise use mock */
const customers = ref<User[]>(props.customers ?? mockCustomers.value)

/* ================================
   SEARCH / FILTER / SORT
================================ */

const search = ref('')
const searchBy = ref<'name' | 'email'>('name')
const filterStatus = ref<'all' | 'active' | 'inactive'>('all')
const sortBy = ref<'newest' | 'oldest' | 'az'>('newest')

const filteredCustomers = computed(() => {
  let data = [...customers.value]

  // Search
  if (search.value) {
    data = data.filter((c) =>
      c[searchBy.value]
        .toLowerCase()
        .includes(search.value.toLowerCase())
    )
  }

  // Filter
  if (filterStatus.value === 'active') {
    data = data.filter((c) => c.is_active)
  }
  if (filterStatus.value === 'inactive') {
    data = data.filter((c) => !c.is_active)
  }

  // Sort
  if (sortBy.value === 'newest') {
    data.sort((a, b) => b.created_at.localeCompare(a.created_at))
  } else if (sortBy.value === 'oldest') {
    data.sort((a, b) => a.created_at.localeCompare(b.created_at))
  } else if (sortBy.value === 'az') {
    data.sort((a, b) => a.name.localeCompare(b.name))
  }

  return data
})

/* ================================
   DIALOG LOGIC
================================ */

const isDialogOpen = ref(false)
const isEditing = ref(false)

const form = ref<User>({
  id: 0,
  name: '',
  email: '',
  phone: '',
  is_active: true,
  created_at: '',
})

const openAddDialog = () => {
  isEditing.value = false
  form.value = {
    id: Date.now(),
    name: '',
    email: '',
    phone: '',
    is_active: true,
    created_at: new Date().toISOString().split('T')[0],
  }
  isDialogOpen.value = true
}

const openEditDialog = (customer: User) => {
  isEditing.value = true
  form.value = { ...customer }
  isDialogOpen.value = true
}

const saveCustomer = () => {
  if (isEditing.value) {
    const index = customers.value.findIndex(
      (c) => c.id === form.value.id
    )
    customers.value[index] = { ...form.value }
  } else {
    customers.value.push({ ...form.value })
  }

  isDialogOpen.value = false
}

/* ================================
   ACTIONS
================================ */

const toggleStatus = (customer: User) => {
  customer.is_active = !customer.is_active
}

const deleteCustomer = (customer: User) => {
  customers.value = customers.value.filter(
    (c) => c.id !== customer.id
  )
}
</script>

<template>
    <Head title="Manage Customers" />

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
                <h1 class="text-2xl font-semibold text-[#245c4a]">Manage Customers</h1>
                <p class="text-muted-foreground">
                    View and manage customer accounts, monitor customer activity, and handle support requests.
                </p>
                </div>

                <div class="flex items-center justify-between">

                <!-- Controls -->
                <div class="flex flex-wrap gap-3 items-center">
                <!-- Search -->
                <div class="flex flex-1 w-100 max-w-md gap-2">
                    <DropdownMenu>
                    <div class="relative w-full">
                        <Input
                        v-model="search"
                        :placeholder="`Search by ${searchBy}...`"
                        class="pr-10"
                        />

                        <DropdownMenuTrigger as-child>
                        <button
                            class="absolute right-2 top-1/2 -translate-y-1/2"
                        >
                            <ChevronDown class="w-4 h-4 opacity-60" />
                        </button>
                        </DropdownMenuTrigger>
                    </div>

                    <DropdownMenuContent>
                        <DropdownMenuItem @click="searchBy = 'name'">
                        Search by Name
                        </DropdownMenuItem>
                        <DropdownMenuItem @click="searchBy = 'email'">
                        Search by Email
                        </DropdownMenuItem>
                    </DropdownMenuContent>
                    </DropdownMenu>
                </div>

                <!-- Filter -->
                <DropdownMenu>
                    <DropdownMenuTrigger as-child>
                    <Button variant="outline">
                        Filter
                        <ChevronDown class="w-4 h-4 ml-2" />
                    </Button>
                    </DropdownMenuTrigger>

                    <DropdownMenuContent>
                    <DropdownMenuItem @click="filterStatus = 'all'">
                        All
                    </DropdownMenuItem>
                    <DropdownMenuItem @click="filterStatus = 'active'">
                        Active
                    </DropdownMenuItem>
                    <DropdownMenuItem @click="filterStatus = 'inactive'">
                        Inactive
                    </DropdownMenuItem>
                    </DropdownMenuContent>
                </DropdownMenu>

                <!-- Sort -->
                <DropdownMenu>
                    <DropdownMenuTrigger as-child>
                    <Button variant="outline">
                        Sort
                        <ChevronDown class="w-4 h-4 ml-2" />
                    </Button>
                    </DropdownMenuTrigger>

                    <DropdownMenuContent>
                    <DropdownMenuItem @click="sortBy = 'newest'">
                        Newest
                    </DropdownMenuItem>
                    <DropdownMenuItem @click="sortBy = 'oldest'">
                        Oldest
                    </DropdownMenuItem>
                    <DropdownMenuItem @click="sortBy = 'az'">
                        Name A–Z
                    </DropdownMenuItem>
                    </DropdownMenuContent>
                </DropdownMenu>
                </div>
                <Button @click="openAddDialog">
                    <Plus class="w-4 h-4 mr-2" />
                    Add Customer
                </Button>
                </div>


                <!-- Table Card -->
                <Card>
                <CardHeader>
                    <CardTitle>Customer List</CardTitle>
                </CardHeader>

                <CardContent>
                    <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead class="border-b">
                        <tr class="text-left">
                            <th class="py-3">Name</th>
                            <th>Email</th>
                            <th>Phone</th>
                            <th>Status</th>
                            <th>Created</th>
                            <th class="text-right">Actions</th>
                        </tr>
                        </thead>

                        <tbody>
                        <tr
                            v-for="customer in filteredCustomers"
                            :key="customer.id"
                            class="border-b hover:bg-muted/50"
                        >
                            <td class="py-3 font-medium">
                            {{ customer.name }}
                            </td>
                            <td>{{ customer.email }}</td>
                            <td>{{ customer.phone }}</td>
                            <td>
                            <Badge
                                :variant="
                                customer.is_active
                                    ? 'default'
                                    : 'secondary'
                                "
                            >
                                {{ customer.is_active ? 'Active' : 'Inactive' }}
                            </Badge>
                            </td>
                            <td>{{ customer.created_at }}</td>

                            <td class="text-right">
                                <div class="flex justify-end gap-2">

                                    <!-- Edit -->
                                    <Button
                                    variant="ghost"
                                    size="icon"
                                    @click="openEditDialog(customer)"
                                    >
                                    <Pencil class="w-4 h-4" />
                                    </Button>

                                    <!-- Activate / Deactivate -->
                                    <Button
                                    variant="ghost"
                                    size="icon"
                                    @click="toggleStatus(customer)"
                                    >
                                    <component
                                        :is="customer.is_active ? PowerOff : Power"
                                        class="w-4 h-4"
                                    />
                                    </Button>

                                    <!-- Delete -->
                                    <Button
                                    variant="ghost"
                                    size="icon"
                                    class="text-red-600 hover:text-red-700"
                                    @click="deleteCustomer(customer)"
                                    >
                                    <Trash2 class="w-4 h-4" />
                                    </Button>

                                </div>
                            </td>
                        </tr>
                        </tbody>
                    </table>
                    </div>
                </CardContent>
                </Card>

                <!-- Dialog -->
                <Dialog v-model:open="isDialogOpen">
                <DialogContent>
                    <DialogHeader>
                    <DialogTitle>
                        {{ isEditing ? 'Edit Customer' : 'Add Customer' }}
                    </DialogTitle>
                    </DialogHeader>

                    <div class="space-y-4 py-4">
                    <Input v-model="form.name" placeholder="Name" />
                    <Input v-model="form.email" placeholder="Email" />
                    <Input v-model="form.phone" placeholder="Phone" />
                    </div>

                    <DialogFooter>
                    <Button variant="outline" @click="isDialogOpen = false">
                        Cancel
                    </Button>
                    <Button @click="saveCustomer">
                        Save
                    </Button>
                    </DialogFooter>
                </DialogContent>
                </Dialog>
            </div>
        </main>
    </div>
</template>
