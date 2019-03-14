import axios from 'axios'

export const http = axios.create({
    baseURL: 'http://localhost:1228'
    // baseURL: ''   //prodaction url
})

http.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

let token = document.head.querySelector('meta[name="csrf-token"]');

if (token) {
    http.defaults.headers.common['X-CSRF-TOKEN'] = token.content;
} else {
    console.error('CSRF token not found');
}
