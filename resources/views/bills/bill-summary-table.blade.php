<div class="min-w-full divide-y divide-gray-200 dark:divide-neutral-700">
    <table class="min-w-full">
        <thead class="bg-gray-50 dark:bg-neutral-800">
            <tr>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-neutral-400 uppercase tracking-wider whitespace-nowrap">Billing Period</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-neutral-400 uppercase tracking-wider whitespace-nowrap">Bill Number</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-neutral-400 uppercase tracking-wider whitespace-nowrap">Bill Date</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-neutral-400 uppercase tracking-wider whitespace-nowrap">Terms</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-neutral-400 uppercase tracking-wider whitespace-nowrap">Due Date</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-neutral-400 uppercase tracking-wider whitespace-nowrap">Status</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-neutral-400 uppercase tracking-wider whitespace-nowrap">Total Amount</th>
            </tr>
        </thead>
        <tbody class="bg-white dark:bg-neutral-900 divide-y divide-gray-200 dark:divide-neutral-700">
            @foreach($data as $item)
                <tr>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-800 dark:text-neutral-200">{{ $item['Billing Period'] }}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-800 dark:text-neutral-200">{{ $item['Power Bill Number'] }}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-800 dark:text-neutral-200">{{ $item['Bill Date'] }}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-800 dark:text-neutral-200">{{ $item['Terms'] }}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-800 dark:text-neutral-200">{{ $item['Due Date'] }}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm">
                        <span class="px-2 py-1 rounded-full text-xs {{ $item['Status'] === 'PAID' ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200' : 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200' }}">
                            {{ $item['Status'] }}
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-800 dark:text-neutral-200">{{ $item['Total Amount'] }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>

@if($data->hasPages())
<div class="px-4 py-3 bg-white dark:bg-neutral-900 border-t border-gray-200 dark:border-neutral-700">
    {{ $data->links() }}
</div>
@endif