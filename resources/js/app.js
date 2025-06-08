import Chart from 'chart.js/auto';
import './energyChart.js';
window.Chart = Chart;


document.addEventListener('livewire:load', () => {
    if (window.livewireFlux?.theme) {
        window.livewireFlux.theme.set('light');
        window.livewireFlux.theme.auto(false);
    }
});
