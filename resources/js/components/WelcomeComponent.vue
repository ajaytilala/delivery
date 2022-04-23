<template>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">Welcome</div>
                    <div class="card-body">Hello, This Blog Post Job</div>
                </div>
            </div>
        </div>
    </div>
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
                    this.$router.push('/login')
                }).catch((errors) => {
                    console.log(errors)
                })
            }
        },
        mounted() {
            window.axios.defaults.headers.common['Authorization'] = `Bearer ${this.token}`
            axios.get('api/user').then((response) => {
                this.currentUser = response.data.data;
                this.token = localStorage.getItem('token')
                this.user_role = localStorage.getItem('user_role')
            }).catch((errors) => {
                console.log(errors)
            })
        }        
    };
</script>
