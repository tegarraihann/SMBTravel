<template>
    <div v-if="show" class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" @click="$emit('close')"></div>

            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

            <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-6xl sm:w-full">
                <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <div class="sm:flex sm:items-start">
                        <div class="w-full">
                            <!-- Modal Header -->
                            <div class="flex items-center justify-between mb-4">
                                <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-title">
                                    {{ isEditing ? 'Edit Paket Umroh' : 'Tambah Paket Umroh' }}
                                </h3>
                                <button @click="$emit('close')" class="text-gray-400 hover:text-gray-600">
                                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                    </svg>
                                </button>
                            </div>

                            <!-- Form -->
                            <form @submit.prevent="savePackage" class="space-y-6">
                                <!-- Package Information Section -->
                                <div class="bg-gray-50 p-4 rounded-lg">
                                    <h4 class="text-md font-medium text-gray-900 mb-4">Informasi Paket</h4>
                                    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                                    <!-- Nama Paket -->
                                    <div class="sm:col-span-2">
                                        <label class="block text-sm font-medium text-gray-700">
                                            Nama Paket <span class="text-red-500">*</span>
                                        </label>
                                        <input
                                            v-model="form.nama_paket"
                                            type="text"
                                            required
                                            class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                                            placeholder="Masukkan nama paket">
                                        <div v-if="errors.nama_paket" class="text-red-500 text-sm mt-1">{{ errors.nama_paket[0] }}</div>
                                    </div>

                                    <!-- Harga -->
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">
                                            Harga (IDR) <span class="text-red-500">*</span>
                                        </label>
                                        <input
                                            v-model.number="form.harga"
                                            type="number"
                                            required
                                            min="0"
                                            step="1000"
                                            class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                                            placeholder="25000000">
                                        <div v-if="errors.harga" class="text-red-500 text-sm mt-1">{{ errors.harga[0] }}</div>
                                    </div>

                                    <!-- Durasi -->
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">
                                            Durasi (Hari) <span class="text-red-500">*</span>
                                        </label>
                                        <input
                                            v-model.number="form.durasi_hari"
                                            type="number"
                                            required
                                            min="1"
                                            class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                                            placeholder="12">
                                        <div v-if="errors.durasi_hari" class="text-red-500 text-sm mt-1">{{ errors.durasi_hari[0] }}</div>
                                    </div>

                                    <!-- Status -->
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">
                                            Status <span class="text-red-500">*</span>
                                        </label>
                                        <select
                                            v-model="form.status"
                                            required
                                            class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                                            <option value="aktif">Aktif</option>
                                            <option value="non_aktif">Non Aktif</option>
                                        </select>
                                        <div v-if="errors.status" class="text-red-500 text-sm mt-1">{{ errors.status[0] }}</div>
                                    </div>

                                    <!-- Deskripsi -->
                                    <div class="sm:col-span-2">
                                        <label class="block text-sm font-medium text-gray-700">
                                            Deskripsi
                                        </label>
                                        <textarea
                                            v-model="form.deskripsi"
                                            rows="3"
                                            class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                                            placeholder="Deskripsi paket umroh..."></textarea>
                                        <div v-if="errors.deskripsi" class="text-red-500 text-sm mt-1">{{ errors.deskripsi[0] }}</div>
                                    </div>
                                </div>
                            </div>

                                <!-- Fasilitas -->
                                <div class="bg-gray-50 p-4 rounded-lg">
                                    <h4 class="text-md font-medium text-gray-900 mb-4">Fasilitas</h4>
                                    <div class="space-y-3">
                                        <div v-for="(value, key) in form.fasilitas" :key="key" class="flex items-center space-x-3">
                                            <label class="w-24 text-sm text-gray-600 capitalize">{{ key.replace('_', ' ') }}:</label>
                                            <input
                                                v-model="form.fasilitas[key]"
                                                type="text"
                                                class="flex-1 border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                                                :placeholder="`Fasilitas ${key.replace('_', ' ')}`">
                                        </div>
                                    </div>
                                </div>

                                <!-- Jadwal Keberangkatan Section -->
                                <div class="bg-blue-50 border border-blue-200 p-4 rounded-lg">
                                    <div class="flex items-center justify-between mb-4">
                                        <h4 class="text-md font-medium text-blue-900">Jadwal Keberangkatan</h4>
                                        <button
                                            type="button"
                                            @click="addNewSchedule"
                                            class="flex items-center px-3 py-1.5 bg-blue-600 text-white text-sm font-medium rounded-md hover:bg-blue-700">
                                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                            </svg>
                                            Tambah Jadwal
                                        </button>
                                    </div>

                                    <!-- Schedules List -->
                                    <div v-if="form.schedules.length === 0" class="text-center py-6 text-gray-500">
                                        <svg class="mx-auto h-8 w-8 text-gray-400 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2-2v12a2 2 0 002 2z"></path>
                                        </svg>
                                        <p>Belum ada jadwal keberangkatan</p>
                                    </div>

                                    <div v-else class="space-y-4">
                                        <div v-for="(schedule, index) in form.schedules" :key="index"
                                             class="bg-white p-4 rounded-lg border border-blue-200">
                                            <div class="flex items-start justify-between mb-3">
                                                <div class="flex-1">
                                                    <h5 class="font-medium text-gray-900 mb-2">Jadwal {{ index + 1 }}</h5>
                                                    <div class="grid grid-cols-2 lg:grid-cols-4 gap-3">
                                                        <!-- Tahun -->
                                                        <div>
                                                            <label class="block text-xs font-medium text-gray-700 mb-1">Tahun</label>
                                                            <select v-model.number="schedule.tahun" required
                                                                    class="block w-full text-sm border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500">
                                                                <option value="">Pilih Tahun</option>
                                                                <option v-for="year in availableYears" :key="year" :value="year">{{ year }}</option>
                                                            </select>
                                                        </div>

                                                        <!-- Bulan -->
                                                        <div>
                                                            <label class="block text-xs font-medium text-gray-700 mb-1">Bulan</label>
                                                            <select v-model.number="schedule.bulan" required
                                                                    class="block w-full text-sm border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500">
                                                                <option value="">Pilih Bulan</option>
                                                                <option v-for="(name, num) in monthNames" :key="num" :value="parseInt(num)">{{ name }}</option>
                                                            </select>
                                                        </div>

                                                        <!-- Tanggal -->
                                                        <div>
                                                            <label class="block text-xs font-medium text-gray-700 mb-1">Tanggal</label>
                                                            <input v-model.number="schedule.tanggal_keberangkatan" type="number" required
                                                                   min="1" max="31" placeholder="1-31"
                                                                   class="block w-full text-sm border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500">
                                                        </div>

                                                        <!-- Kuota -->
                                                        <div>
                                                            <label class="block text-xs font-medium text-gray-700 mb-1">Kuota</label>
                                                            <input v-model.number="schedule.kuota" type="number" required
                                                                   min="1" max="100" placeholder="40"
                                                                   class="block w-full text-sm border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500">
                                                        </div>
                                                    </div>

                                                    <!-- Preview tanggal & biaya tambahan -->
                                                    <div class="mt-3 grid grid-cols-1 lg:grid-cols-2 gap-3">
                                                        <div>
                                                            <label class="block text-xs font-medium text-gray-700 mb-1">Biaya Tambahan (IDR)</label>
                                                            <input v-model.number="schedule.biaya_tambahan" type="number"
                                                                   min="0" step="100000" placeholder="0"
                                                                   class="block w-full text-sm border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500">
                                                        </div>
                                                        <div>
                                                            <label class="block text-xs font-medium text-gray-700 mb-1">Preview Tanggal</label>
                                                            <div class="text-sm bg-gray-50 px-3 py-2 rounded-md">
                                                                {{ getSchedulePreview(schedule) }}
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <!-- Catatan -->
                                                    <div class="mt-3">
                                                        <label class="block text-xs font-medium text-gray-700 mb-1">Catatan (Opsional)</label>
                                                        <textarea v-model="schedule.catatan" rows="2"
                                                                  placeholder="Contoh: Jadwal libur sekolah, cuaca panas, dll..."
                                                                  class="block w-full text-sm border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500 resize-none"></textarea>
                                                    </div>
                                                </div>

                                                <!-- Remove Button -->
                                                <button type="button" @click="removeSchedule(index)"
                                                        class="ml-3 p-1 text-red-600 hover:text-red-800">
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                                    </svg>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Modal Footer -->
                                <div class="flex items-center justify-end space-x-3 pt-4 border-t border-gray-200">
                                    <button
                                        type="button"
                                        @click="$emit('close')"
                                        class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                        Batal
                                    </button>
                                    <button
                                        type="submit"
                                        :disabled="saving"
                                        class="px-4 py-2 text-sm font-medium text-white bg-blue-600 border border-transparent rounded-md shadow-sm hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 disabled:opacity-50">
                                        <span v-if="saving">Menyimpan...</span>
                                        <span v-else>{{ isEditing ? 'Update' : 'Simpan' }}</span>
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

