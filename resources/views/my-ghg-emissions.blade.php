<x-layouts.app>
    <div class="space-y-4">
        <div>

        <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">By utilizing renewable energy amounting to:</label>
            <input type="text" readonly class="mt-1 w-3/4 px-3 py-2 rounded-md bg-gray-100 dark:bg-neutral-800 border border-gray-300 dark:border-neutral-600 text-sm" 
                   value="{{ $consumption }} kWh">
        </div>

            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Our partnership is able to avoid GHG emissions of</label>
            <input type="text" readonly class="mt-1 w-3/4 px-3 py-2 rounded-md bg-gray-100 dark:bg-neutral-800 border border-gray-300 dark:border-neutral-600 text-sm" 
                   value="{{ $avoidedEmissions }} CO2">
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Incadescent bulbs switch to LED bulbs</label>
            <input type="text" readonly class="mt-1 w-3/4 px-3 py-2 rounded-md bg-gray-100 dark:bg-neutral-800 border border-gray-300 dark:border-neutral-600 text-sm" 
                   value="{{ $bulbReplacement }}">
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Tree Seedlings grown for 10 years</label>
            <input type="text" readonly class="mt-1 w-3/4 px-3 py-2 rounded-md bg-gray-100 dark:bg-neutral-800 border border-gray-300 dark:border-neutral-600 text-sm" 
                   value="{{ $treesGrown }} kWh">
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Trash bags of waste recycled instead of landfilled</label>
            <input type="text" readonly class="mt-1 w-3/4 px-3 py-2 rounded-md bg-gray-100 dark:bg-neutral-800 border border-gray-300 dark:border-neutral-600 text-sm" 
                   value="{{ $trashBagsRecycled }}">
        </div>

    </div>
</x-layouts.app>
