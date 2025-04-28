<x-layouts.app>
    <div class="p-6 bg-white dark:bg-neutral-900 rounded-xl shadow-md">

        <!-- Header with Add Buttons -->
        <div class="flex items-center justify-between mb-4">
            <h2 class="text-xl font-semibold text-gray-800 dark:text-white">Account Executives</h2>
            <div class="flex gap-2">

                <!-- Flux Button to Trigger the Modal -->
                <flux:modal.trigger name="customer-modal">
                    <flux:button class="flux-btn flux-btn-primary flux-btn-sm">
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
                        <th>
                            ID
                        </th>
                        <th>
                            Full Name
                        </th>
                        <th>
                            Email Address
                        </th>
                        <th>
                            Actions
                        </th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>
                        </td>
                        <td>
                        </td>
                        <td>
                        </td>
                        <td>

                            <div class="flex items-center gap-2">
                                <!-- Edit Button with Modal Trigger -->

                                <flux:modal.trigger name="edit-customer-modal">
                                    <flux:button
                                        icon="edit"
                                        variant="primary"
                                        class="flux-btn flux-btn-xs flux-btn-info"
                                        data-id=""
                                        data-name=""
                                        data-email=""
                                        data-customer-id="">
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


                </tbody>
            </table>
        </div>
    </div>
</x-layouts.app>