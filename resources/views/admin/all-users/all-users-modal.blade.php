<flux:modal name="edit-customer-modal" class="md:w-96">
    <form
        action="{{ route('all-user-list.update', ['user' => ':user_id']) }}"
        data-base-action="{{ route('all-user-list.update', ['user' => ':user_id']) }}"
        method="POST"
        id="edit-customer-form"
        class="space-y-6">
        @csrf
        @method('PUT')

        <!-- Hidden user_id field -->
        <input type="hidden" name="user_id" value="">

        <div>
            <flux:heading size="lg">
                Edit Customer Account
            </flux:heading>
            <flux:text class="mt-2">
                Update the customer details below.
            </flux:text>
        </div>

        <flux:field>
            <flux:label>Name</flux:label>
            <flux:input
                name="name"
                value="{{ old('name', '') }}"
                placeholder="Enter customer name" />
        </flux:field>

        <flux:field>
            <flux:label>Email</flux:label>
            <flux:input
                name="email"
                value="{{ old('email', '') }}"
                placeholder="Enter customer email" />
        </flux:field>

        <flux:field>
            <flux:label>Customer ID</flux:label>
            <flux:input
                name="customer_id"
                value="{{ old('customer_id', '') }}"
                placeholder="Enter customer ID" />
        </flux:field>

        <flux:field>
            <flux:label>Role</flux:label>
            <flux:select name="role" id="role-select" placeholder="Choose role...">
                @foreach($roles as $role)
                <flux:select.option value="{{ $role->name }}">
                    {{ $role->name }}
                </flux:select.option>
                @endforeach
            </flux:select>
        </flux:field>


        <div class="flex justify-end">
            <flux:button type="submit" variant="primary">Save Changes</flux:button>
        </div>
    </form>
</flux:modal>

<script>
    // Populate the edit modal with the clicked user's data
    document.querySelectorAll('.flux-btn-info').forEach(button => {
        button.addEventListener('click', function() {
            const userId = this.getAttribute('data-id');
            const userName = this.getAttribute('data-name');
            const userEmail = this.getAttribute('data-email');
            const customerId = this.getAttribute('data-customer-id');
            const userRole = this.getAttribute('data-role');

            // Update form action using base-action to avoid double replacements
            const form = document.getElementById('edit-customer-form');
            const baseAction = form.getAttribute('data-base-action');
            form.action = baseAction.replace(':user_id', userId);

            // Set hidden and input field values
            form.querySelector('input[name="user_id"]').value = userId;
            form.querySelector('input[name="name"]').value = userName;
            form.querySelector('input[name="email"]').value = userEmail;
            form.querySelector('input[name="customer_id"]').value = customerId;
            form.querySelector('select[name="role"]').value = userRole;
        });
    });
</script>