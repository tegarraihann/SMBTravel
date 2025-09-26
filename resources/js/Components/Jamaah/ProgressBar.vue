<template>
  <div class="w-full bg-white rounded-lg border border-gray-200 p-6 mb-8">
    <div class="flex items-center justify-between mb-6">
      <h3 class="text-lg font-semibold text-gray-900">Status Pendaftaran</h3>
      <span class="text-sm text-gray-500">
        Step {{ currentStep }} dari {{ totalSteps }}
      </span>
    </div>

    <!-- Progress Bar -->
    <div class="relative">
      <!-- Background Line -->
      <div class="absolute top-5 left-0 w-full h-0.5 bg-gray-200"></div>

      <!-- Progress Line -->
      <div
        class="absolute top-5 left-0 h-0.5 bg-blue-600 transition-all duration-500 ease-in-out"
        :style="{ width: progressPercentage + '%' }"
      ></div>

      <!-- Steps -->
      <div class="relative flex justify-between">
        <div
          v-for="(step, index) in steps"
          :key="step.id"
          class="flex flex-col items-center"
          :class="getStepClass(step.id)"
        >
          <!-- Step Circle -->
          <div
            class="w-10 h-10 rounded-full border-2 flex items-center justify-center bg-white transition-all duration-300"
            :class="getCircleClass(step.id)"
          >
            <!-- Check Icon for Completed -->
            <svg
              v-if="step.id < currentStep"
              class="w-5 h-5 text-white"
              fill="currentColor"
              viewBox="0 0 20 20"
            >
              <path
                fill-rule="evenodd"
                d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                clip-rule="evenodd"
              />
            </svg>

            <!-- Step Number for Current/Future -->
            <span
              v-else
              class="text-sm font-semibold"
              :class="step.id === currentStep ? 'text-white' : 'text-gray-400'"
            >
              {{ step.id }}
            </span>
          </div>

          <!-- Step Label -->
          <div class="mt-3 text-center">
            <p
              class="text-sm font-medium"
              :class="step.id <= currentStep ? 'text-gray-900' : 'text-gray-400'"
            >
              {{ step.title }}
            </p>
            <p
              class="text-xs mt-1"
              :class="step.id <= currentStep ? 'text-gray-600' : 'text-gray-400'"
            >
              {{ step.subtitle }}
            </p>
          </div>

          <!-- Status Badge -->
          <div v-if="step.status" class="mt-2">
            <span
              class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium"
              :class="getStatusClass(step.status)"
            >
              {{ getStatusText(step.status) }}
            </span>
          </div>
        </div>
      </div>
    </div>

    <!-- Current Step Description -->
    <div v-if="currentStepData" class="mt-8 p-4 bg-blue-50 border border-blue-200 rounded-lg">
      <h4 class="text-sm font-semibold text-blue-900 mb-2">
        {{ currentStepData.title }}
      </h4>
      <p class="text-sm text-blue-700 mb-3">
        {{ currentStepData.description }}
      </p>

      <!-- Step 3 Sub-steps -->
      <div v-if="currentStep === 3" class="mb-4">
        <div class="bg-white rounded-lg border border-blue-200 p-4">
          <h5 class="text-sm font-semibold text-gray-900 mb-3">Progress Sub-steps:</h5>

          <!-- Sub-step A: Verifikasi -->
          <div class="flex items-center justify-between py-2 border-b border-gray-100">
            <div class="flex items-center">
              <div class="w-6 h-6 rounded-full mr-3 flex items-center justify-center text-xs font-semibold"
                   :class="getVerificationStatusClass()">
                {{ getVerificationStatus() === 'completed' ? '✓' : 'A' }}
              </div>
              <div>
                <p class="text-sm font-medium text-gray-900">Verifikasi Dokumen & Pembayaran</p>
                <p class="text-xs text-gray-500">{{ getVerificationStatusText() }}</p>
              </div>
            </div>
            <span class="px-2 py-1 text-xs rounded-full"
                  :class="getStatusBadgeClass(getVerificationStatus())">
              {{ getStatusBadgeText(getVerificationStatus()) }}
            </span>
          </div>

          <!-- Sub-step B: Pelunasan -->
          <div class="flex items-center justify-between py-2 border-b border-gray-100">
            <div class="flex items-center">
              <div class="w-6 h-6 rounded-full mr-3 flex items-center justify-center text-xs font-semibold"
                   :class="getPaymentStatusClass()">
                {{ getPaymentStatus() === 'completed' ? '✓' : 'B' }}
              </div>
              <div>
                <p class="text-sm font-medium text-gray-900">Status Pelunasan</p>
                <p class="text-xs text-gray-500">{{ getPaymentStatusText() }}</p>
              </div>
            </div>
            <span class="px-2 py-1 text-xs rounded-full"
                  :class="getStatusBadgeClass(getPaymentStatus())">
              {{ getStatusBadgeText(getPaymentStatus()) }}
            </span>
          </div>

          <!-- Sub-step C: Status Dokumen -->
          <div class="flex items-center justify-between py-2 border-b border-gray-100">
            <div class="flex items-center">
              <div class="w-6 h-6 rounded-full mr-3 flex items-center justify-center text-xs font-semibold"
                   :class="getDocumentStatusClass()">
                {{ getDocumentStatus() === 'completed' ? '✓' : 'C' }}
              </div>
              <div>
                <p class="text-sm font-medium text-gray-900">Status Dokumen</p>
                <p class="text-xs text-gray-500">{{ getDocumentStatusText() }}</p>
              </div>
            </div>
            <span class="px-2 py-1 text-xs rounded-full"
                  :class="getStatusBadgeClass(getDocumentStatus())">
              {{ getStatusBadgeText(getDocumentStatus()) }}
            </span>
          </div>

          <!-- Sub-step D: Manasik -->
          <div class="flex items-center justify-between py-2">
            <div class="flex items-center">
              <div class="w-6 h-6 rounded-full mr-3 flex items-center justify-center text-xs font-semibold"
                   :class="getManasikStatusClass()">
                {{ getManasikStatus() === 'completed' ? '✓' : 'D' }}
              </div>
              <div>
                <p class="text-sm font-medium text-gray-900">Jadwal Manasik</p>
                <p class="text-xs text-gray-500">{{ getManasikStatusText() }}</p>
              </div>
            </div>
            <span class="px-2 py-1 text-xs rounded-full"
                  :class="getStatusBadgeClass(getManasikStatus())">
              {{ getStatusBadgeText(getManasikStatus()) }}
            </span>
          </div>
        </div>
      </div>

      <!-- Action Buttons -->
      <div v-if="currentStepData.actions" class="flex flex-wrap gap-2">
        <button
          v-for="action in currentStepData.actions"
          :key="action.label"
          @click="action.handler"
          class="px-4 py-2 text-sm font-medium rounded-lg transition-colors duration-200"
          :class="action.primary ?
            'bg-blue-600 hover:bg-blue-700 text-white' :
            'bg-white hover:bg-gray-50 text-blue-600 border border-blue-300'"
        >
          {{ action.label }}
        </button>
      </div>
    </div>

    <!-- Next Steps (if any) -->
    <div v-if="nextSteps.length > 0" class="mt-6 p-4 bg-gray-50 border border-gray-200 rounded-lg">
      <h4 class="text-sm font-semibold text-gray-900 mb-2">Langkah Selanjutnya:</h4>
      <ul class="text-sm text-gray-700 space-y-1">
        <li v-for="nextStep in nextSteps" :key="nextStep" class="flex items-start">
          <span class="w-1.5 h-1.5 bg-gray-400 rounded-full mt-2 mr-2 flex-shrink-0"></span>
          {{ nextStep }}
        </li>
      </ul>
    </div>

    <!-- Testing Navigation -->
    <div class="mt-6 p-4 bg-yellow-50 border border-yellow-200 rounded-lg">
      <h4 class="text-sm font-semibold text-yellow-900 mb-3">🧪 Testing Navigation:</h4>
      <div class="flex flex-wrap gap-2">
        <button
          v-for="step in availableSteps"
          :key="step.id"
          @click="navigateToStep(step.id)"
          class="px-3 py-2 text-xs font-medium rounded-md transition-colors duration-200"
          :class="step.id === currentStep ?
            'bg-yellow-600 text-white' :
            'bg-white hover:bg-yellow-100 text-yellow-700 border border-yellow-300'"
        >
          Step {{ step.displayNumber }}: {{ step.title }}
        </button>
      </div>
      <p class="text-xs text-yellow-700 mt-2">
        ⚠️ Untuk testing saja - navigasi ini akan dihapus di production
      </p>
    </div>
  </div>