const saving = ref(false)
const errors = ref({})

const defaultForm = {
    nama_paket: '',
    deskripsi: '',
    harga: '',
    durasi_hari: '',
    status: 'aktif',
    fasilitas: {
        hotel: '',
        transportasi: '',
        makan: '',
        pemandu: '',
        visa: '',
        ziarah: ''
    },
    schedules: []
}

const form = ref({ ...defaultForm })

const isEditing = computed(() => !!props.packageData)

// Watch for packageData prop changes
watch(() => props.packageData, async (newPackage) => {
    if (newPackage) {
        form.value = {
            ...defaultForm,
            ...newPackage,
            fasilitas: {
                ...defaultForm.fasilitas,
                ...(newPackage.fasilitas || {})
            },
            schedules: []
        }

        // Load existing schedules for edit mode (load ALL schedules, not just available ones)
        if (isEditing.value) {
            try {
                const response = await axios.get(`/api/admin/packages/${newPackage.id}/schedules`)
                if (response.data.success) {
                    form.value.schedules = response.data.data.map(schedule => ({
                        id: schedule.id,
                        tahun: schedule.tahun,
                        bulan: schedule.bulan,
                        tanggal_keberangkatan: schedule.tanggal_keberangkatan,
                        kuota: schedule.kuota,
                        biaya_tambahan: schedule.biaya_tambahan || 0,
                        catatan: schedule.catatan || ''
                    }))
                }
            } catch (error) {
                console.error('Error loading schedules for edit:', error)
                // Fallback to empty schedules array
                form.value.schedules = []
            }
        }
    } else {
        form.value = { ...defaultForm }
    }
    errors.value = {}
}, { immediate: true })

