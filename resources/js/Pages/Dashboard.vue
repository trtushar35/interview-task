<script>
import BackendLayout from '@/Layouts/BackendLayout.vue';

export default {
    name: 'Dashboard',
    components: {
        BackendLayout
    },
    data() {
        return {
            monthlySales: [
                { month: 'Jan', value: 400 },
                { month: 'Feb', value: 300 },
                { month: 'Mar', value: 500 },
                { month: 'Apr', value: 450 },
                { month: 'May', value: 600 },
                { month: 'Jun', value: 550 },
                { month: 'Jul', value: 700 },
                { month: 'Aug', value: 650 },
                { month: 'Sep', value: 750 },
                { month: 'Oct', value: 800 },
                { month: 'Nov', value: 780 },
                { month: 'Dec', value: 820 }
            ]
        }
    }
}
</script>

<template>
    <BackendLayout>

        <!-- Stats Cards Row 1 -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 md:gap-6 mb-6 md:mb-8">
            <!-- Customers Card -->
            <div
                class="bg-white rounded-lg shadow-sm p-5 md:p-6 border border-gray-100 hover:shadow-md transition-shadow">
                <div class="flex items-start justify-between">
                    <div>
                        <p class="text-gray-600 text-xs md:text-sm mb-2">Customers</p>
                        <div class="flex items-baseline gap-2">
                            <h3 class="text-2xl md:text-3xl font-bold text-gray-900">3,782</h3>
                            <span class="text-green-500 text-xs md:text-sm font-semibold flex items-center gap-1">
                                <svg class="w-3 h-3 md:w-4 md:h-4" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                        d="M5.293 9.707a1 1 0 010-1.414l4-4a1 1 0 011.414 0l4 4a1 1 0 01-1.414 1.414L11 7.414V15a1 1 0 11-2 0V7.414L6.707 9.707a1 1 0 01-1.414 0z"
                                        clip-rule="evenodd" />
                                </svg>
                                11.01%
                            </span>
                        </div>
                    </div>
                    <div class="bg-blue-50 p-2.5 md:p-3 rounded-lg">
                        <svg class="w-5 h-5 md:w-6 md:h-6 text-blue-600" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Orders Card -->
            <div
                class="bg-white rounded-lg shadow-sm p-5 md:p-6 border border-gray-100 hover:shadow-md transition-shadow">
                <div class="flex items-start justify-between">
                    <div>
                        <p class="text-gray-600 text-xs md:text-sm mb-2">Orders</p>
                        <div class="flex items-baseline gap-2">
                            <h3 class="text-2xl md:text-3xl font-bold text-gray-900">5,359</h3>
                            <span class="text-red-500 text-xs md:text-sm font-semibold flex items-center gap-1">
                                <svg class="w-3 h-3 md:w-4 md:h-4" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                        d="M14.707 10.293a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 111.414-1.414L9 12.586V5a1 1 0 012 0v7.586l2.293-2.293a1 1 0 011.414 0z"
                                        clip-rule="evenodd" />
                                </svg>
                                9.05%
                            </span>
                        </div>
                    </div>
                    <div class="bg-blue-50 p-2.5 md:p-3 rounded-lg">
                        <svg class="w-5 h-5 md:w-6 md:h-6 text-blue-600" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Monthly Sales Card -->
            <div
                class="bg-white rounded-lg shadow-sm p-5 md:p-6 border border-gray-100 hover:shadow-md transition-shadow md:col-span-2 lg:col-span-1">
                <div class="flex items-start justify-between mb-3 md:mb-4">
                    <div>
                        <p class="text-gray-600 text-xs md:text-sm mb-2">Monthly Sales</p>
                        <h3 class="text-2xl md:text-3xl font-bold text-gray-900">75.55%</h3>
                        <p class="text-green-500 text-xs md:text-sm font-semibold mt-1">+10%</p>
                    </div>
                </div>
                <p class="text-gray-600 text-xs md:text-sm">
                    You earn $3287 today, its higher than last month. Keep up your good work!
                </p>
            </div>
        </div>

        <!-- Monthly Sales & Target Section -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-4 md:gap-6 mb-6 md:mb-8">
            <!-- Monthly Sales Chart -->
            <div class="lg:col-span-2 bg-white rounded-lg shadow-sm p-5 md:p-6 border border-gray-100">
                <div class="flex items-center justify-between mb-4 md:mb-6">
                    <h3 class="text-base md:text-lg font-semibold text-gray-900">Monthly Sales</h3>
                    <button class="p-1 hover:bg-gray-100 rounded transition-colors">
                        <svg class="w-5 h-5 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                            <path
                                d="M10 6a2 2 0 110-4 2 2 0 010 4zM10 12a2 2 0 110-4 2 2 0 010 4zM10 18a2 2 0 110-4 2 2 0 010 4z" />
                        </svg>
                    </button>
                </div>

                <div class="flex items-end justify-between h-48 md:h-64 gap-1.5 md:gap-3">
                    <div v-for="(item, index) in monthlySales" :key="index" class="flex flex-col items-center flex-1">
                        <div class="w-full bg-blue-500 rounded-t transition-all duration-300 hover:bg-blue-600 cursor-pointer"
                            :style="{ height: (item.value / 800) * 100 + '%', minHeight: '20px' }"></div>
                        <p class="text-[10px] md:text-xs text-gray-600 mt-2 md:mt-3">{{ item.month }}</p>
                    </div>
                </div>

                <div class="flex justify-between items-center mt-4 md:mt-6 pt-3 md:pt-4 border-t border-gray-200">
                    <div class="text-center">
                        <p class="text-[10px] md:text-xs text-gray-600">Min</p>
                        <p class="text-xs md:text-sm font-semibold text-gray-900">{{Math.min(...monthlySales.map(d => d.value)) }}</p>
                    </div>
                    <div class="text-center">
                        <p class="text-[10px] md:text-xs text-gray-600">Avg</p>
                        <p class="text-xs md:text-sm font-semibold text-gray-900">{{Math.round(monthlySales.reduce((a, b) => a + b.value, 0) / monthlySales.length) }}</p>
                    </div>
                    <div class="text-center">
                        <p class="text-[10px] md:text-xs text-gray-600">Max</p>
                        <p class="text-xs md:text-sm font-semibold text-gray-900">{{Math.max(...monthlySales.map(d => d.value)) }}</p>
                    </div>
                </div>
            </div>

            <!-- Monthly Target Progress -->
            <div class="bg-white rounded-lg shadow-sm p-5 md:p-6 border border-gray-100">
                <div class="flex items-center justify-between mb-4 md:mb-6">
                    <h3 class="text-base md:text-lg font-semibold text-gray-900">Monthly Target</h3>
                    <button class="p-1 hover:bg-gray-100 rounded transition-colors">
                        <svg class="w-5 h-5 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                            <path
                                d="M10 6a2 2 0 110-4 2 2 0 010 4zM10 12a2 2 0 110-4 2 2 0 010 4zM10 18a2 2 0 110-4 2 2 0 010 4z" />
                        </svg>
                    </button>
                </div>

                <div class="flex justify-center items-center mb-6 md:mb-8">
                    <div class="relative w-28 h-28 md:w-32 md:h-32">
                        <svg class="w-full h-full transform -rotate-90" viewBox="0 0 120 120">
                            <!-- Background circle -->
                            <circle cx="60" cy="60" r="54" fill="none" stroke="#e5e7eb" stroke-width="8"></circle>
                            <!-- Progress circle -->
                            <circle cx="60" cy="60" r="54" fill="none" stroke="#3b82f6" stroke-width="8"
                                stroke-dasharray="270 360" stroke-linecap="round"></circle>
                        </svg>
                        <div class="absolute inset-0 flex flex-col items-center justify-center">
                            <p class="text-2xl md:text-3xl font-bold text-gray-900">75.55%</p>
                            <p class="text-green-500 text-xs font-semibold">+10%</p>
                        </div>
                    </div>
                </div>

                <p class="text-gray-600 text-xs md:text-sm text-center mb-4 md:mb-6">
                    You earn $3287 today, its higher than last month. Keep up your good work!
                </p>

                <div class="space-y-2.5 md:space-y-3 text-xs md:text-sm">
                    <div class="flex justify-between items-center">
                        <span class="text-gray-600">Target</span>
                        <div class="flex items-center gap-2">
                            <span class="font-semibold text-gray-900">$20K</span>
                            <span class="text-red-500">↓</span>
                        </div>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-gray-600">Revenue</span>
                        <div class="flex items-center gap-2">
                            <span class="font-semibold text-gray-900">$16K</span>
                            <span class="text-green-500">↑</span>
                        </div>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-gray-600">Today</span>
                        <div class="flex items-center gap-2">
                            <span class="font-semibold text-gray-900">$1.5K</span>
                            <span class="text-green-500">↑</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Statistics Section -->
        <div class="bg-white rounded-lg shadow-sm p-5 md:p-6 border border-gray-100">
            <div class="flex flex-col md:flex-row md:items-center justify-between mb-4 md:mb-6 gap-3">
                <div>
                    <h3 class="text-base md:text-lg font-semibold text-gray-900">Statistics</h3>
                    <p class="text-gray-500 text-xs md:text-sm mt-1">Target you've set for each month</p>
                </div>
                <div class="flex gap-2 overflow-x-auto">
                    <button
                        class="px-3 md:px-4 py-1.5 md:py-2 text-xs md:text-sm text-white bg-blue-600 rounded-lg whitespace-nowrap">
                        Overview
                    </button>
                    <button
                        class="px-3 md:px-4 py-1.5 md:py-2 text-xs md:text-sm text-gray-700 hover:bg-gray-100 rounded-lg whitespace-nowrap">
                        Sales
                    </button>
                    <button
                        class="px-3 md:px-4 py-1.5 md:py-2 text-xs md:text-sm text-gray-700 hover:bg-gray-100 rounded-lg whitespace-nowrap">
                        Revenue
                    </button>
                </div>
            </div>
        </div>
    </BackendLayout>
</template>