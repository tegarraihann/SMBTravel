<template>
  <AdminLayout>

    <div class="py-12">
      <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

        <!-- Page Title -->
        <div class="mb-6">
          <h1 class="text-2xl font-bold text-gray-900">Detail Sesi Manasik</h1>
          <p class="mt-2 text-sm text-gray-600">{{ session.title }}</p>
        </div>

        <!-- Action buttons -->
        <div class="flex justify-between items-center">
          <div class="flex space-x-2">
            <Link
              :href="route('admin.manasik.index')"
              class="inline-flex items-center px-4 py-2 bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150"
            >
              ← Kembali
            </Link>
          </div>
          <div class="flex space-x-2">
            <Link
              :href="route('admin.manasik.edit', session.id)"
              class="inline-flex items-center px-4 py-2 bg-yellow-500 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-yellow-600 focus:bg-yellow-600 active:bg-yellow-700 focus:outline-none focus:ring-2 focus:ring-yellow-500 focus:ring-offset-2 transition ease-in-out duration-150"
            >
              Edit Sesi
            </Link>
          </div>
        </div>

        <!-- Session Information -->
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
          <div class="p-6 text-gray-900">
            <h3 class="text-lg font-semibold mb-4">Informasi Sesi</h3>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
              <div>
                <label class="block text-sm font-medium text-gray-600">Judul</label>
                <p class="text-gray-900">{{ session.title }}</p>
              </div>

              <div>
                <label class="block text-sm font-medium text-gray-600">Tipe</label>
                <span :class="getTypeClass(session.type)"
                      class="inline-flex px-2 py-1 text-xs font-semibold rounded-full">
                  {{ getTypeDisplay(session.type) }}
                </span>
              </div>

              <div>
                <label class="block text-sm font-medium text-gray-600">Status</label>
                <span :class="getStatusClass(session.status)"
                      class="inline-flex px-2 py-1 text-xs font-semibold rounded-full">
                  {{ getStatusDisplay(session.status) }}
                </span>
              </div>

              <div>
                <label class="block text-sm font-medium text-gray-600">Tanggal</label>
                <p class="text-gray-900">{{ formatDate(session.session_date) }}</p>
              </div>

              <div>
                <label class="block text-sm font-medium text-gray-600">Waktu</label>
                <p class="text-gray-900">
                  {{ formatTime(session.start_time) }} - {{ formatTime(session.end_time) }}
                </p>
              </div>

              <div v-if="session.location">
                <label class="block text-sm font-medium text-gray-600">Lokasi</label>
                <p class="text-gray-900">{{ session.location }}</p>
              </div>

              <div v-if="session.instructor_name">
                <label class="block text-sm font-medium text-gray-600">Instruktur</label>
                <p class="text-gray-900">{{ session.instructor_name }}</p>
              </div>

              <div v-if="session.instructor_contact">
                <label class="block text-sm font-medium text-gray-600">Kontak Instruktur</label>
                <p class="text-gray-900">{{ session.instructor_contact }}</p>
              </div>

              <div v-if="session.max_participants">
                <label class="block text-sm font-medium text-gray-600">Maksimal Peserta</label>
                <p class="text-gray-900">{{ session.max_participants }} orang</p>
              </div>

              <div>
                <label class="block text-sm font-medium text-gray-600">Wajib</label>
                <p class="text-gray-900">
                  {{ session.is_mandatory ? 'Ya' : 'Tidak' }}
                </p>
              </div>
            </div>

            <div v-if="session.description" class="mt-6">
              <label class="block text-sm font-medium text-gray-600 mb-2">Deskripsi</label>
              <p class="text-gray-900 whitespace-pre-wrap">{{ session.description }}</p>
            </div>

            <div v-if="session.online_link" class="mt-6">
              <label class="block text-sm font-medium text-gray-600 mb-2">Link Online</label>
              <a :href="session.online_link" target="_blank"
                 class="text-blue-600 hover:text-blue-800 break-all">
                {{ session.online_link }}
              </a>
            </div>

            <div v-if="session.materials && session.materials.length > 0" class="mt-6">
              <label class="block text-sm font-medium text-gray-600 mb-2">Materi</label>
              <div class="flex flex-wrap gap-2">
                <span v-for="material in session.materials" :key="material"
                      class="inline-flex px-2 py-1 text-xs bg-gray-100 text-gray-800 rounded">
                  {{ material }}
                </span>
              </div>
            </div>
          </div>
        </div>

        <!-- Attendance Management -->
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
          <div class="p-6 text-gray-900">
            <div class="flex justify-between items-center mb-4">
              <h3 class="text-lg font-semibold">Daftar Peserta</h3>
              <div class="text-sm text-gray-600">
                Total: {{ session.attendances ? session.attendances.length : 0 }} peserta
              </div>
            </div>

            <div v-if="session.attendances && session.attendances.length > 0">

              <!-- Bulk Actions -->
              <div class="mb-4 p-4 bg-gray-50 rounded-lg">
                <div class="flex items-center justify-between">
                  <div class="flex items-center space-x-4">
                    <button
                      @click="selectAll"
                      class="text-sm bg-gray-200 hover:bg-gray-300  px-3 py-1 rounded"
                    >
                      {{ selectedUsers.length === session.attendances.length ? 'Deselect All' : 'Select All' }}
                    </button>
                    <div class="text-sm text-gray-600">
                      {{ selectedUsers.length }} dipilih
                    </div>
                  </div>

                  <div v-if="selectedUsers.length > 0" class="flex space-x-2">
                    <button
                      @click="markAttendance('attended')"
                      class="bg-green-500 hover:bg-green-700 text-white text-sm font-bold py-1 px-3 rounded"
                    >
                      Hadir
                    </button>
                    <button
                      @click="markAttendance('absent')"
                      class="bg-red-500 hover:bg-red-700 text-white text-sm font-bold py-1 px-3 rounded"
                    >
                      Tidak Hadir
                    </button>
                    <button
                      @click="markAttendance('excused')"
                      class="bg-yellow-500 hover:bg-yellow-700 text-white text-sm font-bold py-1 px-3 rounded"
                    >
                      Izin
                    </button>
                  </div>
                </div>
              </div>

              <!-- Participants Table -->
              <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 ">
                  <thead class="bg-gray-50 ">
                    <tr>
                      <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        <input
                          type="checkbox"
                          @change="toggleSelectAll"
                          :checked="selectedUsers.length === session.attendances.length"
                          class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded"
                        />
                      </th>
                      <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Nama
                      </th>
                      <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Email
                      </th>
                      <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Status
                      </th>
                      <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Terdaftar
                      </th>
                      <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Hadir
                      </th>
                      <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Nilai
                      </th>
                    </tr>
                  </thead>
                  <tbody class="bg-white divide-y divide-gray-200 ">
                    <tr v-for="attendance in session.attendances" :key="attendance.id"
                        class="hover:bg-gray-50 ">
                      <td class="px-6 py-4 whitespace-nowrap">
                        <input
                          type="checkbox"
                          :value="attendance.user_id"
                          v-model="selectedUsers"
                          class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded"
                        />
                      </td>
                      <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm font-medium text-gray-900">
                          {{ attendance.user.name }}
                        </div>
                      </td>
                      <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm text-gray-900">
                          {{ attendance.user.email }}
                        </div>
                      </td>
                      <td class="px-6 py-4 whitespace-nowrap">
                        <span :class="getAttendanceStatusClass(attendance.status)"
                              class="inline-flex px-2 py-1 text-xs font-semibold rounded-full">
                          {{ getAttendanceStatusDisplay(attendance.status) }}
                        </span>
                      </td>
                      <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                        {{ attendance.registered_at ? formatDateTime(attendance.registered_at) : '-' }}
                      </td>
                      <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                        {{ attendance.attended_at ? formatDateTime(attendance.attended_at) : '-' }}
                      </td>
                      <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                        {{ attendance.completion_score || '-' }}
                      </td>
                    </tr>
                  </tbody>
                </table>
              </div>
            </div>

            <div v-else class="text-center py-8">
              <div class="text-gray-400 mb-2">
                <svg class="mx-auto h-12 w-12" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                </svg>
              </div>
              <p class="text-gray-500">
                Belum ada peserta terdaftar untuk sesi ini
              </p>
            </div>
          </div>
        </div>

      </div>
    </div>
  </AdminLayout>
