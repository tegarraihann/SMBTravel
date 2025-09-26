<template>
    <div v-if="show" class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" @click="$emit('close')"></div>

            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

            <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-5xl sm:w-full">
                <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <div class="sm:flex sm:items-start">
                        <div class="w-full">
                            <!-- Modal Header -->
                            <div class="flex items-center justify-between mb-4">
                                <div>
                                    <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-title">
                                        Jadwal Keberangkatan
                                    </h3>
                                    <p class="text-sm text-gray-600">{{ packageData?.nama_paket }}</p>
                                </div>
                                <button @click="$emit('close')" class="text-gray-400 hover:text-gray-600">
                                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                    </svg>
                                </button>
                            </div>

                            <!-- Add New Schedule Form -->
                            <div class="bg-blue-50 border border-blue-200 rounded-lg p-6 mb-6">
                                <div class="flex items-center mb-4">
                                    <svg class="w-5 h-5 text-blue-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                    </svg>
                                    <h4 class="text-lg font-medium text-blue-900">Tambah Jadwal Keberangkatan</h4>
                                </div>

                                <form @submit.prevent="addSchedule" class="space-y-4">
                                    <!-- Tanggal Keberangkatan -->
                                    <div class="grid grid-cols-3 gap-4">
                                        <div>
                                            <label class="block text-sm font-medium text-blue-900 mb-2">Tahun</label>
                                            <select
                                                v-model.number="newSchedule.tahun"
                                                required
                                                class="block w-full px-3 py-2.5 border border-blue-300 rounded-lg shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 bg-white">
                                                <option value="">Pilih Tahun</option>
                                                <option v-for="year in availableYears" :key="year" :value="year">{{ year }}</option>
                                            </select>
                                        </div>
                                        <div>
                                            <label class="block text-sm font-medium text-blue-900 mb-2">Bulan</label>
                                            <select
                                                v-model.number="newSchedule.bulan"
                                                required
                                                class="block w-full px-3 py-2.5 border border-blue-300 rounded-lg shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 bg-white">
                                                <option value="">Pilih Bulan</option>
                                                <option v-for="(name, num) in monthNames" :key="num" :value="parseInt(num)">{{ name }}</option>
                                            </select>
                                        </div>
                                        <div>
                                            <label class="block text-sm font-medium text-blue-900 mb-2">Tanggal</label>
                                            <input
                                                v-model.number="newSchedule.tanggal_keberangkatan"
                                                type="number"
                                                required
                                                min="1"
                                                max="31"
                                                placeholder="1-31"
                                                class="block w-full px-3 py-2.5 border border-blue-300 rounded-lg shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 bg-white">
                                        </div>
                                    </div>

                                    <!-- Preview Tanggal -->
                                    <div v-if="newSchedule.tahun && newSchedule.bulan && newSchedule.tanggal_keberangkatan"
                                         class="bg-white p-3 rounded-lg border border-blue-200">
                                        <div class="flex items-center text-blue-800">
                                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2-2v12a2 2 0 002 2z"></path>
                                            </svg>
                                            <span class="text-sm font-medium">Preview: {{ getDatePreview() }}</span>
                                        </div>
                                    </div>

                                    <!-- Detail Jadwal -->
                                    <div class="grid grid-cols-2 gap-4">
                                        <div>
                                            <label class="block text-sm font-medium text-blue-900 mb-2">Kuota Jamaah</label>
                                            <div class="relative">
                                                <input
                                                    v-model.number="newSchedule.kuota"
                                                    type="number"
                                                    required
                                                    min="1"
                                                    max="100"
                                                    placeholder="40"
                                                    class="block w-full px-3 py-2.5 border border-blue-300 rounded-lg shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 bg-white">
                                                <div class="absolute inset-y-0 right-0 flex items-center pr-3">
                                                    <span class="text-gray-400 text-sm">orang</span>
                                                </div>
                                            </div>
                                        </div>
                                        <div>
                                            <label class="block text-sm font-medium text-blue-900 mb-2">Biaya Tambahan</label>
                                            <div class="relative">
                                                <input
                                                    v-model.number="newSchedule.biaya_tambahan"
                                                    type="number"
                                                    min="0"
                                                    step="100000"
                                                    placeholder="0"
                                                    class="block w-full px-3 py-2.5 border border-blue-300 rounded-lg shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 bg-white pl-8">
                                                <div class="absolute inset-y-0 left-0 flex items-center pl-3">
                                                    <span class="text-gray-400 text-sm">Rp</span>
                                                </div>
                                            </div>
                                            <p class="mt-1 text-xs text-blue-600">Kosongkan jika tidak ada biaya tambahan</p>
                                        </div>
                                    </div>

                                    <!-- Catatan -->
                                    <div>
                                        <label class="block text-sm font-medium text-blue-900 mb-2">Catatan Jadwal (Opsional)</label>
                                        <textarea
                                            v-model="newSchedule.catatan"
                                            rows="2"
                                            placeholder="Contoh: Jadwal libur sekolah, cuaca panas, promo khusus, dll..."
                                            class="block w-full px-3 py-2.5 border border-blue-300 rounded-lg shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 bg-white resize-none"></textarea>
                                    </div>

                                    <!-- Submit Button -->
                                    <div class="flex justify-end pt-2">
                                        <button
                                            type="submit"
                                            :disabled="addingSchedule || !isFormValid"
                                            class="flex items-center px-6 py-2.5 bg-blue-600 text-white font-medium rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 disabled:opacity-50 disabled:cursor-not-allowed transition-colors">
                                            <svg v-if="addingSchedule" class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" fill="none" viewBox="0 0 24 24">
                                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                            </svg>
                                            <svg v-else class="-ml-1 mr-2 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                            </svg>
                                            {{ addingSchedule ? 'Menambah Jadwal...' : 'Tambah Jadwal' }}
                                        </button>
                                    </div>
                                </form>
                            </div>

                            <!-- Filter & Search -->
                            <div class="flex flex-wrap gap-4 mb-4 p-3 bg-gray-50 rounded-lg">
                                <div class="flex-1 min-w-48">
                                    <select v-model="filterYear" class="block w-full text-sm border-gray-300 rounded-md">
                                        <option value="">Semua Tahun</option>
                                        <option v-for="year in availableYears" :key="year" :value="year">{{ year }}</option>
                                    </select>
                                </div>
                                <div class="flex-1 min-w-48">
                                    <select v-model="filterMonth" class="block w-full text-sm border-gray-300 rounded-md">
                                        <option value="">Semua Bulan</option>
                                        <option v-for="(name, num) in monthNames" :key="num" :value="parseInt(num)">{{ name }}</option>
                                    </select>
                                </div>
                                <div class="flex-1 min-w-48">
                                    <select v-model="filterStatus" class="block w-full text-sm border-gray-300 rounded-md">
                                        <option value="">Semua Status</option>
                                        <option value="tersedia">Tersedia</option>
                                        <option value="penuh">Penuh</option>
                                        <option value="ditutup">Ditutup</option>
                                    </select>
                                </div>
                            </div>

                            <!-- Existing Schedules -->
                            <div class="space-y-4">
                                <h4 class="text-sm font-medium text-gray-900 flex items-center justify-between">
                                    <span>Jadwal Tersedia ({{ filteredSchedules.length }} jadwal)</span>
                                    <button
                                        @click="loadSchedules"
                                        :disabled="loading"
                                        class="text-xs px-3 py-1 bg-gray-100 text-gray-600 rounded-md hover:bg-gray-200">
                                        <svg v-if="loading" class="animate-spin w-3 h-3 inline mr-1" fill="none" viewBox="0 0 24 24">
                                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                        </svg>
                                        Refresh
                                    </button>
                                </h4>

                                <div v-if="filteredSchedules.length === 0" class="text-center py-8 text-gray-500">
                                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2-2v12a2 2 0 002 2z"></path>
                                    </svg>
                                    <p class="mt-2">Belum ada jadwal keberangkatan</p>
                                </div>

                                <div v-else class="overflow-hidden shadow ring-1 ring-black ring-opacity-5 md:rounded-lg">
                                    <table class="min-w-full divide-y divide-gray-300">
                                        <thead class="bg-gray-50">
                                            <tr>
                                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                    Nama Jadwal
                                                </th>
                                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                    Tanggal
                                                </th>
                                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                    Kuota
                                                </th>
                                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                    Harga Total
                                                </th>
                                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                    Status
                                                </th>
                                                <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                    Aksi
                                                </th>
                                            </tr>
                                        </thead>
                                        <tbody class="bg-white divide-y divide-gray-200">
                                            <tr v-for="schedule in filteredSchedules" :key="schedule.id">
                                                <td class="px-4 py-3 whitespace-nowrap">
                                                    <div class="text-sm font-medium text-gray-900">{{ schedule.nama_jadwal }}</div>
                                                    <div v-if="schedule.catatan" class="text-xs text-gray-500">{{ schedule.catatan }}</div>
                                                </td>
                                                <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-900">
                                                    {{ formatDate(schedule.tanggal_lengkap) }}
                                                </td>
                                                <td class="px-4 py-3 whitespace-nowrap">
                                                    <div class="text-sm text-gray-900">{{ schedule.terisi }}/{{ schedule.kuota }}</div>
                                                    <div class="text-xs text-gray-500">Sisa: {{ schedule.kuota - schedule.terisi }}</div>
                                                </td>
                                                <td class="px-4 py-3 whitespace-nowrap">
                                                    <div class="text-sm font-medium text-gray-900">{{ formatCurrency(packageData.harga + schedule.biaya_tambahan) }}</div>
                                                    <div v-if="schedule.biaya_tambahan > 0" class="text-xs text-orange-600">
                                                        +{{ formatCurrency(schedule.biaya_tambahan) }}
                                                    </div>
                                                </td>
                                                <td class="px-4 py-3 whitespace-nowrap">
                                                    <span :class="{
                                                        'bg-green-100 text-green-800': schedule.status === 'tersedia',
                                                        'bg-red-100 text-red-800': schedule.status === 'penuh',
                                                        'bg-gray-100 text-gray-800': schedule.status === 'ditutup'
                                                    }" class="inline-flex px-2 text-xs leading-5 font-semibold rounded-full">
                                                        {{ getStatusLabel(schedule.status) }}
                                                    </span>
                                                </td>
                                                <td class="px-4 py-3 whitespace-nowrap text-right text-sm font-medium">
                                                    <div class="flex items-center justify-end space-x-2">
                                                        <button
                                                            @click="editSchedule(schedule)"
                                                            class="text-indigo-600 hover:text-indigo-900">
                                                            Edit
                                                        </button>
                                                        <button
                                                            @click="deleteSchedule(schedule)"
                                                            :disabled="schedule.terisi > 0"
                                                            class="text-red-600 hover:text-red-900 disabled:text-gray-400 disabled:cursor-not-allowed">
                                                            Hapus
                                                        </button>
                                                    </div>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                            <!-- Modal Footer -->
                            <div class="flex items-center justify-end space-x-3 pt-6 border-t border-gray-200">
                                <button
                                    type="button"
                                    @click="$emit('close')"
                                    class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                    Tutup
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Edit Schedule Modal -->
    <div v-if="showEditModal" class="fixed inset-0 z-60 overflow-y-auto" aria-labelledby="edit-modal-title" role="dialog" aria-modal="true">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" @click="closeEditModal"></div>

            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

            <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <div class="sm:flex sm:items-start">
                        <div class="w-full">
                            <div class="flex items-center justify-between mb-4">
                                <h3 class="text-lg leading-6 font-medium text-gray-900" id="edit-modal-title">
                                    Edit Jadwal
                                </h3>
                                <button @click="closeEditModal" class="text-gray-400 hover:text-gray-600">
                                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                    </svg>
                                </button>
                            </div>

                            <form @submit.prevent="saveScheduleEdit" v-if="editingSchedule">
                                <div class="space-y-4">
                                    <!-- Date Inputs -->
                                    <div class="grid grid-cols-3 gap-3">
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 mb-1">Tahun</label>
                                            <select v-model.number="editingSchedule.tahun" required
                                                    class="block w-full text-sm border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500">
                                                <option v-for="year in availableYears" :key="year" :value="year">{{ year }}</option>
                                            </select>
                                        </div>
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 mb-1">Bulan</label>
                                            <select v-model.number="editingSchedule.bulan" required
                                                    class="block w-full text-sm border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500">
                                                <option v-for="(name, num) in monthNames" :key="num" :value="parseInt(num)">{{ name }}</option>
                                            </select>
                                        </div>
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 mb-1">Tanggal</label>
                                            <input v-model.number="editingSchedule.tanggal_keberangkatan" type="number" required
                                                   min="1" max="31"
                                                   class="block w-full text-sm border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500">
                                        </div>
                                    </div>

                                    <!-- Other fields -->
                                    <div class="grid grid-cols-2 gap-3">
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 mb-1">Kuota</label>
                                            <input v-model.number="editingSchedule.kuota" type="number" required
                                                   min="1" max="100"
                                                   class="block w-full text-sm border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500">
                                        </div>
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                                            <select v-model="editingSchedule.status" required
                                                    class="block w-full text-sm border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500">
                                                <option value="tersedia">Tersedia</option>
                                                <option value="penuh">Penuh</option>
                                                <option value="ditutup">Ditutup</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Biaya Tambahan</label>
                                        <input v-model.number="editingSchedule.biaya_tambahan" type="number"
                                               min="0" step="100000" placeholder="0"
                                               class="block w-full text-sm border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500">
                                    </div>

                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Catatan</label>
                                        <textarea v-model="editingSchedule.catatan" rows="2"
                                                  class="block w-full text-sm border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500"></textarea>
                                    </div>

                                    <!-- Preview -->
                                    <div v-if="editingSchedule.tahun && editingSchedule.bulan && editingSchedule.tanggal_keberangkatan"
                                         class="bg-blue-50 p-3 rounded-md border border-blue-200">
                                        <div class="text-sm text-blue-800">
                                            <strong>Preview:</strong> {{ getEditSchedulePreview() }}
                                        </div>
                                    </div>
                                </div>

                                <div class="flex items-center justify-end space-x-3 pt-4 border-t border-gray-200 mt-6">
                                    <button type="button" @click="closeEditModal"
                                            class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50">
                                        Batal
                                    </button>
                                    <button type="submit"
                                            class="px-4 py-2 text-sm font-medium text-white bg-blue-600 border border-transparent rounded-md hover:bg-blue-700">
                                        Simpan Perubahan
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref, computed, watch } from 'vue'
import axios from 'axios'

