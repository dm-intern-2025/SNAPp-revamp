<div>
    <div class="modal @if($showModal) show d-block @endif">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">{{ $roleId ? 'Edit' : 'Create' }} Role</h5>
                    <button type="button" wire:click="resetForm" class="btn-close"></button>
                </div>
                <div class="modal-body">
                    <form wire:submit.prevent="save">
                        <div class="mb-3">
                            <label>Role Name</label>
                            <input type="text" wire:model="title" class="form-control">
                            @error('title') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>
                        <button type="submit" class="btn btn-primary">Save</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>