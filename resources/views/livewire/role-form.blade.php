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
