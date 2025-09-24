<template>
  <Head title="Jadwal Manasik" />

  <AuthenticatedLayout>
    <template #header>
      <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        Jadwal Manasik & Persiapan
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
                      <path d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"/>
                    </svg>
                  </div>
                </div>
                <div class="ml-5 w-0 flex-1">
                  <dl>
                    <dt class="text-sm font-medium text-gray-500 truncate">Step Saat Ini</dt>
                    <dd class="text-lg font-medium text-gray-900">{{ jamaahData.current_step }} dari 4</dd>
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
                    <dt class="text-sm font-medium text-gray-500 truncate">Sesi Terdaftar</dt>
                    <dd class="text-lg font-medium text-gray-900">{{ getRegisteredSessionsCount() }}</dd>
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
                      <path d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                  </div>
                </div>
                <div class="ml-5 w-0 flex-1">
                  <dl>
                    <dt class="text-sm font-medium text-gray-500 truncate">Sesi Tersedia</dt>
                    <dd class="text-lg font-medium text-gray-900">{{ upcomingSessions.length }}</dd>
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
                    <dd class="text-lg font-medium text-gray-900">{{ getAttendedSessionsCount() }}</dd>
                  </dl>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- Upcoming Sessions -->
        <div class="bg-white overflow-hidden shadow rounded-lg mb-8">
          <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg leading-6 font-medium text-gray-900">
              Sesi Manasik Mendatang
            </h3>
            <p class="mt-1 text-sm text-gray-500">
              Daftar dan ikuti sesi manasik yang tersedia untuk persiapan umroh Anda
            </p>
          </div>
          <div class="p-6">

            <div v-if="upcomingSessions.length > 0" class="space-y-4">
              <div v-for="session in upcomingSessions" :key="session.id"
                   class="border border-gray-200 rounded-lg p-4 hover:shadow-md transition-shadow duration-200">

                <!-- Session Header -->
                <div class="flex justify-between items-start mb-3">
                  <div>
                    <h4 class="text-lg font-medium text-gray-900">
                      {{ session.title }}
                    </h4>
                    <p class="text-gray-600 text-sm mt-1" v-if="session.description">
                      {{ session.description }}
                    </p>
                  </div>
                  <div class="flex space-x-2">
                    <span :class="getTypeClass(session.type)"
                          class="inline-flex px-2 py-1 text-xs font-semibold rounded-full">
                      {{ getTypeDisplay(session.type) }}
                    </span>
                    <span v-if="session.is_mandatory"
                          class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-800">
                      Wajib
                    </span>
                  </div>
                </div>

                <!-- Session Details -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-4">
                  <div>
                    <div class="text-sm font-medium text-gray-500">Tanggal</div>
                    <div class="text-sm text-gray-900">
                      {{ formatDate(session.session_date) }}
                    </div>
                  </div>
                  <div>
                    <div class="text-sm font-medium text-gray-500">Waktu</div>
                    <div class="text-sm text-gray-900">
                      {{ formatTime(session.start_time) }} - {{ formatTime(session.end_time) }}
                    </div>
                  </div>
                  <div v-if="session.location">
                    <div class="text-sm font-medium text-gray-500">Lokasi</div>
                    <div class="text-sm text-gray-900">
                      {{ session.location }}
                    </div>
                  </div>
                  <div v-if="session.instructor_name">
                    <div class="text-sm font-medium text-gray-500">Instruktur</div>
                    <div class="text-sm text-gray-900">
                      {{ session.instructor_name }}
                    </div>
                  </div>
                </div>

                <!-- Online Link -->
                <div v-if="session.online_link" class="mb-4">
                  <div class="text-sm font-medium text-gray-500 mb-1">Link Online</div>
                  <a :href="session.online_link" target="_blank"
                     class="text-blue-600 hover:text-blue-800 text-sm break-all">
                    {{ session.online_link }}
                  </a>
                </div>

                <!-- Materials -->
                <div v-if="session.materials && session.materials.length > 0" class="mb-4">
                  <div class="text-sm font-medium text-gray-500 mb-1">Materi yang Diperlukan</div>
                  <div class="flex flex-wrap gap-2">
                    <span v-for="material in session.materials" :key="material"
                          class="inline-flex px-2 py-1 text-xs bg-gray-100 text-gray-800 rounded">
                      {{ material }}
                    </span>
                  </div>
                </div>

                <!-- Registration Status & Actions -->
                <div class="flex justify-between items-center">
                  <div>
                    <span v-if="session.attendances && session.attendances.length > 0"
                          :class="getAttendanceStatusClass(session.attendances[0].status)"
                          class="inline-flex px-3 py-1 text-sm font-semibold rounded-full">
                      {{ getAttendanceStatusDisplay(session.attendances[0].status) }}
                    </span>
                    <span v-else class="text-gray-500 text-sm">
                      Belum terdaftar
                    </span>
                  </div>

                  <div class="flex items-center space-x-3">
                    <!-- Participant Count -->
                    <div v-if="session.max_participants" class="text-xs text-gray-500">
                      {{ session.participant_count || 0 }}/{{ session.max_participants }} peserta
                    </div>

                    <div class="flex space-x-2">
                      <button v-if="!session.attendances || session.attendances.length === 0"
                              @click="registerForSession(session.id)"
                              :disabled="isSessionFull(session)"
                              :class="isSessionFull(session)
                                ? 'bg-gray-300 cursor-not-allowed'
                                : 'bg-blue-500 hover:bg-blue-700'"
                              class="text-white font-bold py-2 px-4 rounded text-sm">
                        <span v-if="isSessionFull(session)">Penuh</span>
                        <span v-else>Daftar</span>
                      </button>

                      <button v-else-if="session.attendances[0].status === 'registered'"
                              @click="unregisterFromSession(session.id)"
                              class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded text-sm">
                        Batal Daftar
                      </button>
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <div v-else class="text-center py-8">
              <div class="text-gray-400 mb-2">
                <svg class="mx-auto h-12 w-12" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                </svg>
              </div>
              <p class="text-gray-500">
                Belum ada sesi manasik terjadwal
              </p>
            </div>
          </div>
        </div>

        <!-- Past Sessions -->
        <div class="bg-white overflow-hidden shadow rounded-lg" v-if="pastSessions.length > 0">
          <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg leading-6 font-medium text-gray-900">
              Riwayat Sesi Manasik
            </h3>
            <p class="mt-1 text-sm text-gray-500">
              Sesi manasik yang telah Anda ikuti sebelumnya
            </p>
          </div>
          <div class="p-6">
            <div class="space-y-3">
              <div v-for="session in pastSessions" :key="session.id"
                   class="border border-gray-200 rounded-lg p-4 bg-gray-50">

                <div class="flex justify-between items-center">
                  <div>
                    <h4 class="font-medium text-gray-900">
                      {{ session.title }}
                    </h4>
                    <p class="text-sm text-gray-600">
                      {{ formatDate(session.session_date) }} • {{ formatTime(session.start_time) }} - {{ formatTime(session.end_time) }}
                    </p>
                  </div>

                  <div class="flex items-center space-x-3">
                    <span v-if="session.attendances && session.attendances.length > 0"
                          :class="getAttendanceStatusClass(session.attendances[0].status)"
                          class="inline-flex px-3 py-1 text-sm font-semibold rounded-full">
                      {{ getAttendanceStatusDisplay(session.attendances[0].status) }}
                    </span>

                    <!-- Completion Score -->
                    <div v-if="session.attendances && session.attendances.length > 0 && session.attendances[0].completion_score"
                         class="text-right">
                      <div class="text-sm font-medium text-gray-900">
                        Nilai: {{ session.attendances[0].completion_score }}
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>

      </div>
    </div>
  </AuthenticatedLayout>
