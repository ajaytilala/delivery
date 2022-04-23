<template>
    <div>
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">Update User</div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-12 mb-2">
                            <div class="form-group" v-if="formData.user_type != 'admin'">
                                <label for="status">Role</label>
                                <select class="form-control" name="user_type" v-model="formData.user_type" v-on:keyup.enter="update">
                                    <option v-for="(user_type, id) in user_types" :key="id" :value="id">{{ user_type }}</option>
                                </select>
                                <p class="text-danger m-0" v-for="error in errors.user_type" v-text="error"></p>
                            </div>                   
                            </div>                   
                            <div class="col-12 mb-2">         
                            <div class="form-group">
                                <label for="name">Name</label>
                                <input type="text" class="form-control" name="name" v-model="formData.name" v-on:keyup.enter="update">
                                <p class="text-danger m-0" v-for="error in errors.name" v-text="error"></p>
                            </div>
                            </div>                   
                            <div class="col-12 mb-2">         
                            <div class="form-group">
                                <label for="email">Email</label>
                                <input type="text" class="form-control" name="email" v-model="formData.email" v-on:keyup.enter="update">
                                <p class="text-danger m-0" v-for="error in errors.email" v-text="error"></p>
                            </div>
                            </div>                   
                            <div class="col-12 mb-2">         
                            <div class="form-group">
                                <label for="password">Password</label>
                                <input type="password" class="form-control" name="password" v-model="formData.password" v-on:keyup.enter="update">
                                <p class="text-danger m-0" v-for="error in errors.password" v-text="error"></p>
                            </div>
                            </div>                   
                            <div class="col-12 mb-2">         
                            <div class="form-group">
                                <label for="password_confirmation">Confirm Password</label>
                                <input type="password" class="form-control" name="password_confirmation" v-model="formData.password_confirmation" v-on:keyup.enter="update">
                                <p class="text-danger m-0" v-for="error in errors.password_confirmation" v-text="error"></p>
                            </div>
                            </div>                   
                            <div class="col-12 mb-2">         
                            <div class="form-group">
                                <label for="status">Status</label>
                                <select class="form-control" name="status" v-model="formData.status"  v-on:keyup.enter="update">
                                    <option v-for="(status, id) in user_status" :key="id" :value="id">{{ status }}</option>
                                </select>
                                <p class="text-danger m-0" v-for="error in errors.status" v-text="error"></p>
                            </div>   
                            </div>  
                            <div class="col-12 mb-2">                           
                            <div class="form-group">
                                <button @click="update" class="btn btn-success">Update</button>
                                <router-link :to='{name:"userList"}' class="btn btn-dark" href="#">Back</router-link>
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
    mounted(){
        this.showUser();
        window.axios.defaults.headers.common['Authorization'] = `Bearer ${this.token}`
        axios.get('api/user').then((response) => {
            this.currentUser = response.data.data
            this.token = localStorage.getItem('token')
            this.user_role = localStorage.getItem('user_role')
        }).catch((errors) => {
        })
    },
    data() {
        return {
            formData: {},
            currentUser: {},
            token: localStorage.getItem('token'),
            user_role: localStorage.getItem('user_role'),
            errors: {},
            user_status: {
                '0':'In-Active',
                '1':'Active'
            },
            user_types: {
                'manager':'Manager',
                'blogger':'Blogger'
            }
        }
    },
    methods: {
        showUser(){
            axios.get('/api/users/' + this.$route.params.id).then((response) => {
                this.formData = response.data.data;
            }).catch((errors) => {
                 this.$toaster.error(errors);
            });
        },
        update() {
            axios.put('/api/users/' + this.$route.params.id, this.formData).then((response) => {
                if(response.data.status == 1) {
                    this.$router.push({name:"userList"})
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
    }
}
</script>
