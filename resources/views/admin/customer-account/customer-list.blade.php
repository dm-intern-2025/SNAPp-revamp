<x-layouts.app>
    <div class="p-6 bg-white dark:bg-neutral-900 rounded-xl shadow-md">

        <!-- Header with Add Button -->
        <div class="flex items-center justify-between mb-4">
            <h2 class="text-xl font-semibold text-gray-800 dark:text-white">User Accounts</h2>
            <div class="flex gap-2">
                <flux:modal.trigger name="customer-modal">
                    <flux:button class="flux-btn flux-btn-primary flux-btn-sm">
                        Add Customer
                    </flux:button>
                </flux:modal.trigger>
            </div>
        </div>

        <!-- Clickable Table -->
        <div class="overflow-x-auto rounded-lg border border-gray-200 dark:border-neutral-700">
            <table class="min-w-full text-left">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Customer ID</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($users as $user)
                    <tr
                    class="cursor-pointer hover:bg-gray-100 dark:hover:bg-neutral-800 transition flux-btn-info"
                    data-id="{{ $user->id }}"
                        data-name="{{ $user->name }}"
                        data-email="{{ $user->email }}"
                        data-customer-id="{{ $user->customer_id }}"
                        onclick="document.getElementById('open-edit-modal').click()"
                    >
                        <td>{{ $user->name }}</td>
                        <td>{{ $user->email }}</td>
                        <td>{{ $user->customer_id }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Hidden Modal Trigger for Edit -->
        <flux:modal.trigger name="edit-customer-modal">
            <button id="open-edit-modal" class="hidden"></button>
        </flux:modal.trigger>

    </div>

    <!-- Modals -->
    @include('admin.customer-account.form-edit-customer')
    @include('admin.customer-account.form-create-customer')

</x-layouts.app>
