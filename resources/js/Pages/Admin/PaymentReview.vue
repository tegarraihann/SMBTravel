<template>
  <Head title="Review Pembayaran - Admin" />

  <AdminLayout>
    <template #header>
      <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        Review Pembayaran Jamaah
      </h2>
    </template>

    <div class="py-12">
      <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <!-- Stats Cards -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
          <div class="bg-white overflow-hidden shadow rounded-lg">
            <div class="p-5">
              <div class="flex items-center">
                <div class="flex-shrink-0">
                  <div class="w-8 h-8 bg-blue-500 rounded-lg flex items-center justify-center">
                    <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 20 20">
                      <path d="M4 4a2 2 0 00-2 2v4a2 2 0 002 2V6h10a2 2 0 00-2-2H4zm2 6a2 2 0 012-2h8a2 2 0 012 2v4a2 2 0 01-2 2H8a2 2 0 01-2-2v-4zm6 4a2 2 0 100-4 2 2 0 000 4z"/>
                    </svg>
                  </div>
                </div>
                <div class="ml-5 w-0 flex-1">
                  <dl>
                    <dt class="text-sm font-medium text-gray-500 truncate">Total Pembayaran</dt>
                    <dd class="text-lg font-medium text-gray-900">{{ jamaahList.length }}</dd>
                  </dl>
                </div>
              </div>
            </div>
          </div>

          <div class="bg-white overflow-hidden shadow rounded-lg">
            <div class="p-5">
              <div class="flex items-center">
                <div class="flex-shrink-0">
                  <div class="w-8 h-8 bg-yellow-500 rounded-lg flex items-center justify-center">
                    <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 20 20">
                      <path fill-rule="evenodd" d="M10 2a4 4 0 00-4 4v1H5a1 1 0 00-.994.89l-1 9A1 1 0 004 18h12a1 1 0 00.994-1.11l-1-9A1 1 0 0015 7h-1V6a4 4 0 00-4-4zm2 5V6a2 2 0 10-4 0v1h4zm-6 3a1 1 0 112 0 1 1 0 01-2 0zm7-1a1 1 0 100 2 1 1 0 000-2z" clip-rule="evenodd"/>
                    </svg>
                  </div>
                </div>
                <div class="ml-5 w-0 flex-1">
                  <dl>
                    <dt class="text-sm font-medium text-gray-500 truncate">Perlu Review Admin</dt>
                    <dd class="text-lg font-medium text-gray-900">{{ pendingAdminCount }}</dd>
                  </dl>
                </div>
              </div>
            </div>
          </div>

          <div class="bg-white overflow-hidden shadow rounded-lg">
            <div class="p-5">
              <div class="flex items-center">
                <div class="flex-shrink-0">
                  <div class="w-8 h-8 bg-green-500 rounded-lg flex items-center justify-center">
                    <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 20 20">
                      <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                    </svg>
                  </div>
                </div>
                <div class="ml-5 w-0 flex-1">
                  <dl>
                    <dt class="text-sm font-medium text-gray-500 truncate">Sudah Disetujui</dt>
                    <dd class="text-lg font-medium text-gray-900">{{ approvedAdminCount }}</dd>
                  </dl>
                </div>
              </div>
            </div>
          </div>

          <div class="bg-white overflow-hidden shadow rounded-lg">
            <div class="p-5">
              <div class="flex items-center">
                <div class="flex-shrink-0">
                  <div class="w-8 h-8 bg-purple-500 rounded-lg flex items-center justify-center">
                    <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 20 20">
                      <path d="M4 4a2 2 0 00-2 2v4a2 2 0 002 2V6h10a2 2 0 00-2-2H4zm2 6a2 2 0 012-2h8a2 2 0 012 2v4a2 2 0 01-2 2H8a2 2 0 01-2-2v-4zm6 4a2 2 0 100-4 2 2 0 000 4z"/>
                    </svg>
                  </div>
                </div>
                <div class="ml-5 w-0 flex-1">
                  <dl>
                    <dt class="text-sm font-medium text-gray-500 truncate">Total Nilai DP</dt>
                    <dd class="text-lg font-medium text-gray-900">{{ formatCurrency(totalDPValue) }}</dd>
                  </dl>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- Payment Review Table -->
        <div class="bg-white shadow rounded-lg">
          <div class="px-4 py-5 sm:p-6">
            <div class="sm:flex sm:items-center">
              <div class="sm:flex-auto">
                <h3 class="text-lg font-semibold text-gray-900">Daftar Pembayaran untuk Review</h3>
                <p class="mt-2 text-sm text-gray-700">Review dan approve bukti transfer yang diupload jamaah.</p>
              </div>
            </div>

            <div class="mt-8 overflow-x-auto">
              <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                  <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Jamaah</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nominal DP</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Bukti Transfer</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status CS</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status Admin</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal Upload</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                  </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                  <tr v-if="jamaahList.length === 0">
                    <td colspan="7" class="px-6 py-8 text-center text-gray-500">
                      <div class="flex flex-col items-center">
                        <svg class="w-12 h-12 text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/>
                        </svg>
                        <p>Belum ada pembayaran untuk direview</p>
                      </div>
                    </td>
                  </tr>
                  <tr v-for="jamaah in jamaahList" :key="jamaah.id" class="hover:bg-gray-50">
                    <td class="px-6 py-4 whitespace-nowrap">
                      <div class="flex items-center">
                        <div class="flex-shrink-0 h-10 w-10">
                          <div class="h-10 w-10 rounded-full bg-gray-300 flex items-center justify-center">
                            <span class="text-sm font-medium text-gray-700">
                              {{ jamaah.nama_lengkap ? jamaah.nama_lengkap.split(' ').map(n => n[0]).join('').slice(0, 2) : '??' }}
                            </span>
                          </div>
                        </div>
                        <div class="ml-4">
                          <div class="text-sm font-medium">
                            <Link :href="route('admin.payment.review.show', jamaah.id)"
                                  class="text-gray-900 hover:text-blue-600 transition-colors">
                              {{ jamaah.nama_lengkap || 'Nama tidak tersedia' }}
                            </Link>
                          </div>
                          <div class="text-sm text-gray-500">{{ jamaah.email }}</div>
                        </div>
                      </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                      <div class="text-sm font-medium text-gray-900">{{ formatCurrency(jamaah.dp_paid) }}</div>
                      <div class="text-sm text-gray-500">Paket ID: {{ jamaah.paket_id }}</div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                      <div v-if="jamaah.bukti_transfer">
                        <button
                          @click="viewTransferProof(jamaah)"
                          class="text-blue-600 hover:text-blue-900 text-sm"
                        >
                          Lihat Bukti Transfer
                        </button>
                      </div>
                      <div v-else class="text-sm text-gray-500">Belum upload</div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                      <span
                        :class="{
                          'bg-green-100 text-green-800': jamaah.data_approved_by_cs,
                          'bg-gray-100 text-gray-800': !jamaah.data_approved_by_cs
                        }"
                        class="inline-flex px-2 py-1 text-xs font-semibold rounded-full"
                      >
                        {{ jamaah.data_approved_by_cs ? 'Disetujui CS' : 'Belum Review CS' }}
                      </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                      <span
                        :class="{
                          'bg-green-100 text-green-800': jamaah.payment_approved_by_admin,
                          'bg-yellow-100 text-yellow-800': !jamaah.payment_approved_by_admin && jamaah.bukti_transfer,
                          'bg-gray-100 text-gray-800': !jamaah.bukti_transfer
                        }"
                        class="inline-flex px-2 py-1 text-xs font-semibold rounded-full"
                      >
                        {{ getAdminPaymentStatus(jamaah) }}
                      </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                      {{ jamaah.bukti_transfer ? formatDate(jamaah.created_at) : '-' }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                      <div class="flex flex-col space-y-1">
                        <button
                          @click="viewJamaahDetail(jamaah)"
                          class="text-blue-600 hover:text-blue-900 text-left"
                        >
                          Lihat Detail
                        </button>

                        <!-- Admin Actions -->
                        <div v-if="jamaah.can_admin_approve" class="flex space-x-2">
                          <button
                            @click="approvePaymentAdmin(jamaah.id)"
                            class="text-green-600 hover:text-green-900 text-xs"
                          >
                            ✓ Approve
                          </button>
                          <button
                            @click="rejectPaymentAdmin(jamaah.id)"
                            class="text-red-600 hover:text-red-900 text-xs"
                          >
                            ✗ Tolak
                          </button>
                        </div>

                        <div v-else-if="jamaah.payment_approved_by_admin" class="text-xs text-green-600">
                          ✓ Sudah Disetujui
                        </div>
                      </div>
                    </td>
                  </tr>
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Transfer Proof Modal -->
    <div v-if="selectedTransferProof" class="fixed inset-0 z-50 overflow-y-auto">
      <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 transition-opacity" @click="closeTransferModal">
          <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
        </div>

        <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
          <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
            <div class="sm:flex sm:items-start">
              <div class="mt-3 text-center sm:mt-0 sm:text-left w-full">
                <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">
                  Bukti Transfer - {{ selectedJamaah?.nama_lengkap }}
                </h3>
                <div class="mt-2">
                  <img
                    :src="`/storage/${selectedTransferProof}`"
                    alt="Bukti Transfer"
                    class="max-w-full h-auto rounded-lg"
                  >
                </div>
              </div>
            </div>
          </div>
          <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
            <button
              type="button"
              @click="closeTransferModal"
              class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-blue-600 text-base font-medium text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:ml-3 sm:w-auto sm:text-sm"
            >
              Tutup
            </button>
          </div>
        </div>
      </div>
    </div>
  </AdminLayout>
</template>

<script setup>
import { ref, computed } from 'vue'
import { Head, Link, router } from '@inertiajs/vue3'
import AdminLayout from '@/Layouts/AdminLayout.vue'

const props = defineProps({
  jamaahList: {
    type: Array,
    default: () => []
  }
})

const selectedJamaah = ref(null)
const selectedTransferProof = ref(null)

// Computed properties for stats
const pendingAdminCount = computed(() => {
  return props.jamaahList.filter(jamaah => jamaah.can_admin_approve).length
})

const approvedAdminCount = computed(() => {
  return props.jamaahList.filter(jamaah => jamaah.payment_approved_by_admin).length
})

const totalDPValue = computed(() => {
  return props.jamaahList.reduce((total, jamaah) => total + (jamaah.dp_paid || 0), 0)
})

// Helper functions
const formatCurrency = (amount) => {
  return new Intl.NumberFormat('id-ID', {
    style: 'currency',
    currency: 'IDR',
    minimumFractionDigits: 0
  }).format(amount)
}

const formatDate = (dateString) => {
  const date = new Date(dateString)
  return date.toLocaleDateString('id-ID', {
    year: 'numeric',
    month: 'short',
    day: 'numeric',
    hour: '2-digit',
    minute: '2-digit'
  })
}

const getAdminPaymentStatus = (jamaah) => {
  if (jamaah.payment_approved_by_admin) {
    return 'Disetujui Admin'
  } else if (jamaah.bukti_transfer) {
    return 'Perlu Review'
  } else {
    return 'Belum Upload'
  }
}

// Modal functions
const viewTransferProof = (jamaah) => {
  selectedJamaah.value = jamaah
  selectedTransferProof.value = jamaah.bukti_transfer
}

const closeTransferModal = () => {
  selectedJamaah.value = null
  selectedTransferProof.value = null
}

const viewJamaahDetail = (jamaah) => {
  router.visit(route('admin.payment.review.show', jamaah.id))
}

// Action functions
const approvePaymentAdmin = (jamaahId) => {
  if (confirm('Apakah Anda yakin pembayaran ini valid dan ingin disetujui?')) {
    router.patch(route('admin.jamaah.approve.payment', jamaahId), {}, {
      onSuccess: () => {
        router.reload({ only: ['jamaahList'] })
      },
      onError: (errors) => {
        alert('Error: ' + (errors.message || 'Gagal approve pembayaran'))
      }
    })
  }
}

const rejectPaymentAdmin = (jamaahId) => {
  if (confirm('Apakah Anda yakin ingin menolak pembayaran ini? Jamaah akan diminta upload ulang bukti transfer.')) {
    router.patch(route('admin.jamaah.reject.payment', jamaahId), {}, {
      onSuccess: () => {
        router.reload({ only: ['jamaahList'] })
      },
      onError: (errors) => {
        alert('Error: ' + (errors.message || 'Gagal reject pembayaran'))
      }
    })
  }
}
</script>