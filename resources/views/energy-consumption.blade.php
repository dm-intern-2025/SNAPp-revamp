<x-layouts.app>
    <div class="h-full w-full px-4 py-6">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 h-full">

            <!-- Revenue Section (Chart Placeholder) -->
            <div class="lg:col-span-8 flex flex-col bg-white rounded-2xl shadow p-6 h-full">
                <div class="flex justify-between items-center">
                    <h2 class="text-lg font-bold">At A Glance</h2>
                </div>
                <!-- Ensure chart has proper height and flexibility -->
                <div class="mt-4 flex-grow flex items-center justify-center text-gray-400 text-sm italic">
                    Chart will be added here
                </div>
            </div>

            <!-- Metrics Beside the Chart -->
            <div class="lg:col-span-4 flex flex-col bg-white rounded-2xl shadow p-6 h-full">
                <div class="flex justify-between items-center">
                    <h2 class="text-base font-medium text-[color:var(--color-accent)] text-sm md:text-base">Environmental Impact</h2>
                </div>

                <!-- Enlarged Consumption -->
                <div class="mt-2 text-3xl font-bold text-[color:var(--color-accent)] text-lg md:text-3xl">
                    <span class="text-black">{{ $consumption }}</span>
                    <span class="text-[color:var(--color-accent)]">kWh</span>
                </div>

                <hr class="my-4">

                <div class="flex flex-col gap-4 flex-grow justify-between">
                    <!-- Bulbs Replaced -->
                    <div class="flex items-center gap-6 flex-1">
                        <img src="{{ asset('images/bulb-icon.png') }}" alt="Bulb Icon" class="w-6 h-6 md:w-8 md:h-8">
                        <span class="text-2xl md:text-3xl text-black">{{ $bulbReplacement }}</span>
                    </div>

                    <!-- CO₂ Avoided -->
                    <div class="flex items-center gap-6 flex-1">
                        <img src="{{ asset('images/co2-icon.png') }}" alt="CO₂ Icon" class="w-6 h-6 md:w-8 md:h-8">
                        <span class="text-2xl md:text-3xl text-black">{{ $avoidedEmissions }}</span>
                    </div>

                    <!-- Trees Grown -->
                    <div class="flex items-center gap-6 flex-1">
                        <img src="{{ asset('images/tree-icon.png') }}" alt="Tree Icon" class="w-6 h-6 md:w-8 md:h-8">
                        <span class="text-2xl md:text-3xl text-black">{{ $treesGrown }}</span>
                    </div>
                </div>
            </div>

            <!-- Bottom 4 Stat Cards -->
            <div class="col-span-6 lg:col-span-3 mb-6 lg:mb-0">
                <div class="bg-white rounded-2xl shadow border-b-4 border-primary p-4">
                    <div class="flex justify-between items-center">
                        <span class="text-sm font-medium">Data 1</span>
                        <span class="text-sm font-semibold"></span>
                    </div>
                </div>
            </div>

            <div class="col-span-6 lg:col-span-3 mb-6 lg:mb-0">
                <div class="bg-white rounded-2xl shadow border-b-4 border-info p-4">
                    <div class="flex justify-between items-center">
                        <span class="text-sm font-medium">Data 2</span>
                        <span class="text-sm font-semibold"></span>
                    </div>
                </div>
            </div>

            <div class="col-span-6 lg:col-span-3 mb-6 lg:mb-0">
                <div class="bg-white rounded-2xl shadow border-b-4 border-danger p-4">
                    <div class="flex justify-between items-center">
                        <span class="text-sm font-medium">Data 3</span>
                        <span class="text-sm font-semibold"></span>
                    </div>
                </div>
            </div>

            <div class="col-span-6 lg:col-span-3 mb-6 lg:mb-0">
                <div class="bg-white rounded-2xl shadow border-b-4 border-warning p-4">
                    <div class="flex justify-between items-center">
                        <span class="text-sm font-medium">Data 4</span>
                        <span class="text-sm font-semibold"></span>
                    </div>
                </div>
            </div>

        </div>
    </div>
</x-layouts.app>

<style>
    /* Media query to ensure content stacks vertically on smaller screens */
    @media (max-width: 768px) {
        .lg\:col-span-8 {
            grid-column: span 12 !important;  /* Chart now takes full width */
        }

        .lg\:col-span-4 {
            grid-column: span 12 !important;  /* Metrics now stack below the chart */
        }

        /* Allow cards to take full width on smaller screens */
        .col-span-6 {
            grid-column: span 12 !important;
        }
    }
</style>
