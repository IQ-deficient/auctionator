import Vue from "vue";
import Vuetify from "vuetify/lib";

Vue.use(Vuetify);

export default new Vuetify({
    theme: {
        themes: {
            light: {
                primary: '#A4B771',
                secondary: '#909C6B',
                accent: '#915A64',
                tertiary: '#F1F3EE',

                info: '#586B5F',
                success: '#67b15a',
                warning: '#e4a122',
                error: '#f44336',
            },
            dark: {
                primary: '#55653c',
                secondary: '#83947B',
                accent: '#643c43',
                tertiary: '#a8b09b',

                info: '#586B5F',
                success: '#63af69',
                warning: '#e09f31',
                error: '#f44336',
            }
        }
    },
    icons: {
        iconfont: 'mdi'
    }
});