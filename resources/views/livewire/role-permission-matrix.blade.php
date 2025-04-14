<div class="p-6 bg-white dark:bg-neutral-900 rounded-xl shadow-md">
    <!-- Header with Add Buttons -->
    <div class="flex items-center justify-between mb-4">
        <h2 class="text-xl font-semibold text-gray-800 dark:text-white">Roles & Permissions</h2>

        <!-- Wrap this entire group in a div -->
        <div class="flex gap-2">
            <!-- Add Role Trigger -->
            <flux:modal.trigger name="role-modal">
                <flux:button class="flux-btn flux-btn-primary flux-btn-sm">
                    Add Role
                </flux:button>
            </flux:modal.trigger>

            <!-- Add Permission Trigger -->
            <flux:modal.trigger name="permission-modal">
                <flux:button class="flux-btn flux-btn-primary flux-btn-sm">
                    Add Permission
                </flux:button>
            </flux:modal.trigger>
        </div> <!-- ✅ This div closes the buttons flex container -->
    </div> <!-- ✅ This div closes the header container -->

<!-- Role Modal -->
<flux:modal name="role-modal" class="md:w-96">
    <div class="space-y-6">
        <div>
            <flux:heading size="lg">
                {{ $editingId && $formType === 'role' ? 'Edit Role' : 'Add Role' }}
            </flux:heading>
            <flux:text class="mt-2">
                {{ $editingId && $formType === 'role'
                    ? 'Update the role name.'
                    : 'Create a new role.' }}
            </flux:text>
        </div>

        <flux:input
            label="Role Name"
            placeholder="Role name"
            wire:model.defer="roleName"
        />

        <div class="flex">
            <flux:spacer />
            <flux:button
                wire:click="saveRole"
                variant="primary"
            >
                {{ $editingId && $formType === 'role' ? 'Save changes' : 'Create Role' }}
            </flux:button>
        </div>
    </div>
</flux:modal>

<!-- Permission Modal -->
<flux:modal name="permission-modal" class="md:w-96">
    <div class="space-y-6">
        <div>
            <flux:heading size="lg">
                {{ $editingId && $formType === 'permission' ? 'Edit Permission' : 'Add Permission' }}
            </flux:heading>
            <flux:text class="mt-2">
                {{ $editingId && $formType === 'permission'
                    ? 'Update the permission name.'
                    : 'Create a new permission.' }}
            </flux:text>
        </div>

        <flux:input
            label="Permission Name"
            placeholder="Permission name"
            wire:model.defer="permissionName"
        />

        <div class="flex">
            <flux:spacer />
            <flux:button
                wire:click="savePermission"
                variant="primary"
            >
                {{ $editingId && $formType === 'permission' ? 'Save changes' : 'Create Permission' }}
            </flux:button>
        </div>
    </div>
</flux:modal>



    <!-- Table -->
    <div class="overflow-x-auto">
        <table class="table-auto min-w-full border border-gray-300 dark:border-neutral-700 text-sm">
            <thead class="bg-gray-100 dark:bg-neutral-800 text-gray-700 dark:text-gray-200">
                <tr>
                    <th class="p-3 border border-gray-300 dark:border-neutral-700">
                        Permission / Role
                    </th>
                    @foreach ($roles as $role)
                        <th class="p-3 text-center border border-gray-300 dark:border-neutral-700">
                            <div class="flex flex-col items-center">
                                <span>{{ $role->name }}</span>
                                <div class="flex gap-1 mt-1">
                                <flux:modal.trigger name="role-modal">
                                    <flux:button 
                                        wire:click="editRole({{ $role->id }})" 
                                        class="flux-btn flux-btn-primary flux-btn-sm">
                                        ✏️
                                    </flux:button>
                                </flux:modal.trigger>          

                                <flux:button 
                                    wire:click="deleteRole({{ $role->id }})" 
                                    class="flux-btn flux-btn-xs flux-btn-danger">
                                    🗑️
                                </flux:button>
                                </div>
                            </div>
                        </th>
                    @endforeach
                </tr>
            </thead>
            <tbody class="bg-white dark:bg-neutral-900 text-gray-800 dark:text-gray-100">
                @foreach ($permissions as $permission)
                    <tr>
                        <td class="p-3 border border-gray-300 dark:border-neutral-700">
                            <div class="flex justify-between items-center">
                                <span>{{ $permission->name }}</span>
                                <div class="flex gap-1">
                                <flux:modal.trigger name="permission-modal">
                                    <flux:button 
                                        wire:click="editPermission({{ $permission->id }})" 
                                        class="flux-btn flux-btn-xs flux-btn-info">
                                        ✏️
                                    </flux:button>
                                </flux:modal.trigger>

                                    <flux:button 
                                        wire:click="deletePermission({{ $permission->id }})" 
                                        class="flux-btn flux-btn-xs flux-btn-danger">
                                        🗑️
                                    </flux:button>
                                </div>
                            </div>
                        </td>
                        @foreach ($roles as $role)
                            <td class="text-center p-3 border border-gray-300 dark:border-neutral-700">
                                <input 
                                    type="checkbox" 
                                    wire:model="selectedPermissions.{{ $role->id }}.{{ $permission->id }}"
                                    class="form-check-input"
                                >
                            </td>
                        @endforeach
                    </tr>
                @endforeach
            </tbody>
            <!-- Save Button Row -->
            <tfoot>
                <tr>
                    @role('admin')
                    <td class="p-4 border-t border-gray-300 dark:border-neutral-700 text-center">
                        <flux:button wire:click="savePermissions" class="flux-btn flux-btn-success flux-btn-sm">
                            💾 Save Changes
                        </flux:button>
                    </td>
                    @endrole
                    @foreach ($roles as $role)
                        <td class="border-t border-gray-300 dark:border-neutral-700"></td>
                    @endforeach
                </tr>
            </tfoot>
        </table>
    </div>
</div>
