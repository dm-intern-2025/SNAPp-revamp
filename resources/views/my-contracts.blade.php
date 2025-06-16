<x-layouts.app>
    {{-- This Alpine.js component controls the modal on this page --}}
    <div x-data="{
        showPdfModal: false,
        pdfUrl: '',
        selectedContract: {},
        openContractViewer(element) {
            this.selectedContract = element.dataset;
            this.pdfUrl = element.dataset.gcsPdfUrl;
            if (this.pdfUrl) {
                this.showPdfModal = true;
            }
        }
    }" @keydown.escape.window="showPdfModal = false">

        <div class="py-6 sm:py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="p-4 sm:p-6 bg-white dark:bg-neutral-900 rounded-xl shadow-md">
                    <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-6">My Contract</h2>

                    <div class="overflow-x-auto rounded-lg border border-gray-200 dark:border-neutral-700">
                        @include('contracts.contract-list-table', ['contracts' => $contracts])
                    </div>
                </div>
            </div>
        </div>

        @include('contracts.view-contract-modal')
    </div>
</x-layouts.app>
