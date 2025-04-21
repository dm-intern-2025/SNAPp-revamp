<x-layouts.app.sidebar :title="$title ?? null">
<script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>    
<flux:main>
        {{ $slot }}
    </flux:main>
</x-layouts.app.sidebar>
