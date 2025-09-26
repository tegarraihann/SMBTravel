<template>
  <Head :title="isEdit ? 'Edit Marketing' : 'Tambah Marketing'" />

  <AdminLayout>
    <template #header>
      <div class="flex items-center space-x-4">
        <Link
          :href="route('admin.marketing.index')"
          class="text-gray-500 hover:text-gray-700"
        >
          <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
          </svg>
        </Link>
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
          {{ isEdit ? 'Edit Marketing' : 'Tambah Marketing' }}
        </h2>
      </div>
    </template>

    <div class="py-12">
      <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
        <form @submit.prevent="submitForm" class="space-y-6">

          <!-- Main Info Card -->
          <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 border-b border-gray-200">
              <h3 class="text-lg font-medium text-gray-900">Informasi Marketing</h3>
              <p class="mt-1 text-sm text-gray-600">
                {{ isEdit ? 'Edit data marketing yang sudah ada' : 'Masukkan data marketing baru' }}
              </p>
            </div>

            <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-6">
              <!-- Kode Marketing -->
              <div>
                <label class="block text-sm font-medium text-gray-700">Kode Marketing</label>
                <input
                  type="text"
                  :value="suggestedCode"
                  disabled
                  class="mt-1 block w-full border-gray-300 rounded-md shadow-sm bg-gray-50 text-gray-500"
                />
                <p class="mt-1 text-xs text-gray-500">Kode otomatis berdasarkan urutan</p>
              </div>

              <!-- Nama -->
              <div>
                <label class="block text-sm font-medium text-gray-700">Nama *</label>
                <input
                  type="text"
                  v-model="form.name"
                  :class="{
                    'border-red-300': errors.name,
                    'border-gray-300': !errors.name
                  }"
                  class="mt-1 block w-full rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500"
                  placeholder="Masukkan nama marketing"
                  required
                />
                <p v-if="errors.name" class="mt-1 text-sm text-red-600">{{ errors.name }}</p>
              </div>

              <!-- Phone -->
              <div>
                <label class="block text-sm font-medium text-gray-700">Nomor Telepon</label>
                <input
                  type="text"
                  v-model="form.phone"
                  :class="{
                    'border-red-300': errors.phone,
                    'border-gray-300': !errors.phone
                  }"
                  class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500"
                  placeholder="Contoh: 08123456789"
                />
                <p v-if="errors.phone" class="mt-1 text-sm text-red-600">{{ errors.phone }}</p>
              </div>

              <!-- Email -->
              <div>
                <label class="block text-sm font-medium text-gray-700">Email</label>
                <input
                  type="email"
                  v-model="form.email"
                  :class="{
                    'border-red-300': errors.email,
                    'border-gray-300': !errors.email
                  }"
                  class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500"
                  placeholder="contoh@email.com"
                />
                <p v-if="errors.email" class="mt-1 text-sm text-red-600">{{ errors.email }}</p>
              </div>

              <!-- Status -->
              <div>
                <label class="block text-sm font-medium text-gray-700">Status *</label>
                <select
                  v-model="form.status"
                  :class="{
                    'border-red-300': errors.status,
                    'border-gray-300': !errors.status
                  }"
                  class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500"
                  required
                >
                  <option value="active">Aktif</option>
                  <option value="inactive">Non Aktif</option>
                </select>
                <p v-if="errors.status" class="mt-1 text-sm text-red-600">{{ errors.status }}</p>
              </div>

              <!-- Notes -->
              <div class="md:col-span-2">
                <label class="block text-sm font-medium text-gray-700">Catatan</label>
                <textarea
                  v-model="form.notes"
                  rows="3"
                  :class="{
                    'border-red-300': errors.notes,
                    'border-gray-300': !errors.notes
                  }"
                  class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500"
                  placeholder="Catatan tambahan tentang marketing ini..."
                ></textarea>
                <p v-if="errors.notes" class="mt-1 text-sm text-red-600">{{ errors.notes }}</p>
              </div>
            </div>
          </div>

          <!-- Agents Section -->
          <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 border-b border-gray-200">
              <div class="flex justify-between items-center">
                <div>
                  <h3 class="text-lg font-medium text-gray-900">Agen Marketing</h3>
                  <p class="mt-1 text-sm text-gray-600">
                    Kelola agen yang berada di bawah marketing ini
                  </p>
                </div>
                <button
                  type="button"
                  @click="addAgent"
                  class="inline-flex items-center px-3 py-2 border border-gray-300 shadow-sm text-sm leading-4 font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500"
                >
                  <svg class="-ml-0.5 mr-2 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                  </svg>
                  Tambah Agen
                </button>
              </div>
            </div>

            <div class="p-6">
              <div v-if="form.agents.length === 0" class="text-center py-8">
                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                </svg>
                <p class="mt-2 text-sm text-gray-600">Belum ada agen ditambahkan</p>
                <button
                  type="button"
                  @click="addAgent"
                  class="mt-3 inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-blue-700 bg-blue-100 hover:bg-blue-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500"
                >
                  Tambah Agen Pertama
                </button>
              </div>

              <div v-else class="space-y-4">
                <div
                  v-for="(agent, index) in form.agents"
                  :key="agent.tempId || agent.id || index"
                  class="border border-gray-200 rounded-lg p-4 bg-gray-50"
                >
                  <div class="flex justify-between items-start mb-4">
                    <h4 class="text-sm font-medium text-gray-900">Agen #{{ index + 1 }}</h4>
                    <button
                      type="button"
                      @click="removeAgent(index)"
                      class="text-red-600 hover:text-red-900 text-sm"
                    >
                      Hapus
                    </button>
                  </div>

                  <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <!-- Nama Agen -->
                    <div>
                      <label class="block text-sm font-medium text-gray-700">Nama Agen *</label>
                      <input
                        type="text"
                        v-model="agent.name"
                        :class="{
                          'border-red-300': errors[`agents.${index}.name`],
                          'border-gray-300': !errors[`agents.${index}.name`]
                        }"
                        class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500"
                        placeholder="Nama agen"
                        required
                      />
                      <p v-if="errors[`agents.${index}.name`]" class="mt-1 text-sm text-red-600">
                        {{ errors[`agents.${index}.name`] }}
                      </p>
                    </div>

                    <!-- Phone Agen -->
                    <div>
                      <label class="block text-sm font-medium text-gray-700">Nomor Telepon</label>
                      <input
                        type="text"
                        v-model="agent.phone"
                        class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500"
                        placeholder="08123456789"
                      />
                    </div>

                    <!-- Email Agen -->
                    <div>
                      <label class="block text-sm font-medium text-gray-700">Email</label>
                      <input
                        type="email"
                        v-model="agent.email"
                        class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500"
                        placeholder="agen@email.com"
                      />
                    </div>

                    <!-- Commission Rate -->
                    <div>
                      <label class="block text-sm font-medium text-gray-700">Komisi (%)</label>
                      <input
                        type="number"
                        v-model="agent.commission_rate"
                        min="0"
                        max="100"
                        step="0.01"
                        class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500"
                        placeholder="0.00"
                      />
                    </div>

                    <!-- Status Agen -->
                    <div>
                      <label class="block text-sm font-medium text-gray-700">Status *</label>
                      <select
                        v-model="agent.status"
                        class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500"
                        required
                      >
                        <option value="active">Aktif</option>
                        <option value="inactive">Non Aktif</option>
                      </select>
                    </div>

                    <!-- Notes Agen -->
                    <div>
                      <label class="block text-sm font-medium text-gray-700">Catatan</label>
                      <textarea
                        v-model="agent.notes"
                        rows="2"
                        class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500"
                        placeholder="Catatan untuk agen ini..."
                      ></textarea>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <!-- Actions -->
          <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 flex justify-end space-x-3">
              <Link
                :href="route('admin.marketing.index')"
                class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500"
              >
                Batal
              </Link>
              <button
                type="submit"
                :disabled="processing"
                class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:bg-blue-700 active:bg-blue-900 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition ease-in-out duration-150 disabled:opacity-50"
              >
                <svg v-if="processing" class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" fill="none" viewBox="0 0 24 24">
                  <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                  <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                {{ isEdit ? 'Update' : 'Simpan' }}
              </button>
            </div>
          </div>
        </form>
      </div>
    </div>
  </AdminLayout>
