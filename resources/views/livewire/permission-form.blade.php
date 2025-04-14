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