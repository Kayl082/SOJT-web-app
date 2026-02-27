<script setup lang="ts">
import { Head } from '@inertiajs/vue3';
import { ref, computed } from 'vue';
import Header from '@/components/Header.vue';
import Sidebar from '@/components/Sidebar.vue';
import AdminNav from '@/components/navigation/AdminNav.vue';
import AdminNavIcons from '@/components/navigation/AdminNavIcons.vue';
import { useSidebar } from '@/composables/useSidebar';
import CustomerNav from '@/components/navigation/CustomerNav.vue';
import CustomerNavIcons from '@/components/navigation/CustomerNavIcons.vue';
import PlaceholderPage from '@/components/PlaceholderPage.vue';
import { Button } from '@/components/ui/button'
import { Card, CardContent } from '@/components/ui/card'
import { Link } from '@inertiajs/vue3'
import { ArrowLeft } from 'lucide-vue-next'
import { ChevronDown, ShoppingCart } from 'lucide-vue-next'
import { Input } from '@/components/ui/input'
import {
  DropdownMenu,
  DropdownMenuTrigger,
  DropdownMenuContent,
  DropdownMenuItem,
} from '@/components/ui/dropdown-menu'

const { isCollapsed } = useSidebar();
const contentClass = computed(() => ({
    'dashboard-content': true,
    'sidebar-collapsed': isCollapsed.value
}));

const props = defineProps<{
  storeId: string
}>()

// Mock Store
const store = ref({
  name: 'Emerald Fresh Market',
  address: '123 Green Valley Rd',
  city: 'New York',
  phone: '(123) 456-7890',
  hours: 'Mon - Fri: 8AM - 8PM',
  isActive: true,
  cover: 'https://picsum.photos/1200/400',
  logo: 'https://ui-avatars.com/api/?name=Emerald+Fresh',
})

// Mock Products
const products = ref([
  {
    id: 1,
    product_name: 'Organic Fuji Apples (1kg Pack)',
    description: 'Premium imported apples.',
    category_id: 1,
    image_url: 'https://picsum.photos/400?1',
    unit_price: 3.99,
    stock_level: 20,
    sold_count: 120,
    rating: 4.5,
    is_available: true,
    is_active: true,
  },
  {
    id: 2,
    product_name: 'Whole Fresh Milk 1L',
    description: 'Fresh dairy milk.',
    category_id: 2,
    image_url: 'https://picsum.photos/400?2',
    unit_price: 2.49,
    stock_level: 3,
    sold_count: 45,
    rating: 4.2,
    is_available: true,
    is_active: true,
  },
])

const searchProduct = ref('')
const selectedCategory = ref<'all' | number>('all')
const sortBy = ref<'name' | 'price_low' | 'price_high'>('name')

const filteredProducts = computed(() => {
  let result = products.value.filter(product => {

    const matchesSearch =
      product.product_name
        .toLowerCase()
        .includes(searchProduct.value.toLowerCase())

    const matchesCategory =
      selectedCategory.value === 'all'
        ? true
        : product.category_id === selectedCategory.value

    return (
      matchesSearch &&
      matchesCategory &&
      product.is_active
    )
  })

  // Sorting
  if (sortBy.value === 'price_low') {
    result.sort((a, b) => a.unit_price - b.unit_price)
  } else if (sortBy.value === 'price_high') {
    result.sort((a, b) => b.unit_price - a.unit_price)
  } else {
    result.sort((a, b) =>
      a.product_name.localeCompare(b.product_name)
    )
  }

  return result
})
</script>