</template>

<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';
import { router } from '@inertiajs/vue3';

const props = defineProps({
  upcomingSessions: Array,
  pastSessions: Array,
  jamaahData: Object
});

// Debug code removed - working correctly

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
    'online': 'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200',
    'offline': 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200',
    'hybrid': 'bg-purple-100 text-purple-800 dark:bg-purple-900 dark:text-purple-200'
  };
  return classes[type] || 'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-200';
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
    'registered': 'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200',
    'attended': 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200',
    'absent': 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200',
    'excused': 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200'
  };
  return classes[status] || 'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-200';
};

const isSessionFull = (session) => {
  return session.max_participants && (session.participant_count || 0) >= session.max_participants;
};

const registerForSession = (sessionId) => {
  if (confirm('Apakah Anda yakin ingin mendaftar untuk sesi manasik ini?')) {
    router.post(route('jamaah.manasik.register', sessionId));
  }
};

const unregisterFromSession = (sessionId) => {
  if (confirm('Apakah Anda yakin ingin membatalkan pendaftaran?')) {
    router.delete(route('jamaah.manasik.unregister', sessionId));
  }
};

const getRegisteredSessionsCount = () => {
  return props.upcomingSessions.filter(session =>
    session.attendances && session.attendances.length > 0
  ).length;
};

const getAttendedSessionsCount = () => {
  return props.pastSessions.filter(session =>
    session.attendances &&
    session.attendances.length > 0 &&
    session.attendances[0].status === 'attended'
  ).length;
};
</script>
