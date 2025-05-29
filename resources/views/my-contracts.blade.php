<x-layouts.app>
    <div class="p-6 bg-white dark:bg-neutral-900 rounded-xl shadow-md">

        <!-- Filters / Search -->
        <form method="GET" action="" class="mb-4 flex flex-wrap justify-end items-center gap-4">
            <flux:input
                icon="magnifying-glass"
                name="search"
                placeholder="Search Contract Period..."
                value=""
                class="w-full md:w-1/4" />
        </form>


        <!-- Table -->
        <div class="overflow-x-auto rounded-lg border border-gray-200 dark:border-neutral-700">
            <table class="min-w-full text-left">
                <thead>
                    <tr>
                        <th>Reference No.</th>
                        <th>Description</th>
                        <th>Contract Period</th>
                        <th>Upload Date</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <tr class="hover:bg-gray-100 dark:hover:bg-neutral-800 transition cursor-pointer">
                        <td>DOC-001</td>
                        <td>Annual Service Agreement</td>
                        <td>Jan 2024 – Dec 2024</td>
                        <td>2024-01-10</td>
                        <td>Active</td>
                    </tr>
                    <tr class="hover:bg-gray-100 dark:hover:bg-neutral-800 transition cursor-pointer">
                        <td>DOC-002</td>
                        <td>Lease Renewal</td>
                        <td>Feb 2023 – Jan 2024</td>
                        <td>2023-02-01</td>
                        <td>Expired</td>
                    </tr>
                    <tr class="hover:bg-gray-100 dark:hover:bg-neutral-800 transition cursor-pointer">
                        <td>DOC-003</td>
                        <td>Consulting Contract</td>
                        <td>Mar 2024 – Aug 2024</td>
                        <td>2024-03-05</td>
                        <td>Active</td>
                    </tr>
                </tbody>
            </table>
        </div>

    </div>
</x-layouts.app>