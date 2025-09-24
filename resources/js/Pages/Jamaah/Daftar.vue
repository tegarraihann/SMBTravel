<template>
    <Head title="Daftar Jamaah" />

    <div class="min-h-screen bg-slate-50 py-12">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Header -->
            <div class="text-center mb-8">
                <h1 class="text-3xl font-bold text-gray-900 mb-2">
                    Formulir Pendaftaran Jamaah Umroh
                </h1>
                <p class="text-lg text-gray-600">
                    Silakan lengkapi data di bawah ini dengan benar dan lengkap
                </p>
            </div>

            <!-- Progress Bar -->
            <ProgressBar :current-step="1" :jamaah-data="{}" @step-action="handleStepAction" />

            <!-- Registration Form -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
                <form @submit.prevent="submitForm" class="p-8 space-y-8">

                    <!-- Data Jamaah -->
                    <div class="space-y-6">
                        <div class="border-b border-gray-200 pb-4">
                            <h2 class="text-xl font-semibold text-gray-900">Data Jamaah</h2>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    Nama Lengkap Bin/Binti <span class="text-red-500">*</span>
                                </label>
                                <input type="text" v-model="form.nama_lengkap_bin_binti" required
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                                    placeholder="Nama lengkap sesuai identitas">
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    NIK <span class="text-red-500">*</span>
                                </label>
                                <input type="text" v-model="form.nik" required maxlength="16"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                                    placeholder="16 digit NIK">
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    Tempat Lahir <span class="text-red-500">*</span>
                                </label>
                                <input type="text" v-model="form.tempat_lahir" required
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                                    placeholder="Kota tempat lahir">
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    Tanggal Lahir <span class="text-red-500">*</span>
                                </label>
                                <input type="date" v-model="form.tanggal_lahir" @change="calculateAge" required
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    Usia <span class="text-red-500">*</span>
                                </label>
                                <input type="number" v-model="form.usia" readonly
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md bg-gray-50"
                                    placeholder="Akan terisi otomatis">
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    Jenis Kelamin <span class="text-red-500">*</span>
                                </label>
                                <select v-model="form.jenis_kelamin" required
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                                    <option value="">Pilih jenis kelamin</option>
                                    <option value="L">Laki-laki</option>
                                    <option value="P">Perempuan</option>
                                </select>
                            </div>

                            <div class="md:col-span-2">
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    Alamat <span class="text-red-500">*</span>
                                </label>
                                <textarea v-model="form.alamat" required rows="3"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                                    placeholder="Alamat lengkap sesuai identitas"></textarea>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    No. Telp/HP/WA <span class="text-red-500">*</span>
                                </label>
                                <input type="tel" v-model="form.no_telepon" required
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                                    placeholder="08xxxxxxxxxx">
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    Pekerjaan <span class="text-red-500">*</span>
                                </label>
                                <input type="text" v-model="form.pekerjaan" required
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                                    placeholder="Pekerjaan saat ini">
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    Nama Marketing Umroh
                                </label>
                                <input type="text" v-model="form.nama_marketing"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                                    placeholder="Nama marketing yang menangani">
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    Paket Umroh Yang Diambil <span class="text-red-500">*</span>
                                </label>
                                <select v-model="form.paket_id" required
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                                    <option value="">Pilih paket umroh</option>
                                    <option value="1">City Tour Malaysia - Rp 24.5 Juta</option>
                                    <option value="2">Plus Tha'if - Rp 34.5 Juta</option>
                                    <option value="3">Plus Tha'if dan Kereta Cepat - Rp 41 Juta</option>
                                </select>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    Rencana Keberangkatan
                                </label>
                                <input type="date" v-model="form.rencana_keberangkatan"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                            </div>
                        </div>
                    </div>

                    <!-- Program Talangan -->
                    <div class="space-y-6">
                        <div class="border-b border-gray-200 pb-4">
                            <h2 class="text-xl font-semibold text-gray-900">Program Talangan</h2>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Program Talangan
                            </label>
                            <div class="space-y-2">
                                <label class="flex items-center">
                                    <input type="radio" v-model="form.program_talangan" :value="false" class="mr-2">
                                    Tidak
                                </label>
                                <label class="flex items-center">
                                    <input type="radio" v-model="form.program_talangan" :value="true" class="mr-2">
                                    Ya
                                </label>
                            </div>
                        </div>

                        <!-- Tabel Cicilan (jika program talangan = ya) -->
                        <div v-if="form.program_talangan" class="mt-6">
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Tabel Cicilan</h3>
                            <div class="overflow-x-auto">
                                <table class="min-w-full border border-gray-300">
                                    <thead class="bg-gray-50">
                                        <tr>
                                            <th class="px-4 py-2 border-b text-left">No</th>
                                            <th class="px-4 py-2 border-b text-left">DP</th>
                                            <th class="px-4 py-2 border-b text-left">Tenor</th>
                                            <th class="px-4 py-2 border-b text-left">Cicilan Perbulan</th>
                                            <th class="px-4 py-2 border-b text-center">Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr v-for="(cicilan, index) in form.cicilan_data" :key="index" class="border-b">
                                            <td class="px-4 py-2">{{ index + 1 }}</td>
                                            <td class="px-4 py-2">
                                                <input type="number" v-model="cicilan.dp" placeholder="Jumlah DP"
                                                    class="w-full px-2 py-1 border border-gray-300 rounded">
                                            </td>
                                            <td class="px-4 py-2">
                                                <input type="number" v-model="cicilan.tenor" placeholder="Bulan"
                                                    class="w-full px-2 py-1 border border-gray-300 rounded">
                                            </td>
                                            <td class="px-4 py-2">
                                                <input type="number" v-model="cicilan.cicilan_perbulan" placeholder="Per bulan"
                                                    class="w-full px-2 py-1 border border-gray-300 rounded">
                                            </td>
                                            <td class="px-4 py-2 text-center">
                                                <button type="button" @click="removeCicilan(index)"
                                                    class="text-red-600 hover:text-red-800">
                                                    Hapus
                                                </button>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                                <button type="button" @click="addCicilan" v-if="form.cicilan_data.length < 5"
                                    class="mt-2 px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">
                                    Tambah Cicilan
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Sistem Pembayaran -->
                    <div class="space-y-6">
                        <div class="border-b border-gray-200 pb-4">
                            <h2 class="text-xl font-semibold text-gray-900">Sistem Pembayaran</h2>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    Sistem Pembayaran
                                </label>
                                <input type="text" v-model="form.sistem_pembayaran" readonly
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md bg-gray-50">
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    DP
                                </label>
                                <input type="number" v-model="form.dp_paid" placeholder="Jumlah DP"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    TGL Pelunasan
                                </label>
                                <input type="date" v-model="form.tgl_pelunasan"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                            </div>
                        </div>
                    </div>

                    <!-- Jumlah Jamaah yang Berangkat -->
                    <div class="space-y-6">
                        <div class="border-b border-gray-200 pb-4">
                            <h2 class="text-xl font-semibold text-gray-900">Jumlah Jamaah yang Berangkat</h2>
                        </div>

                        <div class="overflow-x-auto">
                            <table class="min-w-full border border-gray-300">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-4 py-2 border-b text-left">No</th>
                                        <th class="px-4 py-2 border-b text-left">Nama Lengkap</th>
                                        <th class="px-4 py-2 border-b text-left">NIK</th>
                                        <th class="px-4 py-2 border-b text-left">Tempat/Tanggal Lahir</th>
                                        <th class="px-4 py-2 border-b text-left">Jenis Kelamin</th>
                                        <th class="px-4 py-2 border-b text-left">Alamat</th>
                                        <th class="px-4 py-2 border-b text-left">No. Telp/WA</th>
                                        <th class="px-4 py-2 border-b text-center">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="(jamaah, index) in form.jamaah_rombongan" :key="index" class="border-b">
                                        <td class="px-4 py-2">{{ index + 1 }}</td>
                                        <td class="px-4 py-2">
                                            <input type="text" v-model="jamaah.nama" placeholder="Nama lengkap"
                                                class="w-full px-2 py-1 border border-gray-300 rounded">
                                        </td>
                                        <td class="px-4 py-2">
                                            <input type="text" v-model="jamaah.nik" placeholder="NIK" maxlength="16"
                                                class="w-full px-2 py-1 border border-gray-300 rounded">
                                        </td>
                                        <td class="px-4 py-2">
                                            <input type="text" v-model="jamaah.tempat_tanggal_lahir" placeholder="Tempat, DD/MM/YYYY"
                                                class="w-full px-2 py-1 border border-gray-300 rounded">
                                        </td>
                                        <td class="px-4 py-2">
                                            <select v-model="jamaah.jenis_kelamin" class="w-full px-2 py-1 border border-gray-300 rounded">
                                                <option value="">Pilih</option>
                                                <option value="L">L</option>
                                                <option value="P">P</option>
                                            </select>
                                        </td>
                                        <td class="px-4 py-2">
                                            <input type="text" v-model="jamaah.alamat" placeholder="Alamat"
                                                class="w-full px-2 py-1 border border-gray-300 rounded">
                                        </td>
                                        <td class="px-4 py-2">
                                            <input type="text" v-model="jamaah.no_telp" placeholder="No. Telp"
                                                class="w-full px-2 py-1 border border-gray-300 rounded">
                                        </td>
                                        <td class="px-4 py-2 text-center">
                                            <button type="button" @click="removeJamaah(index)"
                                                class="text-red-600 hover:text-red-800">
                                                Hapus
                                            </button>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                            <button type="button" @click="addJamaah"
                                class="mt-2 px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">
                                Tambah Jamaah
                            </button>
                        </div>
                    </div>

                    <!-- Informasi Tambahan -->
                    <div class="space-y-6">
                        <div class="border-b border-gray-200 pb-4">
                            <h2 class="text-xl font-semibold text-gray-900">Informasi Tambahan</h2>
                        </div>

                        <div class="grid grid-cols-1 gap-6">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    Sumber info MMB diperoleh dari
                                </label>
                                <input type="text" v-model="form.sumber_info_mmb"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                                    placeholder="Dari mana Anda mengetahui layanan kami">
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    Kelengkapan Dokumen Umroh
                                </label>
                                <textarea v-model="form.kelengkapan_dokumen" rows="3"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                                    placeholder="Daftar dokumen yang sudah disiapkan"></textarea>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">
                                        a. Tanggal Diterima
                                    </label>
                                    <input type="date" v-model="form.tanggal_diterima_perlengkapan"
                                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">
                                        b. Detail yang diterima
                                    </label>
                                    <input type="text" v-model="form.detail_perlengkapan_diterima"
                                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                                        placeholder="Detail perlengkapan">
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">
                                        c. PIC Penerima
                                    </label>
                                    <input type="text" v-model="form.pic_penerima"
                                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                                        placeholder="Nama PIC penerima">
                                </div>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    Request Khusus dari jamaah
                                </label>
                                <textarea v-model="form.request_khusus" rows="3"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                                    placeholder="Permintaan khusus, alergi makanan, kondisi kesehatan, dll."></textarea>
                            </div>
                        </div>
                    </div>

                    <!-- Terms and Submit -->
                    <div class="space-y-6">
                        <div class="flex items-start">
                            <input type="checkbox" id="setuju" v-model="form.setuju" required
                                class="mt-1 h-4 w-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500">
                            <label for="setuju" class="ml-2 text-sm text-gray-700">
                                Saya setuju dengan <a href="#" class="text-blue-600 hover:text-blue-800">syarat dan
                                    ketentuan</a>
                                yang berlaku dan menyatakan bahwa data yang saya berikan adalah benar dan dapat
                                dipertanggungjawabkan.
                                <span class="text-red-500">*</span>
                            </label>
                        </div>

                        <div class="flex flex-col sm:flex-row gap-4 pt-6">
                            <button type="button" @click="resetForm"
                                class="flex-1 bg-gray-100 hover:bg-gray-200 text-gray-700 font-semibold py-3 px-6 rounded-lg transition-colors duration-200">
                                Reset Form
                            </button>
                            <button type="submit" :disabled="loading"
                                class="flex-1 bg-blue-600 hover:bg-blue-700 disabled:bg-blue-400 text-white font-semibold py-3 px-6 rounded-lg transition-colors duration-200">
                                <span v-if="loading">Memproses...</span>
                                <span v-else>Daftar Sekarang</span>
                            </button>
                        </div>
                    </div>
                </form>
            </div>

            <!-- Help Section -->
            <div class="mt-8 bg-blue-50 border border-blue-200 rounded-lg p-6">
                <h3 class="text-lg font-semibold text-blue-900 mb-2">Butuh Bantuan?</h3>
                <p class="text-blue-700 mb-4">
                    Tim customer service kami siap membantu Anda dalam proses pendaftaran.
                </p>
                <div class="flex flex-col sm:flex-row gap-3">
                    <a href="https://wa.me/6281234567890" target="_blank"
                        class="bg-yellow-500 hover:bg-yellow-600 text-slate-900 px-4 py-2 rounded-lg font-medium transition-colors duration-200 text-center">
                        Chat WhatsApp
                    </a>
                    <a href="tel:0761-12345"
                        class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg font-medium transition-colors duration-200 text-center">
                        Telepon Langsung
                    </a>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref, reactive, watch } from 'vue'
