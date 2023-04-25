<template lang="">
    <div>
        <!-- <index-layout> </index-layout> -->
        <div class="container text-center w-100 pt-3">
            <h2>Register</h2>

            <form
                class="col col-lg-6 col-sm-10 m-auto text-start"
                @submit.prevent="submit"
            >
                <div class="bg-danger text-white p-2" v-for="error in errors">
                    {{ error }}
                </div>
                <div class="mb-3">
                    <label for="exampleInputEmail1" class="form-label"
                        >Name</label
                    >
                    <input
                        v-model="form.name"
                        type="text"
                        required
                        class="form-control"
                        id="exampleInputEmail1"
                        aria-describedby="emailHelp"
                    />
                </div>
                <div class="mb-3">
                    <label for="exampleInputEmail1" class="form-label"
                        >Email address</label
                    >
                    <input
                        v-model="form.email"
                        required
                        type="email"
                        class="form-control"
                        id="exampleInputEmail1"
                        aria-describedby="emailHelp"
                    />
                </div>
                <div class="mb-3">
                    <label for="team" class="form-label"
                        >Choose Your Team:</label
                    >
                    <b-form-select
                        v-model="form.team"
                        required
                        :options="options"
                        class="form-control"
                        id="team"
                    >
                    </b-form-select>
                    <input v-model="form.management" class="mt-2 p-2" type="checkbox">Management
                </div>
                <div class="mb-3">
                    <label for="exampleInputPassword1" class="form-label"
                        >Password</label
                    >
                    <input
                        v-model="form.password"
                        required
                        type="password"
                        class="form-control"
                        id="password"
                    />
                </div>
                <div class="mb-3">
                    <label for="exampleInputPassword1" class="form-label"
                        >Confirm Password</label
                    >
                    <input
                        v-model="form.password_confirmation"
                        required
                        type="password"
                        class="form-control"
                        id="confirm_password"
                    />
                </div>

                <button type="submit" class="myBtn w-100">Submit</button>
            </form>
             <a href="/login">Already Registered? Click here to Login</a>
        </div>
    </div>
</template>
<script>
export default {
    props: {
        errors: Object,
    },
    data() {
        return {
            form: {
                name: null,
                email: null,
                team: null,
                management: null,
                password: null,
                password_confirmation: null,
            },
            options: ['HR', 'Accounts', 'Sales', 'Development']
        };
    },

    methods: {
        submit() {
            this.$inertia.post("/register", this.form);
        },
    },
};
</script>
