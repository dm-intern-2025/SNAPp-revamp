<div class="min-w-full divide-y divide-gray-200">
    <table>
    <thead>
    <tr>
        <th >Payment Ref</th>
        <th >Payment Date</th>
        <th >Billing Period</th>
        <th >Amount</th>
        <!-- <th >Bill No</th> -->
        <th >Date Posted</th>
    </tr>

        </thead>
        <tbody class="bg-white divide-y divide-gray-200">
            @foreach($data as $item)
                <tr>
                    <td>{{ $item['Payment Reference'] }}</td>
                    <td>{{ $item['Payment Reference Date'] }}</td>
                    <td>{{ $item['Billing Period'] }}</td>
                    <td>₱ {{ $item['Amount'] }}</td>
                    <!-- <td>{{ $item['Power Bill No'] }}</td> -->
                    <td>{{ $item['Date Posted'] }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>

@if($data->hasPages())
<div class="px-4 py-3 bg-white border-t border-gray-200">
    {{ $data->links() }}
</div>
@endif