import Vue from "vue";
import Vuetify from "vuetify/lib";

Vue.use(Vuetify);

export default new Vuetify({
    theme: {
        themes: {
            // light: {
            //     primary: '#8f5782',
            //     secondary: '#C0A28D',
            //     tertiary: '#E9EBE2',
            //     accent: '#757E93',
            //     info: '#2a3136',
            //     success: '#60955f',
            //     warning: '#dd8527',
            //     error: '#f44336'
            // }
            light: {
                // primary: '#1A475A',
                primary: '#1A475A',
                secondary: '#193E49',
                tertiary: '#DFE4DC',
                accent: '#757E93',
                info: '#0C1C28',
                success: '#60955f',
                warning: '#dd8527',
                error: '#f44336'
            }
        }
    },
    icons: {
        iconfont: 'mdi'
    }
});