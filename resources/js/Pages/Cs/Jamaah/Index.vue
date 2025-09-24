<template>
  <Head title="CS - Jamaah Management" />

  <CSLayout>
    <template #header>
      <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        Data Jamaah
      </h2>
    </template>

    <div class="space-y-6">
      <!-- Stats Cards -->
      <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
        <div class="bg-white overflow-hidden shadow rounded-lg">
          <div class="p-5">
            <div class="flex items-center">
              <div class="flex-shrink-0">
                <div class="w-8 h-8 bg-blue-500 rounded-lg flex items-center justify-center">
                  <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M9 6a3 3 0 11-6 0 3 3 0 016 0zM17 6a3 3 0 11-6 0 3 3 0 016 0zM12.93 17c.046-.327.07-.66.07-1a6.97 6.97 0 00-1.5-4.33A5 5 0 0119 16v1h-6.07zM6 11a5 5 0 015 5v1H1v-1a5 5 0 015-5z"/>
                  </svg>
                </div>
              </div>
              <div class="ml-5 w-0 flex-1">
                <dl>
                  <dt class="text-sm font-medium text-gray-500 truncate">Total Jamaah</dt>
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
                <div class="w-8 h-8 bg-green-500 rounded-lg flex items-center justify-center">
                  <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                  </svg>
                </div>
              </div>
              <div class="ml-5 w-0 flex-1">
                <dl>
                  <dt class="text-sm font-medium text-gray-500 truncate">Lengkap</dt>
                  <dd class="text-lg font-medium text-gray-900">{{ completedCount }}</dd>
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
                    <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                  </svg>
                </div>
              </div>
              <div class="ml-5 w-0 flex-1">
                <dl>
                  <dt class="text-sm font-medium text-gray-500 truncate">Perlu Review CS</dt>
                  <dd class="text-lg font-medium text-gray-900">{{ pendingCount }}</dd>
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
                  <dt class="text-sm font-medium text-gray-500 truncate">Perlu Review Admin</dt>
                  <dd class="text-lg font-medium text-gray-900">{{ paymentPendingCount }}</dd>
                </dl>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Jamaah Table -->
      <div class="bg-white shadow rounded-lg">
        <div class="px-4 py-5 sm:p-6">
          <div class="sm:flex sm:items-center">
            <div class="sm:flex-auto">
              <h3 class="text-lg font-semibold text-gray-900">Daftar Jamaah</h3>
              <p class="mt-2 text-sm text-gray-700">Daftar semua jamaah yang terdaftar dalam sistem.</p>
            </div>
          </div>

          <div class="mt-8 overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
              <thead class="bg-gray-50">
                <tr>
                  <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Jamaah</th>
                  <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kontak</th>
                  <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                  <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Step</th>
                  <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal Daftar</th>
                  <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                </tr>
              </thead>
              <tbody class="bg-white divide-y divide-gray-200">
                <tr v-if="jamaahList.length === 0">
                  <td colspan="6" class="px-6 py-8 text-center text-gray-500">
                    <div class="flex flex-col items-center">
                      <svg class="w-12 h-12 text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"/>
                      </svg>
                      <p>Belum ada data jamaah</p>
                    </div>
                  </td>
                </tr>
                <tr v-for="jamaah in jamaahList" :key="jamaah.id" class="hover:bg-gray-50">
                  <td class="px-6 py-4 whitespace-nowrap">
                    <div class="flex items-center">
                      <div class="flex-shrink-0 h-10 w-10">
                        <div class="h-10 w-10 rounded-full bg-gray-300 flex items-center justify-center">
                          <span class="text-sm font-medium text-gray-700">
                            {{ jamaah.nama_lengkap.split(' ').map(n => n[0]).join('').slice(0, 2) }}
                          </span>
                        </div>
                      </div>
                      <div class="ml-4">
                        <div class="text-sm font-medium">
                          <Link :href="route('cs.jamaah.show', jamaah.id)"
                                class="text-gray-900 hover:text-blue-600 transition-colors">
                            {{ jamaah.nama_lengkap }}
                          </Link>
                        </div>
                        <div class="text-sm text-gray-500">{{ jamaah.email }}</div>
                      </div>
                    </div>
                  </td>
                  <td class="px-6 py-4 whitespace-nowrap">
                    <div class="text-sm text-gray-900">{{ jamaah.no_telepon }}</div>
                    <div class="text-sm text-gray-500">{{ jamaah.jenis_kelamin === 'L' ? 'Laki-laki' : 'Perempuan' }}</div>
                  </td>
                  <td class="px-6 py-4 whitespace-nowrap">
                    <div class="flex flex-col space-y-1">
                      <span
                        :class="{
                          'bg-green-100 text-green-800': jamaah.current_step >= 4,
                          'bg-blue-100 text-blue-800': jamaah.current_step === 3,
                          'bg-yellow-100 text-yellow-800': jamaah.current_step === 2,
                          'bg-gray-100 text-gray-800': jamaah.current_step === 1
                        }"
                        class="inline-flex px-2 py-1 text-xs font-semibold rounded-full"
                      >
                        {{ jamaah.step_name }}
                      </span>
                      <span class="text-xs text-gray-500">{{ jamaah.status_display }}</span>
                    </div>
                  </td>
                  <td class="px-6 py-4 whitespace-nowrap">
                    <div class="flex items-center">
                      <div class="text-sm text-gray-900">Step {{ jamaah.current_step }}</div>
                      <div class="ml-2 flex items-center">
                        <div
                          :class="{
                            'bg-green-200': jamaah.current_step >= 1,
                            'bg-gray-200': jamaah.current_step < 1
                          }"
                          class="w-2 h-2 rounded-full mr-1"
                        ></div>
                        <div
                          :class="{
                            'bg-green-200': jamaah.current_step >= 2,
                            'bg-gray-200': jamaah.current_step < 2
                          }"
                          class="w-2 h-2 rounded-full mr-1"
                        ></div>
                        <div
                          :class="{
                            'bg-green-200': jamaah.current_step >= 3,
                            'bg-gray-200': jamaah.current_step < 3
                          }"
                          class="w-2 h-2 rounded-full"
                        ></div>
                      </div>
                    </div>
                  </td>
                  <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                    {{ formatDate(jamaah.created_at) }}
                  </td>
                  <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                    <div class="flex flex-col space-y-1">
                      <button
                        @click="viewJamaah(jamaah)"
                        class="text-blue-600 hover:text-blue-900 text-left"
                      >
                        Lihat Detail
                      </button>

                      <!-- CS Actions -->
                      <div v-if="jamaah.can_cs_approve" class="flex space-x-2">
                        <button
                          @click="approveDataCS(jamaah.id)"
                          class="text-green-600 hover:text-green-900 text-xs"
                        >
                          ✓ Approve Data
                        </button>
                        <button
                          @click="rejectDataCS(jamaah.id)"
                          class="text-red-600 hover:text-red-900 text-xs"
                        >
                          ✗ Tolak Data
                        </button>
                      </div>

                      <!-- Status Indicators -->
                      <div v-if="jamaah.current_step === 3" class="flex flex-col text-xs space-y-1">
                        <span v-if="jamaah.data_approved_by_cs" class="text-green-600">✓ Data OK (CS)</span>
                        <span v-else class="text-gray-500">⏳ Menunggu CS</span>

                        <span v-if="jamaah.payment_approved_by_admin" class="text-green-600">✓ Payment OK (Admin)</span>
                        <span v-else-if="jamaah.bukti_transfer" class="text-yellow-600">⏳ Menunggu Admin</span>
                        <span v-else class="text-gray-500">⏳ Belum upload bukti</span>
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

    <!-- Detail Modal -->
    <div v-if="selectedJamaah" class="fixed inset-0 z-50 overflow-y-auto">
      <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 transition-opacity" @click="closeModal">
          <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
        </div>

        <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
          <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
            <div class="sm:flex sm:items-start">
              <div class="mt-3 text-center sm:mt-0 sm:text-left w-full">
                <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">
                  Detail Jamaah
                </h3>
                <div class="space-y-3">
                  <div>
                    <label class="block text-sm font-medium text-gray-700">Nama Lengkap</label>
                    <p class="mt-1 text-sm text-gray-900">{{ selectedJamaah.nama_lengkap }}</p>
                  </div>
                  <div>
                    <label class="block text-sm font-medium text-gray-700">Email</label>
                    <p class="mt-1 text-sm text-gray-900">{{ selectedJamaah.email }}</p>
                  </div>
                  <div>
                    <label class="block text-sm font-medium text-gray-700">No. Telepon</label>
                    <p class="mt-1 text-sm text-gray-900">{{ selectedJamaah.no_telepon }}</p>
                  </div>
                  <div>
                    <label class="block text-sm font-medium text-gray-700">Jenis Kelamin</label>
                    <p class="mt-1 text-sm text-gray-900">{{ selectedJamaah.jenis_kelamin === 'L' ? 'Laki-laki' : 'Perempuan' }}</p>
                  </div>
                  <div>
                    <label class="block text-sm font-medium text-gray-700">Status Registrasi</label>
                    <p class="mt-1 text-sm text-gray-900">{{ selectedJamaah.is_complete ? 'Lengkap' : 'Belum Lengkap' }}</p>
                  </div>
                  <div>
                    <label class="block text-sm font-medium text-gray-700">Step Saat Ini</label>
                    <p class="mt-1 text-sm text-gray-900">Step {{ selectedJamaah.current_step }}</p>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
            <button
              type="button"
              @click="closeModal"
              class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-blue-600 text-base font-medium text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:ml-3 sm:w-auto sm:text-sm"
            >
              Tutup
            </button>
          </div>
        </div>
      </div>
    </div>
  </CSLayout>
