<template lang="">
    <div>
        <custom-alert v-if="displayAlert" :message="alertMessage" @close="displayAlert = false">
        </custom-alert>
        <h2 class="text-center">Request Holiday</h2>
        <form
            class="col-11 col-lg-6 col-md-8 col-sm-10 m-auto p-4 card rounded-3 shadow h-75 justify-content-between"
            @submit.prevent="submit"
        >
            <label for="start_date">Start date:</label>
            <b-form-datepicker v-model="form.start_date" :max="form.end_date" :date-disabled-fn="disablePastAndWeekends"></b-form-datepicker>
            <select v-model="form.start_time" name="" id="">
                <option>Morning</option>
                <option>Afternoon</option>
            </select>
            <label for="end_date">End date:</label>
            <b-form-datepicker v-model="form.end_date" :min="form.start_date" :date-disabled-fn="disablePastAndWeekends"></b-form-datepicker>
            <select v-model="form.end_time" name="" id="">
                <option>End of Day</option>
                <option>Afternoon</option>
            </select>
            <label for="total">Total:</label>
            <div v-if="form.total_days >= 0" id="total">
                {{ form.total_days + " days" }}
            </div>
            <button type="submit" class="myBtn">Submit</button>
        </form>
    </div>
</template>
<script>
export default {
    data() {
        return {
            form: {
                start_date: null,
                start_time: "Morning",
                end_date: null,
                end_time: "End of Day",
                total_days: 0,
            },
            displayAlert: null,
            alertMessage: null,
        };
    },

    methods: {
        submit() {
            axios.post("/holiday", this.form).then(() => {
                    this.$inertia.get('/');
            }).catch(error => {
                this.displayAlert = true;
                this.alertMessage = error.response.data.message;
            })
        },
        disablePastAndWeekends(date){
            const timeNow = new Date().getTime();
            const time = new Date(date).getTime();
            const day = new Date(date).getDay();
            if(day == 0 || day == 6 || time < timeNow){
                return true;
            }
        },

        getNumberOfDays() {
            const date1 = new Date(this.form.start_date);
            const date2 = new Date(this.form.end_date);

            //get all dates and check for weekends
            const date = new Date(date1.getTime());
            const dates = [];

            while (date <= date2) {
                dates.push(new Date(date));
                date.setDate(date.getDate() + 1);
            }
            let weekendCount = 0
            dates.forEach((date) => {
                if(date.getDay() == 6 || date.getDay() == 0){
                    weekendCount += 1;
                }
            })

            // One day in milliseconds
            const oneDay = 1000 * 60 * 60 * 24;

            // Calculating the time difference between two dates
            const diffInTime = date2.getTime() - date1.getTime();

            // Calculating the no. of days between two dates
            const diffInDays = Math.round(diffInTime / oneDay) + 1;

            if (
                this.form.start_time === "Afternoon" &&
                this.form.end_time === "Afternoon"
            ) {
                this.form.total_days = diffInDays - 1 - weekendCount;
            } else if (
                (this.form.start_time === "Morning" &&
                    this.form.end_time === "Afternoon") ||
                (this.form.start_time === "Afternoon" &&
                    this.form.end_time === "End of Day")
            ) {
                this.form.total_days = diffInDays - 0.5 - weekendCount;
            } else {
                this.form.total_days = diffInDays - weekendCount;
            }
        },
    },

    watch: {
        "form.end_date": {
            handler() {
                this.getNumberOfDays();
            },
            deep: true,
        },

        "form.start_date": {
            handler() {
                this.getNumberOfDays();
            },
            deep: true,
        },

        "form.end_time": {
            handler() {
                this.getNumberOfDays();
            },
            deep: true,
        },
        "form.start_time": {
            handler() {
                this.getNumberOfDays();
            },
            deep: true,
        },
    },
};
</script>
<style lang=""></style>
