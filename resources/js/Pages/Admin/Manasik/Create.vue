<template>
  <AdminLayout>
    <template #header>
      <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        Tambah Sesi Manasik Baru
      </h2>
    </template>

    <div class="py-12">
      <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow rounded-lg">
          <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center">
            <div>
              <h3 class="text-lg leading-6 font-medium text-gray-900">
                Form Sesi Manasik Baru
              </h3>
              <p class="mt-1 text-sm text-gray-500">
                Isi informasi lengkap untuk membuat sesi manasik baru
              </p>
            </div>
            <Link
              :href="route('admin.manasik.index')"
              class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded"
            >
              Kembali
            </Link>
          </div>
          <div class="p-6">

            <form @submit.prevent="submit">
              <!-- Basic Information -->
              <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="md:col-span-2">
                  <label for="title" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                    Judul Sesi <span class="text-red-500">*</span>
                  </label>
                  <input
                    id="title"
                    type="text"
                    v-model="form.title"
                    class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm"
                    required
                  />
                  <InputError :message="form.errors.title" class="mt-2" />
                </div>

                <div class="md:col-span-2">
                  <label for="description" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                    Deskripsi
                  </label>
                  <textarea
                    id="description"
                    v-model="form.description"
                    rows="3"
                    class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm"
                  ></textarea>
                  <InputError :message="form.errors.description" class="mt-2" />
                </div>

                <div>
                  <label for="type" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                    Tipe Sesi <span class="text-red-500">*</span>
                  </label>
                  <select
                    id="type"
                    v-model="form.type"
                    class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm"
                    required
                  >
                    <option value="">Pilih Tipe</option>
                    <option value="online">Online</option>
                    <option value="offline">Offline</option>
                    <option value="hybrid">Hybrid</option>
                  </select>
                  <InputError :message="form.errors.type" class="mt-2" />
                </div>

                <div>
                  <label for="session_date" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                    Tanggal Sesi <span class="text-red-500">*</span>
                  </label>
                  <input
                    id="session_date"
                    type="date"
                    v-model="form.session_date"
                    class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm"
                    required
                  />
                  <InputError :message="form.errors.session_date" class="mt-2" />
                </div>

                <div>
                  <label for="start_time" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                    Waktu Mulai <span class="text-red-500">*</span>
                  </label>
                  <input
                    id="start_time"
                    type="time"
                    v-model="form.start_time"
                    class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm"
                    required
                  />
                  <InputError :message="form.errors.start_time" class="mt-2" />
                </div>

                <div>
                  <label for="end_time" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                    Waktu Selesai <span class="text-red-500">*</span>
                  </label>
                  <input
                    id="end_time"
                    type="time"
                    v-model="form.end_time"
                    class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm"
                    required
                  />
                  <InputError :message="form.errors.end_time" class="mt-2" />
                </div>

                <!-- Location & Online Link -->
                <div v-if="form.type === 'offline' || form.type === 'hybrid'">
                  <label for="location" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                    Lokasi <span class="text-red-500">*</span>
                  </label>
                  <input
                    id="location"
                    type="text"
                    v-model="form.location"
                    class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm"
                  />
                  <InputError :message="form.errors.location" class="mt-2" />
                </div>

                <div v-if="form.type === 'online' || form.type === 'hybrid'">
                  <label for="online_link" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                    Link Online <span class="text-red-500">*</span>
                  </label>
                  <input
                    id="online_link"
                    type="url"
                    v-model="form.online_link"
                    placeholder="https://zoom.us/..."
                    class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm"
                  />
                  <InputError :message="form.errors.online_link" class="mt-2" />
                </div>

                <!-- Instructor Info -->
                <div>
                  <label for="instructor_name" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                    Nama Instruktur
                  </label>
                  <input
                    id="instructor_name"
                    type="text"
                    v-model="form.instructor_name"
                    class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm"
                  />
                  <InputError :message="form.errors.instructor_name" class="mt-2" />
                </div>

                <div>
                  <label for="instructor_contact" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                    Kontak Instruktur
                  </label>
                  <input
                    id="instructor_contact"
                    type="text"
                    v-model="form.instructor_contact"
                    placeholder="WhatsApp/Telepon"
                    class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm"
                  />
                  <InputError :message="form.errors.instructor_contact" class="mt-2" />
                </div>

                <!-- Additional Settings -->
                <div class="md:col-span-2">
                  <label for="materials" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                    Materi (pisahkan dengan koma)
                  </label>
                  <textarea
                    id="materials"
                    v-model="form.materials"
                    rows="2"
                    placeholder="Contoh: Al-Quran, Buku Panduan Manasik, Perlengkapan Shalat"
                    class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm"
                  ></textarea>
                  <InputError :message="form.errors.materials" class="mt-2" />
                </div>

                <div>
                  <label for="max_participants" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                    Maksimal Peserta
                  </label>
                  <input
                    id="max_participants"
                    type="number"
                    min="1"
                    v-model="form.max_participants"
                    class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm"
                  />
                  <InputError :message="form.errors.max_participants" class="mt-2" />
                </div>

                <div class="flex items-center">
                  <input
                    id="is_mandatory"
                    type="checkbox"
                    v-model="form.is_mandatory"
                    class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded"
                  />
                  <label for="is_mandatory" class="ml-2 block text-sm text-gray-700 dark:text-gray-300">
                    Sesi Wajib
                  </label>
                </div>
              </div>

              <div class="mt-8 flex justify-end space-x-3">
                <Link
                  :href="route('admin.manasik.index')"
                  class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded"
                >
                  Batal
                </Link>
                <button
                  type="submit"
                  :disabled="form.processing"
                  class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded disabled:opacity-50"
                >
                  <span v-if="form.processing">Menyimpan...</span>
                  <span v-else>Simpan</span>
                </button>
              </div>
            </form>

          </div>
        </div>
      </div>
    </div>
  </AdminLayout>
</template>

<script setup>
import AdminLayout from '@/Layouts/AdminLayout.vue';
import InputError from '@/Components/InputError.vue';
import { Link, useForm } from '@inertiajs/vue3';

const form = useForm({
  title: '',
  description: '',
  type: '',
  session_date: '',
  start_time: '',
  end_time: '',
  location: '',
  online_link: '',
  instructor_name: '',
  instructor_contact: '',
  materials: '',
  is_mandatory: true,
  max_participants: null
});

const submit = () => {
  form.post(route('admin.manasik.store'));
};
</script>