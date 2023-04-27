<template lang="">
    <div>
        <div
            class="container-fluid w-100 d-flex justify-content-between align-items-center mainNav"
        >
            <div class="p-4">
                <a href="/" id="mainLink"
                    ><h2 class="">Holiday Booker</h2></a
                >
            </div>
            <!-- side bar nav -->
            <div v-if="path != 'login' && path != 'register'" class="navLinks p-4 d-flex align-items-center">
                <button
                    class="barsBtn"
                    type="button"
                    data-bs-toggle="offcanvas"
                    data-bs-target="#offcanvasRight"
                    aria-controls="offcanvasRight"
                >
                    <i class="fa fa-bars"> </i>
                </button>
                <div
                    class="offcanvas offcanvas-end"
                    ref="offcanvas"
                    tabindex="-1"
                    id="offcanvasRight"
                    aria-labelledby="offcanvasRightLabel"
                >
                    <div class="offcanvas-header">
                        <h5 id="offcanvasRightLabel">
                            Hello,
                            {{ user ? user.name : "Guest" }}
                        </h5>
                        <button
                            type="button"
                            class="btn-close text-reset"
                            data-bs-dismiss="offcanvas"
                            aria-label="Close"
                        ></button>
                    </div>
                    <div class="offcanvas-body">
                         <div class="mb-5 navItem">
                            <i class="fa fa-home"></i>
                             <inertia-link
                                class="navLink"
                                data-bs-dismiss="offcanvas"
                                type="button"
                                as="button"
                                href="/"
                                >Home</inertia-link
                            >
                        </div>
                         <div class="mb-5 navItem">
                            <i class="fa fa-user"></i>
                             <inertia-link
                                class="navLink"
                                data-bs-dismiss="offcanvas"
                                type="button"
                                as="button"
                                href="/my-account"
                                >My Account</inertia-link
                            >
                        </div>


                        <div class="mb-5 navItem">
                            <i class="bi bi-calendar"></i>
                            <inertia-link
                                class="navLink"
                                data-bs-dismiss="offcanvas"
                                type="button"
                                as="button"
                                href="/holiday"
                                >Calendar</inertia-link
                            >
                        </div>
                        <!-- <div class="mb-5 navItem">
                            <i class="bi bi-calendar-heart"></i>
                            <inertia-link
                                class="navLink"
                                data-bs-dismiss="offcanvas"
                                type="button"
                                as="button"
                                href="/my_holiday"
                                >My Holiday</inertia-link
                            >
                        </div> -->
                        <div class="mb-5 navItem">
                            <i class="bi bi-calendar-plus"></i>
                            <inertia-link
                                class="navLink"
                                data-bs-dismiss="offcanvas"
                                as="button"
                                type="button"
                                href="/holiday/create"
                                >Request Holiday</inertia-link
                            >
                        </div>
                        <div v-if="isAdmin" class="mb-5 navItem">
                            <i class="bi bi-briefcase-fill"></i>
                            <inertia-link
                                class="navLink"
                                data-bs-dismiss="offcanvas"
                                as="button"
                                type="button"
                                href="/admin"
                                >Admin</inertia-link
                            >
                        </div>
                         <div class="mb-5 navItem">
                            <i class="fa fa-sign-out"></i>
                            <a
                                data-bs-dismiss="offcanvas"
                                class="navLink"
                                type="button"
                                role="button"
                                @click="goToRoute"
                                >{{ user ? "Logout" : "Login/Register" }}</a
                            >
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <slot></slot>
    </div>
</template>
<script>
export default {
    computed: {
        user() {
            return this.$page.props.auth.user;
        },
        isAdmin() {
            if(this.user){
                return this.$page.props.auth.user.roles.filter(role => role.id == 1).length
            }
        },
        path() {
            return this.$page.props.pathname;
        }
    },
    mounted() {
        console.log(this.path);
    },

    methods: {
        goToRoute() {
            if (!this.user) {
                this.$inertia.get("/login");
            } else {
                this.$inertia.post("/logout");
            }
        },
    },
};
</script>
<style scoped>
#mainLink {
    all: unset;
}

#mainLink:hover {
    cursor: pointer;
}
</style>
