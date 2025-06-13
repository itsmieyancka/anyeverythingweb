<x-filament::page>
    {{-- Custom blinking animation styles --}}
    <style>
        @keyframes blink {
            0%, 50%, 100% { opacity: 1; }
            25%, 75%     { opacity: 0; }
        }
        .animate-blink {
            animation: blink 1.5s infinite;
        }
    </style>

    {{-- Server status alert --}}
    <div class="mb-6 flex items-center space-x-3">
        <div class="inline-grid [grid-area:1/1] relative w-4 h-4">
            <div class="status status-error animate-ping absolute inset-0 rounded-full bg-red-500 opacity-75"></div>
            <div class="status status-error absolute inset-0 rounded-full bg-red-600"></div>
        </div>
        <span class="text-red-600 font-semibold animate-blink">Server is down</span>
    </div>

    {{-- First row of stats (4 dynamic items) --}}
    <div class="stats shadow grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
        <!-- Total Users -->
        <div class="stat">
            <div class="stat-figure text-amber-500">
                <svg xmlns="http://www.w3.org/2000/svg"
                     fill="none" viewBox="0 0 24 24"
                     class="inline-block h-8 w-8 stroke-current">
                    <path stroke-linecap="round"
                          stroke-linejoin="round"
                          stroke-width="2"
                          d="M17 20h5v-2a4 4 0 00-3-3.87M9 20H4v-2a4 4 0 013-3.87M15 11a4 4 0 11-6 0 4 4 0 016 0z" />
                </svg>
            </div>
            <div class="stat-title">Total Users</div>
            <div class="stat-value text-amber-600">{{ $this->stats['total_users'] }}</div>
            <div class="stat-desc">Updated recently</div>
        </div>

        <!-- Total Vendors -->
        <div class="stat">
            <div class="stat-figure text-green-500">
                <svg xmlns="http://www.w3.org/2000/svg"
                     fill="none" viewBox="0 0 24 24"
                     class="inline-block h-8 w-8 stroke-current">
                    <path stroke-linecap="round"
                          stroke-linejoin="round"
                          stroke-width="2"
                          d="M3 10h1l1-3 4 4-3 3-3-3zM17 20h-1l-1 3-4-4 3-3 3 3zM20 14l-1 1-3-3 1-1 3 3z" />
                </svg>
            </div>
            <div class="stat-title">Total Vendors</div>
            <div class="stat-value text-green-600">{{ $this->stats['total_vendors'] }}</div>
            <div class="stat-desc">Updated recently</div>
        </div>

        <!-- Total Products -->
        <div class="stat">
            <div class="stat-figure text-blue-500">
                <svg xmlns="http://www.w3.org/2000/svg"
                     fill="none" viewBox="0 0 24 24"
                     class="inline-block h-8 w-8 stroke-current">
                    <path stroke-linecap="round"
                          stroke-linejoin="round"
                          stroke-width="2"
                          d="M20 13V7a1 1 0 00-1-1H5a1 1 0 00-1 1v6M12 20v-6m8 6v-6M4 17v-2a2 2 0 012-2h12a2 2 0 012 2v2" />
                </svg>
            </div>
            <div class="stat-title">Total Products</div>
            <div class="stat-value text-blue-600">{{ $this->stats['total_products'] }}</div>
            <div class="stat-desc">Updated recently</div>
        </div>

        <!-- Total Orders -->
        <div class="stat">
            <div class="stat-figure text-purple-500">
                <svg xmlns="http://www.w3.org/2000/svg"
                     fill="none" viewBox="0 0 24 24"
                     class="inline-block h-8 w-8 stroke-current">
                    <path stroke-linecap="round"
                          stroke-linejoin="round"
                          stroke-width="2"
                          d="M3 3h18l-1 9H4L3 3zM16 21a2 2 0 11-4 0 2 2 0 014 0zM6 21a2 2 0 11-4 0 2 2 0 014 0z" />
                </svg>
            </div>
            <div class="stat-title">Total Orders</div>
            <div class="stat-value text-purple-600">{{ $this->stats['total_orders'] }}</div>
            <div class="stat-desc">Updated recently</div>
        </div>
    </div>

    {{-- Second row of stats (3 static items) --}}
    <div class="stats shadow grid grid-cols-1 md:grid-cols-3 gap-6">
        <!-- Total Likes -->
        <div class="stat">
            <div class="stat-figure text-primary">
                <svg xmlns="http://www.w3.org/2000/svg"
                     fill="none" viewBox="0 0 24 24"
                     class="inline-block h-8 w-8 stroke-current">
                    <path stroke-linecap="round"
                          stroke-linejoin="round"
                          stroke-width="2"
                          d="M4.318 6.318a4.5 4.5 0 000 6.364L12
                              20.364l7.682-7.682a4.5 4.5 0
                              00-6.364-6.364L12 7.636l-1.318-1.318a4.5
                              4.5 0 00-6.364 0z" />
                </svg>
            </div>
            <div class="stat-title">Total Likes</div>
            <div class="stat-value text-primary">{{ number_format($this->stats['total_likes']) }}</div>
            <div class="stat-desc">Compared to last period</div>
        </div>

        <!-- Page Views -->
        <div class="stat">
            <div class="stat-figure text-secondary">
                <svg xmlns="http://www.w3.org/2000/svg"
                     fill="none" viewBox="0 0 24 24"
                     class="inline-block h-8 w-8 stroke-current">
                    <path stroke-linecap="round"
                          stroke-linejoin="round"
                          stroke-width="2"
                          d="M13 10V3L4 14h7v7l9-11h-7z" />
                </svg>
            </div>
            <div class="stat-title">Page Views</div>
            <div class="stat-value text-secondary">{{ number_format($this->stats['total_pageviews']) }}</div>
            <div class="stat-desc">Compared to last period</div>
        </div>

        <!-- Tasks Done -->
        <div class="stat">
            <div class="stat-figure text-secondary">
                <div class="avatar avatar-online">
                    <div class="w-16 rounded-full">
                        <img src="https://img.daisyui.com/images/profile/demo/anakeen@192.webp" alt="Avatar" />
                    </div>
                </div>
            </div>
            <div class="stat-value">{{ number_format($this->stats['tasks_done']) }}%</div>
            <div class="stat-title">Tasks Done</div>
            <div class="stat-desc text-secondary">
                {{ $this->stats['tasks_done'] > 0
                   ? ($this->stats['tasks_done'] . ' tasks completed')
                   : 'No tasks done yet' }}
            </div>
        </div>
    </div>
</x-filament::page>

