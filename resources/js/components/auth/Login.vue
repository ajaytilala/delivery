<template>
    <div>
        <div class="row justify-content-center">
            <div class="col-md-4">
                <div class="card">
                    <div class="card-header">Login</div>
                    <div class="card-body">
                        <div class="col-12 mb-2">
                            <div class="form-group">
                                <label for="email">Email</label>
                                <input type="text" class="form-control" name="email" v-model="formData.email" v-on:keyup.enter="login">
                                <p class="text-danger m-0" v-for="error in errors.email" v-text="error"></p>
                            </div>
                        </div>
                        <div class="col-12 mb-2">
                            <div class="form-group">
                                <label for="password">Password</label>
                                <input type="password" class="form-control" name="password" v-model="formData.password" v-on:keyup.enter="login">
                                <p class="text-danger m-0" v-for="error in errors.password" v-text="error"></p>
                            </div>
                        </div>
                        <div class="col-12 mb-2">
                            <div class="form-group">
                                <div class="row">
                                    <div class="col-6 mb-2">
                                        <button @click="login" class="btn btn-primary">Login</button>
                                    </div>
                                    <div class="col-6 mb-2">
                                        <router-link :to='{name:"register"}' class="float-right">Create New Account ?</router-link>
                                    </div>
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
        data(){
            return {
                formData: {
                    email: '',
                    password: '',
                    device_name: 'browser'
                },
                errors: {},
                currentUser: {},
                token: null,
                user_role: null,
            }
        },
        methods: {
            login(){
                axios.post('api/login', this.formData).then((response) => {
                    if(response.data.status == 1) {
                        localStorage.setItem('token', response.data.access_token);
                        localStorage.setItem('user_role', response.data.data.user_type);
                        this.token = localStorage.getItem('token')
                        this.user_role = localStorage.getItem('user_role')
                        this.currentUser = response.data
                        this.$router.push({name:"postList"})
                        // this.$toaster.success(response.data.message);
                        // this.$router.go();
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
                    this.errors = errors.response.data.errors
                })
            }
        }
    }
</script>