<template>
  <Head :title="`Process Jamaah - ${jamaah?.nama_lengkap_bin_binti || 'Unknown'}`" />

  <AdminLayout>
    <template #header>
      <div class="flex items-center justify-between">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
          Process Jamaah - {{ jamaah?.nama_lengkap_bin_binti || 'Unknown' }}
        </h2>
        <Link :href="route('operasional.jamaah.index')" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-md text-sm">
          Back to List
        </Link>
      </div>
    </template>

    <div class="py-12">
      <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <!-- Jamaah Info -->
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
          <div class="p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Jamaah Information</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
              <div>
                <div class="mb-4">
                  <label class="block text-sm font-medium text-gray-700">Nama Lengkap</label>
                  <p class="mt-1 text-sm text-gray-900">{{ jamaah.nama_lengkap_bin_binti }}</p>
                </div>
                <div class="mb-4">
                  <label class="block text-sm font-medium text-gray-700">Email</label>
                  <p class="mt-1 text-sm text-gray-900">{{ jamaah.user?.email }}</p>
                </div>
                <div class="mb-4">
                  <label class="block text-sm font-medium text-gray-700">No Telepon</label>
                  <p class="mt-1 text-sm text-gray-900">{{ jamaah.no_telepon }}</p>
                </div>
                <div class="mb-4">
                  <label class="block text-sm font-medium text-gray-700">NIK</label>
                  <p class="mt-1 text-sm text-gray-900">{{ jamaah.nik }}</p>
                </div>
              </div>
              <div>
                <div class="mb-4">
                  <label class="block text-sm font-medium text-gray-700">Paket Umroh</label>
                  <p class="mt-1 text-sm text-gray-900">{{ jamaah.umroh_package?.nama_paket }}</p>
                  <p class="text-xs text-gray-500">{{ formatCurrency(jamaah.umroh_package?.harga) }}</p>
                </div>
                <div class="mb-4">
                  <label class="block text-sm font-medium text-gray-700">Rencana Keberangkatan</label>
                  <p class="mt-1 text-sm text-gray-900">{{ formatDate(jamaah.rencana_keberangkatan) }}</p>
                </div>
                <div class="mb-4">
                  <label class="block text-sm font-medium text-gray-700">Status Pembayaran</label>
                  <span :class="jamaah.payment_approved_by_admin ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800'"
                        class="inline-flex px-2 py-1 text-xs font-semibold rounded-full">
                    {{ jamaah.payment_approved_by_admin ? 'Approved' : 'Pending' }}
                  </span>
                </div>
                <div class="mb-4">
                  <label class="block text-sm font-medium text-gray-700">Status Data CS</label>
                  <span :class="jamaah.data_approved_by_cs ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800'"
                        class="inline-flex px-2 py-1 text-xs font-semibold rounded-full">
                    {{ jamaah.data_approved_by_cs ? 'Approved' : 'Pending' }}
                  </span>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- Processing Section -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
          <!-- Ticket Processing -->
          <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6">
              <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-semibold text-gray-900">Ticket Processing</h3>
                <span :class="getStatusBadgeClass(jamaah.ticket_status)"
                      class="px-3 py-1 text-sm font-semibold rounded-full">
                  {{ getStatusText(jamaah.ticket_status) }}
                </span>
              </div>

              <!-- Current Ticket Status -->
              <div class="mb-6">
                <div class="mb-4">
                  <label class="block text-sm font-medium text-gray-700">Current Status</label>
                  <p class="mt-1 text-sm text-gray-900">{{ getStatusText(jamaah.ticket_status) }}</p>
                  <p v-if="jamaah.ticket_processor" class="text-xs text-gray-500">
                    Processed by: {{ jamaah.ticket_processor.name }}
                  </p>
                  <p v-if="jamaah.ticket_processed_at" class="text-xs text-gray-500">
                    {{ formatDateTime(jamaah.ticket_processed_at) }}
                  </p>
                </div>
                <div v-if="jamaah.ticket_notes" class="mb-4">
                  <label class="block text-sm font-medium text-gray-700">Notes</label>
                  <p class="mt-1 text-sm text-gray-900">{{ jamaah.ticket_notes }}</p>
                </div>
              </div>

              <!-- Update Ticket Status -->
              <div class="mb-6">
                <h4 class="text-md font-medium text-gray-900 mb-3">Update Status</h4>
                <form @submit.prevent="updateTicketStatus" class="space-y-4">
                  <div>
                    <label class="block text-sm font-medium text-gray-700">New Status</label>
                    <select v-model="ticketForm.status" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200">
                      <option value="">Select Status</option>
                      <option value="pending">Pending</option>
                      <option value="processing">Processing</option>
                      <option value="completed">Completed</option>
                    </select>
                  </div>
                  <div>
                    <label class="block text-sm font-medium text-gray-700">Notes</label>
                    <textarea v-model="ticketForm.notes" rows="3" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200" placeholder="Add processing notes..."></textarea>
                  </div>
                  <button type="submit" :disabled="ticketForm.processing"
                          class="w-full bg-blue-600 hover:bg-blue-700 disabled:bg-blue-400 text-white px-4 py-2 rounded-md text-sm">
                    {{ ticketForm.processing ? 'Updating...' : 'Update Ticket Status' }}
                  </button>
                </form>
              </div>

              <!-- Upload Ticket Document -->
              <div class="mb-6">
                <h4 class="text-md font-medium text-gray-900 mb-3">Upload Ticket Document</h4>
                <div v-if="jamaah.ticket_file" class="mb-3 p-3 bg-green-50 rounded-md">
                  <div class="flex items-center justify-between">
                    <span class="text-sm text-green-800">Document uploaded</span>
                    <a :href="`/storage/${jamaah.ticket_file}`" target="_blank"
                       class="text-green-600 hover:text-green-800 text-sm underline">
                      View Document
                    </a>
                  </div>
                </div>
                <form @submit.prevent="uploadTicketDocument" class="space-y-4">
                  <div>
                    <label class="block text-sm font-medium text-gray-700">Select File</label>
                    <input type="file" ref="ticketFileInput" @change="handleTicketFileChange"
                           accept=".pdf,.jpg,.jpeg,.png" required
                           class="mt-1 block w-full text-sm text-gray-900 border border-gray-300 rounded-md cursor-pointer bg-gray-50 focus:outline-none">
                    <p class="text-xs text-gray-500 mt-1">PDF, JPG, JPEG, PNG (Max 5MB)</p>
                  </div>
                  <button type="submit" :disabled="ticketUploadForm.processing || !ticketUploadForm.file"
                          class="w-full bg-yellow-600 hover:bg-yellow-700 disabled:bg-yellow-400 text-white px-4 py-2 rounded-md text-sm">
                    {{ ticketUploadForm.processing ? 'Uploading...' : 'Upload Ticket' }}
                  </button>
                </form>
              </div>
            </div>
          </div>

          <!-- Visa Processing -->
          <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6">
              <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-semibold text-gray-900">Visa Processing</h3>
                <span :class="getStatusBadgeClass(jamaah.visa_status)"
                      class="px-3 py-1 text-sm font-semibold rounded-full">
                  {{ getStatusText(jamaah.visa_status) }}
                </span>
              </div>

              <!-- Current Visa Status -->
              <div class="mb-6">
                <div class="mb-4">
                  <label class="block text-sm font-medium text-gray-700">Current Status</label>
                  <p class="mt-1 text-sm text-gray-900">{{ getStatusText(jamaah.visa_status) }}</p>
                  <p v-if="jamaah.visa_processor" class="text-xs text-gray-500">
                    Processed by: {{ jamaah.visa_processor.name }}
                  </p>
                  <p v-if="jamaah.visa_processed_at" class="text-xs text-gray-500">
                    {{ formatDateTime(jamaah.visa_processed_at) }}
                  </p>
                </div>
                <div v-if="jamaah.visa_notes" class="mb-4">
                  <label class="block text-sm font-medium text-gray-700">Notes</label>
                  <p class="mt-1 text-sm text-gray-900">{{ jamaah.visa_notes }}</p>
                </div>
              </div>

              <!-- Update Visa Status -->
              <div class="mb-6">
                <h4 class="text-md font-medium text-gray-900 mb-3">Update Status</h4>
                <form @submit.prevent="updateVisaStatus" class="space-y-4">
                  <div>
                    <label class="block text-sm font-medium text-gray-700">New Status</label>
                    <select v-model="visaForm.status" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200">
                      <option value="">Select Status</option>
                      <option value="pending">Pending</option>
                      <option value="processing">Processing</option>
                      <option value="completed">Completed</option>
                    </select>
                  </div>
                  <div>
                    <label class="block text-sm font-medium text-gray-700">Notes</label>
                    <textarea v-model="visaForm.notes" rows="3" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200" placeholder="Add processing notes..."></textarea>
                  </div>
                  <button type="submit" :disabled="visaForm.processing"
                          class="w-full bg-orange-600 hover:bg-orange-700 disabled:bg-orange-400 text-white px-4 py-2 rounded-md text-sm">
                    {{ visaForm.processing ? 'Updating...' : 'Update Visa Status' }}
                  </button>
                </form>
              </div>

              <!-- Upload Visa Document -->
              <div class="mb-6">
                <h4 class="text-md font-medium text-gray-900 mb-3">Upload Visa Document</h4>
                <div v-if="jamaah.visa_file" class="mb-3 p-3 bg-green-50 rounded-md">
                  <div class="flex items-center justify-between">
                    <span class="text-sm text-green-800">Document uploaded</span>
                    <a :href="`/storage/${jamaah.visa_file}`" target="_blank"
                       class="text-green-600 hover:text-green-800 text-sm underline">
                      View Document
                    </a>
                  </div>
                </div>
                <form @submit.prevent="uploadVisaDocument" class="space-y-4">
                  <div>
                    <label class="block text-sm font-medium text-gray-700">Select File</label>
                    <input type="file" ref="visaFileInput" @change="handleVisaFileChange"
                           accept=".pdf,.jpg,.jpeg,.png" required
                           class="mt-1 block w-full text-sm text-gray-900 border border-gray-300 rounded-md cursor-pointer bg-gray-50 focus:outline-none">
                    <p class="text-xs text-gray-500 mt-1">PDF, JPG, JPEG, PNG (Max 5MB)</p>
                  </div>
                  <button type="submit" :disabled="visaUploadForm.processing || !visaUploadForm.file"
                          class="w-full bg-orange-600 hover:bg-orange-700 disabled:bg-orange-400 text-white px-4 py-2 rounded-md text-sm">
                    {{ visaUploadForm.processing ? 'Uploading...' : 'Upload Visa' }}
                  </button>
                </form>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Success/Error Messages -->
    <div v-if="$page.props.flash?.message" class="fixed top-4 right-4 bg-green-500 text-white px-6 py-3 rounded-md shadow-lg z-50">
      {{ $page.props.flash.message }}
    </div>
    <div v-if="$page.props.flash?.error" class="fixed top-4 right-4 bg-red-500 text-white px-6 py-3 rounded-md shadow-lg z-50">
      {{ $page.props.flash.error }}
    </div>
  </AdminLayout>
