<template>
    <main>
        <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
            <div class="container">
                <router-link :to="{name:'postList'}" class="navbar-brand" href="#">Blog Crud App</router-link>
                <!-- <div class="collapse navbar-collapse" v-if="token">
                    <div class="navbar-nav">
                        <router-link exact-active-class="active" :to="{name:'postList'}" class="nav-item nav-link">Posts</router-link>
                        <router-link exact-active-class="active" :to="{name:'userList'}" v-if="currentUser.user_type == 'admin' " class="nav-item nav-link">Users</router-link>
                    </div>
                </div>
                <h5 class="text-center text-white m-2" v-if="token">Hello, {{currentUser.name}}</h5>
                <button class="btn btn-danger" @click="logout" v-if="token">Logout</button> -->
            </div>
        </nav>
        <div class="container mt-5">
            <router-view></router-view>
        </div>
    </main>
</template>
 
<script>
    export default {
        data() {
            return {
                currentUser: {},
                token: localStorage.getItem('token'),
                user_role: localStorage.getItem('user_role')
            }
        },
        methods: {
            logout(){
                axios.post('api/logout').then((response) => {
                    localStorage.removeItem('token')
                    localStorage.removeItem('user_role')
                    this.token = null;
                    this.user_role = null;
                    this.$router.push({name:"login"})
                }).catch((errors) => {
                })
            }
        },
        mounted() {
            if(this.token) {
                window.axios.defaults.headers.common['Authorization'] = `Bearer ${this.token}`
                axios.get('api/user').then((response) => {
                    this.currentUser = response.data.data
                    this.token = localStorage.getItem('token')
                    this.user_role = localStorage.getItem('user_role')
                }).catch((errors) => {
                })
            } else {
                this.token = null;
            }
        }        
    };
</script>