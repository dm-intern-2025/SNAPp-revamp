<div
    x-data="{}"
    x-init="
    @if (session('show_modal') === 'create-accountexecutive')
        $nextTick(() => $flux.modal('create-accountexecutive').show())
    @endif
"
>    <flux:modal 
        name="create-accountexecutive" 
        class="md:w-96"
    >
        
        <form 
            action="{{ route('admin.users.store-account-executive') }}" 
            method="POST" 
            class="space-y-6"
            id="create-form"
        > 
            @csrf

            <div>
                <flux:heading size="lg">
                    Create New Account Executive Account
                </flux:heading>

                <flux:text class="mt-2">
                    Fill in the details below to create a new customer account.
                </flux:text>
            </div>

            <flux:field>
                <flux:label badge="Required">Name</flux:label>
                <flux:input 
                    name="name" 
                    value="{{ old('name') }}"
                    placeholder="Enter account executive name"/>
                @error('name')
                    <p class="mt-2 text-red-500 dark:text-red-400 text-xs">{{ $message }}</p>
                @enderror
            </flux:field>

            <flux:field>
                <flux:label badge="Required">Email</flux:label>
                <flux:input 
                    name="email" 
                    type="email"
                    value="{{ old('email') }}"
                    placeholder="Enter account executive email"/>
                @error('email')
                    <p class="mt-2 text-red-500 dark:text-red-400 text-xs">{{ $message }}</p>
                @enderror
            </flux:field>

                      <flux:field>
                <div class="mb-4">
                    <label for="customer_id" class="block text-sm font-medium text-gray-700 mb-1">
                        Assign Profile <span class="text-red-500">*</span>
                    </label>
                    <select
                        name="customer_id"
                        id="customer_id"
                        class="w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                        <option value="">— Select account —</option>
                        @foreach ($profiles as $profile)
                        <option value="{{ $profile->customer_id }}"
                            {{ old('customer_id') == $profile->customer_id ? 'selected' : '' }}>
                            {{ $profile->account_name }} ({{ $profile->short_name }})
                        </option>
                        @endforeach
                    </select>
                    @error('customer_id')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </flux:field>

            <div class="flex">
                <flux:spacer />
                <flux:button 
                    type="button" 
                    id="create-button"
                    variant="primary">
                    Create Account
                </flux:button>
            </div>
        </form>
    </flux:modal>

    <script>
        document.getElementById('create-button').addEventListener('click', function() {
            const createBtn = this;
            const form = document.getElementById('create-form');

            createBtn.disabled = true;
            createBtn.innerText = 'Creating Account...';

            // Submit the form
            form.submit();
        });
    </script>
</div>
