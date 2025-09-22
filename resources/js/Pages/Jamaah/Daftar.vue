<template>
  <Head title="Daftar Jamaah" />

  <div class="min-h-screen bg-slate-50 py-12">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
      <!-- Header -->
      <div class="text-center mb-8">
        <h1 class="text-3xl font-bold text-gray-900 mb-2">
          Daftar Sebagai Jamaah
        </h1>
        <p class="text-lg text-gray-600">
          Isi form pendaftaran di bawah untuk bergabung dengan program umroh kami
        </p>
      </div>

      <!-- Progress Bar -->
      <ProgressBar
        :current-step="1"
        :jamaah-data="{}"
        @step-action="handleStepAction"
      />

      <!-- Registration Form -->
      <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
        <form @submit.prevent="submitForm" class="p-8 space-y-8">
          <!-- Personal Information -->
          <div class="space-y-6">
            <div class="border-b border-gray-200 pb-4">
              <h2 class="text-xl font-semibold text-gray-900">Data Pribadi</h2>
              <p class="text-sm text-gray-500 mt-1">Informasi dasar tentang diri Anda</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
              <div>
                <label for="nama_lengkap" class="block text-sm font-medium text-gray-700 mb-2">
                  Nama Lengkap <span class="text-red-500">*</span>
                </label>
                <input
                  type="text"
                  id="nama_lengkap"
                  v-model="form.nama_lengkap"
                  required
                  class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                  placeholder="Masukkan nama lengkap sesuai KTP"
                >
                <div v-if="errors.nama_lengkap" class="text-red-500 text-sm mt-1">
                  {{ errors.nama_lengkap }}
                </div>
              </div>

              <div>
                <label for="jenis_kelamin" class="block text-sm font-medium text-gray-700 mb-2">
                  Jenis Kelamin <span class="text-red-500">*</span>
                </label>
                <select
                  id="jenis_kelamin"
                  v-model="form.jenis_kelamin"
                  required
                  class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                >
                  <option value="">Pilih jenis kelamin</option>
                  <option value="L">Laki-laki</option>
                  <option value="P">Perempuan</option>
                </select>
                <div v-if="errors.jenis_kelamin" class="text-red-500 text-sm mt-1">
                  {{ errors.jenis_kelamin }}
                </div>
              </div>

              <div>
                <label for="tempat_lahir" class="block text-sm font-medium text-gray-700 mb-2">
                  Tempat Lahir <span class="text-red-500">*</span>
                </label>
                <input
                  type="text"
                  id="tempat_lahir"
                  v-model="form.tempat_lahir"
                  required
                  class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                  placeholder="Kota tempat lahir"
                >
                <div v-if="errors.tempat_lahir" class="text-red-500 text-sm mt-1">
                  {{ errors.tempat_lahir }}
                </div>
              </div>

              <div>
                <label for="tanggal_lahir" class="block text-sm font-medium text-gray-700 mb-2">
                  Tanggal Lahir <span class="text-red-500">*</span>
                </label>
                <input
                  type="date"
                  id="tanggal_lahir"
                  v-model="form.tanggal_lahir"
                  required
                  class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                >
                <div v-if="errors.tanggal_lahir" class="text-red-500 text-sm mt-1">
                  {{ errors.tanggal_lahir }}
                </div>
              </div>

              <div>
                <label for="no_ktp" class="block text-sm font-medium text-gray-700 mb-2">
                  Nomor KTP <span class="text-red-500">*</span>
                </label>
                <input
                  type="text"
                  id="no_ktp"
                  v-model="form.no_ktp"
                  required
                  maxlength="16"
                  class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                  placeholder="16 digit nomor KTP"
                >
                <div v-if="errors.no_ktp" class="text-red-500 text-sm mt-1">
                  {{ errors.no_ktp }}
                </div>
              </div>

              <div>
                <label for="pekerjaan" class="block text-sm font-medium text-gray-700 mb-2">
                  Pekerjaan <span class="text-red-500">*</span>
                </label>
                <input
                  type="text"
                  id="pekerjaan"
                  v-model="form.pekerjaan"
                  required
                  class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                  placeholder="Pekerjaan saat ini"
                >
                <div v-if="errors.pekerjaan" class="text-red-500 text-sm mt-1">
                  {{ errors.pekerjaan }}
                </div>
              </div>
            </div>

            <div>
              <label for="alamat" class="block text-sm font-medium text-gray-700 mb-2">
                Alamat Lengkap <span class="text-red-500">*</span>
              </label>
              <textarea
                id="alamat"
                v-model="form.alamat"
                required
                rows="3"
                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                placeholder="Alamat lengkap sesuai KTP"
              ></textarea>
              <div v-if="errors.alamat" class="text-red-500 text-sm mt-1">
                {{ errors.alamat }}
              </div>
            </div>
          </div>

          <!-- Contact Information -->
          <div class="space-y-6">
            <div class="border-b border-gray-200 pb-4">
              <h2 class="text-xl font-semibold text-gray-900">Informasi Kontak</h2>
              <p class="text-sm text-gray-500 mt-1">Cara kami menghubungi Anda</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
              <div>
                <label for="email" class="block text-sm font-medium text-gray-700 mb-2">
                  Email <span class="text-red-500">*</span>
                </label>
                <input
                  type="email"
                  id="email"
                  v-model="form.email"
                  required
                  class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                  placeholder="email@contoh.com"
                >
                <div v-if="errors.email" class="text-red-500 text-sm mt-1">
                  {{ errors.email }}
                </div>
              </div>

              <div>
                <label for="no_telepon" class="block text-sm font-medium text-gray-700 mb-2">
                  Nomor Telepon <span class="text-red-500">*</span>
                </label>
                <input
                  type="tel"
                  id="no_telepon"
                  v-model="form.no_telepon"
                  required
                  class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                  placeholder="08xxxxxxxxxx"
                >
                <div v-if="errors.no_telepon" class="text-red-500 text-sm mt-1">
                  {{ errors.no_telepon }}
                </div>
              </div>

              <div>
                <label for="no_darurat" class="block text-sm font-medium text-gray-700 mb-2">
                  Kontak Darurat
                </label>
                <input
                  type="tel"
                  id="no_darurat"
                  v-model="form.no_darurat"
                  class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                  placeholder="Nomor keluarga yang bisa dihubungi"
                >
              </div>

              <div>
                <label for="hubungan_darurat" class="block text-sm font-medium text-gray-700 mb-2">
                  Hubungan Kontak Darurat
                </label>
                <select
                  id="hubungan_darurat"
                  v-model="form.hubungan_darurat"
                  class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                >
                  <option value="">Pilih hubungan</option>
                  <option value="Orang Tua">Orang Tua</option>
                  <option value="Saudara">Saudara</option>
                  <option value="Pasangan">Pasangan</option>
                  <option value="Anak">Anak</option>
                  <option value="Kerabat">Kerabat</option>
                  <option value="Teman">Teman</option>
                </select>
              </div>
            </div>
          </div>

          <!-- Package Selection -->
          <div class="space-y-6">
            <div class="border-b border-gray-200 pb-4">
              <h2 class="text-xl font-semibold text-gray-900">Pilihan Paket</h2>
              <p class="text-sm text-gray-500 mt-1">Pilih paket umroh yang Anda inginkan</p>
            </div>

            <div>
              <label for="paket_id" class="block text-sm font-medium text-gray-700 mb-2">
                Paket Umroh <span class="text-red-500">*</span>
              </label>
              <select
                id="paket_id"
                v-model="form.paket_id"
                required
                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
              >
                <option value="">Pilih paket umroh</option>
                <option value="1">City Tour Malaysia - Rp 24.5 Juta</option>
                <option value="2">Plus Tha'if - Rp 34.5 Juta</option>
                <option value="3">Plus Tha'if dan Kereta Cepat - Rp 41 Juta</option>
              </select>
              <div v-if="errors.paket_id" class="text-red-500 text-sm mt-1">
                {{ errors.paket_id }}
              </div>
            </div>

            <div>
              <label for="catatan" class="block text-sm font-medium text-gray-700 mb-2">
                Catatan Khusus
              </label>
              <textarea
                id="catatan"
                v-model="form.catatan"
                rows="3"
                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                placeholder="Permintaan khusus, alergi makanan, kondisi kesehatan, dll."
              ></textarea>
            </div>
          </div>

          <!-- Terms and Submit -->
          <div class="space-y-6">
            <div class="flex items-start">
              <input
                type="checkbox"
                id="setuju"
                v-model="form.setuju"
                required
                class="mt-1 h-4 w-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500"
              >
              <label for="setuju" class="ml-2 text-sm text-gray-700">
                Saya setuju dengan <a href="#" class="text-blue-600 hover:text-blue-800">syarat dan ketentuan</a>
                yang berlaku dan menyatakan bahwa data yang saya berikan adalah benar dan dapat dipertanggungjawabkan.
                <span class="text-red-500">*</span>
              </label>
            </div>
            <div v-if="errors.setuju" class="text-red-500 text-sm">
              {{ errors.setuju }}
            </div>

            <div class="flex flex-col sm:flex-row gap-4 pt-6">
              <button
                type="button"
                @click="resetForm"
                class="flex-1 bg-gray-100 hover:bg-gray-200 text-gray-700 font-semibold py-3 px-6 rounded-lg transition-colors duration-200"
              >
                Reset Form
              </button>
              <button
                type="submit"
                :disabled="loading"
                class="flex-1 bg-blue-600 hover:bg-blue-700 disabled:bg-blue-400 text-white font-semibold py-3 px-6 rounded-lg transition-colors duration-200"
              >
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
          <a
            href="https://wa.me/6281234567890"
            target="_blank"
            class="bg-yellow-500 hover:bg-yellow-600 text-slate-900 px-4 py-2 rounded-lg font-medium transition-colors duration-200 text-center"
          >
            Chat WhatsApp
          </a>
          <a
            href="tel:0761-12345"
            class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg font-medium transition-colors duration-200 text-center"
          >
            Telepon Langsung
          </a>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, reactive } from 'vue'