</template>

<script setup>
import { ref, reactive } from 'vue'
import { Head, Link, useForm } from '@inertiajs/vue3'
import AdminLayout from '@/Layouts/AdminLayout.vue'

const props = defineProps({
  jamaah: Object
})

// Forms
const ticketForm = useForm({
  status: '',
  notes: '',
  processing: false
})

const visaForm = useForm({
  status: '',
  notes: '',
  processing: false
})

const ticketUploadForm = reactive({
  file: null,
  processing: false
})

const visaUploadForm = reactive({
  file: null,
  processing: false
})

// Refs
const ticketFileInput = ref(null)
const visaFileInput = ref(null)

// Methods
const updateTicketStatus = () => {
  if (!ticketForm.status) return

  ticketForm.processing = true
  ticketForm.patch(route('operasional.jamaah.ticket.status', props.jamaah.id), {
    onSuccess: () => {
      ticketForm.reset('status', 'notes')
      ticketForm.processing = false
    },
    onError: () => {
      ticketForm.processing = false
    }
  })
}

const updateVisaStatus = () => {
  if (!visaForm.status) return

  visaForm.processing = true
  visaForm.patch(route('operasional.jamaah.visa.status', props.jamaah.id), {
    onSuccess: () => {
      visaForm.reset('status', 'notes')
      visaForm.processing = false
    },
    onError: () => {
      visaForm.processing = false
    }
  })
}

