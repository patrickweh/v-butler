<div class="flex items-center gap-x-2 justify-center" x-data="{
    dark: @entangle('dark'),
    browserDarkMode() {
        return window.matchMedia('(prefers-color-scheme: dark)').matches
    },
    enable() {
        this.dark = true
        window.localStorage.setItem('dark', true)
        document.documentElement.classList.add('dark')
    },
    disable() {
        this.dark = false
        window.localStorage.setItem('dark', false)
        document.documentElement.classList.remove('dark')
    },
    syncDarkMode() {
        this.dark ? this.enable() : this.disable()
    }
}"
     x-init="function() {
    this.syncDarkMode()
    $watch('dark', dark => this.syncDarkMode())
}">
    <x-icon
        class="w-5 h-5 cursor-pointer text-gray-700 dark:text-secondary-200"
        x-on:click="disable"
        name="sun"
    />

    <x-toggle x-model="dark" id="dark-mode-toggle.{{ $this->getId() }}" />

    <x-icon
        class="w-5 h-5 cursor-pointer text-gray-700 dark:text-secondary-200"
        x-on:click="enable"
        name="moon"
    />
</div>
