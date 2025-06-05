document.addEventListener('livewire:load', () => {
    if (window.livewireFlux?.theme) {
        window.livewireFlux.theme.set('light');
        window.livewireFlux.theme.auto(false);
    }
});
