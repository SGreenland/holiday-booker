<template lang="">
    <div>
        <div class="container text-center w-100 pt-3">
            <h2>Login</h2>

            <form
                class="col col-lg-6 col-sm-10 m-auto text-start"
                @submit.prevent="submit"
            >
                <div class="bg-danger text-white p-2" v-for="error in errors">
                    {{ error }}
                </div>
                <div class="mb-3">
                    <label for="exampleInputEmail1" class="form-label"
                        >Email address</label
                    >
                    <input
                        v-model="form.email"
                        type="email"
                        class="form-control"
                        id="exampleInputEmail1"
                        aria-describedby="emailHelp"
                    />
                </div>
                <div class="mb-3">
                    <label for="exampleInputPassword1" class="form-label"
                        >Password</label
                    >
                    <input
                        v-model="form.password"
                        type="password"
                        class="form-control"
                        id="exampleInputPassword1"
                    />
                </div>
                <div class="mb-3 form-check">
                    <input
                        v-model="form.remember"
                        type="checkbox"
                        name="remember"
                        class="form-check-input"
                        id="exampleCheck1"
                    />
                    <label class="form-check-label" for="exampleCheck1"
                        >remember me</label
                    >
                </div>
                <button type="submit" class="myBtn w-100">Submit</button>
            </form>
            <a href="/register">Not Registered? Click here</a>
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
                email: null,
                password: null,
                remember: null,
            },
        };
    },

    mounted() {
        //mobile layout
         if(window.innerWidth < 700){
                this.$nextTick(() => {
                    this.$parent.$refs.offcanvas.classList.remove('show');
                    Array.from(document.getElementsByClassName('navLink')).forEach(link => {
                        link.setAttribute('data-bs-dismiss', 'offcanvas')
                    })
                })
            }
    },

    methods: {
        submit() {
            this.$inertia.post("/login", this.form);
        },
    },
};
</script>
