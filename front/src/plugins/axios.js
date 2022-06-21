"use strict";

import Vue from 'vue';
// import router from "../router";
import axios from "axios";
import Swal from "sweetalert2";

// Full config:  https://github.com/axios/axios#request-config
axios.defaults.baseURL = process.env.baseURL || process.env.apiUrl || 'http://127.0.0.1:8000/api';
axios.defaults.headers.common['Authorization'] = 'Bearer ' + JSON.parse(localStorage.getItem('token'));
// axios.defaults.headers.post['Content-Type'] = 'application/x-www-form-urlencoded';

let config = {
    // baseURL: process.env.baseURL || process.env.apiUrl || ""
    // timeout: 60 * 1000, // Timeout
    // withCredentials: true, // Check cross-site Access-Control

    headers: {'Authorization': 'Bearer ' + JSON.parse(localStorage.getItem('token'))}
};

const _axios = axios.create(config);

_axios.interceptors.request.use(
    function (config) {
        // Do something before request is sent
        config.headers.common['Authorization'] = 'Bearer ' + JSON.parse(localStorage.getItem('token'))

        let token = JSON.parse(localStorage.getItem('token'));

        if (token) {
            config.headers['Authorization'] = `Bearer ${token}`;
        }
        return config;
    },
    function (error) {
        // Do something with request error
        localStorage.clear();
        this.$router.go(0)
        return Promise.reject(error);
    }
);

// Add a response interceptor
// _axios.interceptors.response.use(
//     function (response) {
//         // Do something with response data
//         return response;
//     },
//     function (error) {
//         // Do something with response error
//         return Promise.reject(error);
//     }
// );

axios.interceptors.response.use(function (response) {
    return response;
}, function (error) {
    // console.log(error.response.data.message)
    if ("Unauthenticated." === error.response.data.message) {
        localStorage.clear();
        // Swal.fire({
        //     title: "Session Expired",
        //     text: "Your session has expired. Would you like to be redirected to the login page?",
        //     type: "warning",
        //     showCancelButton: true,
        //     confirmButtonColor: "#DD6B55",
        //     confirmButtonText: "Yes",
        //     closeOnConfirm: false
        // }).then((result) => {
        //     if (result.isConfirmed) {
        //         window.location = '/login';
        //     }
        // });
        // router.push('/login')
        // this.$router.push('/login')
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

// Plugin.install = function(Vue, options) {
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
