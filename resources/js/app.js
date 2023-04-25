require("./bootstrap");
import "bootstrap";
import Vue from "vue";
import Calendar from "v-calendar/lib/components/calendar.umd";
import DatePicker from "v-calendar/lib/components/date-picker.umd";
import { createInertiaApp, Link } from "@inertiajs/inertia-vue";
import Layout from "./Pages/Shared/Layout.vue";
import AdminPage from "./Pages/AdminPage.vue";
import RequestForm from "./Pages/RequestForm.vue";
import CustomAlert from "./Pages/components/CustomAlert.vue";
import VueApexCharts from 'vue-apexcharts';
import { BootstrapVue, BootstrapVueIcons } from 'bootstrap-vue'

import 'bootstrap/dist/css/bootstrap.css'
import 'bootstrap-vue/dist/bootstrap-vue.css'

Vue.use(BootstrapVue)
Vue.use(BootstrapVueIcons)




Vue.component('apexchart', VueApexCharts)

Vue.component("inertia-link", Link);
Vue.component("Layout", Layout);
Vue.component("calender", Calendar);
Vue.component("date-picker", DatePicker);
Vue.component("custom-alert", CustomAlert);
// Vue.component('b-form-datepicker', BFormDatepicker)

createInertiaApp({
    resolve: (name) => {
        const page = require(`./Pages/${name}`).default;
        page.layout = Layout;
        return page;
    },

    setup({ el, App, props, plugin }) {
        Vue.use(plugin);
        Vue.use(VueApexCharts)

        new Vue({
            render: (h) => h(App, props),
        }).$mount(el);
    },
});