const props = defineProps({
    show: Boolean,
    packageData: Object
})

const emit = defineEmits(['close', 'save'])

const loading = ref(false)
const addingSchedule = ref(false)
const schedules = ref([])

// Filters
const filterYear = ref('')
const filterMonth = ref('')
const filterStatus = ref('')

const monthNames = {
    1: 'Januari', 2: 'Februari', 3: 'Maret', 4: 'April',
    5: 'Mei', 6: 'Juni', 7: 'Juli', 8: 'Agustus',
    9: 'September', 10: 'Oktober', 11: 'November', 12: 'Desember'
}

const availableYears = [2024, 2025, 2026, 2027]

const newSchedule = ref({
    tahun: new Date().getFullYear(),
    bulan: '',
    tanggal_keberangkatan: '',
    kuota: 40,
    biaya_tambahan: 0,
    catatan: ''
})

// Computed
const filteredSchedules = computed(() => {
    return schedules.value.filter(schedule => {
        if (filterYear.value && schedule.tahun !== filterYear.value) return false
        if (filterMonth.value && schedule.bulan !== filterMonth.value) return false
        if (filterStatus.value && schedule.status !== filterStatus.value) return false
        return true
    }).sort((a, b) => new Date(a.tanggal_lengkap) - new Date(b.tanggal_lengkap))
})