import { Head, router } from '@inertiajs/vue3'
import ProgressBar from '@/Components/Jamaah/ProgressBar.vue'

const loading = ref(false)
const errors = ref({})

const form = reactive({
    nama_lengkap_bin_binti: '',
    nik: '',
    tempat_lahir: '',
    tanggal_lahir: '',
    usia: '',
    jenis_kelamin: '',
    alamat: '',
    no_telepon: '',
    pekerjaan: '',
    nama_marketing: '',
    paket_id: '',
    rencana_keberangkatan: '',
    program_talangan: false,
    cicilan_data: [],
    sistem_pembayaran: 'Transfer',
    dp_paid: '',
    tgl_pelunasan: '',
    jamaah_rombongan: [
        {
            nama: '',
            nik: '',
            tempat_tanggal_lahir: '',
            jenis_kelamin: '',
            alamat: '',
            no_telp: ''
        }
    ],
    sumber_info_mmb: '',
    kelengkapan_dokumen: '',
    tanggal_diterima_perlengkapan: '',
    detail_perlengkapan_diterima: '',
    pic_penerima: '',
    request_khusus: '',
    setuju: false
})

// Calculate age based on birth date
const calculateAge = () => {
    if (form.tanggal_lahir) {
        const birthDate = new Date(form.tanggal_lahir)
        const today = new Date()
        let age = today.getFullYear() - birthDate.getFullYear()
        const monthDiff = today.getMonth() - birthDate.getMonth()

        if (monthDiff < 0 || (monthDiff === 0 && today.getDate() < birthDate.getDate())) {
            age--
        }

        form.usia = age
    }
}