</template>

<script setup>
import { ref, computed } from 'vue'
import { Head, Link, router } from '@inertiajs/vue3'
import CSLayout from '@/Layouts/CSLayout.vue'

const props = defineProps({
  jamaahList: {
    type: Array,
    default: () => []
  }
})

const selectedJamaah = ref(null)

// Computed properties for stats
const completedCount = computed(() => {
  return props.jamaahList.filter(jamaah => jamaah.current_step >= 4).length
})

const pendingCount = computed(() => {
  return props.jamaahList.filter(jamaah => jamaah.is_awaiting_cs).length
})

const paymentPendingCount = computed(() => {
  return props.jamaahList.filter(jamaah => jamaah.is_awaiting_admin).length
})

const incompleteCount = computed(() => {
  return props.jamaahList.filter(jamaah => jamaah.current_step <= 2).length
})

// Helper functions

const formatDate = (dateString) => {
  const date = new Date(dateString)
  return date.toLocaleDateString('id-ID', {
    year: 'numeric',
    month: 'short',
    day: 'numeric'
  })
}

// Modal functions
const viewJamaah = (jamaah) => {
  router.visit(route('cs.jamaah.show', jamaah.id))
}

const closeModal = () => {
  selectedJamaah.value = null
}

// Action functions
const approveDataCS = (jamaahId) => {
  if (confirm('Apakah Anda yakin data jamaah ini sudah benar dan lengkap?')) {
    router.patch(route('cs.jamaah.approve.data', jamaahId), {}, {
      onSuccess: () => {
        router.reload({ only: ['jamaahList'] })
      },
      onError: (errors) => {
        alert('Error: ' + (errors.message || 'Gagal approve data'))
      }
    })
  }
}

const rejectDataCS = (jamaahId) => {
  if (confirm('Apakah Anda yakin ingin menolak data jamaah ini? Jamaah akan diminta memperbaiki data.')) {
    router.patch(route('cs.jamaah.reject.data', jamaahId), {}, {
      onSuccess: () => {
        router.reload({ only: ['jamaahList'] })
      },
      onError: (errors) => {
        alert('Error: ' + (errors.message || 'Gagal reject data'))
      }
    })
  }
}
</script>