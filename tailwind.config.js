/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './resources/**/*.blade.php',
        './resources/**/*.js',
    ],
    theme: {
        extend: {
            colors: {
                primary: '#68C4CF',
                secondary: '#FFD4B2',
            },
            fontFamily: {
                sans: ['Poppins', 'ui-sans-serif', 'system-ui'],
            },
        },
    },
    plugins: [],
}