</template>

<script setup>
import { computed } from 'vue'

const props = defineProps({
  currentStep: {
    type: Number,
    default: 1
  },
  jamaahData: {
    type: Object,
    default: () => ({})
  }
})

const emit = defineEmits(['step-action', 'navigate-to-step'])

const totalSteps = 4

const steps = [
  {
    id: 1,
    title: 'Pendaftaran & Dokumen',
    subtitle: 'Data Pribadi + Upload',
    status: props.currentStep > 1 ? 'completed' : (props.currentStep === 1 ? 'current' : 'pending')
  },
  {
    id: 2,
    title: 'Pembayaran DP',
    subtitle: 'Down Payment',
    status: props.currentStep > 2 ? 'completed' : (props.currentStep === 2 ? 'current' : 'pending')
  },
  {
    id: 3,
    title: 'Pelunasan & Manasik',
    subtitle: 'Pembayaran & Persiapan',
    status: props.currentStep > 3 ? 'completed' : (props.currentStep === 3 ? 'current' : 'pending')
  },
  {
    id: 4,
    title: 'Keberangkatan',
    subtitle: 'Siap Berangkat',
    status: props.currentStep >= 4 ? 'completed' : 'pending'
  }
]

const progressPercentage = computed(() => {
  // Map steps to visual progress: step 1=0%, step 2=25%, step 3=50%, step 4=75%, step 5=100%
  const stepMap = { 1: 0, 2: 1, 3: 2, 4: 3 }
  const currentStepIndex = stepMap[props.currentStep] || 0
  return (currentStepIndex / (totalSteps - 1)) * 100
})

