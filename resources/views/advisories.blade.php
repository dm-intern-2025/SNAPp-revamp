<x-layouts.app>
    <div class="h-full w-full px-4 py-6">
        <div class="grid grid-cols-1 md:grid-cols-5 gap-6">

            <!-- Left Card: Latest Advisory (larger) -->
            <div class="col-span-1 md:col-span-3 bg-white rounded-2xl shadow p-6 flex flex-col">
                <span class="text-sm font-medium text-[#1443e0]">Latest Advisory</span>

                <!-- Image Placeholder -->
                <div class="mt-4 bg-gray-100 w-full h-48 flex items-center justify-center text-gray-400">
                    Image Placeholder
                </div>

                <!-- Headline -->
                <h3 class="text-xl font-bold mt-4 text-[#1443e0]">Headline</h3>

                <!-- Description -->
                <p class="text-sm text-[#1443e0] mt-2">
                    is the placeholder for descriptions smaller text,
                </p>

   <!-- Read More Button (aligned right) -->
<div class="mt-6 flex justify-end">
    <flux:button class=" bg-[#1443e0] text-white rounded-lg text-sm font-medium">
        <span class="mr-2">Read More</span>
        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
        </svg>
    </flux:button>
</div>
            </div>

            <!-- Right Card: More Advisories (slightly wider) -->
            <div class="col-span-1 md:col-span-2 bg-white rounded-2xl shadow p-6 flex flex-col">
                <!-- Header -->
                <h2 class="text-lg font-bold text-[#1443e0]">More Advisories</h2>

                <!-- List of Smaller Advisories -->
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

                <!-- Pagination Button -->
                <button class="mt-6 flex items-center justify-center w-full py-2 border border-gray-300 rounded-lg text-sm font-medium text-gray-700">
                    <span class="mr-2 text-[#1443e0]">Load More</span>
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                    </svg>
                </button>
            </div>
        </div>
    </div>
</x-layouts.app>
