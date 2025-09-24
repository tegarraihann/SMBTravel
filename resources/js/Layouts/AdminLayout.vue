<script setup>
import { ref } from 'vue';
import ApplicationLogo from '@/Components/ApplicationLogo.vue';
import Dropdown from '@/Components/Dropdown.vue';
import DropdownLink from '@/Components/DropdownLink.vue';
import { Link } from '@inertiajs/vue3';

const showingNavigationDropdown = ref(false);
const sidebarOpen = ref(false);
</script>

<template>
    <div>
        <div class="min-h-screen bg-gray-100">
            <!-- Mobile sidebar overlay -->
            <div v-show="sidebarOpen" class="fixed inset-0 z-40 lg:hidden">
                <div class="fixed inset-0 bg-gray-600 bg-opacity-75" @click="sidebarOpen = false"></div>
            </div>

            <!-- Sidebar -->
            <div class="fixed inset-y-0 left-0 z-50 w-64 bg-white shadow-lg transform transition-transform duration-300 ease-in-out lg:translate-x-0"
                 :class="{ 'translate-x-0': sidebarOpen, '-translate-x-full': !sidebarOpen }">

                <!-- Sidebar Header -->
                <div class="flex items-center justify-center h-16 px-4 border-b border-gray-200">
                    <Link :href="route('admin.dashboard')" class="flex items-center">
                        <ApplicationLogo class="block h-8 w-auto fill-current text-gray-800" />
                        <span class="ml-2 text-lg font-semibold text-gray-800">Admin Panel</span>
                    </Link>
                </div>

                <!-- Sidebar Navigation -->
                <nav class="mt-8 px-4 pb-20">
                    <div class="space-y-2">
                        <!-- Dashboard -->
                        <Link :href="route('admin.dashboard')"
                              :class="route().current('admin.dashboard')
                                ? 'bg-blue-50 border-blue-500 text-blue-700 border-l-4'
                                : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900'"
                              class="group flex items-center px-3 py-2 text-sm font-medium rounded-md transition-colors duration-150">
                            <svg class="flex-shrink-0 -ml-1 mr-3 h-6 w-6"
                                 :class="route().current('admin.dashboard') ? 'text-blue-500' : 'text-gray-400 group-hover:text-gray-500'"
                                 fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2H5a2 2 0 00-2 2v10z" />
                            </svg>
                            Dashboard
                        </Link>

                        <!-- Review Pembayaran -->
                        <Link :href="route('admin.payment.review')"
                              :class="route().current('admin.payment.review*')
                                ? 'bg-blue-50 border-blue-500 text-blue-700 border-l-4'
                                : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900'"
                              class="group flex items-center px-3 py-2 text-sm font-medium rounded-md transition-colors duration-150">
                            <svg class="flex-shrink-0 -ml-1 mr-3 h-6 w-6"
                                 :class="route().current('admin.payment.review*') ? 'text-blue-500' : 'text-gray-400 group-hover:text-gray-500'"
                                 fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                            Review Pembayaran
                        </Link>

                        <!-- Manajemen Manasik -->
                        <Link :href="route('admin.manasik.index')"
                              :class="route().current('admin.manasik*')
                                ? 'bg-blue-50 border-blue-500 text-blue-700 border-l-4'
                                : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900'"
                              class="group flex items-center px-3 py-2 text-sm font-medium rounded-md transition-colors duration-150">
                            <svg class="flex-shrink-0 -ml-1 mr-3 h-6 w-6"
                                 :class="route().current('admin.manasik*') ? 'text-blue-500' : 'text-gray-400 group-hover:text-gray-500'"
                                 fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                            Manajemen Manasik
                        </Link>

                        <!-- Manajemen Cicilan -->
                        <Link :href="route('admin.installments.index')"
                              :class="route().current('admin.installments*')
                                ? 'bg-purple-50 border-purple-500 text-purple-700 border-l-4'
                                : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900'"
                              class="group flex items-center px-3 py-2 text-sm font-medium rounded-md transition-colors duration-150">
                            <svg class="flex-shrink-0 -ml-1 mr-3 h-6 w-6"
                                 :class="route().current('admin.installments*') ? 'text-purple-500' : 'text-gray-400 group-hover:text-gray-500'"
                                 fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1" />
                            </svg>
                            Manajemen Cicilan
                        </Link>
                    </div>
                </nav>

                <!-- Sidebar Footer -->
                <div class="absolute bottom-0 left-0 right-0 p-4 border-t border-gray-200 bg-white">
                    <div class="flex items-center text-sm text-gray-500">
                        <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-6-3a2 2 0 11-4 0 2 2 0 014 0zm-2 4a5 5 0 00-4.546 2.916A5.986 5.986 0 0010 16a5.986 5.986 0 004.546-2.084A5 5 0 0010 11z" clip-rule="evenodd" />
                        </svg>
                        {{ $page.props.auth.user.name }}
                    </div>
                </div>
            </div>

            <!-- Main content -->
            <div class="lg:pl-64">
                <!-- Top navigation -->
                <div class="sticky top-0 z-30 flex-shrink-0 flex h-16 bg-white shadow border-b border-gray-200">
                    <!-- Mobile menu button -->
                    <button type="button"
                            class="px-4 border-r border-gray-200 text-gray-500 focus:outline-none focus:ring-2 focus:ring-inset focus:ring-blue-500 lg:hidden"
                            @click="sidebarOpen = true">
                        <span class="sr-only">Open sidebar</span>
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        </svg>
                    </button>

                    <!-- Top navigation content -->
                    <div class="flex-1 px-4 flex justify-between items-center">
                        <!-- Page title -->
                        <div class="flex items-center">
                            <div v-if="$slots.header">
                                <slot name="header" />
                            </div>
                        </div>

                        <!-- Profile dropdown -->
                        <div class="flex items-center">
                            <Dropdown align="right" width="48">
                                <template #trigger>
                                    <span class="inline-flex rounded-md">
                                        <button type="button"
                                                class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 bg-white hover:text-gray-700 focus:outline-none transition ease-in-out duration-150">
                                            {{ $page.props.auth.user.name }}
                                            <svg class="ml-2 -mr-0.5 h-4 w-4" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                            </svg>
                                        </button>
                                    </span>
                                </template>

                                <template #content>
                                    <DropdownLink :href="route('profile.edit')">Profile</DropdownLink>
                                    <DropdownLink :href="route('logout')" method="post" as="button">
                                        Log Out
                                    </DropdownLink>
                                </template>
                            </Dropdown>
                        </div>
                    </div>
                </div>

                <!-- Page content -->
                <main>
                    <slot />
                </main>
            </div>
        </div>
    </div>
</template>