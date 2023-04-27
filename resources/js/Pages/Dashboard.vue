<template lang="">
    <div id="mainContent" class="container-fluid main-content">
        <custom-alert
            v-if="displayAlert"
            :message="alertMessage"
            :action="alertAction"
            @close="displayAlert = false, alertAction=false"
            @confirmedDelete="deleteRequest"
        ></custom-alert>
        <div class="w-100 text-center">
            <h2>Dashboard</h2>
        </div>
        <div class="row col-12 m-auto">
            <div
                class="col-12 col-lg-6 col-sm-12 p-2 d-flex"
            >
                <div class="card rounded-3 shadow-lg p-3 w-100 h-100">
                    <h5 class="w-100 text-center">My Allowance</h5>
               <div class="d-flex h-100 center-content">
                   <apexchart :key="donutKey" width="380" type="donut" :options="options" :series="series"></apexchart>
               </div>
               </div>
            </div>
            <div class="col col-lg-6 col-sm-12 m-auto p-2">
                <div class="card rounded-3 shadow-lg p-3">
                    <h5 class="w-100 text-center">My Requests</h5>
                    <div class="fixed-table-container-sm">
                        <table class="w-100">
                            <thead scope="row" class="sticky-head">
                                <th class="table-header">Start Date</th>
                                <th class="table-header">End Date</th>
                                <th class="table-header">Total Days</th>
                                <th class="table-header">
                                    <b-dropdown text="Status">
                                        <b-dropdown-form>
                                            <b>Filter:</b>
                                            <b-form-checkbox v-model="filter[0].pending">
                                                Pending
                                            </b-form-checkbox>
                                             <b-form-checkbox v-model="filter[1].approved">
                                                Approved
                                            </b-form-checkbox>
                                            <b-form-checkbox v-model="filter[2].declined">
                                                Declined
                                            </b-form-checkbox>
                                        </b-dropdown-form>
                                    </b-dropdown>
                                </th>
                                <th class="table-header text-center">Actions</th>
                            </thead>
                            <td colspan="5" v-if="!userHoliday.length" class="w-100 text-center"> You haven't requested any holiday yet.</td>
                            <tr
                                v-for="request in getFilteredHoliday"
                                :ref="request.status"
                            >
                                <td>{{ request.start_date }}</td>
                                <td>{{ request.end_date }}</td>
                                <td>{{ request.total_days }}</td>
                                <td>{{ request.status }}</td>
                                <td class="text-center">
                                    <button
                                        v-if="request.status == 'pending' || request.status == 'declined'"
                                        class="btn btn-danger tableBtn w-75"
                                        id="delete-btn"
                                        @click="confirmDelete(request.id)"
                                    >
                                        <b-icon icon="trash"></b-icon>
                                        <b-tooltip placement="left" triggers="hover" target="delete-btn">
                                        Delete Request
                                        </b-tooltip>
                                    </button>
                                    <button
                                        v-else
                                        class="btn btn-outline-secondary tableBtn w-75"
                                        id="cancel-btn"
                                        @click="cancelHoliday(request.id)"
                                    >
                                        <b-icon icon="trash"></b-icon>
                                    </button>
                                    <b-tooltip placement="left" triggers="hover" target="cancel-btn">
                                        Send a request to Cancel this holiday
                                    </b-tooltip>
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
export default {
    props: {userHoliday: Array, totalDaysTaken: Number},
    data() {
        return {
            options: {
                labels: ['Holiday Taken', 'Holiday Remaining'],
                colors: ['#663577', '#b29bb9'],
                dataLabels: {
                    enabled: true,
                    formatter: function(val, opts) {
                     return opts.w.config.series[opts.seriesIndex] + ' days'

                    },
                    }
                },
            series: [this.totalDaysTaken, 30 - this.totalDaysTaken],
            pending: null,
            approved: null,
            rejected: null,
            selectedRequestId: null,
            displayAlert: null,
            alertMessage: null,
            alertAction: null,
            donutKey: 0,
            filter: [{pending: true}, {approved: true}, {declined: true}],
            filteredHoliday: null,

        };
    },



    computed: {
        getFilteredHoliday() {
            if(!this.filteredHoliday) return this.userHoliday;
            else return this.filteredHoliday;
        }
    },
    methods: {
        confirmDelete(id) {
            this.selectedRequestId = id;
            this.displayAlert = true;
            this.alertAction = 'confirmDelete';
            this.alertMessage = "Are you Sure you want to delete this request?";
        },
        deleteRequest() {
            axios
                .delete(`/holiday/${this.selectedRequestId}`)
                .then((response) => {
                    this.alertMessage = "Request Deleted"
                    this.alertAction = null;
                    this.$inertia.reload({ only: ["userHoliday", "totalDaysTaken"] });
                })
                .catch((error) => {
                    this.alertMessage = error.response.data.message;
                    this.alertAction = null;
                });
        },
        cancelHoliday(id) {
            axios.post('/user/cancellation-request', {holidayId: id}).then(response => {
                this.displayAlert = true;
                this.alertMessage = 'A request to cancel this holiday has been sent to your manager.'
            }).catch(error => {
                this.displayAlert = true;
                this.alertMessage = error.response.data;
            });
        },
    },
    watch: {
        totalDaysTaken() {
            this.series = [this.totalDaysTaken, 30 - this.totalDaysTaken];
            this.donutKey += 1;
        },

       filter: {
        deep: true,
        handler() {
            if(this.filter.every((obj) => obj[Object.keys(obj)])){
                this.filteredHoliday = null;
            }
            else {
                this.filteredHoliday = this.userHoliday;
                 this.filter.forEach(filter => {
                if(filter.pending == false){
                    this.filteredHoliday = this.filteredHoliday.filter(hol => hol.status != 'pending')
                }
                if(filter.approved == false){
                    this.filteredHoliday = this.filteredHoliday.filter(hol => hol.status != 'approved')

                }
                if(filter.declined == false){
                    this.filteredHoliday = this.filteredHoliday.filter(hol => hol.status != 'declined')
                }
            })
            }

        }
       }
    }
};
</script>
<style lang=""></style>
