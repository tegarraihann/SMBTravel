<template>
  <AdminLayout>
    <template #header>
      <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        Manajemen Manasik
      </h2>
    </template>

    <div class="py-12">
      <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

        <!-- Stats Cards -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
          <div class="bg-white overflow-hidden shadow rounded-lg">
            <div class="p-5">
              <div class="flex items-center">
                <div class="flex-shrink-0">
                  <div class="w-8 h-8 bg-blue-500 rounded-lg flex items-center justify-center">
                    <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 20 20">
                      <path d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                  </div>
                </div>
                <div class="ml-5 w-0 flex-1">
                  <dl>
                    <dt class="text-sm font-medium text-gray-500 truncate">Total Sesi</dt>
                    <dd class="text-lg font-medium text-gray-900">{{ sessions.data.length }}</dd>
                  </dl>
                </div>
              </div>
            </div>
          </div>

          <div class="bg-white overflow-hidden shadow rounded-lg">
            <div class="p-5">
              <div class="flex items-center">
                <div class="flex-shrink-0">
                  <div class="w-8 h-8 bg-green-500 rounded-lg flex items-center justify-center">
                    <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 20 20">
                      <path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                  </div>
                </div>
                <div class="ml-5 w-0 flex-1">
                  <dl>
                    <dt class="text-sm font-medium text-gray-500 truncate">Sesi Aktif</dt>
                    <dd class="text-lg font-medium text-gray-900">{{ getActiveSessionsCount() }}</dd>
                  </dl>
                </div>
              </div>
            </div>
          </div>

          <div class="bg-white overflow-hidden shadow rounded-lg">
            <div class="p-5">
              <div class="flex items-center">
                <div class="flex-shrink-0">
                  <div class="w-8 h-8 bg-yellow-500 rounded-lg flex items-center justify-center">
                    <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 20 20">
                      <path d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                    </svg>
                  </div>
                </div>
                <div class="ml-5 w-0 flex-1">
                  <dl>
                    <dt class="text-sm font-medium text-gray-500 truncate">Total Peserta</dt>
                    <dd class="text-lg font-medium text-gray-900">{{ getTotalParticipantsCount() }}</dd>
                  </dl>
                </div>
              </div>
            </div>
          </div>

          <div class="bg-white overflow-hidden shadow rounded-lg">
            <div class="p-5">
              <div class="flex items-center">
                <div class="flex-shrink-0">
                  <div class="w-8 h-8 bg-purple-500 rounded-lg flex items-center justify-center">
                    <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 20 20">
                      <path d="M5 13l4 4L19 7"/>
                    </svg>
                  </div>
                </div>
                <div class="ml-5 w-0 flex-1">
                  <dl>
                    <dt class="text-sm font-medium text-gray-500 truncate">Sesi Selesai</dt>
                    <dd class="text-lg font-medium text-gray-900">{{ getCompletedSessionsCount() }}</dd>
                  </dl>
                </div>
              </div>
            </div>
          </div>
        </div>

        <div class="bg-white overflow-hidden shadow rounded-lg">
          <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center">
            <div>
              <h3 class="text-lg leading-6 font-medium text-gray-900">
                Daftar Sesi Manasik
              </h3>
              <p class="mt-1 text-sm text-gray-500">
                Kelola semua sesi manasik untuk jamaah
              </p>
            </div>
            <Link
              :href="route('admin.manasik.create')"
              class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded"
            >
              Tambah Sesi
            </Link>
          </div>
          <div class="p-6">

            <!-- Session List -->
            <div class="overflow-x-auto">
              <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                  <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                      Sesi
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                      Tanggal & Waktu
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                      Tipe
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                      Peserta
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                      Status
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                      Aksi
                    </th>
                  </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                  <tr v-for="session in sessions.data" :key="session.id" class="hover:bg-gray-50">
                    <td class="px-6 py-4 whitespace-nowrap">
                      <div class="text-sm font-medium text-gray-900">
                        {{ session.title }}
                      </div>
                      <div class="text-sm text-gray-500" v-if="session.instructor_name">
                        Instruktur: {{ session.instructor_name }}
                      </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                      <div>{{ formatDate(session.session_date) }}</div>
                      <div class="text-gray-500">
                        {{ formatTime(session.start_time) }} - {{ formatTime(session.end_time) }}
                      </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                      <span :class="getTypeClass(session.type)"
                            class="inline-flex px-2 py-1 text-xs font-semibold rounded-full">
                        {{ getTypeDisplay(session.type) }}
                      </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                      <div>{{ session.participant_count || 0 }} peserta</div>
                      <div class="text-gray-500" v-if="session.max_participants">
                        Max: {{ session.max_participants }}
                      </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                      <span :class="getStatusClass(session.status)"
                            class="inline-flex px-2 py-1 text-xs font-semibold rounded-full">
                        {{ getStatusDisplay(session.status) }}
                      </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                      <div class="flex space-x-2">
                        <Link
                          :href="route('admin.manasik.show', session.id)"
                          class="text-blue-600 hover:text-blue-900"
                        >
                          Detail
                        </Link>
                        <Link
                          :href="route('admin.manasik.edit', session.id)"
                          class="text-yellow-600 hover:text-yellow-900"
                        >
                          Edit
                        </Link>
                        <button
                          @click="deleteSession(session.id)"
                          class="text-red-600 hover:text-red-900"
                        >
                          Hapus
                        </button>
                      </div>
                    </td>
                  </tr>
                </tbody>
              </table>
            </div>

            <!-- Pagination -->
            <div class="mt-6" v-if="sessions.links">
              <nav class="flex items-center justify-between">
                <div class="flex-1 flex justify-between sm:hidden">
                  <Link v-if="sessions.prev_page_url" :href="sessions.prev_page_url"
                        class="relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                    Previous
                  </Link>
                  <Link v-if="sessions.next_page_url" :href="sessions.next_page_url"
                        class="ml-3 relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                    Next
                  </Link>
                </div>
                <div class="hidden sm:flex-1 sm:flex sm:items-center sm:justify-between">
                  <div>
                    <p class="text-sm text-gray-700">
                      Showing {{ sessions.from || 0 }} to {{ sessions.to || 0 }} of {{ sessions.total }} results
                    </p>
                  </div>
                  <div>
                    <nav class="relative z-0 inline-flex rounded-md shadow-sm -space-x-px" aria-label="Pagination">
                      <template v-for="link in sessions.links" :key="link.label">
                        <Link v-if="link.url" :href="link.url"
                              :class="[
                                'relative inline-flex items-center px-3 py-2 border text-sm font-medium',
                                link.active
                                  ? 'z-10 bg-blue-50 border-blue-500 text-blue-600'
                                  : 'bg-white border-gray-300 text-gray-500 hover:bg-gray-50'
                              ]"
                              v-html="link.label">
                        </Link>
                        <span v-else
                              :class="[
                                'relative inline-flex items-center px-3 py-2 border text-sm font-medium cursor-default',
                                'bg-white border-gray-300 text-gray-300'
                              ]"
                              v-html="link.label">
                        </span>
                      </template>
                    </nav>
                  </div>
                </div>
              </nav>
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

const props = defineProps({
  sessions: Object
});

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

const deleteSession = (sessionId) => {
  if (confirm('Apakah Anda yakin ingin menghapus sesi manasik ini?')) {
    router.delete(route('admin.manasik.destroy', sessionId));
  }
};

const getActiveSessionsCount = () => {
  return props.sessions.data.filter(session =>
    session.status === 'scheduled' || session.status === 'ongoing'
  ).length;
};

const getTotalParticipantsCount = () => {
  return props.sessions.data.reduce((total, session) =>
    total + (session.attendances_count || 0), 0
  );
};

const getCompletedSessionsCount = () => {
  return props.sessions.data.filter(session => session.status === 'completed').length;
};
</script>