// Cicilan methods
const addCicilan = () => {
    if (form.cicilan_data.length < 5) {
        form.cicilan_data.push({
            dp: '',
            tenor: '',
            cicilan_perbulan: ''
        })
    }
}

const removeCicilan = (index) => {
    form.cicilan_data.splice(index, 1)
}

// Jamaah rombongan methods
const addJamaah = () => {
    form.jamaah_rombongan.push({
        nama: '',
        nik: '',
        tempat_tanggal_lahir: '',
        jenis_kelamin: '',
        alamat: '',
        no_telp: ''
    })
}

const removeJamaah = (index) => {
    if (form.jamaah_rombongan.length > 1) {
        form.jamaah_rombongan.splice(index, 1)
    }
}

// Watch program talangan to reset cicilan data
watch(() => form.program_talangan, (newVal) => {
    if (!newVal) {
        form.cicilan_data = []
    } else if (form.cicilan_data.length === 0) {
        addCicilan()
    }
})

const submitForm = async () => {
    try {
        loading.value = true
        errors.value = {}

        // Filter out empty jamaah_rombongan entries
        const formData = { ...form }
        formData.jamaah_rombongan = form.jamaah_rombongan.filter(jamaah => {
            return jamaah.nama.trim() !== '' ||
                   jamaah.nik.trim() !== '' ||
                   jamaah.tempat_tanggal_lahir.trim() !== '' ||
                   jamaah.jenis_kelamin.trim() !== '' ||
                   jamaah.alamat.trim() !== '' ||
                   jamaah.no_telp.trim() !== ''
        })

        // Submit ke backend
        router.post('/jamaah/daftar', formData, {
            onSuccess: () => {
                alert('Pendaftaran berhasil! Anda akan diarahkan ke dashboard.')
                router.visit('/jamaah/dashboard')
            },
            onError: (err) => {
                errors.value = err
                console.error('Validation errors:', err)
            }
        })

    } catch (error) {
        console.error('Error submitting form:', error)
        alert('Terjadi kesalahan. Silakan coba lagi.')
    } finally {
        loading.value = false
    }
}

const resetForm = () => {
    Object.keys(form).forEach(key => {
        if (key === 'program_talangan' || key === 'setuju') {
            form[key] = false
        } else if (key === 'sistem_pembayaran') {
            form[key] = 'Transfer'
        } else if (key === 'cicilan_data') {
            form[key] = []
        } else if (key === 'jamaah_rombongan') {
            form[key] = [{
                nama: '',
                nik: '',
                tempat_tanggal_lahir: '',
                jenis_kelamin: '',
                alamat: '',
                no_telp: ''
            }]
        } else {
            form[key] = ''
        }
    })
    errors.value = {}
}

const handleStepAction = (action) => {
    console.log('Step action:', action)
}
</script>