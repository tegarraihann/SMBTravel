<template>
  <Head title="Admin Dashboard" />

  <AdminLayout>
    <template #header>
      <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        Admin Dashboard
      </h2>
    </template>

    <div class="py-12">
      <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
          <div class="p-6 text-gray-900">
            <div class="mb-6">
              <h3 class="text-lg font-semibold text-gray-900 mb-4">System Overview</h3>

              <!-- Stats Cards -->
              <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
                <div class="bg-blue-50 border border-blue-200 rounded-lg p-6">
                  <div class="flex items-center">
                    <div class="flex-shrink-0">
                      <div class="w-8 h-8 bg-blue-500 rounded-lg flex items-center justify-center">
                        <svg class="w-4 h-4 text-white" fill="currentColor" viewBox="0 0 20 20">
                          <path d="M9 6a3 3 0 11-6 0 3 3 0 016 0zM17 6a3 3 0 11-6 0 3 3 0 016 0zM12.93 17c.046-.327.07-.66.07-1a6.97 6.97 0 00-1.5-4.33A5 5 0 0119 16v1h-6.07zM6 11a5 5 0 015 5v1H1v-1a5 5 0 015-5z"/>
                        </svg>
                      </div>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                      <dl>
                        <dt class="text-sm font-medium text-blue-600 truncate">Total Users</dt>
                        <dd class="text-lg font-medium text-blue-900">{{ totalUsers }}</dd>
                      </dl>
                    </div>
                  </div>
                </div>

                <div class="bg-green-50 border border-green-200 rounded-lg p-6">
                  <div class="flex items-center">
                    <div class="flex-shrink-0">
                      <div class="w-8 h-8 bg-green-500 rounded-lg flex items-center justify-center">
                        <svg class="w-4 h-4 text-white" fill="currentColor" viewBox="0 0 20 20">
                          <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                        </svg>
                      </div>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                      <dl>
                        <dt class="text-sm font-medium text-green-600 truncate">Active Jamaah</dt>
                        <dd class="text-lg font-medium text-green-900">{{ activeJamaah }}</dd>
                      </dl>
                    </div>
                  </div>
                </div>

                <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-6">
                  <div class="flex items-center">
                    <div class="flex-shrink-0">
                      <div class="w-8 h-8 bg-yellow-500 rounded-lg flex items-center justify-center">
                        <svg class="w-4 h-4 text-white" fill="currentColor" viewBox="0 0 20 20">
                          <path fill-rule="evenodd" d="M10 2a4 4 0 00-4 4v1H5a1 1 0 00-.994.89l-1 9A1 1 0 004 18h12a1 1 0 00.994-1.11l-1-9A1 1 0 0015 7h-1V6a4 4 0 00-4-4zm2 5V6a2 2 0 10-4 0v1h4zm-6 3a1 1 0 112 0 1 1 0 01-2 0zm7-1a1 1 0 100 2 1 1 0 000-2z" clip-rule="evenodd"/>
                        </svg>
                      </div>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                      <dl>
                        <dt class="text-sm font-medium text-yellow-600 truncate">Pending Approvals</dt>
                        <dd class="text-lg font-medium text-yellow-900">{{ pendingApprovals }}</dd>
                      </dl>
                    </div>
                  </div>
                </div>

                <div class="bg-purple-50 border border-purple-200 rounded-lg p-6">
                  <div class="flex items-center">
                    <div class="flex-shrink-0">
                      <div class="w-8 h-8 bg-purple-500 rounded-lg flex items-center justify-center">
                        <svg class="w-4 h-4 text-white" fill="currentColor" viewBox="0 0 20 20">
                          <path d="M4 4a2 2 0 00-2 2v4a2 2 0 002 2V6h10a2 2 0 00-2-2H4zm2 6a2 2 0 012-2h8a2 2 0 012 2v4a2 2 0 01-2 2H8a2 2 0 01-2-2v-4zm6 4a2 2 0 100-4 2 2 0 000 4z"/>
                        </svg>
                      </div>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                      <dl>
                        <dt class="text-sm font-medium text-purple-600 truncate">Revenue (Month)</dt>
                        <dd class="text-lg font-medium text-purple-900">{{ monthlyRevenue }}</dd>
                      </dl>
                    </div>
                  </div>
                </div>
              </div>

              <!-- Quick Actions -->
              <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="bg-white border border-gray-200 rounded-lg p-6">
                  <h4 class="text-lg font-medium text-gray-900 mb-4">Quick Actions</h4>
                  <div class="space-y-3">
                    <Link :href="route('admin.jamaah.index')" class="flex items-center p-3 text-blue-600 bg-blue-50 rounded-lg hover:bg-blue-100 transition-colors">
                      <svg class="w-5 h-5 mr-3" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M9 6a3 3 0 11-6 0 3 3 0 016 0zM17 6a3 3 0 11-6 0 3 3 0 016 0zM12.93 17c.046-.327.07-.66.07-1a6.97 6.97 0 00-1.5-4.33A5 5 0 0119 16v1h-6.07zM6 11a5 5 0 015 5v1H1v-1a5 5 0 015-5z"/>
                      </svg>
                      Manage Jamaah
                    </Link>
                    <Link :href="route('admin.users.index')" class="flex items-center p-3 text-green-600 bg-green-50 rounded-lg hover:bg-green-100 transition-colors">
                      <svg class="w-5 h-5 mr-3" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M9 6a3 3 0 11-6 0 3 3 0 016 0zM17 6a3 3 0 11-6 0 3 3 0 016 0zM12.93 17c.046-.327.07-.66.07-1a6.97 6.97 0 00-1.5-4.33A5 5 0 0119 16v1h-6.07zM6 11a5 5 0 015 5v1H1v-1a5 5 0 015-5z"/>
                      </svg>
                      Manage Users
                    </Link>
                    <Link :href="route('admin.packages.index')" class="flex items-center p-3 text-purple-600 bg-purple-50 rounded-lg hover:bg-purple-100 transition-colors">
                      <svg class="w-5 h-5 mr-3" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M3 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm0 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm0 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1z" clip-rule="evenodd"/>
                      </svg>
                      Manage Packages
                    </Link>
                    <Link :href="route('admin.payment.review')" class="flex items-center p-3 text-orange-600 bg-orange-50 rounded-lg hover:bg-orange-100 transition-colors">
                      <svg class="w-5 h-5 mr-3" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M4 4a2 2 0 00-2 2v4a2 2 0 002 2V6h10a2 2 0 00-2-2H4zm2 6a2 2 0 012-2h8a2 2 0 012 2v4a2 2 0 01-2 2H8a2 2 0 01-2-2v-4zm6 4a2 2 0 100-4 2 2 0 000 4z"/>
                      </svg>
                      Review Pembayaran
                    </Link>
                    <Link :href="route('admin.reports.index')" class="flex items-center p-3 text-red-600 bg-red-50 rounded-lg hover:bg-red-100 transition-colors">
                      <svg class="w-5 h-5 mr-3" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M3 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm0 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm0 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1z"/>
                      </svg>
                      View Reports
                    </Link>
                  </div>
                </div>

                <div class="bg-white border border-gray-200 rounded-lg p-6">
                  <h4 class="text-lg font-medium text-gray-900 mb-4">Recent Activity</h4>
                  <div class="space-y-3">
                    <div v-for="activity in recentActivities" :key="activity.id" class="flex items-start space-x-3">
                      <div class="w-2 h-2 bg-blue-600 rounded-full mt-2 flex-shrink-0"></div>
                      <div>
                        <p class="text-sm text-gray-900">{{ activity.description }}</p>
                        <p class="text-xs text-gray-500">{{ activity.time }}</p>
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
  </AdminLayout>
</template>

<script setup>
import { ref } from 'vue'
import { Head, Link } from '@inertiajs/vue3'
import AdminLayout from '@/Layouts/AdminLayout.vue'

// Mock data - replace with real data from backend
const totalUsers = ref(1247)
const activeJamaah = ref(89)
const pendingApprovals = ref(12)
const monthlyRevenue = ref('2.4M')

const recentActivities = ref([
  {
    id: 1,
    description: 'New jamaah registration approved',
    time: '1 hour ago'
  },
  {
    id: 2,
    description: 'Payment verification completed',
    time: '3 hours ago'
  },
  {
    id: 3,
    description: 'New marketing user created',
    time: '6 hours ago'
  },
  {
    id: 4,
    description: 'System backup completed',
    time: '1 day ago'
  }
])
</script>