</template>

<script setup>
import { Head, Link, useForm, router } from '@inertiajs/vue3'
import { ref } from 'vue'
import AdminLayout from '@/Layouts/AdminLayout.vue'

const props = defineProps({
  item: Object,
  isEdit: {
    type: Boolean,
    default: false
  },
  suggestedCode: String
})

const processing = ref(false)
const errors = ref({})

const form = useForm({
  name: props.item?.name || '',
  phone: props.item?.phone || '',
  email: props.item?.email || '',
  status: props.item?.status || 'active',
  notes: props.item?.notes || '',
  agents: props.item?.agents || []
})

let tempIdCounter = 1000

const addAgent = () => {
  form.agents.push({
    tempId: tempIdCounter++,
    name: '',
    phone: '',
    email: '',
    commission_rate: 0,
    status: 'active',
    notes: ''
  })
}

const removeAgent = (index) => {
  const agent = form.agents[index]

  if (agent.id && props.isEdit) {
    // Mark for deletion if it's an existing agent
    agent._delete = true
  } else {
    // Remove from array if it's a new agent
    form.agents.splice(index, 1)
  }
}

const submitForm = () => {
  processing.value = true
  errors.value = {}

  const url = props.isEdit
    ? route('admin.marketing.update', props.item.id)
    : route('admin.marketing.store')

  const method = props.isEdit ? 'put' : 'post'

  form[method](url, {
    onSuccess: () => {
      router.visit(route('admin.marketing.index'), {
        onFinish: () => {
          processing.value = false
        }
      })
    },
    onError: (err) => {
      errors.value = err
      processing.value = false
    },
    onFinish: () => {
      processing.value = false
    }
  })
}
</script>