<flux:modal name="all-users-modal" class="md:w-96">
    <form
        action="{{ route('all-user-list.update', 
        ['user' => ':user_id']) }}"
        data-base-action="{{ route('all-user-list.update', 
        ['user' => ':user_id']) }}"
        method="POST"
        id="edit-user-form"
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

        <flux:field>
    <flux:label>Account Status</flux:label>

    <div class="flex items-center gap-3 mt-1">
        <flux:switch id="account-status-switch" />
        <span id="account-status-label" class="text-sm font-medium text-gray-700">Loading...</span>
    </div>

    <input type="hidden" name="active" id="active-value" value="">

    <flux:error name="active" />
</flux:field>





        <div class="flex justify-end">
            <flux:button type="submit" variant="primary">Save Changes</flux:button>
        </div>
    </form>
</flux:modal>

<script>
  document.querySelectorAll('.flux-btn-info').forEach(button => {
    button.addEventListener('click', function() {
      const userId     = this.getAttribute('data-id');
      const userName   = this.getAttribute('data-name');
      const userEmail  = this.getAttribute('data-email');
      const customerId = this.getAttribute('data-customer-id');
      const userRole   = this.getAttribute('data-role');
      const active     = this.getAttribute('data-active'); // "1" or "0"

      // 1) Populate the form fields and action
      const form       = document.getElementById('edit-user-form');
      const baseAction = form.getAttribute('data-base-action');
      form.action      = baseAction.replace(':user_id', userId);

      form.querySelector('input[name="user_id"]').value     = userId;
      form.querySelector('input[name="name"]').value        = userName;
      form.querySelector('input[name="email"]').value       = userEmail;
      form.querySelector('input[name="customer_id"]').value = customerId;
      form.querySelector('select[name="role"]').value       = userRole;

      // 2) Initialize the switch (no flip)
      const isActive = active === '1';
      // flux:switch renders a checkbox input under the hood
      const switchContainer = document.getElementById('account-status-switch');
      const checkbox = switchContainer.querySelector('input[type="checkbox"]');
      if (checkbox) {
        checkbox.checked = isActive;
      }

      // 3) Set initial label & hidden input
      document.getElementById('account-status-label').textContent = isActive ? 'Deactivate' : 'Activate';
      document.getElementById('active-value').value              = isActive ? '1' : '0';

      // 4) Remove any old listener and add a fresh one
      if (checkbox) {
        const newCheckbox = checkbox.cloneNode(true);
        checkbox.replaceWith(newCheckbox);

        newCheckbox.addEventListener('change', function() {
          const nowActive = this.checked;
          document.getElementById('account-status-label').textContent = nowActive ? 'Deactivate' : 'Activate';
          document.getElementById('active-value').value              = nowActive ? '1' : '0';
        });
      }

      // 5) Finally open the modal
      $flux.modal('all-users-modal').show();
    });
  });
</script>
