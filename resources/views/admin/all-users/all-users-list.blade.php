<x-layouts.app>
    <div class="p-6 bg-white dark:bg-neutral-900 rounded-xl shadow-md">

        <!-- Header with Add Buttons -->
        <div class="flex items-center justify-between mb-4">
            <h2 class="text-xl font-semibold text-gray-800 dark:text-white">All Users List</h2>
            <div class="flex gap-2">
                <!-- (Add New User button could go here) -->
            </div>
        </div>

        <!-- Table -->
        <div class="overflow-x-auto rounded-lg border border-gray-200 dark:border-neutral-700">
            <table class="min-w-full text-left">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Full Name</th>
                        <th>Email Address</th>
                        <th>Role</th>
                        <th>Customer ID</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($allUsers as $user)
                    <tr 
                        class="cursor-pointer hover:bg-gray-100 dark:hover:bg-neutral-800 transition flux-btn-info"
                        data-id="{{ $user->id }}"
                        data-name="{{ $user->name }}"
                        data-email="{{ $user->email }}"
                        data-customer-id="{{ $user->customer_id }}"
                        data-role="{{ $user->roles->pluck('name')->join(', ') }}"
                        onclick="document.getElementById('open-edit-modal').click()"
                    >
                        <td>{{ $user->id }}</td>
                        <td>{{ $user->name }}</td>
                        <td>{{ $user->email }}</td>
                        <td>{{ $user->roles->pluck('name')->join(', ') }}</td>
                        <td>{{ $user->customer_id }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Hidden Modal Trigger -->
        <flux:modal.trigger name="edit-customer-modal">
            <button id="open-edit-modal" class="hidden"></button>
        </flux:modal.trigger>

    </div>

    {{-- Include the edit modal partial --}}
    @include('admin.all-users.all-users-modal')
</x-layouts.app>