<x-layouts.app>
    <div class="p-6 bg-white dark:bg-neutral-900 rounded-xl shadow-md">
        
    <!-- Header with Add Buttons -->
        <div class="flex items-center justify-between mb-4">
            <h2 class="text-xl font-semibold text-gray-800 dark:text-white">Roles & Permissions</h2>
            <div class="flex gap-2">

       <!-- Flux Button to Trigger the Modal -->
                <flux:modal.trigger name="customer-modal">
                    <flux:button class="flux-btn flux-btn-primary flux-btn-sm">
                        Add Customer
                    </flux:button>
                </flux:modal.trigger>

                <!-- Add Permission Trigger -->
                <flux:modal.trigger name="permission-modal">
                    <flux:button class="flux-btn flux-btn-primary flux-btn-sm">
                        Add Admin
                    </flux:button>
                </flux:modal.trigger>
            </div>
        </div>

        <!-- Table -->
        <table class="table-auto min-w-full border border-gray-300 dark:border-neutral-700 text-sm">
            <thead class="bg-gray-100 dark:bg-neutral-800 text-gray-700 dark:text-gray-200">
                <tr>
                    <th class="p-3 border border-gray-300 dark:border-neutral-700">Name</th>
                    <th class="p-3 border border-gray-300 dark:border-neutral-700">Email</th>
                    <th class="p-3 border border-gray-300 dark:border-neutral-700">Customer ID</th>
                </tr>
            </thead>
            <tbody class="bg-white dark:bg-neutral-900 text-gray-800 dark:text-gray-100">
                @foreach ($users as $user)
                    <tr>
                        <td class="p-3 border border-gray-300 dark:border-neutral-700">{{ $user->name }}</td>
                        <td class="p-3 border border-gray-300 dark:border-neutral-700">{{ $user->email }}</td>
                        <td class="p-3 border border-gray-300 dark:border-neutral-700">{{ $user->customer_id }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <livewire:customer-modal />

    </div>
</x-layouts.app>
