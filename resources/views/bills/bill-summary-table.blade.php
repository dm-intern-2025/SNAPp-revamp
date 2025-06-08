{{--
    The entire component is wrapped in an x-data directive to manage state.
    'selectedBill' will hold the data from the clicked table row.
--}}
<div x-data="{
    selectedBill: {},
    showDetails(element) {
        this.selectedBill = element.dataset;
        document.getElementById('open-billing-modal').click();
    }
}">
    <div class="min-w-full divide-y divide-gray-200 dark:divide-neutral-700">
        <table>
            <thead>
                <tr>
                    <th>Billing Period</th>
                    <th>Bill Number</th>
                    <th>Bill Date</th>
                    <th>Terms</th>
                    <th>Due Date</th>
                    <th>Status</th>
                    <th>Total Amount</th>
                    {{-- Empty header for the download column --}}
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @foreach($data as $item)
                    {{--
                        This table row is now clickable and carries the data for the modal.
                        Clicking it calls the showDetails function, passing its own data.
                    --}}
                    <tr class="cursor-pointer hover:bg-gray-100 dark:hover:bg-neutral-800 transition"
                        data-billing-period="{{ $item['Billing Period'] }}"
                        data-bill-number="{{ $item['Power Bill Number'] }}"
                        data-bill-date="{{ $item['Bill Date'] }}"
                        data-due-date="{{ $item['Due Date'] }}"
                        data-status="{{ $item['Status'] }}"
                        {{-- REVERTED: The Total Amount is now passed as raw data without formatting --}}
                        data-total-amount="{{ $item['Total Amount'] }}"
                        {{-- FIX: Use !empty() to prevent "Undefined array key" error --}}
                        data-attachment="{{ !empty($item['attachment']) ? Storage::url($item['attachment']) : '' }}"
                        @click="showDetails($el)">

                        <td>{{ $item['Billing Period'] }}</td>
                        <td>{{ $item['Power Bill Number'] }}</td>
                        <td>{{ $item['Bill Date'] }}</td>
                        <td>{{ $item['Terms'] }}</td>
                        <td>{{ $item['Due Date'] }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm">
                            <span class="px-2 py-1 rounded-full text-xs {{ $item['Status'] === 'PAID' ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200' : 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200' }}">
                                {{ $item['Status'] }}
                            </span>
                        </td>
                        {{-- REVERTED: Displaying the Total Amount without number_format --}}
                        <td>₱{{ $item['Total Amount'] }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-center">
                            {{-- Add stopPropagation to prevent the row's click event --}}
                            <button type="button" @click.stop="" title="Download" class="inline-flex items-center justify-center h-8 w-8 rounded-full bg-[#1443e0] text-white hover:bg-[#0d3ab9] transition-colors">
                                <flux:icon name="download" class="h-4 w-4" />
                            </button>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <!-- Hidden Modal Trigger -->
        <flux:modal.trigger name="billing-details-modal">
            <button id="open-billing-modal" class="hidden"></button>
        </flux:modal.trigger>
    </div>

    {{-- Pagination Links --}}
    @if($data->hasPages())
    <div class="mt-4 px-4 py-3 bg-white dark:bg-neutral-900 border-t border-gray-200 dark:border-neutral-700">
        {{ $data->links() }}
    </div>
    @endif


    <!-- =============================================================== -->
    <!-- INLINE BILLING DETAILS MODAL                                    -->
    <!-- This modal's content is now populated by the 'selectedBill'     -->
    <!-- data object when a table row is clicked.                        -->
    <!-- =============================================================== -->
    <flux:modal name="billing-details-modal" max-width="2xl">
        <div class="p-6">
            <h2 class="text-xl font-bold text-gray-900 dark:text-white">
                Billing Details
            </h2>
            <p class="mt-1 text-sm text-gray-500">
                Bill Number: <span x-text="selectedBill.billNumber"></span>
            </p>

            {{-- Attachment Display --}}
            <div class="mt-4 bg-gray-100 dark:bg-neutral-800 rounded-lg p-4 min-h-[300px] flex items-center justify-center">
                <template x-if="selectedBill.attachment">
                    {{-- Assumes the attachment is an image. For PDFs, you might use an <iframe> --}}
                    <img :src="selectedBill.attachment" class="max-h-96 object-contain" alt="Billing Attachment">
                </template>
                <template x-if="!selectedBill.attachment">
                    <div class="text-center text-gray-500">
                        <svg class="mx-auto h-12 w-12" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" /></svg>
                        <p class="mt-2 text-sm">No attachment available</p>
                    </div>
                </template>
            </div>

            {{-- Bill Details Grid --}}
            <div class="mt-6 grid grid-cols-1 sm:grid-cols-2 gap-4 text-sm">
                <div>
                    <dt class="font-medium text-gray-500">Billing Period</dt>
                    <dd class="mt-1 text-gray-900 dark:text-white" x-text="selectedBill.billingPeriod"></dd>
                </div>
                <div>
                    <dt class="font-medium text-gray-500">Status</dt>
                    <dd class="mt-1">
                        <span class="px-2 py-1 rounded-full text-xs"
                              :class="{
                                'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200': selectedBill.status === 'PAID',
                                'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200': selectedBill.status !== 'PAID'
                              }"
                              x-text="selectedBill.status">
                        </span>
                    </dd>
                </div>
                <div>
                    <dt class="font-medium text-gray-500">Bill Date</dt>
                    <dd class="mt-1 text-gray-900 dark:text-white" x-text="selectedBill.billDate"></dd>
                </div>
                <div>
                    <dt class="font-medium text-gray-500">Due Date</dt>
                    <dd class="mt-1 text-gray-900 dark:text-white" x-text="selectedBill.dueDate"></dd>
                </div>
                <div class="sm:col-span-2">
                    <dt class="font-medium text-gray-500">Total Amount</dt>
                    {{-- The modal will now display the raw amount. You can add formatting here with JS if needed. --}}
                    <dd class="mt-1 text-2xl font-semibold text-gray-900 dark:text-white">₱<span x-text="selectedBill.totalAmount"></span></dd>
                </div>
            </div>

            {{-- Modal Actions --}}
            <div class="mt-6 flex justify-end gap-3">
                 <flux:modal.close>
                    {{-- Replaced with a standard styled button --}}
                    <button type="button" class="inline-flex justify-center rounded-md border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:bg-neutral-700 dark:border-neutral-600 dark:text-neutral-200 dark:hover:bg-neutral-600">
                        Close
                    </button>
                </flux:modal.close>
                <a :href="selectedBill.attachment" download>
                     <flux:button>Download</flux:button>
                </a>
            </div>
        </div>
    </flux:modal>
</div>
