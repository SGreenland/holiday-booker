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
            <div v-show="path != 'login' && path != 'register'" class="navLinks p-4 d-flex align-items-center">
                <button
                    class="barsBtn"
                    type="button"
                    aria-controls="offcanvasRight"
                    data-bs-toggle="offcanvas"
                    data-bs-target="#offcanvasRight"
                    @click="reduceContent"
                >
                    <i class="fa fa-bars"> </i>
                </button>
                <div
                    v-show="path != 'login' && path != 'register'"
                    class="offcanvas show offcanvas-end"
                    data-bs-backdrop="false"
                    data-bs-scroll="true"
                    ref="offcanvas"
                    tabindex="-1"
                    id="offcanvasRight"
                    aria-labelledby="offcanvasRightLabel"
                >
                    <div class="offcanvas-header">
                        <div class="h5 m-0" id="offcanvasRightLabel">
                                Hello,
                                {{ user ? user.name : "Guest" }}
                        </div>
                        <b-icon
                            type="button"
                            data-bs-dismiss="offcanvas"
                            @click="expandContent"
                            aria-label="Close"
                            icon="x-lg"
                        ></b-icon>
                    </div>
                    <div class="offcanvas-body">
                         <div class="mb-5 navItem">
                            <i class="fa fa-home"></i>
                             <inertia-link
                                class="navLink"
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
                                type="button"
                                as="button"
                                href="/holiday"
                                >Calendar</inertia-link
                            >
                        </div>
                        <div class="mb-5 navItem">
                            <i class="bi bi-calendar-plus"></i>
                            <inertia-link
                                class="navLink"
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
                                as="button"
                                type="button"
                                href="/admin"
                                >Admin</inertia-link
                            >
                        </div>
                         <div class="mb-5 navItem">
                            <i class="fa fa-sign-out"></i>
                            <inertia-link
                                class="navLink"
                                as="button"
                                type="button"
                                method="post"
                                href="/logout">
                                Logout
                            </inertia-link>
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
    methods: {
        expandContent() {
            document.getElementById('mainContent').classList.remove('main-content')
        },
        reduceContent() {
            document.getElementById('mainContent').classList.add('main-content')
        }
    }

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
