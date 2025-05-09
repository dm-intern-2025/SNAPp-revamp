<x-layouts.app>
    <div class="p-6 bg-white dark:bg-neutral-900 rounded-xl shadow-md">

        <!-- Header with Add Buttons -->
        <div class="flex items-center justify-between mb-4">
            <h2 class="text-xl font-semibold text-gray-800 dark:text-white">Admin List</h2>
            <div class="flex gap-2">

                <!-- Flux Button to Trigger the Modal -->
                <flux:modal.trigger name="create-admin">
                    <flux:button class="flux-btn flux-btn-primary flux-btn-sm">
                        Create Admin Account
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
                    @foreach ($admins as $admin)
                    <tr
                        class="cursor-pointer hover:bg-gray-100 dark:hover:bg-neutral-800 transition flux-btn-info"
                        data-id="{{ $admin->id }}"
                        data-name="{{ $admin->name }}"
                        data-email="{{ $admin->email }}"
                        data-customer-id="{{ $admin->customer_id }}"
                        onclick="document.getElementById('open-edit-modal').click()"
                    >

                        <td>{{ $admin->id }}</td>
                        <td>{{ $admin->name }}</td>
                        <td>{{ $admin->email }}</td>
                    </tr>

                    @endforeach

                </tbody>
            </table>
        </div>
        
        <!-- Hidden Modal Trigger for Edit -->
        <flux:modal.trigger name="edit-admin-modal">
            <button id="open-edit-modal" class="hidden"></button>
        </flux:modal.trigger>

    </div>
    @include('admin.admin-account.form-edit-admin')
    @include('admin.admin-account.form-create-admin')


</x-layouts.app>