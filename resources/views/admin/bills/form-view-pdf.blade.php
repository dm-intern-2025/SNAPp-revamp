{{-- resources/views/admin/bills/form-view-pdf.blade.php --}}

<div x-show="showContractModal" class="fixed inset-0 z-50 overflow-y-auto" style="display: none;">
    <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 transition-opacity" aria-hidden="true">
            <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
        </div>

        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
        <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-4xl sm:w-full">
            <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                <div class="sm:flex sm:items-start">
                    <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left w-full">
                        <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-title">
                            Bill Details: <span x-text="selectedContract.billNumber"></span>
                        </h3>
                        <div class="mt-2">
                            {{-- IMPORTANT: Update these x-text bindings to match your bill data- attributes --}}
                            <p><strong>Customer Name:</strong> <span x-text="selectedContract.customerName"></span></p>
                            <p><strong>Billing Period:</strong> <span x-text="selectedContract.billingPeriod"></span></p>
                            <p><strong>Uploaded At:</strong> <span x-text="selectedContract.uploadedAt"></span></p>

                            {{-- This part for the PDF iframe should be correct IF contractUrl has a valid PDF URL --}}
                            <template x-if="contractUrl">
                                <div class="mt-4" style="height: 60vh;">
                                    <iframe :src="contractUrl" frameborder="0" class="w-full h-full"></iframe>
                                </div>
                            </template>
                            <template x-if="!contractUrl">
                                <p class="text-red-500 mt-4">PDF not available for this bill.</p>
                            </template>
                        </div>
                    </div>
                </div>
            </div>
            <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                <button @click="showContractModal = false" type="button" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                    Close
                </button>
            </div>
        </div>
    </div>
</div>