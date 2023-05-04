<template lang="">
    <div ref="mainContent" id="mainContent" class="container-fluid row m-auto h-75 main-content">
        <calender :height="'max-content'" :disabled-dates='{ weekends: [0, 6] }' :key="calendarKey" ref="cal" :attributes="attrs" class="col h-100 p-3 me-4"></calender>
        <div class="row col-12 justify-content-evenly pt-2">
            <div class="col-lg-3 col-sm-12">
                <b>Change my Color:</b>
                <select v-model="myColor" @change="changeColor">
                    <option v-for="color in colorOptions">{{ color }}</option>
                </select>
            </div>
                <div class="col-lg-3 col-sm-12">
                    <b>Filter by Team:</b>
                    <b-select v-model="selectedTeam" :options="teamOptions">
                    </b-select>
                </div>
                <div class="col-lg-3 col-sm-12">
                    <b>Filter by Status:</b>
                    <b-select v-model="selectedStatus" :options="statusOptions">
                    </b-select>
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
      teamOptions: ['HR', 'Accounts', 'Sales'],
      statusOptions: ['pending', 'approved', 'declined'],
      selectedTeam: null,
      selectedStatus: null,
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
    selectedTeam() {
        if(this.selectedStatus){
            this.holidays = this.allHoliday.filter(hol => hol.team == this.selectedTeam && hol.status == this.selectedStatus);
        }
        else {
            this.holidays = this.allHoliday.filter(hol => hol.team == this.selectedTeam);
        }
        this.attrs = [];
        this.$nextTick(() => {
             this.setCalendar();
        })

    },
    selectedStatus() {
        if(this.selectedTeam){
            this.holidays = this.allHoliday.filter(hol => hol.team == this.selectedTeam && hol.status == this.selectedStatus);
        }
        else {
            this.holidays = this.allHoliday.filter(hol => hol.status == this.selectedStatus);
        }
        this.attrs = [];
        this.$nextTick(() => {
            this.setCalendar();
        })
    }
  }
};
</script>
<style lang=""></style>
