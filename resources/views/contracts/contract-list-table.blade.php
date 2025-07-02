<div class="min-w-full divide-y divide-gray-200">
    <table>
        <thead>
            <tr>
                <!-- <th>Reference No.</th> -->
                <th>Description</th>
                <th>Contract Period</th>
                <th>Upload Date</th>
                <th>Status</th>
                <th></th>
            </tr>
        </thead>
        <tbody>
            @forelse($contracts as $item)
            <tr
                @if($item['gcsPdfUrl'])
                class="cursor-pointer hover:bg-gray-100 transition"
                @click="openContractViewer($el)"
                @endif
                data-contract-name="{{ $item['contract_name'] }}"
                data-gcs-pdf-url="{{ $item['gcsPdfUrl'] ?? '' }}">

                <!-- <td>{{$item['reference_number']}}</td> -->
                <td>{{ $item['contract_name'] }}</td>
                <td>{{$item['contract_period']}}</td>
                <td>{{$item['upload_date']}}</td>
                <td>
                    <span class="px-2 py-1 rounded-full text-xs 
        {{ $item['status'] === 'Active' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                        {{ $item['status'] }}
                    </span>
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-center">
                    @if(array_key_exists('gcsPdfUrl', $item) && $item['gcsPdfUrl'])
                    <div class="inline-flex items-center justify-center h-8 w-8 text-blue-600" title="View Contract">
                        <flux:icon name="document-text" class="h-5 w-5" />
                    </div>
                    @endif
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="3" class="text-center py-4 text-gray-500">No contract found for your account.</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>
        @include('contracts.view-contract-modal')
