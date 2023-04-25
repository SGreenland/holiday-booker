<template lang="">
    <div class="container-fluid">
        <custom-alert
            v-if="displayAlert"
            :message="alertMessage"
            :action="alertAction"
            @close="displayAlert = false"
            @confirmedDelete="deleteRequest"
        ></custom-alert>
        <div class="row mb-4">
            <h2 class="col text-center">My Holiday</h2>
        </div>
        <div class="row col-12 m-auto">
            <div
                class="col-12 col-lg-6 col-sm-10 p-4 d-flex justify-content-evenly align-items-center"
            >
               <apexchart :key="donutKey" width="380" type="donut" :options="options" :series="series"></apexchart>
            </div>
            <div class="col col-lg-6 col-sm-12 m-auto">
                <h5 class="w-100 text-center">My Requests</h5>
                <div class="fixed-table-container-sm">
                    <table class="w-100">
                        <thead scope="row" class="sticky-head">
                            <th class="table-header">Start Date</th>
                            <th class="table-header">End Date</th>
                            <th class="table-header">Total Days</th>
                            <th class="table-header">Status</th>
                            <th class="table-header text-center">Actions</th>
                        </thead>
                        <td colspan="5" v-if="!userHoliday.length" class="w-100 text-center"> You haven't requested any holiday yet.</td>
                        <tr
                            v-for="request in userHoliday"
                        >
                            <td>{{ request.start_date }}</td>
                            <td>{{ request.end_date }}</td>
                            <td>{{ request.total_days }}</td>
                            <td>{{ request.status }}</td>
                            <td class="text-center">
                                <button
                                    v-if="request.status == 'pending' || request.status == 'declined'"
                                    class="btn btn-secondary m-1 tableBtn w-75"
                                    id="reject-btn"
                                    @click="confirmDelete(request.id)"
                                >
                                    Delete
                                </button>
                                <button
                                    v-else
                                    class="btn btn-outline-danger m-1 tableBtn w-75"
                                    id="reject-btn"
                                    @click="cancelHoliday(request.id)"
                                >
                                    Cancel
                                </button>
                            </td>
                        </tr>
                    </table>
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
                dataLabels: {
                    enabled: true,
                    formatter: function(val, opts) {
                     return opts.w.config.series[opts.seriesIndex]

                    }}
                },
            series: [this.totalDaysTaken, 30 - this.totalDaysTaken],
            pending: null,
            approved: null,
            rejected: null,
            doughnutColor: null,
            selectedRequestId: null,
            displayAlert: null,
            alertMessage: null,
            alertAction: null,
            donutKey: 0,

        };
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
                    console.log(response)
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
        }
    }
};
</script>
<style lang=""></style>
