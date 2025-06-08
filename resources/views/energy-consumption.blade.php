<x-layouts.app>
    <div class="h-screen w-full flex flex-col">
        <div class="px-4 py-6 flex flex-col flex-1">
            <!-- Select Filters Container -->
            <div class="flex gap-4 mb-6 flex-wrap">
                <flux:select name="date" placeholder="Select Date" class="w-40 min-w-[150px] max-w-[180px]">
                    <flux:select.option value="">All Dates</flux:select.option>
                    <flux:select.option value="last_7_days" :selected="request('date') === 'last_7_days'">Last 7 Days</flux:select.option>
                    <flux:select.option value="last_30_days" :selected="request('date') === 'last_30_days'">Last 30 Days</flux:select.option>
                    <flux:select.option value="this_month" :selected="request('date') === 'this_month'">This Month</flux:select.option>
                    <flux:select.option value="last_month" :selected="request('date') === 'last_month'">Last Month</flux:select.option>
                </flux:select>

                <flux:select name="resource" placeholder="Select Resource" class="w-40 min-w-[150px] max-w-[180px]">
                    <flux:select.option value="">All Resources</flux:select.option>
                    <flux:select.option value="electricity" :selected="request('resource') === 'electricity'">Electricity</flux:select.option>
                    <flux:select.option value="water" :selected="request('resource') === 'water'">Water</flux:select.option>
                    <flux:select.option value="gas" :selected="request('resource') === 'gas'">Gas</flux:select.option>
                </flux:select>
            </div>

            <!-- Main Grid Content -->
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 h-full">

                <!-- Chart Section -->
                <div class="lg:col-span-8 flex flex-col bg-white rounded-2xl shadow p-6">
                    <div class="flex justify-between items-center">
                        <h2 class="text-lg font-bold">At A Glance</h2>
                    </div>

                    <div class="mt-4 flex-grow min-h-[250px] md:min-h-[300px] relative">
                        <canvas id="energyChart" class="absolute inset-0 w-full h-full"></canvas>
                    </div>
                </div>

                <!-- Metrics Section -->
                <div class="lg:col-span-4 flex flex-col bg-white rounded-2xl shadow p-6">
                    <div class="flex justify-between items-center">
                        <h2 class="text-base font-medium text-[color:var(--color-accent)] text-sm md:text-base">Environmental Impact</h2>
                    </div>

                    <div class="mt-2 text-3xl font-bold text-[color:var(--color-accent)]">
                        <span class="text-black">{{ $consumption }}</span>
                        <span class="text-[color:var(--color-accent)]">kWh</span>
                    </div>

                    <hr class="my-4">

                    <div class="flex flex-col gap-4 flex-grow justify-between">
                        <div class="flex items-center gap-6 flex-1">
                            <img src="{{ asset('images/bulb-icon.png') }}" alt="Bulb Icon" class="w-6 h-6 md:w-8 md:h-8">
                            <span class="text-2xl md:text-3xl text-black">{{ $bulbReplacement }}</span>
                        </div>

                        <div class="flex items-center gap-6 flex-1">
                            <img src="{{ asset('images/co2-icon.png') }}" alt="CO₂ Icon" class="w-6 h-6 md:w-8 md:h-8">
                            <span class="text-2xl md:text-3xl text-black">{{ $avoidedEmissions }}</span>
                        </div>

                        <div class="flex items-center gap-6 flex-1">
                            <img src="{{ asset('images/tree-icon.png') }}" alt="Tree Icon" class="w-6 h-6 md:w-8 md:h-8">
                            <span class="text-2xl md:text-3xl text-black">{{ $treesGrown }}</span>
                        </div>
                    </div>
                </div>

                <!-- Bottom Stat Cards -->
                <div class="col-span-6 lg:col-span-3">
                    <div class="bg-white rounded-2xl shadow border-b-4 border-primary p-4">
                        <div class="flex justify-between items-center">
                            <span class="text-sm font-medium text-[color:var(--color-accent)]">Data 1</span>
                            <span class="text-2xl md:text-3xl font-semibold text-black">123</span>
                        </div>
                    </div>
                </div>

                <div class="col-span-6 lg:col-span-3">
                    <div class="bg-white rounded-2xl shadow border-b-4 border-info p-4">
                        <div class="flex justify-between items-center">
                            <span class="text-sm font-medium text-[color:var(--color-accent)]">Data 2</span>
                            <span class="text-2xl md:text-3xl font-semibold text-black">456</span>
                        </div>
                    </div>
                </div>

                <div class="col-span-6 lg:col-span-3">
                    <div class="bg-white rounded-2xl shadow border-b-4 border-danger p-4">
                        <div class="flex justify-between items-center">
                            <span class="text-sm font-medium text-[color:var(--color-accent)]">Data 3</span>
                            <span class="text-2xl md:text-3xl font-semibold text-black">789</span>
                        </div>
                    </div>
                </div>

                <div class="col-span-6 lg:col-span-3">
                    <div class="bg-white rounded-2xl shadow border-b-4 border-warning p-4">
                        <div class="flex justify-between items-center">
                            <span class="text-sm font-medium text-[color:var(--color-accent)]">Data 4</span>
                            <span class="text-2xl md:text-3xl font-semibold text-black">101</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-layouts.app>

<style>
    /* Core scrollbar prevention */
    html,
    body {
        height: 100%;
        margin: 0;
        padding: 0;
        /* overflow: hidden; */
    }

    /* Responsive adjustments */
    @media (max-width: 768px) {

        .lg\:col-span-8,
        .lg\:col-span-4,
        .col-span-6 {
            grid-column: span 12;
        }

        /* Allow vertical scrolling on mobile if needed */

    }
</style>