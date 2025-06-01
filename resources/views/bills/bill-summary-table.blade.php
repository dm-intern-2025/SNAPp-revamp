<div class="min-w-full divide-y divide-gray-200 dark:divide-neutral-700">

    
    <table>
    <thead>
    <tr>
        <th >Billing Period</th>
        <th >Bill Number</th>
        <th >Bill Date</th>
        <th >Terms</th>
        <th >Due Date</th>
        <th >Status</th>
        <th >Total Amount</th>

    </tr>
</thead>

        <tbody>
            @foreach($data as $item)
                <tr>
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
                    <td  >₱{{ $item['Total Amount'] }}</td>
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