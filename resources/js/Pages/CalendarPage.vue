<template lang="">
    <div class="container-fluid row m-auto vh-75">
        <calender :disabled-dates='{ weekends: [0, 6] }' :key="calendarKey" ref="cal" :attributes="attrs" class="col p-3"></calender>
        <div class="d-flex w-100 justify-content-between">
            <div>
                <b class="me-2">Change my Color:</b>
                <select v-model="myColor" @change="changeColor">
                    <option v-for="color in colorOptions">{{ color }}</option>
                </select>
            </div>
            <div class="col-lg-6 col-sm-8 d-flex justify-content-around align-items-center">
                <b class="me-2">Filter Holiday:</b>
                <span>
                    <label for="my-holiday" class="me-2">Just My Holiday</label>
                    <input v-model="filter.myHoliday" type="checkbox" name="" id="my-holiday">
                </span>
                <span>
                    <label for="pending" class="me-2">Pending</label>
                    <input v-model="filter.pending" type="checkbox" name="" id="pending">
                </span>
                <span>
                    <label for="approved" class="me-2">Approved</label>
                    <input v-model="filter.approved" type="checkbox" name="" id="approved">
                </span>
            </div>
        </div>
    </div>
</template>
<script>
export default {
  props: {
      allHoliday: Array,
  },
   data() {
    return {
      holidays: null,
      attrs: [],
      filter: {
        myHoliday: false,
        pending: false,
        approved: false,
      },
      calendarKey: 1,
      myColor: 'pink',
      colorOptions: ['pink', 'green', 'blue',  'yellow', 'orange', 'teal', 'purple'],
    };
  },

  mounted() {
    this.holidays = this.allHoliday;
    this.setCalendar();
    if(localStorage.getItem('myColor')){
        this.myColor = localStorage.getItem('myColor');
    }
  },
  methods: {
    setCalendar() {

      this.holidays.forEach((holiday) => {
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
            color: this.getColor(holiday.user_id),
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
          },

          popover: {
            ...(holiday.status === "approved" && {
              label: `${holiday.name} holiday approved.`,
            }),
            ...(holiday.status === "declined" && {
              label: `${holiday.name} holiday declined`,
            }),
            ...(holiday.status === "pending" && {
              label: `${holiday.name} holiday pending.`,
            }),
          },

        });
      });
    },

    changeColor(e) {
      this.attrs.forEach((attr) => {
        if (attr.key === this.$page.props.auth.user.name) {
          attr.highlight.color = e.target.value;
        }
        localStorage.setItem("myColor", e.target.value);
        this.setCalendar();
      });
    },

    getColor(userId) {
        if(this.$page.props.auth.user.id == userId){
            return this.checkSavedColor();
        }
        else return this.colorOptions[userId]
    },

    checkSavedColor() {
      if (
        localStorage.getItem("myColor")
      ) {
        return localStorage.getItem("myColor");
      } else {
        return "pink";
      }
    },
  },
  watch: {
    filter: {
        deep: true,
        handler() {
            if(Object.values(this.filter).filter(val => !val).length == 3){
                this.holidays = this.allHoliday;
                this.attrs = [];
                this.setCalendar()
                this.calendarKey += 1
            }
            else {
                  if(this.filter.pending && !this.filter.myHoliday && !this.filter.approved){
                this.holidays = this.allHoliday.filter(hol => hol.status == 'pending')

                }
                else if(this.filter.pending && this.filter.myHoliday && !this.filter.approved){
                    this.holidays = this.allHoliday.filter(hol => hol.status == 'pending' && hol.user_id == this.$page.props.auth.user.id)
                }
                else if (this.filter.pending && this.filter.myHoliday && this.filter.approved){
                    this.holidays = this.allHoliday.filter(hol => hol.status == 'pending|approved' && hol.user_id == this.$page.props.auth.user.id)
                }
                else if (this.filter.approved && !this.filter.pending && !this.filter.myHoliday){
                    this.holidays = this.allHoliday.filter(hol => hol.status == 'approved')
                }
                else if (this.filter.approved && this.filter.pending && !this.filter.myHoliday){
                    this.holidays = this.allHoliday.filter(hol => hol.status == 'approved|pending')
                }
                else if(this.filter.myHoliday && !this.filter.pending && !this.filter.approved){
                    this.holidays = this.allHoliday.filter(hol => hol.user_id == this.$page.props.auth.user.id)
                }
                this.attrs = [];
                this.setCalendar();
            }
        }
    },
  }
};
</script>
<style lang=""></style>
