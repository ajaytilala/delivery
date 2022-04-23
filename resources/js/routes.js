import Login from './components/auth/Login.vue'
import Register from './components/auth/Register.vue'
import Welcome from './components/WelcomeComponent.vue'
import PostList from './components/post/List.vue'
import PostCreate from './components/post/Add.vue'
import PostEdit from './components/post/Edit.vue'
import UserList from './components/users/List.vue'
import UserCreate from './components/users/Add.vue'
import UserEdit from './components/users/Edit.vue'

export default {
    mode: 'history',
    base: '/',
    fallback: true,
    routes: [
        {
            name: 'login',
            path: '/login',
            component: Login,            
            meta: {guest: true}
        },
        {
            name: 'register',
            path: '/register',
            component: Register,            
            meta: {guest: true}
        },
        {
            name: 'Welcome',
            path: '/',
            component: Welcome,            
            meta: {requiresAuth: true}        
        },
        {
            name: 'postList',
            path: '/posts',
            component: PostList,
            meta: {requiresAuth: true}
        },
        {
            name: 'postEdit',
            path: '/post/:id/edit',
            component: PostEdit,
            meta: {requiresAuth: true}
        },
        {
            name: 'postAdd',
            path: '/post/add',
            component: PostCreate,
            meta: {requiresAuth: true}
        },
        {
            name: 'userList',
            path: '/users',
            component: UserList,
            meta: {requiresAuth: true, allowToAdmin: true}
        },
        {
            name: 'userEdit',
            path: '/users/:id/edit',
            component: UserEdit,
            meta: {requiresAuth: true, allowToAdmin: true}
        },
        {
            name: 'userAdd',
            path: '/users/add',
            component: UserCreate,
            meta: {requiresAuth: true, allowToAdmin: true}
        }
    ]
}