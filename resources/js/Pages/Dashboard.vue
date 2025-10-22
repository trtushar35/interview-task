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
            ],
            recentOrders: [
                {
                    id: 1,
                    name: 'Macbook pro 13"',
                    variants: '2 Variants',
                    category: 'Laptop',
                    price: 2399.00,
                    status: 'Delivered',
                    image: '💻'
                },
                {
                    id: 2,
                    name: 'Apple Watch Ultra',
                    variants: '1 Variants',
                    category: 'Watch',
                    price: 879.00,
                    status: 'Pending',
                    image: '⌚'
                },
                {
                    id: 3,
                    name: 'iPhone 15 Pro Max',
                    variants: '2 Variants',
                    category: 'Smart Phone',
                    price: 1869.00,
                    status: 'Delivered',
                    image: '📱'
                },
                {
                    id: 4,
                    name: 'iPad Pro 3rd Gen',
                    variants: '2 Variants',
                    category: 'Electronics',
                    price: 1699.00,
                    status: 'Canceled',
                    image: '📱'
                },
                {
                    id: 5,
                    name: 'Airpods Pro 2nd Gen',
                    variants: '1 Variants',
                    category: 'Accessories',
                    price: 240.00,
                    status: 'Delivered',
                    image: '🎧'
                }
            ],
            customerDemographic: [
                { country: 'USA', customers: 2379, percentage: 79, flag: '🇺🇸' },
                { country: 'France', customers: 589, percentage: 23, flag: '🇫🇷' },
                { country: 'Japan', customers: 412, percentage: 18, flag: '🇯🇵' },
                { country: 'India', customers: 328, percentage: 15, flag: '🇮🇳' }
            ],
            map: null
        }
    },
    methods: {
        getStatusClass(status) {
            const classes = {
                'Delivered': 'text-green-600 bg-green-50',
                'Pending': 'text-orange-600 bg-orange-50',
                'Canceled': 'text-red-600 bg-red-50'
            };
            return classes[status] || 'text-gray-600 bg-gray-50';
        },
        
        loadLeaflet() {
            return new Promise((resolve, reject) => {
                // Check if Leaflet is already loaded
                if (typeof L !== 'undefined') {
                    resolve();
                    return;
                }
                
                // Load Leaflet CSS
                const link = document.createElement('link');
                link.rel = 'stylesheet';
                link.href = 'https://unpkg.com/leaflet@1.9.4/dist/leaflet.css';
                link.integrity = 'sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=';
                link.crossOrigin = '';
                document.head.appendChild(link);
                
                // Load Leaflet JS
                const script = document.createElement('script');
                script.src = 'https://unpkg.com/leaflet@1.9.4/dist/leaflet.js';
                script.integrity = 'sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=';
                script.crossOrigin = '';
                script.onload = () => resolve();
                script.onerror = () => reject(new Error('Failed to load Leaflet'));
                document.head.appendChild(script);
            });
        },
        
        initCustomerMap() {
            // Wait for the DOM to be fully rendered
            this.$nextTick(() => {
                // Check if Leaflet is available
                if (typeof L === 'undefined') {
                    console.error('Leaflet is not loaded. Please check the script import.');
                    this.showFallbackMap();
                    return;
                }
                
                // Check if the map container exists
                const mapContainer = document.getElementById('customer-map');
                if (!mapContainer) {
                    console.error('Map container not found');
                    return;
                }
                
                // Check if map already initialized
                if (mapContainer._leaflet_id) {
                    return;
                }
                
                try {
                    // Initialize the map
                    this.map = L.map('customer-map').setView([30, 0], 2);
                    
                    // Add OpenStreetMap tiles
                    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                        attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors',
                        maxZoom: 18,
                    }).addTo(this.map);
                    
                    // Remove default Leaflet controls for cleaner look
                    this.map.attributionControl.remove();
                    this.map.removeControl(this.map.zoomControl);
                    
                    // Add customer markers
                    const customerData = [
                        { country: 'USA', lat: 37.0902, lng: -95.7129, customers: 2379, color: '#3b82f6' },
                        { country: 'France', lat: 46.6034, lng: 1.8883, customers: 589, color: '#10b981' },
                    ];
                    
                    customerData.forEach(location => {
                        // Calculate marker size based on customer count
                        const size = Math.max(8, Math.min(20, location.customers / 100));
                        
                        // Create custom marker
                        const marker = L.circleMarker([location.lat, location.lng], {
                            color: location.color,
                            fillColor: location.color,
                            fillOpacity: 0.7,
                            radius: size,
                            weight: 2
                        }).addTo(this.map);
                        
                        // Add pulsing animation
                        this.addPulseEffect(marker, location.color);
                        
                        // Add tooltip
                        marker.bindTooltip(`
                            <div class="text-sm font-semibold">${location.country}</div>
                            <div class="text-xs">${location.customers.toLocaleString()} Customers</div>
                        `);
                    });
                    
                    // Force map resize to ensure proper rendering
                    setTimeout(() => {
                        if (this.map) {
                            this.map.invalidateSize();
                        }
                    }, 100);
                } catch (error) {
                    console.error('Error initializing map:', error);
                    this.showFallbackMap();
                }
            });
        },
        
        addPulseEffect(marker, color) {
            try {
                // Create pulsing effect
                const pulse = L.circleMarker(marker.getLatLng(), {
                    color: color,
                    fillColor: color,
                    fillOpacity: 0.2,
                    radius: marker.options.radius * 1.5,
                    weight: 1
                }).addTo(marker._map);
                
                // Animate the pulse
                let scale = 1;
                const animatePulse = () => {
                    if (pulse && pulse.setRadius) {
                        scale = scale >= 2 ? 1 : scale + 0.05;
                        pulse.setRadius(marker.options.radius * scale);
                        requestAnimationFrame(animatePulse);
                    }
                };
                animatePulse();
            } catch (error) {
                console.error('Error adding pulse effect:', error);
            }
        },
        
        showFallbackMap() {
            const mapContainer = document.getElementById('customer-map');
            if (mapContainer && !mapContainer.querySelector('svg')) {
                mapContainer.innerHTML = `
                    <svg class="w-full h-full" viewBox="0 0 400 200" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <rect width="400" height="200" fill="url(#mapGradient)" />
                        <defs>
                            <linearGradient id="mapGradient" x1="0%" y1="0%" x2="100%" y2="100%">
                                <stop offset="0%" stop-color="#dbeafe" />
                                <stop offset="100%" stop-color="#e0e7ff" />
                            </linearGradient>
                        </defs>
                        <path d="M80,80 Q100,60 120,80 Q140,100 120,120 Q100,140 80,120 Q60,100 80,80Z"
                            fill="#93c5fd" fill-opacity="0.3" stroke="#3b82f6" stroke-width="1" />
                        <path d="M200,60 Q220,40 240,60 Q260,80 240,100 Q220,120 200,100 Q180,80 200,60Z"
                            fill="#93c5fd" fill-opacity="0.3" stroke="#3b82f6" stroke-width="1" />
                        <path d="M300,100 Q320,80 340,100 Q360,120 340,140 Q320,160 300,140 Q280,120 300,100Z"
                            fill="#93c5fd" fill-opacity="0.3" stroke="#3b82f6" stroke-width="1" />
                        <circle cx="120" cy="80" r="8" fill="#3b82f6" stroke="white" stroke-width="2">
                            <animate attributeName="r" values="8;10;8" dur="2s" repeatCount="indefinite" />
                        </circle>
                        <circle cx="220" cy="70" r="6" fill="#10b981" stroke="white" stroke-width="2">
                            <animate attributeName="r" values="6;8;6" dur="2s" repeatCount="indefinite" begin="0.5s" />
                        </circle>
                    </svg>
                `;
            }
        }
    },
    
    mounted() {
        // Load Leaflet dynamically if not already loaded
        if (typeof L === 'undefined') {
            this.loadLeaflet().then(() => {
                setTimeout(() => {
                    this.initCustomerMap();
                }, 100);
            }).catch(error => {
                console.error('Failed to load Leaflet:', error);
                this.showFallbackMap();
            });
        } else {
            setTimeout(() => {
                this.initCustomerMap();
            }, 100);
        }
    },
    
    beforeUnmount() {
        // Clean up the map when component is destroyed
        if (this.map) {
            this.map.remove();
            this.map = null;
        }
    }
}
</script>