const handleTicketFileChange = (event) => {
  const file = event.target.files[0]
  if (file && file.size <= 5 * 1024 * 1024) { // 5MB limit
    ticketUploadForm.file = file
  } else {
    alert('File size must be less than 5MB')
    event.target.value = ''
  }
}

const handleVisaFileChange = (event) => {
  const file = event.target.files[0]
  if (file && file.size <= 5 * 1024 * 1024) { // 5MB limit
    visaUploadForm.file = file
  } else {
    alert('File size must be less than 5MB')
    event.target.value = ''
  }
}

const uploadTicketDocument = () => {
  if (!ticketUploadForm.file) return

  const formData = new FormData()
  formData.append('ticket_file', ticketUploadForm.file)

  ticketUploadForm.processing = true

  fetch(route('operasional.jamaah.ticket.upload', props.jamaah.id), {
    method: 'POST',
    body: formData,
    headers: {
      'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
    }
  })
  .then(response => {
    if (!response.ok) throw new Error('Upload failed')
    return response.json()
  })
  .then(data => {
    if (data.message) {
      alert(data.message)
      ticketUploadForm.file = null
      if (ticketFileInput.value) ticketFileInput.value.value = ''
      location.reload() // Refresh to show updated data
    }
  })
  .catch(error => {
    console.error('Error:', error)
    alert('Upload failed: ' + error.message)
  })
  .finally(() => {
    ticketUploadForm.processing = false
  })
}

