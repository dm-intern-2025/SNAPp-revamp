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
        <div class="overflow-x-auto rounded-lg border border-gray-200 dark:border-neutral-700">
            <table>
                <thead>
                    <tr>
                        <th>
                            Name
                        </th>
                        <th>
                            Email
                        </th>
                        <th>
                            Customer ID
                        </th>
                        <th>
                            Actions
                        </th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($users as $user)
                    <tr>
                        <td>
                            {{ $user->name }}
                        </td>
                        <td>
                            {{ $user->email }}
                        </td>
                        <td>
                            {{ $user->customer_id }}
                        </td>
                        <td>

                            <div class="flex items-center gap-2">
                                <!-- Edit Button with Modal Trigger -->

                                <flux:modal.trigger name="edit-customer-modal">
                                    <flux:button
                                        icon="edit"
                                        variant="primary"
                                        class="flux-btn flux-btn-xs flux-btn-info"
                                        data-id="{{ $user->id }}"
                                        data-name="{{ $user->name }}"
                                        data-email="{{ $user->email }}"
                                        data-customer-id="{{ $user->customer_id }}">
                                    </flux:button>
                                </flux:modal.trigger>


                                <!-- Delete Button -->
                                <flux:modal.trigger name="confirm-delete">
                                    <flux:button
                                        icon="trash-2"
                                        variant="danger"
                                        class="flux-btn flux-btn-xs flux-btn-danger">
                                    </flux:button>
                                </flux:modal.trigger>


                            </div>
                        </td>
                    </tr>

                    @endforeach

                </tbody>
            </table>
        </div>
    </div>
    @include('admin.customer-account.confirm-delete')
    @include('admin.customer-account.form-edit-customer')
    @include('admin.customer-account.form-create-customer')


</x-layouts.app>