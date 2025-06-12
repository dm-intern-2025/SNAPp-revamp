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
                        {{-- THE FIX: Added value="{{ old(...) }}" --}}
                        <flux:input name="edit_name" placeholder="Enter customer name" value="{{ old('edit_name') }}" />
                        @error('edit_name')<p class="mt-2 text-xs text-red-500">{{ $message }}</p>@enderror
                    </flux:field>
                    
                    <flux:field>
                        <flux:label badge="Required">Email</flux:label>
                        <flux:input name="edit_email" type="email" placeholder="Enter customer email" value="{{ old('edit_email') }}" />
                        @error('edit_email')<p class="mt-2 text-xs text-red-500">{{ $message }}</p>@enderror
                    </flux:field>
                    
                    <flux:field>
                        <flux:label badge="Required">Customer ID</flux:label>
                        <flux:input name="edit_customer_id" placeholder="Enter customer ID" value="{{ old('edit_customer_id') }}" />
                        @error('edit_customer_id')<p class="mt-2 text-xs text-red-500">{{ $message }}</p>@enderror
                    </flux:field>
                </div>

                {{-- Column 2: Profile Info --}}
                <div class="space-y-4">
                    <flux:field>
                        <flux:label>Account Name</flux:label>
                        <flux:input name="edit_account_name" placeholder="Enter Account Name" value="{{ old('edit_account_name') }}" />
                        @error('edit_account_name')<p class="mt-2 text-xs text-red-500">{{ $message }}</p>@enderror
                    </flux:field>

                    <flux:field>
                        <flux:label>Short Name</flux:label>
                        <flux:input name="edit_short_name" placeholder="Enter Short Name" value="{{ old('edit_short_name') }}" />
                        @error('edit_short_name')<p class="mt-2 text-xs text-red-500">{{ $message }}</p>@enderror
                    </flux:field>
                    
                    <flux:field>
                        <flux:label>Customer Category</flux:label>
                        <flux:input name="edit_customer_category" placeholder="Enter Customer Category" value="{{ old('edit_customer_category') }}" />
                        @error('edit_customer_category')<p class="mt-2 text-xs text-red-500">{{ $message }}</p>@enderror
                    </flux:field>
                </div>

                {{-- Full-width fields at the bottom --}}
                <div class="md:col-span-2 pt-4">
                    <hr class="mb-4">
                     <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                        <flux:field>
                            <flux:label>Contract Price</flux:label>
                            <flux:input name="edit_contract_price" type="number" step="0.01" value="{{ old('edit_contract_price') }}" />
                             @error('edit_contract_price')<p class="mt-2 text-xs text-red-500">{{ $message }}</p>@enderror
                        </flux:field>
                        <flux:field>
                            <flux:label>Contracted Demand</flux:label>
                            <flux:input name="edit_contracted_demand" type="number" value="{{ old('edit_contracted_demand') }}" />
                             @error('edit_contracted_demand')<p class="mt-2 text-xs text-red-500">{{ $message }}</p>@enderror
                        </flux:field>
                        <flux:field>
                            <flux:label>Cooperation Start</flux:label>
                            <flux:input name="edit_cooperation_period_start_date" type="date" value="{{ old('edit_cooperation_period_start_date') }}" />
                             @error('edit_cooperation_period_start_date')<p class="mt-2 text-xs text-red-500">{{ $message }}</p>@enderror
                        </flux:field>
                        <flux:field>
                            <flux:label>Cooperation End</flux:label>
                            <flux:input name="edit_cooperation_period_end_date" type="date" value="{{ old('edit_cooperation_period_end_date') }}" />
                             @error('edit_cooperation_period_end_date')<p class="mt-2 text-xs text-red-500">{{ $message }}</p>@enderror
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

{{-- This is your correct, working script. It populates the form on click. --}}
{{-- The `value` attributes added above will handle repopulation after failed validation. --}}
<script>
    document.addEventListener('click', function (event) {
        const button = event.target.closest('.flux-btn-info');
        if (!button) {
            return;
        }
        
        const form = document.getElementById('edit-customer-form');
        const dataset = button.dataset;

        const baseAction = form.dataset.baseAction;
        form.action = baseAction.replace(':user_id', dataset.id);

        const setInputValue = (name, value) => {
            const input = form.querySelector(`[name="${name}"]`);
            if (input) {
                input.value = value || '';
            }
        };

        setInputValue('edit_name', dataset.name);
        setInputValue('edit_email', dataset.email);
        setInputValue('edit_customer_id', dataset.customerId);
        setInputValue('edit_account_name', dataset.accountName);
        setInputValue('edit_short_name', dataset.shortName);
        setInputValue('edit_customer_category', dataset.customerCategory);
        setInputValue('edit_contract_price', dataset.contractPrice);
        setInputValue('edit_contracted_demand', dataset.contractedDemand);
        setInputValue('edit_cooperation_period_start_date', dataset.startDate);
        setInputValue('edit_cooperation_period_end_date', dataset.endDate);
    });
</script>
