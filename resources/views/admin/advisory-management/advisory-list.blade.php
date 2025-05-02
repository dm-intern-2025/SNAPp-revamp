<x-layouts.app>
    <div class="p-6 bg-white dark:bg-neutral-900 rounded-xl shadow-md">
        <div class="flex items-center justify-between mb-4">
            <h2 class="text-xl font-semibold text-gray-800 dark:text-white">Advisories</h2>

            <!-- Button aligned right -->
            <flux:modal.trigger name="create-advisory">
                <flux:button class="flux-btn flux-btn-primary flux-btn-sm ml-auto">
                    Create Advisory
                </flux:button>
            </flux:modal.trigger>
        </div>

        <div class="overflow-x-auto rounded-lg border border-gray-200 dark:border-neutral-700">
            <table class="min-w-full text-left">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Headline</th>
                        <th>Created By</th>
                        <th>Date</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($advisories as $advisory)
                    <tr
                        class="cursor-pointer hover:bg-gray-100 dark:hover:bg-neutral-800 transition flux-btn-info"
                        data-id="{{ $advisory->id }}"
                        data-headline="{{ $advisory->headline }}"
                        data-description="{{ $advisory->description }}"
                        data-content="{{ $advisory->content }}"
                        data-attachment="{{ $advisory->attachment ? asset('storage/' . $advisory->attachment) : '' }}"
                        data-date="{{ $advisory->created_at->format('M d, Y') }}"
                        data-created-by="{{ $advisory->created_by }}"
                        onclick="document.getElementById('open-view-modal').click()">
                        <td>{{ $advisory->id }}</td>
                        <td>{{ $advisory->headline }}</td>
                        <td>{{ $advisory->user->name }}</td>
                        <td>{{ $advisory->created_at->format('M d, Y') }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="mt-6">
            {{ $advisories->links() }}
        </div>

        <flux:modal.trigger name="advisory-show-modal">
            <button id="open-view-modal" class="hidden"></button>
        </flux:modal.trigger>
    </div>

    @include('admin.advisory-management.advisory-show-modal')
    @include('admin.advisory-management.form-create-advisory')

</x-layouts.app>
