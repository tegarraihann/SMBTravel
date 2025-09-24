<template>
  <div class="space-y-3">
    <label class="block text-sm font-medium text-gray-700">
      {{ label }}
    </label>

    <!-- Existing Document Display -->
    <div v-if="existing && existing.path" class="bg-green-50 border border-green-200 rounded-lg p-3">
      <div class="flex items-center justify-between">
        <div class="flex items-center">
          <svg class="w-5 h-5 text-green-600 mr-2" fill="currentColor" viewBox="0 0 20 20">
            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
          </svg>
          <span class="text-sm text-green-700 font-medium">Dokumen sudah diupload</span>
        </div>
        <button
          @click="viewDocument"
          class="text-blue-600 hover:text-blue-800 text-sm"
        >
          Lihat
        </button>
      </div>
      <div class="mt-2 text-xs text-green-600">
        {{ existing.uploaded_at ? formatDate(existing.uploaded_at) : 'Uploaded' }}
      </div>
    </div>

    <!-- Upload Area -->
    <div class="border-2 border-dashed border-gray-300 rounded-lg p-6 hover:border-gray-400 transition-colors">
      <div class="text-center">
        <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48">
          <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
        </svg>

        <div class="mt-4">
          <label class="cursor-pointer">
            <span class="mt-2 block text-sm font-medium text-gray-900">
              {{ existing && existing.path ? 'Upload ulang dokumen' : 'Upload dokumen' }}
            </span>
            <input
              ref="fileInput"
              type="file"
              class="sr-only"
              accept="image/*,.pdf"
              @change="handleFileSelect"
            >
            <span class="mt-1 block text-xs text-gray-500">
              PNG, JPG, JPEG, PDF hingga 5MB
            </span>
          </label>
        </div>
      </div>
    </div>

    <!-- Selected File Display -->
    <div v-if="selectedFile" class="bg-blue-50 border border-blue-200 rounded-md p-3">
      <div class="flex items-center justify-between">
        <div class="flex items-center">
          <svg class="w-5 h-5 text-blue-600 mr-2" fill="currentColor" viewBox="0 0 20 20">
            <path fill-rule="evenodd" d="M3 17a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm3.293-7.707a1 1 0 011.414 0L9 10.586V3a1 1 0 112 0v7.586l1.293-1.293a1 1 0 111.414 1.414l-3 3a1 1 0 01-1.414 0l-3-3a1 1 0 010-1.414z" clip-rule="evenodd"/>
          </svg>
          <div>
            <p class="text-sm font-medium text-blue-700">{{ selectedFile.name }}</p>
            <p class="text-xs text-blue-600">{{ formatFileSize(selectedFile.size) }}</p>
          </div>
        </div>
        <div class="flex space-x-2">
          <button
            @click="uploadFile"
            :disabled="uploading"
            class="px-3 py-1 bg-blue-600 text-white text-xs rounded hover:bg-blue-700 disabled:opacity-50"
          >
            {{ uploading ? 'Uploading...' : 'Upload' }}
          </button>
          <button
            @click="clearSelection"
            class="px-3 py-1 bg-gray-500 text-white text-xs rounded hover:bg-gray-600"
          >
            Batal
          </button>
        </div>
      </div>
    </div>
  </div>

  <!-- Document Viewer Modal -->
  <div v-if="showViewer" class="fixed inset-0 z-50 overflow-y-auto">
    <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
      <div class="fixed inset-0 transition-opacity" @click="closeViewer">
        <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
      </div>

      <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-3xl sm:w-full">
        <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
          <div class="sm:flex sm:items-start">
            <div class="mt-3 text-center sm:mt-0 sm:text-left w-full">
              <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">
                {{ label }}
              </h3>
              <div class="mt-2">
                <img
                  v-if="existing && existing.path && isImage(existing.path)"
                  :src="`/storage/${existing.path}`"
                  :alt="label"
                  class="max-w-full h-auto rounded-lg"
                >
                <div v-else-if="existing && existing.path" class="text-center py-8">
                  <svg class="mx-auto h-16 w-16 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4zm2 6a1 1 0 011-1h6a1 1 0 110 2H7a1 1 0 01-1-1zm1 3a1 1 0 100 2h6a1 1 0 100-2H7z" clip-rule="evenodd"/>
                  </svg>
                  <p class="mt-2 text-sm text-gray-600">{{ label }}</p>
                  <a
                    :href="`/storage/${existing.path}`"
                    target="_blank"
                    class="mt-2 inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-blue-700 bg-blue-100 hover:bg-blue-200"
                  >
                    Download & Lihat File
                  </a>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
          <button
            type="button"
            @click="closeViewer"
            class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-blue-600 text-base font-medium text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:ml-3 sm:w-auto sm:text-sm"
          >
            Tutup
          </button>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref } from 'vue'

const props = defineProps({
  modelValue: {
    type: File,
    default: null
  },
  name: {
    type: String,
    required: true
  },
  label: {
    type: String,
    required: true
  },
  existing: {
    type: Object,
    default: null
  }
})

const emit = defineEmits(['update:modelValue', 'upload'])

const selectedFile = ref(null)
const uploading = ref(false)
const showViewer = ref(false)
const fileInput = ref(null)

// Methods
const formatDate = (dateString) => {
  if (!dateString) return ''
  const date = new Date(dateString)
  return date.toLocaleDateString('id-ID', {
    day: 'numeric',
    month: 'short',
    year: 'numeric'
  })
}

const formatFileSize = (bytes) => {
  if (bytes === 0) return '0 Bytes'
  const k = 1024
  const sizes = ['Bytes', 'KB', 'MB', 'GB']
  const i = Math.floor(Math.log(bytes) / Math.log(k))
  return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i]
}

const isImage = (filename) => {
  return /\.(jpg|jpeg|png|gif|bmp|webp)$/i.test(filename)
}

const handleFileSelect = (event) => {
  const file = event.target.files[0]
  if (file) {
    // Validate file
    if (file.size > 5 * 1024 * 1024) { // 5MB
      alert('File terlalu besar. Maksimal 5MB.')
      return
    }

    const allowedTypes = ['image/jpeg', 'image/jpg', 'image/png', 'application/pdf']
    if (!allowedTypes.includes(file.type)) {
      alert('Format file tidak didukung. Gunakan JPG, PNG, atau PDF.')
      return
    }

    selectedFile.value = file
    emit('update:modelValue', file)
  }
}

const uploadFile = async () => {
  if (!selectedFile.value) return

  uploading.value = true
  try {
    await emit('upload', props.name, selectedFile.value)
    selectedFile.value = null
    if (fileInput.value) {
      fileInput.value.value = ''
    }
  } catch (error) {
    console.error('Upload failed:', error)
  } finally {
    uploading.value = false
  }
}

const clearSelection = () => {
  selectedFile.value = null
  emit('update:modelValue', null)
  if (fileInput.value) {
    fileInput.value.value = ''
  }
}

const viewDocument = () => {
  showViewer.value = true
}

const closeViewer = () => {
  showViewer.value = false
}
</script>