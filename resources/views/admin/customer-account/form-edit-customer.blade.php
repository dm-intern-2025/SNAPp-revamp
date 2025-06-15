<div x-data="{}" x-init="
    @if (session('show_modal') === 'edit-customer-modal')
        $nextTick(() => $flux.modal('edit-customer-modal').show())
    @endif
">
    {{-- Expanded the modal width --}}
    <flux:modal name="edit-customer-modal" class="md:max-w-3xl">
        <form
            data-base-action="{{ route('users.update', ['user' => ':user_id']) }}"
            method="POST"
            id="edit-customer-form"
            class="space-y-6"
        >
            @csrf
            @method('PUT')

            <div>
                <flux:heading size="lg">Edit Customer Account</flux:heading>
                <flux:text class="mt-2">Update user details and the shared profile information.</flux:text>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-x-6 gap-y-2">
                
                {{-- Column 1: User Info --}}
                <div class="space-y-4">
                    <flux:field>
                        <flux:label badge="Required">Name</flux:label>
                        <flux:input name="edit_name" placeholder="Enter customer name" />
                    </flux:field>
                    
                    <flux:field>
                        <flux:label badge="Required">Email</flux:label>
                        <flux:input name="edit_email" type="email" placeholder="Enter customer email" />
                    </flux:field>
                    
                    <flux:field>
                        <flux:label badge="Required">Customer ID</flux:label>
                        <flux:input name="edit_customer_id" placeholder="Enter customer ID" />
                    </flux:field>
                </div>

                {{-- Column 2: Profile Info --}}
                <div class="space-y-4">
                    <flux:field>
                        <flux:label>Account Name</flux:label>
                        <flux:input name="edit_account_name" placeholder="Enter Account Name" />
                    </flux:field>

                    <flux:field>
                        <flux:label>Short Name</flux:label>
                        <flux:input name="edit_short_name" placeholder="Enter Short Name" />
                    </flux:field>
                    
                    <flux:field>
                        <flux:label>Customer Category</flux:label>
                        <flux:input name="edit_customer_category" placeholder="Enter Customer Category" />
                    </flux:field>
                </div>

                {{-- Full-width fields at the bottom --}}
                <div class="md:col-span-2 pt-4">
                    <hr class="mb-4">
                     <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                        <flux:field>
                            <flux:label>Contract Price</flux:label>
                            <flux:input name="edit_contract_price" type="number" step="0.01" />
                        </flux:field>
                        <flux:field>
                            <flux:label>Contracted Demand</flux:label>
                            <flux:input name="edit_contracted_demand" type="number" />
                        </flux:field>
                        <flux:field>
                            <flux:label>Cooperation Start</flux:label>
                            <flux:input name="edit_cooperation_period_start_date" type="date" />
                        </flux:field>
                        <flux:field>
                            <flux:label>Cooperation End</flux:label>
                            <flux:input name="edit_cooperation_period_end_date" type="date" />
                        </flux:field>
                    </div>
                </div>
            </div>

            <div class="flex pt-4">
                <flux:spacer />
                <flux:button type="submit" variant="primary">Save Changes</flux:button>
            </div>
        </form>
    </flux:modal>
</div>

{{-- This script now lives with the modal it controls --}}
<script>
    // This script only needs to run once when the page loads.
    document.addEventListener('click', function (event) {
        // We check if the clicked element (or its parent) is a table row with the 'flux-btn-info' class
        const button = event.target.closest('.flux-btn-info');
        if (!button) {
            return; // If not, do nothing.
        }
        
        const form = document.getElementById('edit-customer-form');
        const dataset = button.dataset; // The data-* attributes of the clicked row

        // Update form action
        const baseAction = form.dataset.baseAction;
        form.action = baseAction.replace(':user_id', dataset.id);

        // Helper to populate fields
        const setInputValue = (name, value) => {
            const input = form.querySelector(`[name="${name}"]`);
            if (input) {
                // Use value || '' to prevent "null" or "undefined" from appearing in fields
                input.value = value || '';
            }
        };

        // Populate all fields using the 'edit_' prefix, reading from the dataset
        setInputValue('edit_name', dataset.name);
        setInputValue('edit_email', dataset.email);
        setInputValue('edit_customer_id', dataset.customerId);
        
        // THE FIX: Populate the profile fields
        setInputValue('edit_account_name', dataset.accountName);
        setInputValue('edit_short_name', dataset.shortName);
        setInputValue('edit_customer_category', dataset.customerCategory);
        setInputValue('edit_contract_price', dataset.contractPrice);
        setInputValue('edit_contracted_demand', dataset.contractedDemand);
        setInputValue('edit_cooperation_period_start_date', dataset.startDate);
        setInputValue('edit_cooperation_period_end_date', dataset.endDate);
    });
</script>
