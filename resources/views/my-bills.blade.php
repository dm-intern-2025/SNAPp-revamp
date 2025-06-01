<x-layouts.app>
    <div class="p-6 bg-white dark:bg-neutral-900 rounded-xl shadow-md">
       <!-- Top Bar: Search + Export + Tabs -->
<div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
  
  <!-- Filters / Search -->
  <form method="GET" action="" class="w-full sm:w-auto">
    <flux:input
      icon="magnifying-glass"
      name="search"
      placeholder="Search Contract Period..."
      value="{{ request('search') }}"
      class="w-full md:w-64" />
  </form>

  <!-- Export + Tabs -->
  <div class="flex flex-col sm:flex-row sm:items-center gap-4">
    @if($activeTab === 'bills')
      <button
        onclick="downloadThenRedirect('{{ route('bills.export') }}', '{{ route('bills.show') }}')"
        class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">
        Export Bills
      </button>
    @endif

    @if($activeTab === 'payments')
      <button
        onclick="downloadThenRedirect('{{ route('payments.export') }}', '{{ route('payments.history') }}')"
        class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">
        Export Payments
      </button>
    @endif

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

        <!-- Tables -->
        <div class="overflow-x-auto rounded-lg border border-gray-200 dark:border-neutral-700">
            @if($activeTab === 'bills')
                @include('bills.bill-summary-table', ['data' => $bills])
            @endif

            @if($activeTab === 'payments')
                @include('bills.payments-history-table', ['data' => $payments])
            @endif
        </div>
    </div>
<!-- Custom Export Loader -->
<div id="export-loader"
    class="hidden fixed inset-0 bg-white/75 z-[9999] flex items-center justify-center">
    <button type="button" class="bg-indigo-600 text-white px-4 py-2 rounded inline-flex items-center" disabled>
        <svg class="animate-spin h-5 w-5 mr-3 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"/>
        </svg>
        Exporting…
    </button>
</div>

<!-- JavaScript -->
<script>
    function downloadThenRedirect(fileUrl, returnUrl) {
        const loader = document.getElementById('export-loader');
        loader.classList.remove('hidden');

        fetch(fileUrl, {
            method: 'GET',
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => {
            if (!response.ok) throw new Error('Network error during export');

            const disposition = response.headers.get('Content-Disposition');
            let filename = 'export.csv';

            if (disposition && disposition.includes('filename=')) {
                filename = disposition.split('filename=')[1].replace(/"/g, '');
            }

            return response.blob().then(blob => ({ blob, filename }));
        })
        .then(({ blob, filename }) => {
            const url = window.URL.createObjectURL(blob);
            const a = document.createElement('a');
            a.href = url;
            a.download = filename;
            document.body.appendChild(a);
            a.click();
            a.remove();
            window.URL.revokeObjectURL(url);
        })
        .catch(err => {
            alert('Failed to export file.');
            console.error(err);
        })
        .finally(() => {
            loader.classList.add('hidden');
            window.location.href = returnUrl;
        });
    }
</script>

</x-layouts.app>