<template>
    <Head title="Store Details" />

    <div class="dashboard-wrapper">
        <Header />
        <Sidebar role="admin">
            <AdminNav />
            <template #icons>
                <AdminNavIcons />
            </template>
        </Sidebar>

        <main :class="contentClass">
            <Head :title="store.name" />

            <div class="space-y-5 pb-12">

                <div class="max-w-6xl mx-auto px-6 pt-6">
                <Link href="/admin/vendors">
                    <Button variant="ghost" class="gap-2 text-[#245c4a]">
                    <ArrowLeft class="w-4 h-4" />
                    Back to Vendors
                    </Button>
                </Link>
                </div>

                <!-- Cover Section -->
                <div class="max-w-6xl mx-auto px-6">
                    <div class="relative h-64 w-full overflow-hidden rounded-2xl">
                        <img
                        :src="store.cover"
                        class="h-full w-full object-cover"
                        />
                        <div class="absolute inset-0 bg-gradient-to-t from-black/40 to-transparent"></div>
                    </div>
                </div>

                <!-- Store Info -->
                <div class="max-w-6xl mx-auto px-6">

                    <div class="p-8 space-y-6">

                        <!-- Store Name (Full Width Top) -->
                        <h1 class="text-3xl font-semibold text-[#245c4a]">
                        {{ store.name }}
                        </h1>

                        <!-- Middle Row -->
                        <div class="flex items-center justify-between">

                        <!-- Left: Logo + Address -->
                        <div class="flex items-center gap-4">

                            <!-- Logo -->
                            <img
                            :src="store.logo"
                            class="h-14 w-14 rounded-xl shadow-md object-cover border"
                            />

                            <!-- Address -->
                            <div>
                            <p class="text-base text-muted-foreground">
                                {{ store.address }}, {{ store.city }}
                            </p>
                            <p class="text-sm text-muted-foreground">
                                {{ store.phone }}
                            </p>
                            </div>

                        </div>

                        <!-- Right: Status -->
                        <span
                            class="px-4 py-1.5 text-sm rounded-full font-medium"
                            :class="store.isActive
                            ? 'bg-green-100 text-green-700'
                            : 'bg-red-100 text-red-700'"
                        >
                            {{ store.isActive ? 'Open' : 'Closed' }}
                        </span>

                        </div>

                        <!-- Operating Hours -->
                        <div class="pt-4 border-t border-border">
                        <p class="text-sm text-muted-foreground">
                            <span class="font-medium text-foreground">
                            Operating Hours:
                            </span>
                            {{ store.hours }}
                        </p>
                    </div>

                </div>


                <!-- Products Section -->
                <div class="mt-10">
                    <h2 class="text-xl font-semibold text-[#245c4a] mb-6 mt-6">
                    Products
                    </h2>

                    <div class="flex flex-col lg:flex-row gap-4 items-start lg:items-center justify-between mb-6">

                        <!-- Search -->
                        <Input
                            v-model="searchProduct"
                            placeholder="Search products..."
                            class="w-full lg:max-w-sm"
                        />

                        <div class="flex gap-3 w-full lg:w-auto">

                            <!-- Category Filter -->
                            <DropdownMenu>
                            <DropdownMenuTrigger as-child>
                                <Button variant="outline" class="w-36 justify-between">
                                Category
                                <ChevronDown class="w-4 h-4 opacity-60" />
                                </Button>
                            </DropdownMenuTrigger>

                            <DropdownMenuContent>
                                <DropdownMenuItem @click="selectedCategory = 'all'">
                                All
                                </DropdownMenuItem>
                                <DropdownMenuItem @click="selectedCategory = 1">
                                Fruits
                                </DropdownMenuItem>
                                <DropdownMenuItem @click="selectedCategory = 2">
                                Dairy
                                </DropdownMenuItem>
                                <DropdownMenuItem @click="selectedCategory = 3">
                                Snacks
                                </DropdownMenuItem>
                            </DropdownMenuContent>
                            </DropdownMenu>

                            <!-- Sort -->
                            <DropdownMenu>
                            <DropdownMenuTrigger as-child>
                                <Button variant="outline" class="w-30 justify-between">
                                Sort
                                <ChevronDown class="w-4 h-4 opacity-60" />
                                </Button>
                            </DropdownMenuTrigger>

                            <DropdownMenuContent>
                                <DropdownMenuItem @click="sortBy = 'name'">
                                Name
                                </DropdownMenuItem>
                                <DropdownMenuItem @click="sortBy = 'price_low'">
                                Price: Low to High
                                </DropdownMenuItem>
                                <DropdownMenuItem @click="sortBy = 'price_high'">
                                Price: High to Low
                                </DropdownMenuItem>
                            </DropdownMenuContent>
                            </DropdownMenu>

                        </div>

                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                    <Card
                    v-for="product in filteredProducts"
                    :key="product.id"
                    class="group rounded-xl border border-border bg-white dark:bg-muted shadow-sm hover:shadow-md transition-all duration-200 overflow-hidden cursor-pointer p-0"
                    >

                    <!-- Image Section -->
                    <div class="relative w-full aspect-square overflow-hidden bg-gray-100">

                        <img
                        :src="product.image_url"
                        class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300"
                        />

                        <!-- Top Badge -->
                        <span
                        v-if="!product.is_available"
                        class="absolute top-2 left-2 bg-red-500 text-white text-xs px-2 py-1 rounded-md"
                        >
                        Sold Out
                        </span>

                    </div>

                    <!-- Content -->
                    <CardContent class="space-y-2">

                        <!-- Product Name -->
                        <h3 class="text-sm font-medium text-foreground line-clamp-2 min-h-[40px]">
                        {{ product.product_name }}
                        </h3>

                        <!-- Price -->
                        <div class="flex items-center justify-between">

                        <span class="text-lg font-semibold text-[#245c4a]">
                            ${{ product.unit_price.toFixed(2) }}
                        </span>

                        <span
                            v-if="product.stock_level <= 5 && product.stock_level > 0"
                            class="text-xs text-orange-600"
                        >
                            Low stock
                        </span>

                        </div>

                        <!-- Rating + Sold -->
                        <div class="flex items-center justify-between text-xs text-muted-foreground pb-5">

                        <div class="flex items-center gap-1">
                            <span>⭐</span>
                            <span>{{ product.rating }}</span>
                        </div>

                        <span>{{ product.sold_count }} sold</span>

                        </div>

                    </CardContent>

                    </Card>
                    </div>

                </div>

                </div>

            </div>
        </main>
    </div>
</template>
