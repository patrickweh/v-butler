const defaultTheme = require('tailwindcss/defaultTheme')
const colors = require('tailwindcss/colors')

module.exports = {
    content: [
        "./resources/**/*.blade.php",
        "./resources/**/*.js",
        "./resources/**/*.vue",

        './vendor/wireui/wireui/resources/**/*.blade.php',
        './vendor/wireui/wireui/ts/**/*.ts',
        './vendor/wireui/wireui/src/View/**/*.php'
    ],
    theme: {
        extend: {
            colors: {
                "color-range": {
                    10: "#16a34a",
                    9: "#4ade80",
                    8: "#a3e635",
                    7: "#facc15",
                    6: "#fde047",
                    5: "#f59e0b",
                    4: "#f97316",
                    3: "#fb923c",
                    2: "#fbbf24",
                    1: "#fb923c",
                    0: "#dc2626",
                }
            },
        },
    },
    presets: [
        require('./vendor/wireui/wireui/tailwind.config.js')
    ],
    plugins: [
        require('@tailwindcss/typography'),
        require('@tailwindcss/forms'),
        require('@tailwindcss/aspect-ratio'),
    ],
}
