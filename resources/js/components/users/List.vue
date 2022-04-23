<template>
    <div class="row">
        <div class="col-6 mb-2 text-start">
            <h5 class="text-left m-2" v-if="token">Hello, {{currentUser.name}}</h5>
        </div>
        <div class="col-6 mb-2 text-end">
            <router-link :to='{name:"postList"}' class="btn btn-dark">Posts</router-link>
            <router-link :to='{name:"userList"}' class="btn btn-dark" v-if="currentUser.user_type =='admin'">Users</router-link>
            <button class="btn btn-danger" @click="logout" v-if="token">Logout</button>
        </div>
        <hr>
        <div class="col-12">
            <div class="col-12 mb-2 text-start">
                <router-link :to='{name:"userAdd"}' class="btn btn-primary">+ Create User</router-link>
            </div>
            <div class="card">
                <div class="card-header">
                    <h4>Users</h4>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Role</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody v-if="users.length > 0">
                                <tr v-for="(user,key) in users" :key="key">
                                    <td>{{ user.id }}</td>
                                    <td>{{ user.name }}</td>                                    
                                    <td>{{ user.email }}</td>
                                    <td>{{ user.user_type }}</td>
                                    <td>{{ user.status }}</td>
                                    <td>
                                        <router-link :to="{name: 'userEdit', params: {id: user.id}}" class="btn btn-info">Edit</router-link>
                                        <a @click="deleteUser(user.id)" v-if="user.user_type != 'admin'" class="btn btn-danger">Delete</a>
                                    </td>                                    
                                </tr>
                            </tbody>
                            <tbody v-else>
                                <tr>
                                    <td colspan="5" align="center">No Users Found.</td>
                                </tr>
                            </tbody>                            
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    data() {
        return {
            users: {},
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
        },
        getUsers(){
            axios.get('/api/users').then((response) => {
                if(response.data.status == 1) {
                    this.users = response.data.data
                } else if(response.data.status == 0) {
                    this.errors = response.data.errors;
                    if(!this.errors) {
                        this.$toaster.error(response.data.message);
                    }
                } else {
                    this.errors = response.data.errors
                    this.$toaster.error(response.data.message);
                }
            }).catch((errors) => {
                console.log(errors)
            });
        },
        deleteUser(user_id){
            Swal.fire({
                title: 'Are you sure?',
                text: "You won't be able to revert this!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    axios.delete('/api/users/' + user_id).then((response) => {
                        this.getUsers()                            
                    }).catch((errors) => {
                    })
                    Swal.fire(
                        'Deleted!',
                        'User has been deleted.',
                        'success'
                    )
                }
            })
        }
        
    },
    mounted() {
        this.getUsers();
        window.axios.defaults.headers.common['Authorization'] = `Bearer ${this.token}`
        axios.get('api/user').then((response) => {
            this.currentUser = response.data.data
            this.token = localStorage.getItem('token')
            this.user_role = localStorage.getItem('user_role')
        }).catch((errors) => {
        })
    }
}

</script>