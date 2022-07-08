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
                primary: '#99AEA4',
                secondary: '#B3AE56',
                accent: '#a98c65',
                tertiary: '#F8F8F8',

                info: '#475E6D',
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