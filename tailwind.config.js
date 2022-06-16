const defaultTheme = require('tailwindcss/defaultTheme');

module.exports = {
    darkMode: 'media',
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
    ],
    safelist: process.env.NODE_ENV === "development" ? [{ pattern: /.*/ }] : [],
    theme: {
        extend: {
            fontFamily: {
                sans: ['Karla', ...defaultTheme.fontFamily.sans],
            },
        },
    },

    plugins: [require('@tailwindcss/forms')],
};