import { Head, router } from '@inertiajs/vue3'
import ProgressBar from '@/Components/Jamaah/ProgressBar.vue'

const loading = ref(false)
const errors = ref({})

const form = reactive({
  nama_lengkap: '',
  jenis_kelamin: '',
  tempat_lahir: '',
  tanggal_lahir: '',
  no_ktp: '',
  pekerjaan: '',
  alamat: '',
  email: '',
  no_telepon: '',
  no_darurat: '',
  hubungan_darurat: '',
  paket_id: '',
  catatan: '',
  setuju: false
})

const submitForm = async () => {
  try {
    loading.value = true
    errors.value = {}

    // Validasi form
    if (!validateForm()) {
      loading.value = false
      return
    }

    // Simulasi pengiriman data (ganti dengan router.post untuk integrasi Laravel)
    await new Promise(resolve => setTimeout(resolve, 2000))

    // router.post('/jamaah/daftar', form, {
    //   onSuccess: () => {
    //     alert('Pendaftaran berhasil! Tim kami akan menghubungi Anda segera.')
    //     resetForm()
    //   },
    //   onError: (err) => {
    //     errors.value = err
    //   }
    // })

    // Submit ke backend untuk menyimpan data jamaah
    router.post('/jamaah/daftar', form, {
      onSuccess: () => {
        alert('Pendaftaran berhasil! Anda akan diarahkan ke dashboard.')
        router.visit('/jamaah/dashboard')
      },
      onError: (err) => {
        errors.value = err
      }
    })

  } catch (error) {
    console.error('Error submitting form:', error)
    alert('Terjadi kesalahan. Silakan coba lagi.')
  } finally {
    loading.value = false
  }
}

