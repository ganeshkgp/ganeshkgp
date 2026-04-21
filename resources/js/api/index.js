import axios from 'axios';

const api = axios.create({
    baseURL: '/api',
    headers: {
        'Content-Type': 'application/json',
        Accept: 'application/json',
        'X-Requested-With': 'XMLHttpRequest',
    },
});

// Attach CSRF token from the meta tag on every mutating request
api.interceptors.request.use((config) => {
    const token = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
    if (token) {
        config.headers['X-CSRF-TOKEN'] = token;
    }
    return config;
});

// Simple error handler — log to console in dev
api.interceptors.response.use(
    (response) => response,
    (error) => {
        console.error('[API Error]', error.response?.data ?? error.message);
        return Promise.reject(error);
    },
);

export default api;