const isFormValid = computed(() => {
    return newSchedule.value.tahun &&
           newSchedule.value.bulan &&
           newSchedule.value.tanggal_keberangkatan &&
           newSchedule.value.kuota > 0
})

// Watch for package changes
watch(() => props.packageData, (newPackage) => {
    if (newPackage && props.show) {
        loadSchedules()
    }
}, { immediate: true })

// Watch show prop
watch(() => props.show, (show) => {
    if (show && props.packageData) {
        loadSchedules()
    }
})

const loadSchedules = async () => {
    if (!props.packageData?.id) return

    try {
        loading.value = true
        const response = await axios.get(`/api/admin/packages/${props.packageData.id}/schedules`)
        if (response.data.success) {
            schedules.value = response.data.data
        }
    } catch (error) {
        console.error('Error loading schedules:', error)
    } finally {
        loading.value = false
    }
}

const addSchedule = async () => {
    try {
        addingSchedule.value = true

        const response = await axios.post(`/api/admin/packages/${props.packageData.id}/schedules`, {
            ...newSchedule.value,
            umroh_package_id: props.packageData.id
        })

        if (response.data.success) {
            schedules.value.push(response.data.data)

            // Reset form
            newSchedule.value = {
                tahun: new Date().getFullYear(),
                bulan: '',
                tanggal_keberangkatan: '',
                kuota: 40,
                biaya_tambahan: 0,
                catatan: ''
            }

            emit('save')
        }
    } catch (error) {
        console.error('Error adding schedule:', error)
        if (error.response?.data?.message) {
            alert(error.response.data.message)
        } else {
            alert('Gagal menambah jadwal')
        }
    } finally {
        addingSchedule.value = false
    }
}

