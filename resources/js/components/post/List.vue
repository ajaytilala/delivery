<template>
    <div class="row">
        <div class="col-6 mb-2 text-start">
            <h5 class="text-left m-2" v-if="token">Hello, {{currentUser.name}}</h5>
        </div>
        <div class="col-6 mb-2 text-end">
            <router-link :to='{name:"postList"}' class="btn btn-dark" v-if="currentUser.user_type =='admin'">Posts</router-link>
            <router-link :to='{name:"userList"}' class="btn btn-dark" v-if="currentUser.user_type =='admin'">Users</router-link>
            <button class="btn btn-danger" @click="logout" v-if="token">Logout</button>
        </div>
        <hr>
        <div class="col-12">
            <div class="col-12 mb-2 text-start">
                <router-link :to='{name:"postAdd"}' class="btn btn-primary">+ Create Post</router-link>
            </div>
            <div class="card">
                <div class="card-header">
                    <h4>Posts</h4>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Title</th>
                                    <th v-if="currentUser.user_type != 'blogger'">Author</th>
                                    <th>Content</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody v-if="posts.length > 0">
                                <tr v-for="(post,key) in posts" :key="key">
                                    <td>{{ post.id }}</td>
                                    <td>{{ post.title }}</td>                                    
                                    <td v-if="currentUser.user_type != 'blogger'">{{ post.user_name }}</td>
                                    <td>{{ post.content }}</td>
                                    <td>{{ post.status }}</td>
                                    <td>
                                        <router-link :to="{name: 'postEdit', params: {id: post.id}}" class="btn btn-info">Edit</router-link>
                                        <a @click="deletePost(post.id)" class="btn btn-danger">Delete</a>
                                    </td>                                    
                                </tr>
                            </tbody>
                            <tbody v-else>
                                <tr>
                                    <td v-if="currentUser.user_type != 'blogger'" colspan="6" align="center">No Posts Found.</td>
                                    <td v-if="currentUser.user_type == 'blogger'" colspan="5" align="center">No Posts Found.</td>
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
            posts: {},
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
        getPosts(){
            axios.get('/api/post').then((response) => {
                if(response.data.status == 1) {
                    this.posts = response.data.data
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
            });
        },
        deletePost(post_id){
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
                    axios.delete('/api/post/' + post_id).then((response) => {
                        this.getPosts()                            
                    }).catch((errors) => {
                    })
                    Swal.fire(
                        'Deleted!',
                        'Your post has been deleted.',
                        'success'
                    )
                }
            })
        }
        
    },
    mounted() {
        this.getPosts();
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