<template>
  <Head title="Master Data Marketing" />

  <AdminLayout>
    <template #header>
      <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        Master Data Marketing
      </h2>
    </template>

    <div class="py-12">
      <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
          <!-- Header Actions -->
          <div class="p-6 border-b border-gray-200">
            <div class="flex justify-between items-center">
              <div>
                <h3 class="text-lg font-medium text-gray-900">Daftar Marketing</h3>
                <p class="mt-1 text-sm text-gray-600">
                  Kelola data marketing dan agen yang berada di bawahnya
                </p>
              </div>
              <div class="flex space-x-3">
                <button
                  @click="refreshData"
                  :disabled="loading"
                  class="inline-flex items-center px-3 py-2 border border-gray-300 shadow-sm text-sm leading-4 font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 disabled:opacity-50"
                >
                  <svg v-if="loading" class="animate-spin -ml-1 mr-2 h-4 w-4 text-gray-700" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                  </svg>
                  <svg v-else class="-ml-1 mr-2 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                  </svg>
                  Refresh
                </button>

                <Link
                  :href="route('admin.marketing.create')"
                  class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:bg-blue-700 active:bg-blue-900 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition ease-in-out duration-150"
                >
                  <svg class="-ml-1 mr-2 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                  </svg>
                  Tambah Marketing
                </Link>
              </div>
            </div>
          </div>

          <!-- Table -->
          <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
              <thead class="bg-gray-50">
                <tr>
                  <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                    Kode & Nama
                  </th>
                  <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                    Kontak
                  </th>
                  <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                    Status
                  </th>
                  <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                    Jumlah Agen
                  </th>
                  <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                    Aksi
                  </th>
                </tr>
              </thead>

              <tbody class="bg-white divide-y divide-gray-200">
                <!-- Loading skeleton -->
                <tr v-if="loading" v-for="i in 3" :key="`loading-${i}`">
                  <td class="px-6 py-4 whitespace-nowrap" colspan="5">
                    <div class="animate-pulse flex space-x-4">
                      <div class="rounded-full bg-gray-300 h-10 w-10"></div>
                      <div class="flex-1 space-y-2 py-1">
                        <div class="h-4 bg-gray-300 rounded w-3/4"></div>
                        <div class="h-4 bg-gray-300 rounded w-1/2"></div>
                      </div>
                    </div>
                  </td>
                </tr>

                <!-- Empty state -->
                <tr v-else-if="items.data.length === 0">
                  <td colspan="5" class="px-6 py-4 text-center text-gray-500">
                    <div class="flex flex-col items-center justify-center py-8">
                      <svg class="h-12 w-12 text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7h6l2 3h10M6 10v10m6-7v7m6-10v10" />
                      </svg>
                      <p class="text-lg font-medium text-gray-900 mb-2">Belum ada marketing</p>
                      <p class="text-gray-500 mb-4">Mulai dengan menambahkan data marketing</p>
                      <Link
                        :href="route('admin.marketing.create')"
                        class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700"
                      >
                        Tambah Marketing Pertama
                      </Link>
                    </div>
                  </td>
                </tr>

                <!-- Rows -->
                <tr v-else v-for="row in items.data" :key="row.id" class="hover:bg-gray-50">
                  <td class="px-6 py-4 whitespace-nowrap">
                    <div>
                      <div class="text-sm font-medium text-gray-900">
                        {{ row.code || '-' }} <span class="text-gray-400">•</span> {{ row.name }}
                      </div>
                      <div class="text-xs text-gray-500" v-if="row.notes">{{ row.notes }}</div>
                    </div>
                  </td>
                  <td class="px-6 py-4 whitespace-nowrap">
                    <div class="text-sm text-gray-900">{{ row.phone || '-' }}</div>
                    <div class="text-sm text-gray-500">{{ row.email || '-' }}</div>
                  </td>
                  <td class="px-6 py-4 whitespace-nowrap">
                    <span
                      :class="{
                        'bg-green-100 text-green-800': row.status === 'active',
                        'bg-gray-100 text-gray-800': row.status !== 'active'
                      }"
                      class="inline-flex px-2 text-xs leading-5 font-semibold rounded-full"
                    >
                      {{ row.status === 'active' ? 'Aktif' : 'Non Aktif' }}
                    </span>
                  </td>
                  <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                    {{ row.agents_count ?? 0 }} agen
                  </td>
                  <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                    <div class="flex items-center justify-end space-x-2">
                      <Link
                        :href="route('admin.marketing.edit', row.id)"
                        class="text-indigo-600 hover:text-indigo-900 text-xs"
                      >
                        Edit
                      </Link>
                      <button
                        @click="deleteRow(row)"
                        class="text-red-600 hover:text-red-900 text-xs"
                        :disabled="row.agents_count > 0"
                        :title="row.agents_count > 0 ? 'Tidak dapat dihapus karena memiliki agen' : ''"
                      >
                        Hapus
                      </button>
                    </div>
                  </td>
                </tr>
              </tbody>
            </table>
          </div>

        </div>
      </div>
    </div>
  </AdminLayout>
</template>

<script setup>
import { Head, Link, router } from '@inertiajs/vue3'
import { ref } from 'vue'
import AdminLayout from '@/Layouts/AdminLayout.vue'

const props = defineProps({
  items: Object,
  filters: Object
})

const loading = ref(false)

const refreshData = () => {
  loading.value = true
  router.reload({
    only: ['items'],
    onFinish: () => {
      loading.value = false
    }
  })
}

const deleteRow = (row) => {
  if (row.agents_count > 0) {
    alert('Tidak dapat menghapus marketing yang masih memiliki agen.')
    return
  }
  if (!confirm(`Hapus marketing "${row.name}"?`)) return

  router.delete(route('admin.marketing.destroy', row.id), {
    onSuccess: () => {
      // Success message handled by backend
    },
    onError: (errors) => {
      console.error('Delete failed:', errors)
      alert('Gagal menghapus data.')
    }
  })
}
</script>
