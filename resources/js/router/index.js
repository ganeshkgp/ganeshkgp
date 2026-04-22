import Welcome from '@/pages/Welcome.vue';
import BlogList from '@/pages/BlogList.vue';
import BlogDetail from '@/pages/BlogDetail.vue';

const routes = [
    {
        path: '/',
        name: 'home',
        component: Welcome,
    },
    {
        path: '/blog',
        name: 'blog',
        component: BlogList,
    },
    {
        path: '/blog/:slug',
        name: 'blog-detail',
        component: BlogDetail,
    },
];

export default routes;
