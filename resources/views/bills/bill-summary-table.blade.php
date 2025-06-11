<div class="min-w-full divide-y divide-gray-200 dark:divide-neutral-700">
    <table>
        <thead>
            <tr>
                <th>Bill Number</th>
                <th>Billing Period</th>
                <th>Posting Date</th>
                <th>Status</th>
                <th>Total Amount</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($bills as $item)
                <tr>
                    <td>{{ $item['Power Bill Number'] }}</td>
                    <td>{{ $item['Billing Period'] }}</td>
                    <td>{{ $item['Posting Date'] }}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm">
                        <span class="px-2 py-1 rounded-full text-xs {{ $item['Status'] === 'PAID' ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200' : 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200' }}">
                            {{ $item['Status'] }}
                        </span>
                    </td>
                    <td>₱{{ $item['Total Amount'] }}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-center">
                        {{-- This is now a simple link that opens the PDF in a new tab --}}
                        @if($item['gcsPdfUrl'])
                            <a
                                href="{{ $item['gcsPdfUrl'] }}"
                                target="_blank"
                                title="View/Download Bill"
                                class="inline-flex items-center justify-center h-8 w-8 rounded-full bg-[#1443e0] text-white hover:bg-[#0d3ab9] transition-colors">
                                
                                {{-- Your original icon --}}
                                <flux:icon name="download" class="h-4 w-4" />
                            </a>
                        @endif
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="text-center py-4 text-gray-500">
                        No bills found.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

{{-- Pagination links remain here --}}
@if(isset($bills) && $bills->hasPages())
    <div class="mt-4 px-4 py-3 bg-white dark:bg-neutral-900 border-t border-gray-200 dark:border-neutral-700">
        {{ $bills->links() }}
    </div>
@endif