const showEditModal = ref(false)
const editingSchedule = ref(null)

const editSchedule = (schedule) => {
    if (schedule.terisi > 0) {
        alert('Tidak dapat mengubah jadwal yang sudah memiliki jamaah terdaftar')
        return
    }

    editingSchedule.value = {
        id: schedule.id,
        tahun: schedule.tahun,
        bulan: schedule.bulan,
        tanggal_keberangkatan: schedule.tanggal_keberangkatan,
        kuota: schedule.kuota,
        biaya_tambahan: schedule.biaya_tambahan || 0,
        catatan: schedule.catatan || '',
        status: schedule.status
    }
    showEditModal.value = true
}

const closeEditModal = () => {
    showEditModal.value = false
    editingSchedule.value = null
}

const saveScheduleEdit = async () => {
    if (!editingSchedule.value) return

    try {
        const response = await axios.put(`/api/admin/schedules/${editingSchedule.value.id}`, {
            tahun: editingSchedule.value.tahun,
            bulan: editingSchedule.value.bulan,
            tanggal_keberangkatan: editingSchedule.value.tanggal_keberangkatan,
            kuota: editingSchedule.value.kuota,
            biaya_tambahan: editingSchedule.value.biaya_tambahan,
            catatan: editingSchedule.value.catatan,
            status: editingSchedule.value.status
        })

        if (response.data.success) {
            // Reload schedules
            await loadSchedules()
            closeEditModal()
            emit('save')
            alert('Jadwal berhasil diupdate')
        }
    } catch (error) {
        console.error('Error updating schedule:', error)
        if (error.response?.data?.message) {
            alert(error.response.data.message)
        } else {
            alert('Gagal mengupdate jadwal')
        }
    }
}