const validateForm = () => {
  const newErrors = {}

  if (!form.nama_lengkap.trim()) {
    newErrors.nama_lengkap = 'Nama lengkap harus diisi'
  }

  if (!form.jenis_kelamin) {
    newErrors.jenis_kelamin = 'Jenis kelamin harus dipilih'
  }

  if (!form.tempat_lahir.trim()) {
    newErrors.tempat_lahir = 'Tempat lahir harus diisi'
  }

  if (!form.tanggal_lahir) {
    newErrors.tanggal_lahir = 'Tanggal lahir harus diisi'
  }

  if (!form.no_ktp.trim() || form.no_ktp.length !== 16) {
    newErrors.no_ktp = 'Nomor KTP harus 16 digit'
  }

  if (!form.pekerjaan.trim()) {
    newErrors.pekerjaan = 'Pekerjaan harus diisi'
  }

  if (!form.alamat.trim()) {
    newErrors.alamat = 'Alamat harus diisi'
  }

  if (!form.email.trim() || !isValidEmail(form.email)) {
    newErrors.email = 'Email harus valid'
  }

  if (!form.no_telepon.trim()) {
    newErrors.no_telepon = 'Nomor telepon harus diisi'
  }

  if (!form.paket_id) {
    newErrors.paket_id = 'Paket umroh harus dipilih'
  }

  if (!form.setuju) {
    newErrors.setuju = 'Anda harus menyetujui syarat dan ketentuan'
  }

  errors.value = newErrors
  return Object.keys(newErrors).length === 0
}

const isValidEmail = (email) => {
  const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/
  return emailRegex.test(email)
}

const resetForm = () => {
  Object.keys(form).forEach(key => {
    if (typeof form[key] === 'boolean') {
      form[key] = false
    } else {
      form[key] = ''
    }
  })
  errors.value = {}
}

const handleStepAction = (action) => {
  switch (action) {
    case 'continue-registration':
      // Focus on first input field
      document.getElementById('nama_lengkap')?.focus()
      break
    default:
      console.log('Step action:', action)
  }
}
</script>
