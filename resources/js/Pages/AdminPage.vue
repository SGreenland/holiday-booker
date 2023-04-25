<template lang="">
    <div class="container-fluid d-flex m-auto">
        <custom-alert
            v-if="displayAlert"
            :action="alertAction"
            :message="alertMessage"
            @close="displayAlert = false"
            @confirmedReject="rejectRequest"
        >
        </custom-alert>

        <div class="col col-lg-5 col-sm-12 m-auto fixed-table-container">
            <h2 class="text-center mb-3">Holiday Requests</h2>
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
                            class="btn btn-info m-1 tableBtn"
                            id="approve-btn"
                            @click="approveHoliday(request.id)"
                            data-bs-target="#exampleModalCenter"
                        >
                            Approve</button
                        ><button
                            class="btn btn-secondary m-1 tableBtn"
                            id="reject-btn"
                            @click="confirmRejection(request.id)"
                        >
                            Reject
                        </button>
                    </td>
                </tr>
            </table>
        </div>
         <div class="col col-lg-5 col-sm-12 m-auto fixed-table-container">
            <h2 class="text-center mb-3">Cancellation Requests</h2>
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
                    <td class="">{{ request.name }}</td>
                    <td class="">{{ request.start_date }}</td>
                    <td class="">{{ request.end_date }}</td>
                    <td class="">{{ request.total_days }}</td>
                    <td class="text-center">
                        <button
                            class="btn btn-info m-1 tableBtn"
                            id="approve-btn"
                            @click="approveHoliday(request.id)"
                            data-bs-target="#exampleModalCenter"
                        >
                            Approve</button
                        ><button
                            class="btn btn-secondary m-1 tableBtn"
                            id="reject-btn"
                            @click="confirmRejection(request.id)"
                        >
                            Reject
                        </button>
                    </td>
                </tr>
            </table>
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
    },
};
</script>
