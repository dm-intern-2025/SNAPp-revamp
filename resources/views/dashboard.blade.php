<x-layouts.app :title="__('Dashboard')">
    <div class="flex flex-col md:flex-row justify-between gap-6 p-6">
        <!-- LEFT: Customer Info -->
        <div class="md:w-1/2 space-y-4">
            <h1 class="text-3xl font-bold text-neutral-800 dark:text-white">
                HELLO {{ strtoupper($customerName) }}!
            </h1>
            <p class="text-lg text-neutral-600 dark:text-neutral-400 mb-6">Welcome to your SnaPp Account.</p>

            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Customer ID</label>
                    <input type="text" readonly class="mt-1 w-3/4 px-3 py-2 rounded-md bg-gray-100 dark:bg-neutral-800 border border-gray-300 dark:border-neutral-600 text-sm" 
                           value="{{ $customerId }}">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Billing Period</label>
                    <input type="text" readonly class="mt-1 w-3/4 px-3 py-2 rounded-md bg-gray-100 dark:bg-neutral-800 border border-gray-300 dark:border-neutral-600 text-sm" 
                           value="{{ $billingPeriod }}">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Total Consumption</label>
                    <input type="text" readonly class="mt-1 w-3/4 px-3 py-2 rounded-md bg-gray-100 dark:bg-neutral-800 border border-gray-300 dark:border-neutral-600 text-sm" 
                           value="{{ $consumption }} kWh">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Previous Balance</label>
                    <input type="text" readonly class="mt-1 w-3/4 px-3 py-2 rounded-md bg-gray-100 dark:bg-neutral-800 border border-gray-300 dark:border-neutral-600 text-sm" 
                           value="₱{{ $previousBalance }}">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Total Amount Due</label>
                    <input type="text" readonly class="mt-1 w-3/4 px-3 py-2 rounded-md bg-gray-100 dark:bg-neutral-800 border border-gray-300 dark:border-neutral-600 text-sm" 
                           value="₱{{ $currentAmount }}">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Due Date</label>
                    <input type="text" readonly class="mt-1 w-3/4 px-3 py-2 rounded-md bg-gray-100 dark:bg-neutral-800 border border-gray-300 dark:border-neutral-600 text-sm" 
                           value="{{ $dueDate }}">
                </div>
            </div>
        </div>

        <!-- RIGHT: Nav Buttons -->
        <div class="md:w-1/2 grid grid-cols-2 gap-4 place-items-center">
            <a href="" class="w-32 py-3 text-center rounded-lg border border-neutral-400 dark:border-neutral-600 hover:bg-neutral-100 dark:hover:bg-neutral-700 text-sm font-medium">
                Profile
            </a>
            <a href="{{ route('bills.show') }}" class="w-32 py-3 text-center rounded-lg border border-neutral-400 dark:border-neutral-600 hover:bg-neutral-100 dark:hover:bg-neutral-700 text-sm font-medium">
                Billing Statement
            </a>
            <a href="" class="w-32 py-3 text-center rounded-lg border border-neutral-400 dark:border-neutral-600 hover:bg-neutral-100 dark:hover:bg-neutral-700 text-sm font-medium">
                Contract
            </a>
            <a href="" class="w-32 py-3 text-center rounded-lg border border-neutral-400 dark:border-neutral-600 hover:bg-neutral-100 dark:hover:bg-neutral-700 text-sm font-medium">
                Consumption
            </a>
            <a href="" class="w-32 py-3 text-center rounded-lg border border-neutral-400 dark:border-neutral-600 hover:bg-neutral-100 dark:hover:bg-neutral-700 text-sm font-medium">
                GHG
            </a>
            <a href="" class="w-32 py-3 text-center rounded-lg border border-neutral-400 dark:border-neutral-600 hover:bg-neutral-100 dark:hover:bg-neutral-700 text-sm font-medium">
                Contact Us
            </a>
        </div>
    </div>
</x-layouts.app>