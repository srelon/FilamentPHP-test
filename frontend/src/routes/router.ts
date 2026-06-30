import { createRouter, createWebHistory } from 'vue-router'
import type { RouteRecordRaw } from 'vue-router'

import Layout from '@/components/layout/Layout.vue'
import Home from '@/views/Pages/Home.vue'
import AboutPage from '@/views/Pages/Static/AboutPage.vue'
import ContactPage from '@/views/Pages/Static/ContactPage.vue'
import ProductList from '@/views/Pages/Products/ProductList.vue'
import ProductPage from '@/views/Pages/Products/ProductPage.vue'
import AuthorList from '@/views/Pages/Authors/AuthorList.vue'
import NewsList from '@/views/Pages/News/NewsList.vue'
import NewsPage from '@/views/Pages/News/NewsPage.vue'

import middlewarePipeline from './middlewarePipeline'

const APP_NAME = 'BookStore'

const routes: RouteRecordRaw[] = [
    {
        path: '/',
        component: Layout,
        children: [
            {
                name: 'home',
                path: '',
                component: Home,
                meta: { title: 'Home' },
            },
            {
                name: 'about',
                path: 'about-us',
                component: AboutPage,
                meta: { title: 'About Us' },
            },
            {
                name: 'contact',
                path: 'contact-us',
                component: ContactPage,
                meta: { title: 'Contact Us' },
            },
            {
                name: 'products',
                path: 'products',
                component: ProductList,
                meta: { title: 'Products' },
            },
            {
                name: 'product',
                path: 'product/:slug',
                component: ProductPage,
                meta: { title: 'Product' },
            },
            {
                name: 'authors',
                path: 'authors',
                component: AuthorList,
                meta: { title: 'Authors' },
            },
            {
                path: 'news',
                children: [
                    {
                        name: 'news',
                        path: '',
                        component: NewsList,
                        meta: { title: 'News' },
                    },
                    {
                        name: 'post',
                        path: ':slug',
                        component: NewsPage,
                        meta: { title: 'Post' },
                    },
                ],
            },
        ],
    },
    {
        path: '/:pathMatch(.*)*',
        name: 'not_found',
        redirect: '/',
    },
]

const router = createRouter({
    history: createWebHistory(),
    routes,
    scrollBehavior(to, from) {
        if (to.path !== from.path) return { top: 0 }
    },
})

router.beforeEach((to, from, next) => {
    document.title = to.meta?.title
        ? `${to.meta.title} | ${APP_NAME}`
        : APP_NAME

    if (!to.meta?.middleware) {
        return next()
    }

    const middleware = to.meta.middleware as Function[]
    const context = { to, from, next }

    return middleware[0]({
        ...context,
        next: middlewarePipeline(context, middleware, 1),
    })
})

export default router
