<template>
    <div>
        <div class="row justify-content-center">
            <div class="col-md-4">
                <div class="card">
                    <div class="card-header">Register</div>
                    <div class="card-body">
                        <div class="col-12 mb-2">
                            <div class="form-group">
                                <label for="name">Name</label>
                                <input type="text" class="form-control" name="name" v-model="formData.name" v-on:keyup.enter="registerUser">
                                <p class="text-danger m-0" v-for="error in errors.name" v-text="error"></p>
                            </div>
                        </div>
                        <div class="col-12 mb-2">
                            <div class="form-group">
                                <label for="email">Email</label>
                                <input type="text" class="form-control" name="email" v-model="formData.email" v-on:keyup.enter="registerUser">
                                <p class="text-danger m-0" v-for="error in errors.email" v-text="error"></p>
                            </div>
                        </div>
                        <div class="col-12 mb-2">
                            <div class="form-group">
                                <label for="password">Password</label>
                                <input type="password" class="form-control" name="password" v-model="formData.password" v-on:keyup.enter="registerUser">
                                <p class="text-danger m-0" v-for="error in errors.password" v-text="error"></p>
                            </div>
                        </div>
                        <div class="col-12 mb-2">
                            <div class="form-group">
                                <label for="password_confirmation">Confirm Password</label>
                                <input type="password" class="form-control" name="password_confirmation" v-model="formData.password_confirmation" v-on:keyup.enter="registerUser">
                                <p class="text-danger m-0" v-for="error in errors.password_confirmation" v-text="error"></p>
                            </div>
                        </div>
                        <div class="col-12 mb-2">
                            <div class="form-group">
                                <div class="row">
                                    <div class="col-6 mb-2">
                                        <button @click="registerUser" class="btn btn-primary">Register</button>
                                    </div>
                                    <div class="col-6 mb-2">
                                        <router-link :to='{name:"login"}'>Already have an account?</router-link>
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
    data() {
        return {
            formData: {
                name: '',
                email: '',
                password: '',
                password_confirmation: '',
            },
            errors: {},
            token: null
        }
    },
    methods: {
        registerUser(){
            axios.post('api/register', this.formData).then((response) => {
                if(response.data.status == 1) {
                    this.formData.name = this.formData.email = this.formData.password = this.formData.password_confirmation = '';
                    this.$router.push({name:"login"})
                    this.$toaster.success(response.data.message);
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
            });
        }
    }
}
</script>