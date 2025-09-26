<template>
  <Head title="Operasional - Process Jamaah" />

  <AdminLayout>
    <template #header>
      <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        Process Jamaah
      </h2>
    </template>

    <div class="py-12">
      <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <!-- Stats Overview -->
        <div class="grid grid-cols-1 md:grid-cols-5 gap-6 mb-6">
          <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 text-center">
            <div class="text-2xl font-bold text-blue-900">{{ stats.total_ready }}</div>
            <div class="text-sm text-blue-600">Ready to Process</div>
          </div>
          <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4 text-center">
            <div class="text-2xl font-bold text-yellow-900">{{ stats.ticket_pending }}</div>
            <div class="text-sm text-yellow-600">Ticket Pending</div>
          </div>
          <div class="bg-orange-50 border border-orange-200 rounded-lg p-4 text-center">
            <div class="text-2xl font-bold text-orange-900">{{ stats.visa_pending }}</div>
            <div class="text-sm text-orange-600">Visa Pending</div>
          </div>
          <div class="bg-green-50 border border-green-200 rounded-lg p-4 text-center">
            <div class="text-2xl font-bold text-green-900">{{ stats.ticket_completed }}</div>
            <div class="text-sm text-green-600">Ticket Completed</div>
          </div>
          <div class="bg-green-50 border border-green-200 rounded-lg p-4 text-center">
            <div class="text-2xl font-bold text-green-900">{{ stats.visa_completed }}</div>
            <div class="text-sm text-green-600">Visa Completed</div>
          </div>
        </div>

        <!-- Filters -->
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
          <div class="p-6">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
              <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Ticket Status</label>
                <select v-model="filters.ticket_status" @change="applyFilters" class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                  <option value="">All Status</option>
                  <option value="pending">Pending</option>
                  <option value="processing">Processing</option>
                  <option value="completed">Completed</option>
                </select>
              </div>
              <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Visa Status</label>
                <select v-model="filters.visa_status" @change="applyFilters" class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                  <option value="">All Status</option>
                  <option value="pending">Pending</option>
                  <option value="processing">Processing</option>
                  <option value="completed">Completed</option>
                </select>
              </div>
              <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Search</label>
                <input v-model="filters.search" @input="applyFilters" type="text" placeholder="Search nama jamaah..."
                       class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
              </div>
              <div class="flex items-end">
                <button @click="resetFilters" class="px-4 py-2 bg-gray-500 text-white rounded-md hover:bg-gray-600 transition-colors">
                  Reset Filters
                </button>
              </div>
            </div>
          </div>
        </div>

        <!-- Jamaah List -->
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
          <div class="p-6">
            <div class="overflow-x-auto">
              <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                  <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                      Jamaah
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                      Package
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                      Ticket Status
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                      Visa Status
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                      Registered
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                      Actions
                    </th>
                  </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                  <tr v-for="j in jamaah.data" :key="j.id" class="hover:bg-gray-50">
                    <td class="px-6 py-4 whitespace-nowrap">
                      <div>
                        <div class="text-sm font-medium text-gray-900">{{ j.nama_lengkap_bin_binti }}</div>
                        <div class="text-sm text-gray-500">{{ j.user?.email }}</div>
                      </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                      <div class="text-sm text-gray-900">{{ j.umroh_package?.nama_paket || 'N/A' }}</div>
                      <div class="text-sm text-gray-500">{{ formatCurrency(j.umroh_package?.harga) }}</div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                      <span :class="getStatusBadgeClass(j.ticket_status)" class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full">
                        {{ getStatusText(j.ticket_status) }}
                      </span>
                      <div v-if="j.ticket_processor" class="text-xs text-gray-500 mt-1">
                        by {{ j.ticket_processor.name }}
                      </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                      <span :class="getStatusBadgeClass(j.visa_status)" class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full">
                        {{ getStatusText(j.visa_status) }}
                      </span>
                      <div v-if="j.visa_processor" class="text-xs text-gray-500 mt-1">
                        by {{ j.visa_processor.name }}
                      </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                      {{ formatDate(j.created_at) }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                      <Link :href="route('operasional.jamaah.show', j.id)"
                            class="text-indigo-600 hover:text-indigo-900 mr-3">
                        Process
                      </Link>
                    </td>
                  </tr>
                </tbody>
              </table>
            </div>

            <!-- Pagination -->
            <div v-if="jamaah.links.length > 3" class="mt-6">
              <nav class="flex items-center justify-between">
                <div class="flex justify-between flex-1 sm:hidden">
                  <Link v-if="jamaah.prev_page_url" :href="jamaah.prev_page_url"
                        class="relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                    Previous
                  </Link>
                  <Link v-if="jamaah.next_page_url" :href="jamaah.next_page_url"
                        class="ml-3 relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                    Next
                  </Link>
                </div>
                <div class="hidden sm:flex-1 sm:flex sm:items-center sm:justify-between">
                  <div>
                    <p class="text-sm text-gray-700">
                      Showing <span class="font-medium">{{ jamaah.from }}</span> to <span class="font-medium">{{ jamaah.to }}</span> of <span class="font-medium">{{ jamaah.total }}</span> results
                    </p>
                  </div>
                  <div>
                    <nav class="relative z-0 inline-flex rounded-md shadow-sm -space-x-px">
                      <Link v-for="link in jamaah.links" :key="link.label" :href="link.url"
                            :class="[
                              link.active
                                ? 'z-10 bg-indigo-50 border-indigo-500 text-indigo-600'
                                : 'bg-white border-gray-300 text-gray-500 hover:bg-gray-50',
                              'relative inline-flex items-center px-2 py-2 border text-sm font-medium'
                            ]"
                            v-html="link.label">
                      </Link>
                    </nav>
                  </div>
                </div>
              </nav>
            </div>
          </div>
        </div>
      </div>
    </div>
  </AdminLayout>
</template>

<script setup>
import { ref, reactive } from 'vue'
import { Head, Link, router } from '@inertiajs/vue3'
import AdminLayout from '@/Layouts/AdminLayout.vue'

const props = defineProps({
  jamaah: Object,
  stats: Object,
  filters: Object
})

// Reactive filters
const filters = reactive({
  ticket_status: props.filters?.ticket_status || '',
  visa_status: props.filters?.visa_status || '',
  search: props.filters?.search || ''
})

// Methods
const applyFilters = () => {
  router.get(route('operasional.jamaah.index'), filters, {
    preserveState: true,
    replace: true
  })
}

const resetFilters = () => {
  filters.ticket_status = ''
  filters.visa_status = ''
  filters.search = ''
  applyFilters()
}

const getStatusBadgeClass = (status) => {
  const classes = {
    pending: 'bg-yellow-100 text-yellow-800',
    processing: 'bg-blue-100 text-blue-800',
    completed: 'bg-green-100 text-green-800'
  }
  return classes[status] || 'bg-gray-100 text-gray-800'
}

const getStatusText = (status) => {
  const texts = {
    pending: 'Pending',
    processing: 'Processing',
    completed: 'Completed'
  }
  return texts[status] || 'Unknown'
}

const formatCurrency = (amount) => {
  if (!amount) return '-'
  return new Intl.NumberFormat('id-ID', {
    style: 'currency',
    currency: 'IDR'
  }).format(amount)
}

const formatDate = (dateString) => {
  return new Date(dateString).toLocaleDateString('id-ID', {
    year: 'numeric',
    month: 'short',
    day: 'numeric'
  })
}
</script>