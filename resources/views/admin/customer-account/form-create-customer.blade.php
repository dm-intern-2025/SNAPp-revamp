<div x-data="{}" x-init="
    @if (session('show_modal') === 'customer-modal')
        $nextTick(() => $flux.modal('customer-modal').show())
    @endif
">
    {{-- Changed class to make the modal wider --}}
    <flux:modal name="customer-modal" class="md:max-w-3xl">
        <form action="{{ route('users.store') }}" method="POST" class="space-y-6" id="create-form">
            @csrf
            <div>
                <flux:heading size="lg">Create New Customer Account</flux:heading>
                <flux:text class="mt-2">Fill in the required user details and optional shared profile information.</flux:text>
            </div>

            {{-- Main grid for layout --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-x-6 gap-y-2">
                
                {{-- Column 1: User Info --}}
                <div class="space-y-4">
                    <flux:field>
                        <flux:label badge="Required">Name</flux:label>
                        <flux:input name="name" value="{{ old('name') }}" placeholder="Enter customer name" />
                        @error('name')<p class="mt-2 text-xs text-red-500">{{ $message }}</p>@enderror
                    </flux:field>
                    
                    <flux:field>
                        <flux:label badge="Required">Email</flux:label>
                        <flux:input name="email" type="email" value="{{ old('email') }}" placeholder="Enter customer email" />
                        @error('email')<p class="mt-2 text-xs text-red-500">{{ $message }}</p>@enderror
                    </flux:field>
                    
                    <flux:field>
                        <flux:label badge="Required">Customer ID</flux:label>
                        <flux:input name="customer_id" value="{{ old('customer_id') }}" placeholder="Enter customer ID" />
                        @error('customer_id')<p class="mt-2 text-xs text-red-500">{{ $message }}</p>@enderror
                    </flux:field>
                </div>

                {{-- Column 2: Profile Info --}}
                <div class="space-y-4">
                    <flux:field>
                        <flux:label>Account Name (Optional)</flux:label>
                        <flux:input name="account_name" value="{{ old('account_name') }}" placeholder="Enter Account Name" />
                        @error('account_name')<p class="mt-2 text-xs text-red-500">{{ $message }}</p>@enderror
                    </flux:field>

                    <flux:field>
                        <flux:label badge="Required">Short Name</flux:label>
                        <flux:input name="short_name" value="{{ old('short_name') }}" placeholder="Enter Short Name" />
                        @error('short_name')<p class="mt-2 text-xs text-red-500">{{ $message }}</p>@enderror
                    </flux:field>
                    
                    <flux:field>
                        <flux:label>Customer Category (Optional)</flux:label>
                        <flux:input name="customer_category" value="{{ old('customer_category') }}" placeholder="Enter Customer Category" />
                        @error('customer_category')<p class="mt-2 text-xs text-red-500">{{ $message }}</p>@enderror
                    </flux:field>
                </div>

                {{-- Full-width fields at the bottom --}}
                <div class="md:col-span-2 pt-4">
                    <hr class="mb-4">
                     <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                        <flux:field>
                            <flux:label>Contract Price</flux:label>
                            <flux:input name="contract_price" type="number" step="0.01" value="{{ old('contract_price') }}" placeholder="e.g., 1500.50" />
                            @error('contract_price')<p class="mt-2 text-xs text-red-500">{{ $message }}</p>@enderror
                        </flux:field>

                        <flux:field>
                            <flux:label>Contracted Demand</flux:label>
                            <flux:input name="contracted_demand" type="number" value="{{ old('contracted_demand') }}" placeholder="e.g., 5000" />
                            @error('contracted_demand')<p class="mt-2 text-xs text-red-500">{{ $message }}</p>@enderror
                        </flux:field>

                        <flux:field>
                            <flux:label>Cooperation Start</flux:label>
                            <flux:input name="cooperation_period_start_date" type="date" value="{{ old('cooperation_period_start_date') }}" />
                            @error('cooperation_period_start_date')<p class="mt-2 text-xs text-red-500">{{ $message }}</p>@enderror
                        </flux:field>
                        
                        <flux:field>
                            <flux:label>Cooperation End</flux:label>
                            <flux:input name="cooperation_period_end_date" type="date" value="{{ old('cooperation_period_end_date') }}" />
                            @error('cooperation_period_end_date')<p class="mt-2 text-xs text-red-500">{{ $message }}</p>@enderror
                        </flux:field>
                    </div>
                </div>
            </div>

            <div class="flex pt-4">
                <flux:spacer />
                <flux:button type="submit" variant="primary">Create Account</flux:button>
            </div>
        </form>
    </flux:modal>
</div>
