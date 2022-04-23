<template>
    <div>
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">Create New Post</div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-12 mb-2">
                                <div class="form-group">
                                    <label for="title">Title</label>
                                    <input type="text" class="form-control" name="title" v-model="post.title" v-on:keyup.enter="create">
                                    <p class="text-danger m-0" v-for="error in errors.title" v-text="error"></p>
                                </div>                        
                            </div>
                            <div class="col-12 mb-2">
                                <div class="form-group">
                                    <label for="content">Content</label>
                                    <textarea class="form-control" rows="10" name="content" v-model="post.content"></textarea>
                                    <p class="text-danger m-0" v-for="error in errors.content" v-text="error"></p>
                                </div>
                            </div>
                            <div class="col-12 mb-2">
                                <div class="form-group">
                                    <label for="status">Status</label>
                                    <select class="form-control" name="status" v-model="post.status" v-on:keyup.enter="create">
                                        <option v-for="(status, id) in post_status" :key="id" :value="id">{{ status }}</option>
                                        <p class="text-danger m-0" v-for="error in errors.status" v-text="error"></p>
                                    </select>
                                </div>
                            </div>
                            <div class="col-12 mb-2">
                                <div class="form-group">
                                    <button @click.prevent="create" class="btn btn-success">Create</button>
                                    <router-link :to="{name:'postList'}" class="btn btn-dark" href="#">Back</router-link>
                                </div>
                            </div>
                        </div>
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
            currentUser: {},
            bloggers: {},
            token: localStorage.getItem('token'),
            user_role: localStorage.getItem('user_role'),
            errors: {},
            post:{
                title:"",
                content:"",
                status: "0"
            },
            post_status: {
                '0':'Draft',
                '1':'Published',
                '2':'In-Active'
            },
        }
    },
    methods: {
        create() {
            axios.post('/api/post', this.post).then((response) => {
                if(response.data.status == 1) {
                    this.$router.push({name:"postList"})
                    this.$toaster.success(response.data.message)
                } else if(response.data.status == 0) {
                    this.errors = response.data.errors;
                    if(!this.errors) {
                        this.$toaster.error(response.data.message);
                    }
                } else {
                    this.errors = response.data.errors
                    this.$toaster.error(response.data.message);
                }
            }).catch((error) => {
                this.$toaster.error(error);
            });
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