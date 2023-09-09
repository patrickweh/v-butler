import './features/dark-mode'

document.addEventListener('livewire:navigated', function() {
    wireNavigation();
});

document.addEventListener('livewire:init', function() {
    wireNavigation();
});

function wireNavigation() {
    let links = document.querySelectorAll('a')

    links.forEach(link => {
        link.setAttribute('wire:navigate', 'true')
    });
}