const savePackage = async () => {
    try {
        saving.value = true
        errors.value = {}

        const endpoint = isEditing.value
            ? `/api/admin/packages/${props.packageData.id}`
            : '/api/admin/packages'

        const method = isEditing.value ? 'put' : 'post'

        // Debug logging
        console.log('=== FRONTEND DEBUG ===')
        console.log('Endpoint:', endpoint)
        console.log('Method:', method)
        console.log('Form Data:', JSON.stringify(form.value, null, 2))
        console.log('Is Editing:', isEditing.value)
        console.log('Package Data:', props.packageData)
        console.log('======================')

        const response = await axios[method](endpoint, form.value)

        if (response.data.success) {
            emit('save')
        }
    } catch (error) {
        if (error.response?.status === 422) {
            errors.value = error.response.data.errors || {}
        } else {
            console.error('Error saving package:', error)
            alert('Gagal menyimpan paket')
        }
    } finally {
        saving.value = false
    }
}

// Schedule management functions
const monthNames = {
    1: 'Januari', 2: 'Februari', 3: 'Maret', 4: 'April',
    5: 'Mei', 6: 'Juni', 7: 'Juli', 8: 'Agustus',
    9: 'September', 10: 'Oktober', 11: 'November', 12: 'Desember'
}

const availableYears = [2024, 2025, 2026, 2027]

const addNewSchedule = () => {
    form.value.schedules.push({
        tahun: new Date().getFullYear(),
        bulan: '',
        tanggal_keberangkatan: '',
        kuota: 40,
        biaya_tambahan: 0,
        catatan: ''
    })
}

const removeSchedule = (index) => {
    form.value.schedules.splice(index, 1)
}

const getSchedulePreview = (schedule) => {
    if (!schedule.tahun || !schedule.bulan || !schedule.tanggal_keberangkatan) {
        return 'Pilih tanggal lengkap'
    }

    try {
        const date = new Date(
            schedule.tahun,
            schedule.bulan - 1, // JavaScript months are 0-indexed
            schedule.tanggal_keberangkatan
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