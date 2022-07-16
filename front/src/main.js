import Vue from 'vue'
import './plugins/axios'
import App from './App.vue'
import vuetify from './plugins/vuetify'
import router from './router'
import store from './store'
import VueSweetalert2 from 'vue-sweetalert2';
import 'sweetalert2/dist/sweetalert2.min.css';
import DatetimePicker from 'vuetify-datetime-picker';

Vue.config.productionTip = false

const options = {
  confirmButtonColor: '#67b15a',
  cancelButtonColor: '#e4a122',
};
Vue.use(VueSweetalert2, options);
Vue.use(DatetimePicker)

new Vue({
  vuetify,
  router,
  store,
  render: h => h(App)
}).$mount('#app')