const uploadVisaDocument = () => {
  if (!visaUploadForm.file) return

  const formData = new FormData()
  formData.append('visa_file', visaUploadForm.file)

  visaUploadForm.processing = true

  fetch(route('operasional.jamaah.visa.upload', props.jamaah.id), {
    method: 'POST',
    body: formData,
    headers: {
      'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
    }
  })
  .then(response => {
    if (!response.ok) throw new Error('Upload failed')
    return response.json()
  })
  .then(data => {
    if (data.message) {
      alert(data.message)
      visaUploadForm.file = null
      if (visaFileInput.value) visaFileInput.value.value = ''
      location.reload() // Refresh to show updated data
    }
  })
  .catch(error => {
    console.error('Error:', error)
    alert('Upload failed: ' + error.message)
  })
  .finally(() => {
    visaUploadForm.processing = false
  })
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
  if (!dateString) return '-'
  return new Date(dateString).toLocaleDateString('id-ID', {
    year: 'numeric',
    month: 'long',
    day: 'numeric'
  })
}

const formatDateTime = (dateString) => {
  if (!dateString) return '-'
  return new Date(dateString).toLocaleString('id-ID', {
    year: 'numeric',
    month: 'short',
    day: 'numeric',
    hour: '2-digit',
    minute: '2-digit'
  })
}
</script>