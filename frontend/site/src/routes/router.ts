import { createRouter, createWebHistory } from 'vue-router'
import type { RouteRecordRaw } from 'vue-router'

const routes: RouteRecordRaw[] = [
    {
        path: '/',
        name: 'home',
        component: () => import('../views/Pages/Home.vue'),
    },
    {
        path: '/:pathMatch(.*)*',
        name: 'not-found',
        redirect: '/',
    },
]

const router = createRouter({
    history: createWebHistory(),
    routes,
    scrollBehavior() {
        return {
            top: 0,
        }
    },
})

export default router
