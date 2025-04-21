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
       <table class="min-w-full divide-y divide-gray-200">
       <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Name
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Email
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Customer ID
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Actions
                    </th>
                </tr>
            </thead>
            <tbody class="bg-white dark:bg-neutral-900 text-gray-800 dark:text-gray-100">
                @foreach ($users as $user)
                    <tr>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                        {{ $user->name }}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                        {{ $user->email }}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                        {{ $user->customer_id }}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">

                            <div class="flex items-center gap-2">
                                <!-- Edit Button with Modal Trigger -->
                                
                                <flux:modal.trigger name="permission-modal">
                                    <flux:button 
                                        icon="edit"
                                        variant="primary"
                                        wire:click="editPermission({{ $user->id }})"
                                        class="flux-btn flux-btn-xs flux-btn-info">
                                    </flux:button>
                                </flux:modal.trigger>

                                <!-- Delete Button -->
                                <flux:button 
                                    icon="trash-2"
                                    variant="danger"
                                    wire:click="deletePermission({{ $user->id }})"
                                    class="flux-btn flux-btn-xs flux-btn-danger">
                                </flux:button>
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <livewire:customer-modal />
    </div>
</x-layouts.app>
