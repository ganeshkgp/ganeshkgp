import { createApp } from 'vue';
import { createRouter, createWebHistory } from 'vue-router';
import App from './App.vue';
import routes from './router/index.js';

const router = createRouter({
    history: createWebHistory(),
    routes,
    scrollBehavior(to, from, savedPosition) {
        if (savedPosition) {
            return savedPosition;
        }
        if (to.hash) {
            return { el: to.hash, behavior: 'smooth' };
        }
        return { top: 0 };
    },
});

const app = createApp(App);

app.use(router);
app.mount('#app');
