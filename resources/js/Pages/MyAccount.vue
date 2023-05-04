<template lang="">
    <div id="mainContent" class="main-content">
        <!-- <div class="w-100 text-center">
            <h2>My Account</h2>
        </div> -->
        <div class="col-11 col-lg-6 col-md-8 col-sm-10 m-auto p-3 mb-3 card rounded-3 shadow">

                <form @submit.prevent="submitPersonalInfo" class="w-100 me-3">
                     <h5 class="text-left">Personal Details</h5>
                    <div class="d-flex justify-content-end"><i role="button" @click="editPersonalInfo('name')" class="fa fa-edit fa-lg"></i></div>
                    <label class="fw-bold" for="name">User Name:</label>
                    <input required ref="nameField" v-model="form.name" :disabled="!editing" id="name" class="w-100 mb-3" type="text">
                    <label class="fw-bold" for="email">Email:</label>
                    <div class="bg-danger text-white p-2" v-if="errors.email">{{ errors.email }}</div>
                    <input required v-model="form.email" :disabled="!editing" id="email" class="w-100 mb-4" type="text">
                    <div class="row w-100 m-auto justify-content-center"><button class="myBtn col col-lg-6 col-md-12" type="submit">Save</button></div>
                </form>

                <form @submit.prevent="submitNewPassword" class="w-100">
                    <h5 class="text-left">Change Password</h5>
                    <div class="d-flex justify-content-end"><i role="button" @click="editPersonalInfo('password')" class="fa fa-edit fa-lg"></i></div>
                    <label class="fw-bold" for="current-pw">Current Password:</label>
                    <div class="bg-danger text-white p-2" v-if="errors.currentPw">{{ errors.currentPw }}</div>
                    <input ref="currentPw" v-model="pwForm.current_password" :disabled="!editing" id="current-pw" class="w-100 mb-3" type="password">
                    <label class="fw-bold" for="new-pw">New Password:</label>
                    <div class="bg-danger text-white p-2" v-if="errors.newPw">{{ errors.newPw }}</div>
                    <input v-model="pwForm.password" :disabled="!editing" id="new-pw" class="w-100 mb-4" type="password">
                    <label class="fw-bold" for="confirm-pw">Confirm New Password:</label>
                    <input v-model="pwForm.password_confirmation" :disabled="!editing" id="confirm-pw" class="w-100 mb-4" type="password">
                    <div class="row w-100 m-auto justify-content-center"><button class="myBtn col col-lg-6 col-md-12" type="submit">Save</button></div>
                </form>
        </div>
    </div>
</template>
<script>
export default {

    data() {
        return {
            editing: false,
            form: {
                name: this.$page.props.auth.user.name,
                email:this.$page.props.auth.user.email
            },
            pwForm: {
                current_password: '',
                password: '',
                password_confirmation: ''
            },
            errors: {
                email: null,
                currentPw: null,
                newPw: null,
            }
        }
    },
    methods: {
        editPersonalInfo(field) {
            this.editing = !this.editing;
            if(field == 'password'){
                this.$nextTick(() => {
                this.$refs.currentPw.focus()
            })}
            else {
                this.$nextTick(() => {
                this.$refs.nameField.focus()
            })
            }
        },
        submitPersonalInfo() {
            this.$inertia.put('/user/profile-information', this.form, {
                onSuccess: () => {
                    this.clearErrors();
                    alert('Info updated!')
                    this.editing = false;
                },
                onError: (errors) => {
                    if(errors.updateProfileInformation.email) this.errors.email = errors.updateProfileInformation.email;
                }
                })
        },
        submitNewPassword(){
            this.$inertia.put('/user/password', this.pwForm, {
                onSuccess: () => {
                    this.clearErrors();
                    alert('Password updated!');
                    this.editing = false;
                },
                onError: (errors) => {
                if(errors.updatePassword.current_password || errors.updatePassword.password){
                    this.errors.currentPw = errors.updatePassword.current_password;
                    this.errors.newPw = errors.updatePassword.password;
                }
                else alert('Sorry an unknown error occurred, please try again later.')
            }})
        },
        clearErrors() {
            Object.keys(this.errors).forEach((key) => {
                this.errors[key] = null;
            })
        }
    }

}
</script>
<style lang="">

</style>
