<flux:modal name="edit-admin-modal"
    class="md:w-96">
    <form
        data-base-action="{{ route('admin.users.update-admin', 
                        ['user' => ':user_id']) }}"

        method="POST"
        id="edit-admin-form"
        class="space-y-6">
        @csrf
        @method('PUT')

        <input type="hidden" name="user_id" value="">

        <div>
            <flux:heading size="lg">
                Edit admin Account
            </flux:heading>

            <flux:text class="mt-2">
                Update the admin details below.
            </flux:text>
        </div>

        <flux:field>
            <flux:label>Name</flux:label>
            <flux:input
                name="name"
                value="{{ old('name', '') }}"
                placeholder="Enter admin name" />
                @error('name')
                    <p class="mt-2 text-red-500 dark:text-red-400 text-xs">{{ $message }}</p>
                @enderror
        </flux:field>

        <flux:field>
            <flux:label>Email</flux:label>
            <flux:input
                name="email"
                value="{{ old('email', '') }}"
                placeholder="Enter admin email" />
                @error('email')
                    <p class="mt-2 text-red-500 dark:text-red-400 text-xs">{{ $message }}</p>
                @enderror
        </flux:field>

        <flux:field>
            <flux:label>Customer ID</flux:label>
            <flux:input
                name="customer_id"
                value="{{ old('customer_id', '') }}"
                placeholder="Enter admin ID" />
                @error('customer_id')
                    <p class="mt-2 text-red-500 dark:text-red-400 text-xs">{{ $message }}</p>
                @enderror
        </flux:field>

        <div class="flex">
            <flux:spacer />
            <flux:button type="submit" variant="primary">Save Changes</flux:button>
        </div>
    </form>
</flux:modal>

<script>
    document.querySelectorAll('.flux-btn-info').forEach(button => {
        button.addEventListener('click', function() {
            const userId = this.getAttribute('data-id');
            const userName = this.getAttribute('data-name');
            const userEmail = this.getAttribute('data-email');
            const customerId = this.getAttribute('data-customer-id');

            // Dynamically update the form action URL with the correct user ID
            const form = document.getElementById('edit-admin-form');
            const baseAction = form.getAttribute('data-base-action');
            form.action = baseAction.replace(':user_id', userId);

            // Set the values in the form fields
            document.querySelector('input[name="user_id"]').value = userId;
            document.querySelector('input[name="name"]').value = userName;
            document.querySelector('input[name="email"]').value = userEmail;
            document.querySelector('input[name="customer_id"]').value = customerId;
        });
    });
</script>