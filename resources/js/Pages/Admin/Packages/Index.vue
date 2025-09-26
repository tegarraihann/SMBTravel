<template>
    <Head title="Master Data Paket Umroh" />

    <AdminLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Master Data Paket Umroh
            </h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <!-- Header Actions -->
                    <div class="p-6 border-b border-gray-200">
                        <div class="flex justify-between items-center">
                            <div>
                                <h3 class="text-lg font-medium text-gray-900">Daftar Paket Umroh</h3>
                                <p class="mt-1 text-sm text-gray-600">
                                    Kelola paket umroh, harga, jadwal, dan fasilitas
                                </p>
                            </div>
                            <div class="flex space-x-3">
                                <button @click="refreshData"
                                        :disabled="loading"
                                        class="inline-flex items-center px-3 py-2 border border-gray-300 shadow-sm text-sm leading-4 font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 disabled:opacity-50">
                                    <svg v-if="loading" class="animate-spin -ml-1 mr-2 h-4 w-4 text-gray-700" fill="none" viewBox="0 0 24 24">
                                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                    </svg>
                                    <svg v-else class="-ml-1 mr-2 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                                    </svg>
                                    Refresh
                                </button>
                                <button @click="showCreateModal"
                                        class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:bg-blue-700 active:bg-blue-900 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                    <svg class="-ml-1 mr-2 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                    </svg>
                                    Tambah Paket
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Table -->
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Paket
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Harga & Durasi
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Jadwal Tersedia
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Status
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Jamaah Terdaftar
                                    </th>
                                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Aksi
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                <tr v-if="loading" v-for="i in 3" :key="`loading-${i}`">
                                    <td class="px-6 py-4 whitespace-nowrap" colspan="6">
                                        <div class="animate-pulse flex space-x-4">
                                            <div class="rounded-full bg-gray-300 h-10 w-10"></div>
                                            <div class="flex-1 space-y-2 py-1">
                                                <div class="h-4 bg-gray-300 rounded w-3/4"></div>
                                                <div class="h-4 bg-gray-300 rounded w-1/2"></div>
                                            </div>
                                        </div>
                                    </td>
                                </tr>

                                <tr v-else-if="packages.length === 0">
                                    <td colspan="6" class="px-6 py-4 text-center text-gray-500">
                                        <div class="flex flex-col items-center justify-center py-8">
                                            <svg class="h-12 w-12 text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                                            </svg>
                                            <p class="text-lg font-medium text-gray-900 mb-2">Belum ada paket</p>
                                            <p class="text-gray-500 mb-4">Mulai dengan membuat paket umroh pertama</p>
                                            <button @click="showCreateModal"
                                                    class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700">
                                                Tambah Paket Pertama
                                            </button>
                                        </div>
                                    </td>
                                </tr>

                                <tr v-else v-for="packageItem in packages" :key="packageItem.id" class="hover:bg-gray-50">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div>
                                            <div class="text-sm font-medium text-gray-900">{{ packageItem.nama_paket }}</div>
                                            <div class="text-sm text-gray-500 max-w-xs truncate">{{ packageItem.deskripsi }}</div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm font-medium text-gray-900">{{ formatCurrency(packageItem.harga) }}</div>
                                        <div class="text-sm text-gray-500">{{ packageItem.durasi_hari }} hari</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-900">
                                            {{ getAvailableSchedulesCount(packageItem) }} jadwal
                                        </div>
                                        <div v-if="getNextSchedule(packageItem)" class="text-xs text-gray-500">
                                            Terdekat: {{ formatDate(getNextSchedule(packageItem).tanggal_lengkap) }}
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span :class="{
                                            'bg-green-100 text-green-800': packageItem.status === 'aktif',
                                            'bg-red-100 text-red-800': packageItem.status === 'non_aktif'
                                        }" class="inline-flex px-2 text-xs leading-5 font-semibold rounded-full">
                                            {{ packageItem.status === 'aktif' ? 'Aktif' : 'Non Aktif' }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        {{ packageItem.jamaah_count || 0 }} jamaah
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                        <div class="flex items-center justify-end space-x-2">
                                            <button @click="showScheduleModal(packageItem)"
                                                    class="text-blue-600 hover:text-blue-900 text-xs">
                                                Jadwal
                                            </button>
                                            <button @click="editPackage(packageItem)"
                                                    class="text-indigo-600 hover:text-indigo-900 text-xs">
                                                Edit
                                            </button>
                                            <button @click="deletePackage(packageItem)"
                                                    class="text-red-600 hover:text-red-900 text-xs"
                                                    :disabled="packageItem.jamaah_count > 0">
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

        <!-- Create/Edit Modal -->
        <PackageFormModal
            v-if="showModal"
            :show="showModal"
            :package-data="editingPackage"
            @close="closeModal"
            @save="handleSave"
        />

        <!-- Schedule Modal -->
        <ScheduleModal
            v-if="showScheduleModalFlag"
            :show="showScheduleModalFlag"
            :package-data="selectedPackage"
            @close="closeScheduleModal"
            @save="handleScheduleSave"
        />
    </AdminLayout>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import { Head } from '@inertiajs/vue3'
import AdminLayout from '@/Layouts/AdminLayout.vue'
import PackageFormModal from './Components/PackageFormModal.vue'
import ScheduleModal from './Components/ScheduleModal.vue'
import axios from 'axios'

const loading = ref(true)
const packages = ref([])
const showModal = ref(false)
const showScheduleModalFlag = ref(false)
const editingPackage = ref(null)
const selectedPackage = ref(null)

onMounted(() => {
    loadPackages()
})

const loadPackages = async () => {
    try {
        loading.value = true
        const response = await axios.get('/api/admin/packages')
        if (response.data.success) {
            packages.value = response.data.data
        }
    } catch (error) {
        console.error('Error loading packages:', error)
    } finally {
        loading.value = false
    }
}

const refreshData = () => {
    loadPackages()
}

const showCreateModal = () => {
    editingPackage.value = null
    showModal.value = true
}

const editPackage = (packageData) => {
    editingPackage.value = { ...packageData }
    showModal.value = true
}

const closeModal = () => {
    showModal.value = false
    editingPackage.value = null
}

const handleSave = () => {
    closeModal()
    loadPackages()
}

const showScheduleModal = (packageData) => {
    selectedPackage.value = packageData
    showScheduleModalFlag.value = true
}

const closeScheduleModal = () => {
    showScheduleModalFlag.value = false
    selectedPackage.value = null
}

const handleScheduleSave = () => {
    closeScheduleModal()
    loadPackages()
}

const deletePackage = async (packageData) => {
    if (packageData.jamaah_count > 0) {
        alert('Tidak dapat menghapus paket yang sudah memiliki jamaah terdaftar')
        return
    }

    if (confirm(`Apakah Anda yakin ingin menghapus paket "${packageData.nama_paket}"?`)) {
        try {
            const response = await axios.delete(`/api/admin/packages/${packageData.id}`)
            if (response.data.success) {
                loadPackages()
            }
        } catch (error) {
            console.error('Error deleting package:', error)
            alert('Gagal menghapus paket')
        }
    }
}

const formatCurrency = (amount) => {
    return new Intl.NumberFormat('id-ID', {
        style: 'currency',
        currency: 'IDR',
        minimumFractionDigits: 0
    }).format(amount)
}

const formatDate = (dateString) => {
    return new Date(dateString).toLocaleDateString('id-ID', {
        day: 'numeric',
        month: 'short',
        year: 'numeric'
    })
}

const getAvailableSchedulesCount = (packageData) => {
    if (!packageData.available_schedules) return 0
    return packageData.available_schedules.filter(schedule =>
        schedule.status === 'tersedia' && new Date(schedule.tanggal_lengkap) >= new Date()
    ).length
}

const getNextSchedule = (packageData) => {
    if (!packageData.available_schedules) return null
    const availableSchedules = packageData.available_schedules
        .filter(schedule => schedule.status === 'tersedia' && new Date(schedule.tanggal_lengkap) >= new Date())
        .sort((a, b) => new Date(a.tanggal_lengkap) - new Date(b.tanggal_lengkap))

    return availableSchedules[0] || null
}
</script>