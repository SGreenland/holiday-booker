<template lang="">
    <div ref="mainContent" id="mainContent" class="container-fluid d-flex flex-wrap m-auto main-content">
        <custom-alert
            v-if="displayAlert"
            :action="alertAction"
            :message="alertMessage"
            @close="displayAlert = false"
            @confirmedReject="rejectRequest"
        >
        </custom-alert>

        <div class="col-xl-6 col-lg-10 col-md-12 col-sm-12 mx-auto p-2">
            <div class="fixed-table-container card p-3 rounded-3 shadow-lg">
            <h4 class="text-center mb-3">Holiday Requests</h4>
            <table class="w-100">
                <thead scope="row" class="sticky-head">
                    <th class="table-header">Name</th>
                    <th class="table-header">Start Date</th>
                    <th class="table-header">End Date</th>
                    <th class="table-header">Total Days</th>
                    <th class="table-header text-center">Actions</th>
                </thead>
                <td colspan="5" v-if="!requests.length" class="w-100 text-center"> You haven't got any requests at the moment.</td>
                <tr v-for="request in requests">
                    <td class="">{{ request.name }}</td>
                    <td class="">{{ request.start_date }}</td>
                    <td class="">{{ request.end_date }}</td>
                    <td class="">{{ request.total_days }}</td>
                    <td class="text-center">
                        <button
                            class="btn btn-danger m-1 tableBtn"
                            id="reject-btn"
                            @click="confirmRejection(request.id, 'request')"
                        >
                            <b-icon icon="clipboard-x"></b-icon>
                        </button>
                          <b-tooltip placement="top" triggers="hover" :target="'reject-btn' + request.id">
                                Decline request
                         </b-tooltip>
                        <button
                            class="btn btn-success m-1 tableBtn"
                            id="approve-btn"
                            @click="approveHoliday(request.id)"
                            data-bs-target="#exampleModalCenter"
                        >
                            <b-icon icon="clipboard-check"></b-icon></button
                        >
                        <b-tooltip placement="top" triggers="hover" :target="'approve-btn' + request.id">
                                Approve request
                         </b-tooltip>
                    </td>
                </tr>
            </table>
            </div>
        </div>
         <div class="col-xl-6 col-lg-10 col-sm-12 mx-auto p-2">
            <div class="fixed-table-container card p-3 rounded-3 shadow-lg h-100">
            <h4 class="text-center mb-3">Cancellation Requests</h4>
            <table class="w-100">
                <thead scope="row" class="sticky-head">
                    <th class="table-header">Name</th>
                    <th class="table-header">Start Date</th>
                    <th class="table-header">End Date</th>
                    <th class="table-header">Total Days</th>
                    <th class="table-header text-center">Actions</th>
                </thead>
                <td colspan="5" v-if="!cancellationRequests.length" class="w-100 text-center"> You haven't got any cancellation requests at the moment.</td>
                <tr v-for="request in cancellationRequests">
                    <td class="">{{ request.holiday.name }}</td>
                    <td class="">{{ request.holiday.start_date }}</td>
                    <td class="">{{ request.holiday.end_date }}</td>
                    <td class="">{{ request.holiday.total_days }}</td>
                    <td class="text-center">
                       <div class="d-flex">
                           <button
                                class="btn btn-danger m-1 tableBtn"
                                :id="'reject-cancel' + request.id"
                                @click="rejectCancellation(request.id)"
                            >
                                <b-icon icon="clipboard-x"></b-icon>
                            </button>
                            <b-tooltip placement="top" triggers="hover" :target="'reject-cancel' + request.id">
                                    Decline request
                             </b-tooltip>
                             <button
                                class="btn btn-success m-1 tableBtn"
                                :id="'approve-cancel' + request.id"
                                @click="approveCancellation(request.id)"
                                data-bs-target="#exampleModalCenter"
                            >
                                <b-icon icon="clipboard-check"></b-icon></button
                            >
                             <b-tooltip placement="top" triggers="hover" :target="'approve-cancel' + request.id">
                                    Approve request
                             </b-tooltip>
                       </div>
                    </td>
                </tr>
            </table>
            </div>
        </div>
    </div>
</template>
<script>
export default {
    props: {
        user: Object,
        requests: Array,
        cancellationRequests: Array,
    },

    data() {
        return {
            alertMessage: "",
            displayAlert: false,
            rejected: false,
            rejectedId: null,
            alertAction: null
        };
    },

    mounted() {
        console.log(this.cancellationRequests);
    },

    methods: {
        approveHoliday(id) {
            axios
                .put(`holiday/${id}`, {
                    status: "approved",
                })
                .then((response) => {
                    if (response.status === 200) {
                        this.displayAlert = true;
                        this.alertMessage = "Holiday Approved!"
                        this.$inertia.reload({only: ['requests']});
                    }
                });
        },

        confirmRejection(id) {
            this.rejectedId = id;
            this.displayAlert = true;
            this.alertMessage = 'Please enter a reason for declining holiday:'
            this.alertAction = 'confirmReject'
        },
        rejectRequest(){
             axios
                .put(`/holiday/${this.rejectedId}`, {
                    status: "declined",
                    rejection_reason: this.rejectionReason,
                })
                .then((response) => {
                    if (response.status === 200) {
                        this.alertMessage = 'Holiday declined.'
                        this.alertAction = null;
                        this.$inertia.reload({only: ['requests']})
                    }
                });
        },
        approveCancellation(id) {
            axios.put(`/admin/cancellation-requests/update`, {id: id, status: 'approved'}).then(() => {
                this.displayAlert = true;
                this.alertMessage = 'Cancellation approved.';
                this.$inertia.reload({only: ['cancellationRequests']});
            }
            ).catch(() => {
                this.displayAlert = true;
                this.alertMessage = 'Sorry an error occurred, please try again later.';
            })
        },
        rejectCancellation(id){
            axios.put(`/admin/cancellation-requests/update`, {id: id, status: 'declined'}).then(() => {
                this.displayAlert = true;
                this.alertMessage = 'Cancellation request declined.';
                this.$inertia.reload({only: ['cancellationRequests']});
            }).catch(() => {
                this.displayAlert = true;
                this.alertMessage = 'Sorry an error occurred, please try again later.';
            })
        }
    },
};
</script>