const deleteSchedule = async (schedule) => {
    if (schedule.terisi > 0) {
        alert('Tidak dapat menghapus jadwal yang sudah memiliki jamaah terdaftar')
        return
    }

    if (confirm(`Hapus jadwal ${schedule.nama_jadwal}?`)) {
        try {
            const response = await axios.delete(`/api/admin/schedules/${schedule.id}`)
            if (response.data.success) {
                schedules.value = schedules.value.filter(s => s.id !== schedule.id)
                emit('save')
            }
        } catch (error) {
            console.error('Error deleting schedule:', error)
            alert('Gagal menghapus jadwal')
        }
    }
}

const formatDate = (dateString) => {
    return new Date(dateString).toLocaleDateString('id-ID', {
        weekday: 'long',
        year: 'numeric',
        month: 'long',
        day: 'numeric'
    })
}

const formatCurrency = (amount) => {
    return new Intl.NumberFormat('id-ID', {
        style: 'currency',
        currency: 'IDR',
        minimumFractionDigits: 0
    }).format(amount)
}

const getStatusLabel = (status) => {
    const labels = {
        'tersedia': 'Tersedia',
        'penuh': 'Penuh',
        'ditutup': 'Ditutup'
    }
    return labels[status] || status
}

const getDatePreview = () => {
    if (!newSchedule.value.tahun || !newSchedule.value.bulan || !newSchedule.value.tanggal_keberangkatan) {
        return ''
    }

    try {
        const date = new Date(
            newSchedule.value.tahun,
            newSchedule.value.bulan - 1, // JavaScript months are 0-indexed
            newSchedule.value.tanggal_keberangkatan
        )

        // Check if date is valid
        if (isNaN(date.getTime())) {
            return 'Tanggal tidak valid'
        }

        return date.toLocaleDateString('id-ID', {
            weekday: 'long',
            year: 'numeric',
            month: 'long',
            day: 'numeric'
        })
    } catch (error) {
        return 'Tanggal tidak valid'
    }
}

const getEditSchedulePreview = () => {
    if (!editingSchedule.value || !editingSchedule.value.tahun || !editingSchedule.value.bulan || !editingSchedule.value.tanggal_keberangkatan) {
        return ''
    }

    try {
        const date = new Date(
            editingSchedule.value.tahun,
            editingSchedule.value.bulan - 1, // JavaScript months are 0-indexed
            editingSchedule.value.tanggal_keberangkatan
        )

        // Check if date is valid
        if (isNaN(date.getTime())) {
            return 'Tanggal tidak valid'
        }

        return date.toLocaleDateString('id-ID', {
            weekday: 'long',
            year: 'numeric',
            month: 'long',
            day: 'numeric'
        })
    } catch (error) {
        return 'Tanggal tidak valid'
    }
}
</script>