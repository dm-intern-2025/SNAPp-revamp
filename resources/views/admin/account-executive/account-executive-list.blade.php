<x-layouts.app>
    <div class="p-6 bg-white dark:bg-neutral-900 rounded-xl shadow-md">

        <!-- Header -->
        <div class="flex items-center justify-between mb-4">
            <h2 class="text-xl font-semibold text-gray-800 dark:text-white">Account Executives</h2>
            <div class="flex gap-2">
                <!-- Add Button -->
                <flux:modal.trigger name="create-accountexecutive">
                    <flux:button variant="primary">
                        Add Account Executive
                    </flux:button>
                </flux:modal.trigger>
            </div>
        </div>

        <!-- Table -->
        <div class="overflow-x-auto rounded-lg border border-gray-200 dark:border-neutral-700">
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Full Name</th>
                        <th>Email Address</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($accountExecutives as $accountExecutive)
                    <tr
                        class="cursor-pointer hover:bg-gray-100 dark:hover:bg-neutral-800 transition flux-btn-info"
                        data-id="{{ $accountExecutive->id }}"
                        data-name="{{ $accountExecutive->name }}"
                        data-email="{{ $accountExecutive->email }}"
                        data-customer-id="{{ $accountExecutive->customer_id }}"

                        onclick="document.getElementById('open-edit-modal').click()"
                    >
                        <td>{{ $accountExecutive->id }}</td>
                        <td>{{ $accountExecutive->name }}</td>
                        <td>{{ $accountExecutive->email }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Hidden Modal Trigger for Edit -->
        <flux:modal.trigger name="edit-accountexecutive-modal">
            <button id="open-edit-modal" class="hidden"></button>
        </flux:modal.trigger>

    </div>

    @include('admin.account-executive.form-create-accountexecutive')
    @include('admin.account-executive.form-edit-accountexecutive')

</x-layouts.app>
