import Vue from 'vue'
import Vuetify from 'vuetify'
import i18n from '@/i18n'
import '@/sass/overrides.sass'
import '@mdi/font/css/materialdesignicons.css'
Vue.use(Vuetify)

const theme = {
  primary: '#4CAF50',
  secondary: '#9C27b0',
  accent: '#9C27b0',
  info: '#00CAE3',
  yellow: '#f1c40f',
  black: '#1e1e1e',
  greyLight: '#ecf0f1'
}

export default new Vuetify({
  lang: {
    t: (key, ...params) => i18n.t(key, params),
  },
  theme: {
    themes: {
      dark: theme,
      light: theme,
    },
  },
})