<template>
    <BackendLayout>
        <div class="px-3 md:px-6 py-4 md:py-6">
            <!-- Stats Cards Row -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 mb-6">
                <!-- Customers Card -->
                <div
                    class="bg-white rounded-2xl shadow-sm p-5 border border-gray-100 hover:shadow-md transition-shadow">
                    <div class="flex items-center gap-4 mb-4">
                        <div class="bg-gray-50 p-3 rounded-xl">
                            <svg class="w-6 h-6 text-gray-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                            </svg>
                        </div>
                    </div>
                    <p class="text-gray-500 text-sm mb-2">Customers</p>
                    <div class="flex items-end justify-between">
                        <h3 class="text-3xl font-bold text-gray-900">3,782</h3>
                        <span class="text-green-500 text-sm font-semibold flex items-center gap-1 mb-1">
                            ↑ 11.01%
                        </span>
                    </div>
                </div>

                <!-- Orders Card -->
                <div
                    class="bg-white rounded-2xl shadow-sm p-5 border border-gray-100 hover:shadow-md transition-shadow">
                    <div class="flex items-center gap-4 mb-4">
                        <div class="bg-gray-50 p-3 rounded-xl">
                            <svg class="w-6 h-6 text-gray-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                            </svg>
                        </div>
                    </div>
                    <p class="text-gray-500 text-sm mb-2">Orders</p>
                    <div class="flex items-end justify-between">
                        <h3 class="text-3xl font-bold text-gray-900">5,359</h3>
                        <span class="text-red-500 text-sm font-semibold flex items-center gap-1 mb-1">
                            ↓ 9.05%
                        </span>
                    </div>
                </div>

                <!-- Monthly Sales Card (Mobile Full Width) -->
                <div
                    class="bg-white rounded-2xl shadow-sm p-5 border border-gray-100 hover:shadow-md transition-shadow md:col-span-2 lg:col-span-1">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-base font-semibold text-gray-900">Monthly Sales</h3>
                        <button class="p-1.5 hover:bg-gray-100 rounded-lg transition-colors">
                            <svg class="w-5 h-5 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                <path
                                    d="M10 6a2 2 0 110-4 2 2 0 010 4zM10 12a2 2 0 110-4 2 2 0 010 4zM10 18a2 2 0 110-4 2 2 0 010 4z" />
                            </svg>
                        </button>
                    </div>

                    <div class="flex items-end justify-between h-32 gap-1.5">
                        <div v-for="(item, index) in monthlySales" :key="index"
                            class="flex flex-col items-center flex-1">
                            <div class="w-full rounded-t transition-all duration-300 hover:opacity-80 cursor-pointer"
                                :class="index === 2 ? 'bg-blue-600' : 'bg-gray-200'"
                                :style="{ height: (item.value / 800) * 100 + '%', minHeight: '16px' }">
                            </div>
                        </div>
                    </div>

                    <div class="mt-3 pt-3 border-t border-gray-100">
                        <p class="text-[10px] text-gray-500 text-center">
                            Jan, Feb, Mar, Apr, May, Jun, Jul, Aug, Sep, Oct, Nov, Dec
                        </p>
                    </div>
                </div>
            </div>

            <!-- Monthly Target & Statistics -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">
                <!-- Monthly Sales Large Chart -->
                <div class="lg:col-span-2 bg-white rounded-2xl shadow-sm p-5 md:p-6 border border-gray-100">
                    <div class="flex items-center justify-between mb-6">
                        <h3 class="text-lg font-semibold text-gray-900">Monthly Sales</h3>
                        <button class="p-1.5 hover:bg-gray-100 rounded-lg transition-colors">
                            <svg class="w-5 h-5 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                <path
                                    d="M10 6a2 2 0 110-4 2 2 0 010 4zM10 12a2 2 0 110-4 2 2 0 010 4zM10 18a2 2 0 110-4 2 2 0 010 4z" />
                            </svg>
                        </button>
                    </div>

                    <div class="flex items-end justify-between h-64 gap-2 md:gap-3">
                        <div v-for="(item, index) in monthlySales" :key="index"
                            class="flex flex-col items-center flex-1">
                            <div class="w-full bg-blue-600 rounded-t transition-all duration-300 hover:bg-blue-700 cursor-pointer"
                                :style="{ height: (item.value / 800) * 100 + '%', minHeight: '24px' }">
                            </div>
                            <p class="text-xs text-gray-600 mt-3">{{ item.month }}</p>
                        </div>
                    </div>

                    <div class="flex justify-between items-center mt-6 pt-4 border-t border-gray-200">
                        <div class="text-center">
                            <p class="text-xs text-gray-500">Min</p>
                            <p class="text-sm font-semibold text-gray-900">{{Math.min(...monthlySales.map(d =>
                                d.value))}}</p>
                        </div>
                        <div class="text-center">
                            <p class="text-xs text-gray-500">Avg</p>
                            <p class="text-sm font-semibold text-gray-900">{{Math.round(monthlySales.reduce((a, b) => a
                                + b.value, 0) / monthlySales.length)}}</p>
                        </div>
                        <div class="text-center">
                            <p class="text-xs text-gray-500">Max</p>
                            <p class="text-sm font-semibold text-gray-900">{{Math.max(...monthlySales.map(d =>
                                d.value))}}</p>
                        </div>
                    </div>
                </div>

                <!-- Monthly Target -->
                <div class="bg-white rounded-2xl shadow-sm p-5 md:p-6 border border-gray-100">
                    <div class="flex items-center justify-between mb-6">
                        <h3 class="text-lg font-semibold text-gray-900">Monthly Target</h3>
                        <button class="p-1.5 hover:bg-gray-100 rounded-lg transition-colors">
                            <svg class="w-5 h-5 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                <path
                                    d="M10 6a2 2 0 110-4 2 2 0 010 4zM10 12a2 2 0 110-4 2 2 0 010 4zM10 18a2 2 0 110-4 2 2 0 010 4z" />
                            </svg>
                        </button>
                    </div>

                    <p class="text-sm text-gray-500 mb-4">Target you've set for each month</p>

                    <div class="flex justify-center items-center mb-6">
                        <div class="relative w-36 h-36">
                            <svg class="w-full h-full transform -rotate-90" viewBox="0 0 120 120">
                                <circle cx="60" cy="60" r="52" fill="none" stroke="#e5e7eb" stroke-width="10"></circle>
                                <circle cx="60" cy="60" r="52" fill="none" stroke="#3b82f6" stroke-width="10"
                                    stroke-dasharray="246 327" stroke-linecap="round"></circle>
                            </svg>
                            <div class="absolute inset-0 flex flex-col items-center justify-center">
                                <p class="text-3xl font-bold text-gray-900">75.55%</p>
                                <p class="text-green-500 text-xs font-semibold">+10%</p>
                            </div>
                        </div>
                    </div>

                    <p class="text-xs text-gray-600 text-center mb-6">
                        You earn $3287 today, its higher than last month. Keep up your good work!
                    </p>

                    <div class="space-y-3 text-sm">
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

            <!-- Statistics Section with Line Chart -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">
                <!-- Statistics Chart -->
                <div class="lg:col-span-3 bg-white rounded-2xl shadow-sm p-5 md:p-6 border border-gray-100">
                    <div class="flex flex-col md:flex-row md:items-center justify-between mb-6 gap-3">
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900">Statistics</h3>
                            <p class="text-sm text-gray-500 mt-1">Target you've set for each month</p>
                        </div>
                        <div class="flex gap-2 flex-wrap">
                            <button class="px-4 py-2 text-sm text-white bg-gray-900 rounded-lg">Overview</button>
                            <button class="px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 rounded-lg">Sales</button>
                            <button
                                class="px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 rounded-lg">Revenue</button>
                            <button
                                class="px-4 py-2 text-sm text-gray-500 border border-gray-300 hover:bg-gray-50 rounded-lg flex items-center gap-2">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                                05 Feb - 06 March
                            </button>
                        </div>
                    </div>

                    <!-- Line Chart with proper scaling -->
                    <div class="relative h-64 pl-12">
                        <!-- Y-axis labels -->
                        <div class="absolute left-0 top-0 bottom-8 flex flex-col justify-between text-xs text-gray-500">
                            <span>1,000</span>
                            <span>800</span>
                            <span>600</span>
                            <span>400</span>
                            <span>200</span>
                            <span>0</span>
                        </div>

                        <svg class="w-full h-full" viewBox="0 0 1200 280" preserveAspectRatio="xMidYMid meet">
                            <!-- Grid lines -->
                            <line x1="0" y1="0" x2="1200" y2="0" stroke="#f3f4f6" stroke-width="1" />
                            <line x1="0" y1="56" x2="1200" y2="56" stroke="#f3f4f6" stroke-width="1" />
                            <line x1="0" y1="112" x2="1200" y2="112" stroke="#f3f4f6" stroke-width="1" />
                            <line x1="0" y1="168" x2="1200" y2="168" stroke="#f3f4f6" stroke-width="1" />
                            <line x1="0" y1="224" x2="1200" y2="224" stroke="#f3f4f6" stroke-width="1" />
                            <line x1="0" y1="280" x2="1200" y2="280" stroke="#f3f4f6" stroke-width="1" />

                            <!-- Area fill for top line (Blue gradient) -->
                            <defs>
                                <linearGradient id="blueGradient" x1="0%" y1="0%" x2="0%" y2="100%">
                                    <stop offset="0%" style="stop-color:#3b82f6;stop-opacity:0.3" />
                                    <stop offset="100%" style="stop-color:#3b82f6;stop-opacity:0.05" />
                                </linearGradient>
                                <linearGradient id="lightBlueGradient" x1="0%" y1="0%" x2="0%" y2="100%">
                                    <stop offset="0%" style="stop-color:#93c5fd;stop-opacity:0.2" />
                                    <stop offset="100%" style="stop-color:#93c5fd;stop-opacity:0.02" />
                                </linearGradient>
                            </defs>

                            <!-- Top line area fill -->
                            <path fill="url(#blueGradient)"
                                d="M0,60 L100,50 L200,45 L300,42 L400,38 L500,35 L600,30 L700,25 L800,22 L900,18 L1000,15 L1100,10 L1200,5 L1200,280 L0,280 Z" />

                            <!-- Bottom line area fill -->
                            <path fill="url(#lightBlueGradient)"
                                d="M0,140 L100,138 L200,136 L300,134 L400,132 L500,130 L600,128 L700,126 L800,124 L900,122 L1000,120 L1100,118 L1200,115 L1200,280 L0,280 Z" />

                            <!-- Line 1 (Top - Blue) -->
                            <polyline fill="none" stroke="#3b82f6" stroke-width="3"
                                points="0,60 100,50 200,45 300,42 400,38 500,35 600,30 700,25 800,22 900,18 1000,15 1100,10 1200,5" />

                            <!-- Line 2 (Bottom - Light Blue) -->
                            <polyline fill="none" stroke="#93c5fd" stroke-width="3"
                                points="0,140 100,138 200,136 300,134 400,132 500,130 600,128 700,126 800,124 900,122 1000,120 1100,118 1200,115" />
                        </svg>
                    </div>

                    <!-- X-axis labels -->
                    <div class="flex justify-between text-xs text-gray-500 mt-2 pl-12">
                        <span>Jan</span>
                        <span>Feb</span>
                        <span>Mar</span>
                        <span>Apr</span>
                        <span>May</span>
                        <span>Jun</span>
                        <span>Jul</span>
                        <span>Aug</span>
                        <span>Sep</span>
                        <span>Oct</span>
                        <span>Nov</span>
                        <span>Dec</span>
                    </div>
                </div>
            </div>

            <!-- Customers Demographic & Recent Orders -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <!-- Customer Demographic Card -->
                <div class="bg-white rounded-2xl shadow-sm p-5 md:p-6 border border-gray-100">
                    <div class="flex items-center justify-between mb-6">
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900">Customers Demographic</h3>
                            <p class="text-sm text-gray-500 mt-1">Number of customer based on country</p>
                        </div>
                        <button class="p-1.5 hover:bg-gray-100 rounded-lg transition-colors">
                            <svg class="w-5 h-5 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                <path
                                    d="M10 6a2 2 0 110-4 2 2 0 010 4zM10 12a2 2 0 110-4 2 2 0 010 4zM10 18a2 2 0 110-4 2 2 0 010 4z" />
                            </svg>
                        </button>
                    </div>

                    <!-- World Map Visualization -->
                    <div
                        class="bg-gradient-to-br from-blue-50 to-indigo-50 rounded-xl h-48 mb-6 flex items-center justify-center relative overflow-hidden">
                        <!-- OpenStreetMap Container -->
                        <div id="customer-map" class="w-full h-full rounded-xl"></div>

                        <!-- Map Title -->
                        <div class="absolute bottom-3 left-3 bg-white/80 backdrop-blur-sm rounded-lg px-3 py-1">
                            <p class="text-xs font-medium text-gray-700">🌍 Customer Distribution</p>
                        </div>
                    </div>

                    <!-- Country Stats -->
                    <div class="space-y-4">
                        <!-- USA -->
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-3 flex-1">
                                <div
                                    class="w-10 h-10 rounded-full bg-blue-100 flex items-center justify-center border-2 border-white shadow-sm">
                                    <span class="text-lg">🇺🇸</span>
                                </div>
                                <div>
                                    <p class="text-sm font-semibold text-gray-900">USA</p>
                                    <p class="text-xs text-gray-500">2,379 Customers</p>
                                </div>
                            </div>
                            <div class="flex items-center gap-3 flex-1 max-w-[140px]">
                                <div class="flex-1 bg-gray-200 rounded-full h-2">
                                    <div class="bg-blue-600 h-2 rounded-full transition-all duration-500"
                                        style="width: 79%"></div>
                                </div>
                                <span class="text-sm font-semibold text-gray-900 w-8 text-right">79%</span>
                            </div>
                        </div>

                        <!-- France -->
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-3 flex-1">
                                <div
                                    class="w-10 h-10 rounded-full bg-green-100 flex items-center justify-center border-2 border-white shadow-sm">
                                    <span class="text-lg">🇫🇷</span>
                                </div>
                                <div>
                                    <p class="text-sm font-semibold text-gray-900">France</p>
                                    <p class="text-xs text-gray-500">589 Customers</p>
                                </div>
                            </div>
                            <div class="flex items-center gap-3 flex-1 max-w-[140px]">
                                <div class="flex-1 bg-gray-200 rounded-full h-2">
                                    <div class="bg-green-500 h-2 rounded-full transition-all duration-500"
                                        style="width: 23%"></div>
                                </div>
                                <span class="text-sm font-semibold text-gray-900 w-8 text-right">23%</span>
                            </div>
                        </div>

                    </div>
                </div>

                <!-- Recent Orders -->
                <div class="bg-white rounded-2xl shadow-sm p-5 md:p-6 border border-gray-100">
                    <div class="flex items-center justify-between mb-6">
                        <h3 class="text-lg font-semibold text-gray-900">Recent Orders</h3>
                        <div class="flex gap-2">
                            <button
                                class="px-3 py-1.5 text-sm text-gray-700 hover:bg-gray-100 rounded-lg flex items-center gap-2">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z" />
                                </svg>
                                Filter
                            </button>
                            <button class="px-3 py-1.5 text-sm text-blue-600 hover:bg-blue-50 rounded-lg font-medium">
                                See all
                            </button>
                        </div>
                    </div>

                    <!-- Orders Table -->
                    <div class="overflow-x-auto -mx-5 px-5">
                        <table class="w-full">
                            <thead>
                                <tr class="border-b border-gray-200">
                                    <th class="text-left text-xs font-medium text-gray-500 pb-3">Products</th>
                                    <th class="text-left text-xs font-medium text-gray-500 pb-3">Category</th>
                                    <th class="text-left text-xs font-medium text-gray-500 pb-3">Price</th>
                                    <th class="text-left text-xs font-medium text-gray-500 pb-3">Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="order in recentOrders" :key="order.id"
                                    class="border-b border-gray-100 hover:bg-gray-50">
                                    <td class="py-3">
                                        <div class="flex items-center gap-3">
                                            <div class="w-10 h-10 rounded-lg flex items-center justify-center text-xl">
                                                {{ order.image }}
                                            </div>
                                            <div>
                                                <p class="text-sm font-medium text-gray-900">{{ order.name }}</p>
                                                <p class="text-xs text-gray-500">{{ order.variants }}</p>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="py-3 text-sm text-gray-600">{{ order.category }}</td>
                                    <td class="py-3 text-sm font-medium text-gray-900">${{ order.price.toFixed(2) }}
                                    </td>
                                    <td class="py-3">
                                        <span class="px-3 py-1 rounded-full text-xs font-medium"
                                            :class="getStatusClass(order.status)">
                                            {{ order.status }}
                                        </span>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </BackendLayout>
</template>