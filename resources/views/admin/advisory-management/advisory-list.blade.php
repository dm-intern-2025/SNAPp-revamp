<x-layouts.app>
    <div class="h-full w-full px-4 py-6 space-y-6">

        <!-- Top Section: Chart and More Advisories -->
        <div class="grid grid-cols-1 md:grid-cols-5 gap-6">
            <!-- Chart (Left) -->
            <div class="col-span-1 md:col-span-3 bg-white rounded-2xl shadow p-6 flex flex-col">
                <h2 class="text-lg font-bold text-[#1443e0]">Energy Consumption</h2>
                <!-- Chart Placeholder -->
                <div class="mt-6 h-64 bg-gray-100 flex items-center justify-center text-gray-400">
                    Chart Placeholder
                </div>
            </div>

            <!-- More Advisories (Right) -->
            <div class="col-span-1 md:col-span-2 bg-white rounded-2xl shadow p-6 flex flex-col">
                <h2 class="text-lg font-bold text-[#1443e0]">More Advisories</h2>

                <div class="mt-4 space-y-4 flex-grow">
                    <div>
                        <h4 class="text-md font-semibold text-[#1443e0]">Headline One</h4>
                        <p class="text-sm text-[#1443e0] mt-1">Brief description for advisory one.</p>
                        <hr class="border-t border-gray-200 my-2">
                    </div>
                    <div>
                        <h4 class="text-md font-semibold text-[#1443e0]">Headline Two</h4>
                        <p class="text-sm text-[#1443e0] mt-1">Brief description for advisory two.</p>
                        <hr class="border-t border-gray-200 my-2">
                    </div>
                    <div>
                        <h4 class="text-md font-semibold text-[#1443e0]">Headline Three</h4>
                        <p class="text-sm text-[#1443e0] mt-1">Brief description for advisory three.</p>
                    </div>
                </div>

                <button class="mt-6 flex items-center justify-center w-full py-2 border border-[#1443e0] rounded-lg text-sm font-medium text-[#1443e0]">
                    <span class="mr-2">Load More</span>
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                    </svg>
                </button>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-5 gap-6">
    <!-- Billing Statement Card (wider) -->
    <div class="col-span-1 md:col-span-3 bg-white rounded-2xl shadow p-6 flex flex-col justify-center">
        <div class="flex items-center justify-between">
            <!-- Total Amount -->
            <div class="flex-1 text-center">
                <span class="text-xs font-medium text-[#1443e0]">Total Amount</span>
                <div class="text-2xl font-bold text-[#1443e0] mt-1">₱5,000.00</div>
            </div>

            <!-- Divider -->
            <div class="h-12 w-px bg-gray-300 mx-4"></div>

            <!-- Due Date -->
            <div class="flex-1 text-center">
                <span class="text-xs font-medium text-[#1443e0]">Due Date</span>
                <div class="text-2xl font-bold text-[#1443e0] mt-1">May 30, 2025</div>
            </div>
        </div>
    </div>

    <!-- Contract Expiration Card (smaller) -->
    <div class="col-span-1 md:col-span-2 bg-white rounded-2xl shadow p-6 flex flex-col justify-center items-center">
        <span class="text-xs font-medium text-[#1443e0]">Contract Expiration</span>
        <div class="text-2xl font-bold text-[#1443e0] mt-2">December 31, 2026</div>
    </div>
</div>


    </div>
</x-layouts.app>
