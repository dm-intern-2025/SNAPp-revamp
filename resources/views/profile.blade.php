<x-layouts.app>
    <div class="h-full w-full px-4 py-6">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 h-full">

            <!-- First Card - Account Information -->
            <div class="flex flex-col bg-white rounded-2xl shadow p-6">
                <div class="flex justify-between items-center">
                    <h2 class="text-lg font-bold">Account Information</h2>
                </div>
                <flux:field>
                    <flux:input label="Account Name" placeholder="Enter Account Name" />
                    <flux:input label="Short Name" placeholder="Enter Short Name" />
                    <flux:input badge="customer" label="Business Address" placeholder="Enter Business Address" />
                    <flux:input badge="customer" label="Facility Address" placeholder="Enter Facility Address" />
                    <flux:input label="Customer Category" placeholder="Enter Customer Category" />
                </flux:field>
            </div>

            <!-- Second Card - Contract Information -->
            <div class="flex flex-col bg-white rounded-2xl shadow p-6">
                <div class="flex justify-between items-center">
                    <h2 class="text-lg font-bold">Contract Information</h2>
                </div>
                <flux:field>
                    <flux:input label="Cooperation Period Start Date" placeholder="Enter Start Date" type="date" />
                    <flux:input label="Cooperation Period End Date" placeholder="Enter End Date" type="date" />
                    <flux:input label="Contract Price" placeholder="Enter Contract Price" />
                    <flux:input label="Contract Demand" placeholder="Enter Contract Demand" />
                    <flux:input badge="customer" label="Other Information" placeholder="Enter Additional Info" />
                </flux:field>
            </div>

            <!-- Third Card - Contact Information -->
            <div class="flex flex-col bg-white rounded-2xl shadow p-6">
                <div class="flex justify-between items-center">
                    <h2 class="text-lg font-bold">Contact Information</h2>
                </div>
                <flux:field>
                    <flux:input badge="customer" label="Contact Name" placeholder="Enter Contact Name" />
                    <flux:input badge="customer" label="Designation" placeholder="Enter Designation" />
                    <flux:input badge="customer"label="Email" placeholder="Enter Email" type="email" />
                    <flux:input badge="customer" label="Mobile Number" placeholder="Enter Mobile Number" type="tel" />
                </flux:field>
            </div>

        </div>
    </div>
</x-layouts.app>