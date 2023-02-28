<template>
    <div class="calendar">
        <div class="d-flex px-5 justify-content-between py-3">
            <h4><b>{{ selectedMonth}}</b></h4>
            <div>
                <span class="px-2 pointer" @click="selectPrevious"><i class="fas fa-backward"></i></span>
                <span class="px-2 pointer" @click="selectCurrent">Today</span>
                <span class="px-2 pointer" @click="selectNext"><i class="fas fa-forward"></i></span>
            </div>
        </div>
        <div class="day-of-week">
            <div class="item-week" v-for="(dayName, idx) in weekdays" :key="idx">
                <span>{{ dayName }}</span>
            </div>
        </div>
        <div class="days-grid" >
            <div class="item-day" :class="!dateData.isCurrentMonth? 'inactive-day': ''" v-for="(dateData, idx) in days" :key="idx" v-tooltip="{content: getEventHtml(dateData.date), html:true, trigger:'click', autoHide: false}" :style="{'background-color': getEvent(dateData.date)[0]?.color, 'color': getEvent(dateData.date).length>0 ? 'white !important' : ''}">
                <span >{{ $dayjs(dateData.date).format("D") }}</span>
            </div>
        </div>
    </div>
</template>
<style>
    .pointer{
        cursor: pointer;
    }
    .calendar{
        background-color: #FBFBFB;
    }
    .day-of-week,
    .days-grid {
        display: grid;
        grid-template-columns: repeat(7, 1fr);
    }
    .item-week{
        background-color: #F0F0F0;
        color: #222222;
        font-weight: 600;
        font-size: 16px;
        padding: 5px;
        min-height: 20px;
        margin: 2px;
        text-align: center;
    }
    .item-day{
        color: #222222;
        text-align: center;
        margin: 2px;
        font-size: 16px;
        background-color: #fff;
        padding: 5px;
        padding-top: 20%;
        padding-bottom: 20%;
        position: relative;
        cursor: pointer;
    }
    .inactive-day{
        background-color: #e4e9f0;
        color: #b5c0cd;
    }
    .calendar-event{
        position: absolute;
        bottom: 2px;
        right: 2px;
        display: flex;
    }
    .event-item{
        width: 5px;
        height: 20px;
        margin-right: 1px;
    }
</style>
<script>
    const WEEKDAYS = ["Mon", "Tue", "Wed", "Thu", "Fri", "Sat", "Sun"];
    export default {
        name: "Calendar",
        props:['events'],
        data() {
            return {
            selectedDate: this.$dayjs()
            };
        },

        computed: {
            days() {
            return [
                ...this.previousMonthDays,
                ...this.currentMonthDays,
                ...this.nextMonthDays
            ];
            },

            today() {
            return this.$dayjs().format("YYYY-MM-DD");
            },

            weekdays() {
            return WEEKDAYS;
            },

            month() {
            return Number(this.selectedDate.format("M"));
            },

            year() {
            return Number(this.selectedDate.format("YYYY"));
            },

            selectedMonth() {
            return this.selectedDate.format("MMMM YYYY");
            },

            numberOfDaysInMonth() {
            return this.$dayjs(this.selectedDate).daysInMonth();
            },

            currentMonthDays() {
            return [...Array(this.numberOfDaysInMonth)].map((day, index) => {
                return {
                date: this.$dayjs(`${this.year}-${this.month}-${index + 1}`).format(
                    "YYYY-MM-DD"
                ),
                isCurrentMonth: true
                };
            });
            },

            previousMonthDays() {
            const firstDayOfTheMonthWeekday = this.getWeekday(
                this.currentMonthDays[0].date
            );
            const previousMonth = this.$dayjs(`${this.year}-${this.month}-01`).subtract(
                1,
                "month"
            );

            // Cover first day of the month being sunday (firstDayOfTheMonthWeekday === 0)
            const visibleNumberOfDaysFromPreviousMonth = firstDayOfTheMonthWeekday
                ? firstDayOfTheMonthWeekday - 1
                : 6;

            const previousMonthLastMondayDayOfMonth = this.$dayjs(
                this.currentMonthDays[0].date
            )
                .subtract(visibleNumberOfDaysFromPreviousMonth, "day")
                .date();

            return [...Array(visibleNumberOfDaysFromPreviousMonth)].map(
                (day, index) => {
                return {
                    date: this.$dayjs(
                    `${previousMonth.year()}-${previousMonth.month() +
                        1}-${previousMonthLastMondayDayOfMonth + index}`
                    ).format("YYYY-MM-DD"),
                    isCurrentMonth: false
                };
                }
            );
            },

            nextMonthDays() {
            const lastDayOfTheMonthWeekday = this.getWeekday(
                `${this.year}-${this.month}-${this.currentMonthDays.length}`
            );

            const nextMonth = this.$dayjs(`${this.year}-${this.month}-01`).add(1, "month");

            const visibleNumberOfDaysFromNextMonth = lastDayOfTheMonthWeekday
                ? 7 - lastDayOfTheMonthWeekday
                : lastDayOfTheMonthWeekday;

            return [...Array(visibleNumberOfDaysFromNextMonth)].map((day, index) => {
                return {
                date: this.$dayjs(
                    `${nextMonth.year()}-${nextMonth.month() + 1}-${index + 1}`
                ).format("YYYY-MM-DD"),
                isCurrentMonth: false
                };
            });
            }
        },
        mounted(){
            this.selectCurrent()
        },
        methods: {
            getWeekday(date) {
                return this.$dayjs(date).weekday();
            },
            getEvent(date){
                return this.events[this.$dayjs(date).format('YYYY-MM-DD')] || []
            },
            getEventHtml(date){
                const events = this.events[this.$dayjs(date).format('YYYY-MM-DD')];
                let html =''
                for(const idx in events){
                    if(idx>5){
                        return html
                    }
                    const _event = events[idx]
                    html += `<div style='background-color: ${_event.color};text-align:left;border-bottom: 1px solid #fff;'><a class="text-white" href="/training/${_event.type}/${_event.id}" target="_blank" >${_event.title}</a></div>`
                }
                return html
            },
            selectDate(newSelectedDate) {
                this.selectedDate = newSelectedDate;
            },

            selectCurrent() {
                this.selectedDate = this.$dayjs();
                this.$emit('monthChange', {start: this.days[0].date, end: this.days.slice(-1)[0].date})
            },

            selectPrevious() {
                this.selectedDate = this.$dayjs(this.selectedDate).subtract(1, "month");
                this.$emit('monthChange', {start: this.days[0].date, end: this.days.slice(-1)[0].date})
            },

            selectNext() {
                this.selectedDate =  this.$dayjs(this.selectedDate).add(1, "month");
                this.$emit('monthChange', {start: this.days[0].date, end: this.days.slice(-1)[0].date})
            },
        }
    };
</script>