</template>

<script setup>
import AdminLayout from '@/Layouts/AdminLayout.vue';
import { Link, router } from '@inertiajs/vue3';
import { ref } from 'vue';

defineProps({
  session: Object
});

const selectedUsers = ref([]);

const formatDate = (dateString) => {
  if (!dateString) return '';
  return new Date(dateString).toLocaleDateString('id-ID', {
    weekday: 'long',
    year: 'numeric',
    month: 'long',
    day: 'numeric'
  });
};

const formatTime = (timeString) => {
  if (!timeString) return '';
  return new Date('1970-01-01T' + timeString).toLocaleTimeString('id-ID', {
    hour: '2-digit',
    minute: '2-digit'
  });
};

const formatDateTime = (dateTimeString) => {
  if (!dateTimeString) return '';
  return new Date(dateTimeString).toLocaleString('id-ID', {
    day: '2-digit',
    month: '2-digit',
    year: 'numeric',
    hour: '2-digit',
    minute: '2-digit'
  });
};

const getTypeDisplay = (type) => {
  const types = {
    'online': 'Online',
    'offline': 'Offline',
    'hybrid': 'Hybrid'
  };
  return types[type] || type;
};

const getTypeClass = (type) => {
  const classes = {
    'online': 'bg-blue-100 text-blue-800',
    'offline': 'bg-green-100 text-green-800',
    'hybrid': 'bg-purple-100 text-purple-800'
  };
  return classes[type] || 'bg-gray-100 text-gray-800';
};

