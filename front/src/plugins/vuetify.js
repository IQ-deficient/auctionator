import Vue from "vue";
import Vuetify from "vuetify/lib";

Vue.use(Vuetify);

export default new Vuetify({
    theme: {
        themes: {
            light: {
                primary: '#605290',
                secondary: '#819fC9',
                tertiary: '#f5f4f4',
                accent: '#5b84bc',
                info: '#1c263e',
                success: '#529363',
                warning: '#cf832b',
                error: '#f44336'
            }
        }
    },
    icons: {
        iconfont: 'mdi'
    }
});