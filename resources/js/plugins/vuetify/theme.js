import { themeConfig } from '@themeConfig'

const theme = {
  defaultTheme: localStorage.getItem(`${themeConfig.app.title}-theme`) || themeConfig.app.theme.value,
  themes: {
    light: {
      dark: false,
      colors: {
        'primary': localStorage.getItem(`${themeConfig.app.title}-lightThemePrimaryColor`) || '#0076C1',
        'secondary': '#8A8D93',
        'on-secondary': '#FFFFFF',
        'success': '#56CA00',
        'info': '#16B1FF',
        'warning': '#FFB400',
        'error': '#FF4C51',
        'on-primary': '#FFFFFF',
        'on-success': '#FFFFFF',
        'on-warning': '#FFFFFF',
        'background': '#F4F5FA',
        'on-background': '#3A3541',
        'surface': '#FFFFFF',
        'on-surface': '#3A3541',
        'grey-50': '#FAFAFA',
        'grey-100': '#F5F5F5',
        'grey-200': '#EEEEEE',
        'grey-300': '#E0E0E0',
        'grey-400': '#BDBDBD',
        'grey-500': '#9E9E9E',
        'grey-600': '#757575',
        'grey-700': '#616161',
        'grey-800': '#424242',
        'grey-900': '#212121',
        'perfect-scrollbar-thumb': '#DBDADE',
        'navbar-primary': localStorage.getItem(`${themeConfig.app.title}-lightThemeNavbarColor`) || '#FFFFFF',
      },
      variables: {
        'border-color': '#3A3541',

        // Shadows
        'shadow-key-umbra-opacity': 'rgba(var(--v-theme-on-surface), 0.08)',
        'shadow-key-penumbra-opacity': 'rgba(var(--v-theme-on-surface), 0.12)',
        'shadow-key-ambient-opacity': 'rgba(var(--v-theme-on-surface), 0.04)',
      },
    },
    dark: {
      dark: true,
      colors: {
        'primary': localStorage.getItem(`${themeConfig.app.title}-darkThemePrimaryColor`) || '#0069C4',
        'secondary': '#9EA5AD',
        'on-secondary': '#FFFFFF',
        'success': '#5BA65F',
        'info': '#589DD6',
        'warning': '#D6B300',
        'error': '#D65350',
        'on-primary': '#FFFFFF',
        'on-success': '#FFFFFF',
        'on-warning': '#FFFFFF',
        'background': '#28243D',
        'on-background': '#E7E3FC',
        'surface': '#312D4B',
        'on-surface': '#E7E3FC',
        'grey-50': '#2A2E42',
        'grey-100': '#2F3349',
        'grey-200': '#4A5072',
        'grey-300': '#5E6692',
        'grey-400': '#7983BB',
        'grey-500': '#8692D0',
        'grey-600': '#AAB3DE',
        'grey-700': '#B6BEE3',
        'grey-800': '#CFD3EC',
        'grey-900': '#E7E9F6',
        'perfect-scrollbar-thumb': '#4A5072',
        'navbar-primary': localStorage.getItem(`${themeConfig.app.title}-darkThemeNavbarColor`) || '#121212',
      },
      variables: {
        'border-color': '#C2BED4',

        // Shadows
        'shadow-key-umbra-opacity': 'rgba(5, 3, 18, 0.08)',
        'shadow-key-penumbra-opacity': 'rgba(5, 3, 18, 0.12)',
        'shadow-key-ambient-opacity': 'rgba(5, 3, 18, 0.04)',
      },
    },
  },
}

export default theme
