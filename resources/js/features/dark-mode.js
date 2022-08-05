document.addEventListener('turbo:load', () => {
    const preferDark = JSON.parse(window.localStorage.getItem('dark'))
    const browserDark = window.matchMedia('(prefers-color-scheme: dark)').matches

    if ((browserDark && preferDark !== null && preferDark !== false) || preferDark === true) {
        document.documentElement.classList.add('dark')
    }
})
