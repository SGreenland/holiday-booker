<template lang="">
    <div ref="mainContent" id="mainContent" class="container-fluid main-content">
        <!--alert modal-->
        <custom-alert
            v-if="displayAlert"
            :message="alertMessage"
            :action="alertAction"
            @close="displayAlert = false, alertAction=false"
            @confirmedDelete="deleteRequest"
        ></custom-alert>

        <div class="col-12 d-flex flex-wrap vh-85">
             <!--pie-chart allowance-->
            <div
                class="col-12 col-lg-6 col-sm-12 p-2 d-flex flex-column h-100"
            >
                <div class="card rounded-3 shadow-lg p-3 w-100 h-50 mb-3">
                    <h5 class="w-100 text-center">My Allowance</h5>
               <div class="d-flex h-100 center-content">
                   <apexchart :key="donutKey" width="380" type="donut" :options="options" :series="series"></apexchart>
               </div>
               </div>
               <!--mini-calendar-->
               <div class="card rounded-3 shadow-lg p-3 w-100 h-50">
                <calender class="w-100 h-100" :disabled-dates='{ weekends: [0, 6] }' ref="cal" :attributes="attrs"></calender>
               </div>

            </div>
            <!--requests-->
            <div class="col col-lg-6 col-sm-12 p-2 h-100">
                <div class="card rounded-3 shadow-lg p-3 h-100">
                    <h5 class="w-100 text-center">My Requests</h5>
                    <div class="">
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
                                        :id="'delete-btn' + request.id"
                                        @click="confirmDelete(request.id)"
                                    >
                                        <b-icon icon="trash"></b-icon>
                                        <b-tooltip placement="left" triggers="hover" :target="'delete-btn' + request.id">
                                        Delete Request
                                        </b-tooltip>
                                    </button>
                                    <button
                                        v-else
                                        class="btn btn-outline-secondary tableBtn w-75"
                                        :id="'cancel-btn' + request.id"
                                        @click="cancelHoliday(request.id)"
                                    >
                                        <b-icon icon="trash"></b-icon>
                                    </button>
                                    <b-tooltip placement="left" triggers="hover" :target="'cancel-btn' + request.id">
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
            attrs: [],
        };
    },

    mounted() {
        this.userHoliday.forEach((holiday) => {
        this.attrs.push({
          key: holiday.name,
          dates: [
            {
              start: new Date(holiday.start_date),
              end: new Date(holiday.end_date),
            },
          ],
          excludeDates: { weekends: [0, 6] },
          highlight: {
            color: 'purple',
            ...(holiday.status === "declined" && { color: "gray" }),
            ...(holiday.status === "pending" && {
              fillMode: "light",
            }),
            ...(holiday.status === "approved" && {
              fillMode: "solid",
            }),
            ...(holiday.status === "declined" && {
              fillMode: "outline",
            }),
          },})})

          if(window.innerWidth < 700) {
            //format for mobile
            this.$refs.mainContent.classList.remove('main-content');
            this.$parent.$refs.bell.style.right = '90px';
          }
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
