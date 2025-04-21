<x-layouts.app>
    <div class="p-6 bg-white dark:bg-neutral-900 rounded-xl shadow-md">
        <!-- Tab Navigation -->
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
            <h2 class="text-xl font-semibold text-gray-800 dark:text-white">
                @if($activeTab === 'bills') My Bills @else Payment History @endif
            </h2>
            
            <div class="mb-6">
    <flux:button.group>
        <flux:button 
            href="{{ route('bills.show') }}" 
            variant="{{ $activeTab === 'bills' ? 'primary' : 'outline' }}">
            Bills Summary
        </flux:button>

        <flux:button 
            href="{{ route('payments.history') }}" 
            variant="{{ $activeTab === 'payments' ? 'primary' : 'outline' }}">
            Payment History
        </flux:button>
    </flux:button.group>
</div>

        </div>

        <!-- Tables Container with Overflow Control -->
        <div class="overflow-x-auto rounded-lg border border-gray-200 dark:border-neutral-700">
            <!-- Bills Table -->
            @if($activeTab === 'bills')
                @include('bills.bill-summary-table', ['data' => $bills])
            @endif

            <!-- Payments Table -->
            @if($activeTab === 'payments')
                @include('bills.payments-history-table', ['data' => $payments])
            @endif
        </div>

        
    </div>

</x-layouts.app>