const currentStepData = computed(() => {
  const stepInfo = {
    1: {
      title: 'Lengkapi Data Pendaftaran & Upload Dokumen',
      description: 'Silakan lengkapi data pribadi, pilih paket umroh, dan upload dokumen persyaratan (KTP, KK, Paspor, Foto).',
      actions: [
        {
          label: 'Lanjutkan Pendaftaran',
          primary: true,
          handler: () => emit('step-action', 'continue-registration')
        }
      ]
    },
    2: {
      title: 'Pembayaran Down Payment',
      description: 'Lakukan pembayaran DP minimal Rp 1.000.000 untuk mengkonfirmasi tempat Anda.',
      actions: [
        {
          label: 'Bayar Sekarang',
          primary: true,
          handler: () => emit('step-action', 'pay-dp')
        },
        {
          label: 'Lihat Detail Pembayaran',
          primary: false,
          handler: () => emit('step-action', 'view-payment-details')
        }
      ]
    },
    3: {
      title: 'Pelunasan & Manasik',
      description: 'Tahap akhir persiapan sebelum keberangkatan dengan verifikasi, pelunasan, dan manasik.',
      actions: [
        {
          label: 'Cek Status Verifikasi',
          primary: false,
          handler: () => emit('step-action', 'check-verification-status')
        },
        {
          label: 'Bayar Pelunasan',
          primary: true,
          handler: () => emit('step-action', 'pay-remaining-balance')
        },
        {
          label: 'Jadwal Manasik',
          primary: false,
          handler: () => emit('step-action', 'view-manasik-schedule')
        },
        {
          label: 'Cek Dokumen Keberangkatan',
          primary: false,
          handler: () => emit('step-action', 'check-departure-documents')
        }
      ]
    },
    4: {
      title: 'Siap Berangkat',
      description: 'Selamat! Anda sudah siap untuk berangkat umroh. Semoga Allah mudahkan perjalanan Anda.',
      actions: [
        {
          label: 'Lihat Itinerary',
          primary: true,
          handler: () => emit('step-action', 'view-itinerary')
        }
      ]
    }
  }

  return stepInfo[props.currentStep] || null
})

