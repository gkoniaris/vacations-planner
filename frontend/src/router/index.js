import Vue from 'vue'
import VueRouter from 'vue-router'
import notifier from '../notifier'

import Login from '../views/Login.vue'
import Register from '../views/Register.vue'

import UsersIndex from '../views/Admin/Users/Index.vue'
import UsersCreate from '../views/Admin/Users/Create.vue'
import UsersEdit from '../views/Admin/Users/Edit.vue'
import ApplicationsIndex from '../views/Employee/Applications/Index.vue'
import ApplicationsCreate from '../views/Employee/Applications/Create.vue'

Vue.use(VueRouter)
const routes = [{
        path: '/',
        name: 'Login',
        component: Login
    },
    {
        path: '/register',
        name: 'Register',
        component: Register
    },
    {
        path: '/admin/users',
        name: 'Admin Index Users',
        component: UsersIndex,
        meta: {
            requiresAuth: true,
            role: 'supervisor'
        }
    },
    {
        path: '/admin/users/Create',
        name: 'Admin Create Users',
        component: UsersCreate,
        meta: {
            requiresAuth: true,
            role: 'supervisor'
        }
    },
    {
        path: '/admin/users/:id',
        name: 'Admin Edit Users',
        component: UsersEdit,
        meta: {
            requiresAuth: true,
            role: 'supervisor'
        }
    },
    {
        path: '/employee/applications',
        name: 'Index Applications',
        component: ApplicationsIndex,
        meta: {
            requiresAuth: true,
            role: 'employee'
        }
    },
    {
        path: '/employee/applications/create',
        name: 'Create Applications ',
        component: ApplicationsCreate,
        meta: {
            requiresAuth: true,
            role: 'employee'
        }
    }
]


const router = new VueRouter({
    mode: 'history',
    base: process.env.BASE_URL,
    routes
})

router.beforeEach((to, from, next) => {
    if (to.query.message) {
        to.query.error ? notifier.alert(to.query.message) : notifier.success(to.query.message)
    }

    if (to.name === 'Login' && localStorage.getItem('isLogged')) {
        if (localStorage.getItem('role') === 'employee') return next({ name: 'Index Applications' })
        if (localStorage.getItem('role') === 'supervisor') return next({ name: 'Admin Index Users' })
    }

    if ((to.name !== 'Login' && to.name !== 'Register') && !localStorage.getItem('isLogged')) return next({ name: 'Login' })

    if (to.meta.role && localStorage.getItem('role') !== to.meta.role) return next({ name: 'Login' })

    next()
})
export default router