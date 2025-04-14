<flux:modal name="customer-modal" class="md:w-96">
    <form action="{{ route('users.store') }}" method="POST" class="space-y-6">
        @csrf

        <div>
            <flux:heading size="lg">Create New Customer Account</flux:heading>
            <flux:text class="mt-2">Fill in the details below to create a new customer account.</flux:text>
        </div>

        <flux:input name="name" label="Customer Name" placeholder="Enter customer name" />
        <flux:input name="email" label="Email" placeholder="Enter customer email" type="email" />
        <flux:input name="customer_id" label="Customer ID" placeholder="Enter customer ID" />

        <div class="flex">
            <flux:spacer />
            <flux:button type="submit" variant="primary">Create Account</flux:button>
        </div>
    </form>
</flux:modal>