const nextSteps = computed(() => {
  const nextStepsInfo = {
    1: [
      'Pastikan semua data pribadi sudah benar',
      'Upload semua dokumen yang diperlukan',
      'Setelah mendaftar, lakukan pembayaran DP minimal Rp 1.000.000'
    ],
    2: [
      'Upload bukti pembayaran DP',
      'Tunggu verifikasi dari admin',
      'Lanjutkan ke pelunasan pembayaran'
    ],
    3: [
      'Lunasi sisa pembayaran paket umroh',
      'Ikuti sesi manasik umroh',
      'Persiapan dokumen keberangkatan'
    ],
    4: []
  }

  return nextStepsInfo[props.currentStep] || []
})

const getStepClass = (stepId) => {
  return {
    'opacity-50': stepId > props.currentStep
  }
}

const getCircleClass = (stepId) => {
  if (stepId < props.currentStep) {
    return 'border-blue-600 bg-blue-600'
  } else if (stepId === props.currentStep) {
    return 'border-blue-600 bg-blue-600'
  } else {
    return 'border-gray-300'
  }
}

const getStatusClass = (status) => {
  const classes = {
    completed: 'bg-green-100 text-green-800',
    current: 'bg-blue-100 text-blue-800',
    pending: 'bg-gray-100 text-gray-600',
    waiting: 'bg-yellow-100 text-yellow-800',
    rejected: 'bg-red-100 text-red-800'
  }
  return classes[status] || classes.pending
}

const getStatusText = (status) => {
  const texts = {
    completed: 'Selesai',
    current: 'Aktif',
    pending: 'Menunggu',
    waiting: 'Proses',
    rejected: 'Ditolak'
  }
  return texts[status] || 'Menunggu'
}

const availableSteps = computed(() => {
  return [
    { id: 1, displayNumber: 1, title: 'Pendaftaran & Dokumen' },
    { id: 2, displayNumber: 2, title: 'Pembayaran DP' },
    { id: 3, displayNumber: 3, title: 'Pelunasan & Manasik' },
    { id: 4, displayNumber: 4, title: 'Keberangkatan' }
  ]
})

const navigateToStep = (stepId) => {
  emit('navigate-to-step', stepId)
}

// Step 3 Sub-steps Status Methods
const getVerificationStatus = () => {
  // Check dokumen verified, CS approval, dan DP payment approved
  const documentsVerified = props.jamaahData.documents_verified || false
  const dataApprovedByCS = props.jamaahData.data_approved_by_cs || false
  const paymentApproved = props.jamaahData.payment_approved_by_admin || false

  if (documentsVerified && dataApprovedByCS && paymentApproved) {
    return 'completed'
  } else if (documentsVerified || dataApprovedByCS || paymentApproved) {
    return 'partial'
  } else {
    return 'pending'
  }
}

const getPaymentStatus = () => {
  // Periksa apakah jamaah menggunakan program talangan atau tidak
  const isProgramTalangan = props.jamaahData.is_program_talangan || false
  const paymentComplete = props.jamaahData.is_payment_complete || false

  if (isProgramTalangan) {
    // Untuk program talangan: cek apakah semua cicilan sudah lunas
    const hasOutstanding = (props.jamaahData.total_outstanding || 0) > 0

    if (paymentComplete && !hasOutstanding) {
      return 'completed'
    } else {
      return 'pending'
    }
  } else {
    // Untuk non-talangan: cek apakah DP sudah disetujui admin dan data disetujui CS
    const paymentApproved = props.jamaahData.payment_approved_by_admin || false
    const dataApproved = props.jamaahData.data_approved_by_cs || false

    if (paymentApproved && dataApproved) {
      return 'completed'
    } else {
      return 'pending'
    }
  }
}

const getDocumentStatus = () => {
  // Check document completion status
  const documentsVerified = props.jamaahData.documents_verified || false
  const dataApprovedByCS = props.jamaahData.data_approved_by_cs || false

  if (documentsVerified && dataApprovedByCS) {
    return 'completed'
  } else if (documentsVerified || dataApprovedByCS) {
    return 'partial'
  } else {
    return 'pending'
  }
}

