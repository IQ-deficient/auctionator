"use strict";

import Vue from 'vue';
import axios from "axios";
import Swal from "sweetalert2";

axios.defaults.baseURL = process.env.baseURL || process.env.apiUrl || 'http://127.0.0.1:8000/api';
axios.defaults.headers.common['Authorization'] = 'Bearer ' + JSON.parse(localStorage.getItem('token'));

let config = {

    headers: {'Authorization': 'Bearer ' + JSON.parse(localStorage.getItem('token'))}
};

const _axios = axios.create(config);

_axios.interceptors.request.use(
    function (config) {

        config.headers.common['Authorization'] = 'Bearer ' + JSON.parse(localStorage.getItem('token'))

        let token = JSON.parse(localStorage.getItem('token'));

        if (token) {
            config.headers['Authorization'] = `Bearer ${token}`;
        }
        return config;
    },
    function (error) {

        localStorage.clear();
        this.$router.go(0)
        return Promise.reject(error);
    }
);

axios.interceptors.response.use(function (response) {
    return response;
}, function (error) {

    if ("Unauthenticated." === error.response.data.message) {
        localStorage.clear();
        window.location = '/login'
    } else if ("This account has been suspended." === error.response.data) {
        localStorage.clear();
        Swal.fire(
            'This account has been suspended.',
            'If you think this is a mistake, please contact our management team.',
            'error'
        )
            .then((result) => {
                if (result.isConfirmed) window.location = '/home'
            })
    } else {
        return Promise.reject(error);
    }
});

Plugin.install = function (Vue) {
    Vue.axios = _axios;
    window.axios = _axios;
    Object.defineProperties(Vue.prototype, {
        axios: {
            get() {
                return _axios;
            }
        },
        $axios: {
            get() {
                return _axios;
            }
        },
    });
};

Vue.use(Plugin)

export default Plugin;
