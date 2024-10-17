/** @type {import('tailwindcss').Config} */
module.exports = {
  darkMode: ["class"],
  content: [
    "./pages/**/*.{js,jsx}",
    "./components/**/*.{js,jsx}",
    "./app/**/*.{js,jsx}",
    "./src/**/*.{js,jsx}",
    "./templates/**/*.html.twig",
    "./assets/**/*.{js,jsx,ts,tsx}",
  ],
  theme: {
    extend: {
      fontFamily: {
        sans: ['Geist', 'sans-serif'],
      },

      colors: {
        main: '#ff00ff',
        mainAccent: '#d100d1', // not needed for shadcn components
        secondary: '#00ffff', // not needed for shadcn components
        secondaryAccent: '#00b7b7', // not needed for shadcn components
        overlay: 'rgba(0,0,0,0.8)', // background color overlay for alert dialogs, modals, etc.
  
        // light mode
        bg: '#e3dff2',
        bgSecondary: '#f5f5f5',
        text: '#000',
        border: '#000',
  
        // dark mode
        darkBg: '#272733',
        darkText: '#eeefe9',
        darkBorder: '#000',
        secondaryBlack: '#212121', // opposite of plain white, not used pitch black because borders and box-shadows are that color 
        
      },
      borderRadius: {
        base: '5px'
      },
      boxShadow: {
        light: '4px 4px 0px 0px #000',
        dark: '4px 4px 0px 0px #000',
        xl: '8px 8px 0px 0px #000',
      },
      translate: {
        boxShadowX: '4px',
        boxShadowY: '4px',
        reverseBoxShadowX: '-4px',
        reverseBoxShadowY: '-4px',
      },
      fontWeight: {
        base: '500',
        heading: '700',
      },
      backgroundImage: {
        'custom-grid': 'linear-gradient(to right, #80808033 1px, transparent 1px), linear-gradient(to bottom, #80808033 1px, transparent 1px)',
      },
      backgroundSize: {
        'custom-size': '70px 70px',
      },
    },
  },
  plugins: [require("tailwindcss-animate")],
};
