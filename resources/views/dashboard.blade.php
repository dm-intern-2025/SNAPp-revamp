<x-layouts.app>
    <div class="min-h-screen w-full px-4 py-6 space-y-6 overflow-x-hidden" 
    
            x-data="{
        activeAdvisory: null,
        showModal: false,
        moreAdvisories: {{ Js::from($moreAdvisories) }},
        loading: false,
        loadMoreUrl: '{{ route('dashboard.load-more') }}',
        async loadMore() {
            this.loading = true;
            try {
                const response = await fetch(`${this.loadMoreUrl}?skip=${this.moreAdvisories.length}`);
                const data = await response.json();
                this.moreAdvisories = [...this.moreAdvisories, ...data];
            } catch (error) {
                console.error('Error loading more advisories:', error);
            }
            this.loading = false;
        }
    }">

        <!-- Top Section: Chart and Advisories -->
        <div class="grid grid-cols-1 md:grid-cols-5 gap-6">
            <!-- Chart (Left) -->
            <div class="col-span-1 md:col-span-3 bg-white rounded-2xl shadow p-6 flex flex-col">
                <h2 class="text-lg font-bold text-[#1443e0]">Energy Consumption</h2>
                <div class="mt-6 h-64 bg-gray-100 flex items-center justify-center text-gray-400">
                    Chart Placeholder
                </div>
            </div>

            <!-- Advisories (Right) -->
            <div class="col-span-1 md:col-span-2 bg-white rounded-2xl shadow p-6 flex flex-col">
                <h2 class="text-lg font-bold text-[#1443e0]">Latest Advisories</h2>

                <div class="mt-4 space-y-4 overflow-y-auto max-h-[250px] pr-2">
                <template x-for="(advisory, index) in moreAdvisories" :key="advisory.id">
                        <div @click="activeAdvisory = advisory; showModal = true" 
                             class="cursor-pointer group hover:bg-blue-50 p-3 rounded-lg transition border border-gray-100">
                            <h4 class="text-md font-semibold text-[#1443e0] group-hover:text-[#0d3ab9]" 
                                x-text="advisory.headline"></h4>
                            <p class="text-sm text-[#1443e0] mt-1 line-clamp-2" 
                               x-text="advisory.description"></p>
                            <div class="text-xs text-gray-500 mt-1" 
                                 x-text="new Date(advisory.created_at).toLocaleDateString()"></div>
                            <hr class="border-t border-gray-200 my-2">
                        </div>
                    </template>
                    
                    <template x-if="moreAdvisories.length === 0">
                        <p class="text-sm text-gray-500">No advisories available.</p>
                    </template>
                </div>

                <button @click="loadMore()" 
                        :disabled="loading"
                        class="mt-6 flex items-center justify-center w-full py-2 border border-[#1443e0] rounded-lg text-sm font-medium text-[#1443e0] hover:bg-blue-50 transition">
                    <span x-text="loading ? 'Loading...' : 'Load More'" class="mr-2"></span>
                    <svg x-show="!loading" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                    </svg>
                    <svg x-show="loading" class="animate-spin h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                </button>
            </div>
        </div>

        <!-- Billing Section -->
        <div class="grid grid-cols-1 md:grid-cols-5 gap-6">
            <!-- Billing Statement Card (wider) -->
            <div class="col-span-1 md:col-span-3 bg-white rounded-2xl shadow p-6 flex flex-col justify-center">
                <div class="flex items-center justify-between">
                    <!-- Total Amount -->
                    <div class="flex-1 text-center">
                        <span class="text-xs font-medium text-[#1443e0]">Total Amount</span>
                        <div class="text-2xl font-bold text-[#1443e0] mt-1">₱{{ $currentAmount }}</div>
                    </div>

                    <!-- Divider -->
                    <div class="h-12 w-px bg-gray-300 mx-4"></div>

                    <!-- Due Date -->
                    <div class="flex-1 text-center">
                        <span class="text-xs font-medium text-[#1443e0]">Due Date</span>
                        <div class="text-2xl font-bold text-[#1443e0] mt-1">{{ $dueDate }}</div>
                    </div>
                </div>
            </div>

            <!-- Contract Expiration Card (smaller) -->
            <div class="col-span-1 md:col-span-2 bg-white rounded-2xl shadow p-6 flex flex-col justify-center items-center">
                <span class="text-xs font-medium text-[#1443e0]">Contract Expiration</span>
                <div class="text-2xl font-bold text-[#1443e0] mt-2">What data to place?</div>
            </div>
        </div>

        <!-- Advisory Modal -->
        <div x-show="showModal" 
             x-transition:enter="ease-out duration-300"
             x-transition:leave="ease-in duration-200"
             class="fixed inset-0 z-50 overflow-y-auto">
            <div class="flex items-center justify-center min-h-screen p-4">
                <div class="fixed inset-0 bg-black/50" @click="showModal = false"></div>
                
                <div class="relative bg-white rounded-xl max-w-2xl w-full max-h-[90vh] flex flex-col">
                    <div class="p-4 border-b flex justify-between items-center">
                        <h3 class="text-xl font-bold text-[#1443e0]" x-text="activeAdvisory?.headline"></h3>
                        <button @click="showModal = false" class="text-gray-500 hover:text-gray-700">
                            ✕
                        </button>
                    </div>

                    <div class="overflow-y-auto p-4">
                        <div x-show="activeAdvisory?.attachment" class="mb-4 bg-gray-100 rounded-lg overflow-hidden">
                            <img :src="`/storage/${activeAdvisory.attachment}`" 
                                 class="w-full h-auto max-h-[50vh] object-contain"
                                 :alt="activeAdvisory.headline">
                        </div>

                        <div class="prose max-w-none text-[#1443e0]">
                            <div x-html="activeAdvisory?.description || activeAdvisory?.description"></div>
                                <br>
                            <div x-html="activeAdvisory?.content || activeAdvisory?.content"></div>

                            <div class="text-sm text-gray-500 mt-4">
                                Published on <span x-text="new Date(activeAdvisory?.created_at).toLocaleDateString('en-US', {dateStyle: 'long'})"></span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
</x-layouts.app>


