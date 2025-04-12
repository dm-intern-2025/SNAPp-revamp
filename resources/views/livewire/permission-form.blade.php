<div>
    @if($showModal)
        <div class="modal show d-block" tabindex="-1" role="dialog">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">{{ $permissionId ? 'Edit' : 'Create' }} Permission</h5>
                        <button type="button" wire:click="closeModal" class="btn-close" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form wire:submit.prevent="save">
                            <div class="mb-3">
                                <label class="form-label">Permission Name</label>
                                <input type="text" wire:model="title" class="form-control">
                                @error('title') <span class="text-danger">{{ $message }}</span> @enderror
                            </div>
                            <button type="submit" class="btn btn-primary">Save</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal-backdrop fade show"></div>
    @endif
</div>