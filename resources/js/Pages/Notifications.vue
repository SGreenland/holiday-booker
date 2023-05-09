<template lang="">
    <div class="main-content">
        <div class="p-4">
            <div class="w-100 mb-2">
                <h5 class="text-center">My Notifications</h5>
                <a href="" role="button" @click="markAllAsRead">Mark All as Read</a>
            </div>
            <div v-if="!$page.props.auth.user.notifications.length">
                No Notifications.
            </div>
            <div
                v-else
                v-for="notification in $page.props.auth.user.notifications"
                :class="`card rounded-5 w-100 p-3 d-flex flex-row justify-content-between ${
                    notification.data.status == 'approved'
                        ? 'bg-success'
                        : 'bg-declined'
                }`"
            >
                <div>{{ formatNotification(notification) }}</div>
                <a href="" role="button" @click="markAsRead(notification.id)">Mark as Read</a>
            </div>
        </div>
    </div>
</template>
<script>
export default {
    methods: {
        formatNotification(notification) {
            if (notification.type.includes("HolidayStatusNotification")) {
                return `${notification.data.adminUser} has ${notification.data.status} your request to book holiday from ${notification.data.startDate} till ${notification.data.endDate}.`;
            }
        },
        markAsRead(id){
            axios.post(`/notifications/mark-as-read/${id}`).then(() => {
                window.location.reload();
            }).catch(error => {
                alert(error.response.data);
            })
        },
        markAllAsRead(){
            axios.post('/notifications/mark-all-as-read').then(() => {
                window.location.reload();
            }).catch(error => {
                alert(error.response.data.message);
            })
        }
    },
};
</script>
<style lang="">
</style>