const getManasikStatus = () => {
  // Manasik available after verification and payment complete
  const verificationComplete = getVerificationStatus() === 'completed'
  const paymentComplete = getPaymentStatus() === 'completed'
  const documentComplete = getDocumentStatus() === 'completed'

  if (verificationComplete && paymentComplete && documentComplete) {
    return 'available'
  } else {
    return 'locked'
  }
}

const getVerificationStatusClass = () => {
  const status = getVerificationStatus()
  return {
    'bg-green-500 text-white': status === 'completed',
    'bg-yellow-500 text-white': status === 'partial',
    'bg-gray-300 text-gray-600': status === 'pending'
  }
}

const getPaymentStatusClass = () => {
  const status = getPaymentStatus()
  return {
    'bg-green-500 text-white': status === 'completed',
    'bg-blue-500 text-white': status === 'pending'
  }
}

const getDocumentStatusClass = () => {
  const status = getDocumentStatus()
  return {
    'bg-green-500 text-white': status === 'completed',
    'bg-yellow-500 text-white': status === 'partial',
    'bg-gray-300 text-gray-600': status === 'pending'
  }
}

const getManasikStatusClass = () => {
  const status = getManasikStatus()
  return {
    'bg-purple-500 text-white': status === 'available',
    'bg-gray-300 text-gray-600': status === 'locked'
  }
}

const getVerificationStatusText = () => {
  const status = getVerificationStatus()
  const texts = {
    completed: 'Dokumen telah diverifikasi, data disetujui CS, dan pembayaran DP disetujui admin',
    partial: 'Menunggu verifikasi dokumen, persetujuan CS, atau persetujuan admin',
    pending: 'Menunggu verifikasi dokumen, persetujuan CS, dan verifikasi pembayaran DP'
  }
  return texts[status]
}

const getPaymentStatusText = () => {
  const status = getPaymentStatus()
  const isProgramTalangan = props.jamaahData.is_program_talangan || false
  const isFullPayment = props.jamaahData.is_full_payment_upfront || false

  if (status === 'completed') {
    if (isProgramTalangan) {
      return 'Semua cicilan telah lunas'
    } else if (isFullPayment) {
      return 'Pembayaran lunas 100% (dibayar penuh di awal)'
    } else {
      return 'DP dan pelunasan telah dibayar dan disetujui'
    }
  } else {
    if (isProgramTalangan) {
      return 'Masih ada cicilan yang belum dibayar'
    } else if (isFullPayment) {
      return 'Menunggu persetujuan pembayaran penuh'
    } else {
      return 'Menunggu pelunasan setelah DP disetujui'
    }
  }
}

const getDocumentStatusText = () => {
  const status = getDocumentStatus()
  const texts = {
    completed: 'Dokumen telah diverifikasi dan disetujui',
    partial: 'Menunggu verifikasi dokumen atau persetujuan CS',
    pending: 'Menunggu verifikasi dokumen dan persetujuan CS'
  }
  return texts[status]
}

const getManasikStatusText = () => {
  const status = getManasikStatus()
  const texts = {
    available: 'Siap mengikuti jadwal manasik',
    locked: 'Tersedia setelah verifikasi, dokumen, dan pelunasan selesai'
  }
  return texts[status]
}

const getStatusBadgeClass = (status) => {
  const classes = {
    completed: 'bg-green-100 text-green-800',
    partial: 'bg-yellow-100 text-yellow-800',
    pending: 'bg-gray-100 text-gray-600',
    available: 'bg-purple-100 text-purple-800',
    locked: 'bg-gray-100 text-gray-600'
  }
  return classes[status] || 'bg-gray-100 text-gray-600'
}

const getStatusBadgeText = (status) => {
  const texts = {
    completed: 'Selesai',
    partial: 'Sebagian',
    pending: 'Menunggu',
    available: 'Tersedia',
    locked: 'Terkunci'
  }
  return texts[status] || 'Menunggu'
}
</script>