const getStatusDisplay = (status) => {
  const statuses = {
    'scheduled': 'Terjadwal',
    'ongoing': 'Berlangsung',
    'completed': 'Selesai',
    'cancelled': 'Dibatalkan'
  };
  return statuses[status] || status;
};

const getStatusClass = (status) => {
  const classes = {
    'scheduled': 'bg-yellow-100 text-yellow-800',
    'ongoing': 'bg-blue-100 text-blue-800',
    'completed': 'bg-green-100 text-green-800',
    'cancelled': 'bg-red-100 text-red-800'
  };
  return classes[status] || 'bg-gray-100 text-gray-800';
};

const getAttendanceStatusDisplay = (status) => {
  const statuses = {
    'registered': 'Terdaftar',
    'attended': 'Hadir',
    'absent': 'Tidak Hadir',
    'excused': 'Izin'
  };
  return statuses[status] || status;
};

const getAttendanceStatusClass = (status) => {
  const classes = {
    'registered': 'bg-blue-100 text-blue-800',
    'attended': 'bg-green-100 text-green-800',
    'absent': 'bg-red-100 text-red-800',
    'excused': 'bg-yellow-100 text-yellow-800'
  };
  return classes[status] || 'bg-gray-100 text-gray-800';
};

const selectAll = () => {
  if (selectedUsers.value.length === session.attendances.length) {
    selectedUsers.value = [];
  } else {
    selectedUsers.value = session.attendances.map(attendance => attendance.user_id);
  }
};

const toggleSelectAll = (event) => {
  if (event.target.checked) {
    selectedUsers.value = session.attendances.map(attendance => attendance.user_id);
  } else {
    selectedUsers.value = [];
  }
};

const markAttendance = (status) => {
  if (selectedUsers.value.length === 0) {
    alert('Pilih peserta terlebih dahulu');
    return;
  }

  let notes = null;
  if (status === 'excused') {
    notes = prompt('Alasan izin (opsional):');
  }

  router.post(route('admin.manasik.attendance', session.id), {
    user_ids: selectedUsers.value,
    status: status,
    notes: notes
  }, {
    onSuccess: () => {
      selectedUsers.value = [];
    }
  });
};
</script>
