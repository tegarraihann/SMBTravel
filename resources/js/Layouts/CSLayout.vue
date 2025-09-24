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
    <div class="min-h-screen bg-gray-100">
        <!-- Sidebar -->
        <div
            :class="[
                'fixed inset-y-0 left-0 z-50 w-64 bg-white shadow-lg transition-transform duration-300 ease-in-out lg:translate-x-0',
                sidebarOpen ? 'translate-x-0' : '-translate-x-full'
            ]"
        >
            <!-- Sidebar Header -->
            <div class="flex h-16 items-center justify-between border-b border-gray-200 px-4">
                <Link :href="route('cs.dashboard')" class="flex items-center">
                    <ApplicationLogo class="block h-8 w-auto fill-current text-blue-600" />
                    <span class="ml-2 text-lg font-semibold text-gray-900">CS Panel</span>
                </Link>
            </div>

            <!-- Sidebar Navigation -->
            <nav class="mt-5 px-2">
                <div class="space-y-1">
                    <!-- Dashboard -->
                    <Link
                        :href="route('cs.dashboard')"
                        :class="[
                            'group flex items-center px-2 py-2 text-sm font-medium rounded-md transition-colors',
                            route().current('cs.dashboard')
                                ? 'bg-blue-100 text-blue-900'
                                : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900'
                        ]"
                    >
                        <svg
                            :class="[
                                'mr-3 h-5 w-5',
                                route().current('cs.dashboard') ? 'text-blue-500' : 'text-gray-400'
                            ]"
                            fill="none"
                            stroke="currentColor"
                            viewBox="0 0 24 24"
                        >
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2H5a2 2 0 00-2-2z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 5a2 2 0 012-2h4a2 2 0 012 2v0H8v0z"/>
                        </svg>
                        Dashboard
                    </Link>

                    <!-- Jamaah Management -->
                    <Link
                        :href="route('cs.jamaah.index')"
                        :class="[
                            'group flex items-center px-2 py-2 text-sm font-medium rounded-md transition-colors',
                            route().current('cs.jamaah.*')
                                ? 'bg-blue-100 text-blue-900'
                                : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900'
                        ]"
                    >
                        <svg
                            :class="[
                                'mr-3 h-5 w-5',
                                route().current('cs.jamaah.*') ? 'text-blue-500' : 'text-gray-400'
                            ]"
                            fill="none"
                            stroke="currentColor"
                            viewBox="0 0 24 24"
                        >
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"/>
                        </svg>
                        Jamaah
                    </Link>

                    <!-- Inquiries -->
                    <Link
                        :href="route('cs.inquiries.index')"
                        :class="[
                            'group flex items-center px-2 py-2 text-sm font-medium rounded-md transition-colors',
                            route().current('cs.inquiries.*')
                                ? 'bg-blue-100 text-blue-900'
                                : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900'
                        ]"
                    >
                        <svg
                            :class="[
                                'mr-3 h-5 w-5',
                                route().current('cs.inquiries.*') ? 'text-blue-500' : 'text-gray-400'
                            ]"
                            fill="none"
                            stroke="currentColor"
                            viewBox="0 0 24 24"
                        >
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                        </svg>
                        Pertanyaan
                    </Link>

                    <!-- Complaints -->
                    <Link
                        :href="route('cs.complaints.index')"
                        :class="[
                            'group flex items-center px-2 py-2 text-sm font-medium rounded-md transition-colors',
                            route().current('cs.complaints.*')
                                ? 'bg-blue-100 text-blue-900'
                                : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900'
                        ]"
                    >
                        <svg
                            :class="[
                                'mr-3 h-5 w-5',
                                route().current('cs.complaints.*') ? 'text-blue-500' : 'text-gray-400'
                            ]"
                            fill="none"
                            stroke="currentColor"
                            viewBox="0 0 24 24"
                        >
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.732-.833-2.464 0L4.35 16.5c-.77.833.192 2.5 1.732 2.5z"/>
                        </svg>
                        Keluhan
                    </Link>
                </div>

                <!-- Section divider -->
                <div class="mt-8">
                    <div class="px-2 mb-2">
                        <h3 class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Laporan</h3>
                    </div>
                    <div class="space-y-1">
                        <!-- Reports could be added here -->
                        <div class="px-2 py-2 text-sm text-gray-400">
                            Segera hadir...
                        </div>
                    </div>
                </div>
            </nav>
        </div>

        <!-- Main Content -->
        <div class="lg:pl-64">
            <!-- Top Navigation -->
            <div class="sticky top-0 z-40 lg:mx-auto lg:max-w-7xl lg:px-8">
                <div class="flex h-16 items-center gap-x-4 border-b border-gray-200 bg-white px-4 shadow-sm sm:gap-x-6 sm:px-6 lg:px-0 lg:shadow-none">
                    <!-- Sidebar toggle, controls the 'sidebarOpen' sidebar state. -->
                    <button
                        type="button"
                        class="-m-2.5 p-2.5 text-gray-700 lg:hidden"
                        @click="sidebarOpen = !sidebarOpen"
                    >
                        <span class="sr-only">Open sidebar</span>
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5" />
                        </svg>
                    </button>

                    <!-- Separator -->
                    <div class="h-6 w-px bg-gray-200 lg:hidden" aria-hidden="true"></div>

                    <div class="flex flex-1 gap-x-4 self-stretch lg:gap-x-6">
                        <div class="flex flex-1 items-center">
                            <!-- Page title will go here -->
                            <slot name="header" />
                        </div>
                        <div class="flex items-center gap-x-4 lg:gap-x-6">
                            <!-- User dropdown -->
                            <div class="relative">
                                <Dropdown align="right" width="48">
                                    <template #trigger>
                                        <span class="inline-flex rounded-md">
                                            <button
                                                type="button"
                                                class="inline-flex items-center rounded-md border border-transparent bg-white px-3 py-2 text-sm font-medium leading-4 text-gray-500 transition duration-150 ease-in-out hover:text-gray-700 focus:outline-none"
                                            >
                                                {{ $page.props.auth.user.name }}
                                                <svg
                                                    class="-me-0.5 ms-2 h-4 w-4"
                                                    xmlns="http://www.w3.org/2000/svg"
                                                    viewBox="0 0 20 20"
                                                    fill="currentColor"
                                                >
                                                    <path
                                                        fill-rule="evenodd"
                                                        d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                                        clip-rule="evenodd"
                                                    />
                                                </svg>
                                            </button>
                                        </span>
                                    </template>

                                    <template #content>
                                        <DropdownLink :href="route('profile.edit')">
                                            Profile
                                        </DropdownLink>
                                        <DropdownLink
                                            :href="route('logout')"
                                            method="post"
                                            as="button"
                                        >
                                            Log Out
                                        </DropdownLink>
                                    </template>
                                </Dropdown>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Page Content -->
            <main class="py-10">
                <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                    <slot />
                </div>
            </main>
        </div>

        <!-- Sidebar overlay for mobile -->
        <div
            v-if="sidebarOpen"
            class="fixed inset-0 z-40 bg-gray-600 bg-opacity-75 lg:hidden"
            @click="sidebarOpen = false"
        ></div>
    </div>
</template>