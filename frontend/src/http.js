import axios from 'axios'

axios.interceptors.response.use(function (response) {
    return response;
}, function (error) {
    if (401 === error.response.status || 403 === error.response.status) {
        localStorage.removeItem('isLogged')
        localStorage.removeItem('role')

        window.location.href = "/"
    } else {
        return Promise.reject(error)
    }
});

axios.defaults.baseURL = process.env.VUE_APP_API_URL

export default axios