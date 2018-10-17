import axios                from 'axios'

import store                from '../store'
import config               from '../config'

window.axios = axios.create({
    headers: config.http.defaultRequest.headers
});

/**
 * Sets the X-CSRFToken header for ajax non-GET request to make CSRF protection easy.
 */
if (window.Laravel.csrf_token) {
    window.axios.defaults.headers.common['X-CSRF-TOKEN'] = window.Laravel.csrf_token;
} else {
    console.error('CSRF token not found: https://laravel.com/docs/csrf#csrf-x-csrf-token');
}

/**
 * @param url
 * @param param
 * @returns {AxiosPromise}
 */
export function get(url, param = null) {
    return axios({
        method: 'GET',
        url: url,
        params: param,
        headers: {
            'Authorization': `Bearer ${store.state.api_token}`
        }
    })
}

/**
 * @param url
 * @param payload
 * @returns {AxiosPromise}
 */
export function post(url, payload) {
    return axios({
        method: 'POST',
        url: url,
        data: payload,
        headers: {
            'Authorization': `Bearer ${store.state.api_token}`
        }
    })
}

/**
 * @param url
 * @param payload
 * @returns {AxiosPromise}
 */
export function del(url, payload) {
    return axios({
        method: 'DELETE',
        url: url,
        data: payload,
        headers: {
            'Authorization': `Bearer ${store.state.api_token}`
        }
    })
}

/**
 * @param url
 * @param payload
 * @returns {AxiosPromise}
 */
export function put(url, payload) {
    return axios({
        method: 'PUT',
        url: url,
        data: payload,
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'Content-Type': 'application/x-www-form-urlencoded',
            'Authorization': `Bearer ${store.state.api_token}`
        }
    })
}

/**
 * @param cb
 */
export function interceptors(cb) {
    axios.interceptors.response.use((res) => {
        return res;
    }, (err) => {
        cb(err);
        return Promise.reject(err)
    })
}