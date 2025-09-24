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
              {{ step.id === 4 ? 3 : (step.id === 5 ? 4 : step.id) }}
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

const emit = defineEmits(['step-action'])

const totalSteps = 4

const steps = [
  {
    id: 1,
    title: 'Pendaftaran',
    subtitle: 'Data Pribadi',
    status: props.currentStep > 1 ? 'completed' : (props.currentStep === 1 ? 'current' : 'pending')
  },
  {
    id: 2,
    title: 'Pembayaran DP',
    subtitle: 'Down Payment',
    status: props.currentStep > 2 ? 'completed' : (props.currentStep === 2 ? 'current' : 'pending')
  },
  {
    id: 4,
    title: 'Persiapan',
    subtitle: 'Dokumen & Manasik',
    status: props.currentStep > 4 ? 'completed' : (props.currentStep === 4 ? 'current' : 'pending')
  },
  {
    id: 5,
    title: 'Keberangkatan',
    subtitle: 'Siap Berangkat',
    status: props.currentStep >= 5 ? 'completed' : 'pending'
  }
]

const progressPercentage = computed(() => {
  // Map steps to visual progress: step 1=0%, step 2=33%, step 4=66%, step 5=100%
  const stepMap = { 1: 0, 2: 1, 4: 2, 5: 3 }
  const currentStepIndex = stepMap[props.currentStep] || 0
  return (currentStepIndex / (totalSteps - 1)) * 100
})

const currentStepData = computed(() => {
  const stepInfo = {
    1: {
      title: 'Lengkapi Data Pendaftaran',
      description: 'Silakan lengkapi data pribadi dan pilih paket umroh yang sesuai.',
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
      description: 'Lakukan pembayaran DP sebesar yang tertera untuk mengkonfirmasi tempat Anda.',
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
    4: {
      title: 'Persiapan Keberangkatan',
      description: 'Siapkan dokumen yang diperlukan dan ikuti sesi manasik umroh.',
      actions: [
        {
          label: 'Upload Dokumen',
          primary: true,
          handler: () => emit('step-action', 'upload-documents')
        },
        {
          label: 'Jadwal Manasik',
          primary: false,
          handler: () => emit('step-action', 'view-manasik-schedule')
        }
      ]
    },
    5: {
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
      'Setelah mendaftar, lakukan pembayaran DP',
      'Upload bukti pembayaran',
      'Siapkan dokumen yang diperlukan'
    ],
    2: [
      'Upload bukti pembayaran',
      'Siapkan dokumen: KTP, KK, Paspor',
      'Persiapan untuk upload dokumen'
    ],
    4: [
      'Ikuti briefing keberangkatan',
      'Cek jadwal dan meeting point',
      'Siapkan perlengkapan umroh'
    ],
    5: []
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
</script>
