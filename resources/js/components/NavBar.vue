<template>
    <div>
        <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
            <div class="container">
                <router-link :to="{name:'Welcome'}" class="navbar-brand" href="#">Blog Crud App</router-link>
                <div class="collapse navbar-collapse" v-if="token">
                    <div class="navbar-nav">
                        <router-link exact-active-class="active" :to="{name:'postList'}" class="nav-item nav-link">Posts</router-link>
                        <router-link exact-active-class="active" :to="{name:'userList'}" class="nav-item nav-link">Users</router-link>
                    </div>
                </div>
                <h5 class="text-center text-white m-2" v-if="token">Hello, {{currentUser.name}}</h5>
                <button class="btn btn-danger" @click="logout" v-if="token">Logout</button>
            </div>
        </nav>
    </div>
</template>

<script>
    export default {
        name: 'NavBar',
        data() {
            return {
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
                    this.$router.push('/login')
                }).catch((errors) => {
                    console.log(errors)
                })
            }
        },
        mounted() {
        }
    };
</script>