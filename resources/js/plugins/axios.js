import axios from 'axios';
// Call variable VITE_API_URL from .env file
const apiURL = import.meta.env.VITE_API_URL

const axiosIns = axios.create({
    // You can add your headers here
    // ================================
    // baseURL: 'https://some-domain.com/api/',
    baseURL: apiURL,
    // timeout: 1000,
    // headers: {'X-Custom-Header': 'foobar'}
})

// Add a request interceptor
axiosIns.interceptors.request.use(function (config) {
    // @ToDo: Review this configuration is the one that is generating conflicts when uploading files.
    // config.headers['X-Requested-With'] = 'XMLHttpRequest'
    // config.headers.Accept = 'application/json'
    // config.headers['Content-Type'] = 'application/json'

    // Do something before request is sent
    return config;
}, function (error) {
    // Do something with request error
    return Promise.reject(error);
});

// Add a response interceptor for all requests made by this instance (axiosIns)
axiosIns.interceptors.response.use(
    response => {
        switch (response.status) {
            case 200:
                const data = {};
                // Return data from response and response.status both
                // FIX: Before how to use pagination with Laravel API, we used to return data from response.data.data
                const dataFound = response.data.data ?? response.data;

                data.data = dataFound;
                data.status = response.status;

                // Pagination
                data.current_page = response.data?.meta?.current_page ?? response.data.current_page;
                data.last_page = response.data?.meta?.last_page ?? response.data.last_page;
                data.total = response.data?.meta?.total ?? response.data.total;
                data.per_page = response.data?.meta?.per_page ?? response.data.per_page;

                return data;
            case 201:
                // Manejar el código de respuesta 201
                const dataCreated = response.data.data ?? response.data;

                return { data: dataCreated, status: response.status };
            case 400:
                // Manejar el código de respuesta 400
                return Promise.reject(new Error('Bad request'));
            case 401:
                // Manejar el código de respuesta 401
                return Promise.reject(new Error('Unauthorized'));
            case 404:
                // Manejar el código de respuesta 404
                return Promise.reject(new Error('Not found'));
            default:
                // Manejar otros códigos de respuesta
                return response;
        }
    },
    error => {
        // Manejar los errores de respuesta aquí
        // TODO: Implementar un sistema de notificaciones para mostrar los errores al usuario
        return Promise.reject(error);
    }
);

export default axiosIns
