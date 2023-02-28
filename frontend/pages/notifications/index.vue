<template>
    <div>
        <div class="main-home pt-100"  ref="notif">
            <div class="container">
                <div class="card p-4" style="min-height: 300px;" >
                    <div class="d-flex justify-content-between py-3">
                        <h4>Notification</h4>
                        <div>
                            <button class="btn btn-sm btn-primary" @click="readAll"><i class="fas fa-book-open"></i>&nbsp;Read All</button>
                        </div>
                    </div>
                    <table class="w-100">
                        <tbody>
                           
                            <tr :style="{'background-color': notif.read_at ? '#ccc' : '', 'border': notif.read_at ? '1px solid #ffff' : '1px solid #ccc'}" v-for="(notif, idx) in notifications">
                                <td width="30" align="center"><input type="checkbox" v-model="selected" :value="notif.id"></td>
                                <td align="center" width="8%" style="cursor: pointer;"  @click="readAll(notif)">
                                    <img :src="notif.sender?.photo ? notif.sender?.photo : '/img/home/user.png'" class="img-circle avatar-thread" alt="">
                                </td>
                                <td class="pointer" style="cursor: pointer;" @click="readAll(notif)">
                                    <h5><b>{{ notif.message_title }}</b></h5>
                                    <p v-html="notif.message"></p>
                                </td>
                                <td align="center">
                                   {{ notif.human_date }}
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
export default {
    head: {
        title: "Notifications",
    },
    layout: "default",
    middleware: "completed",
    data() {
        return {
            notifications: [],
            loading: false,
            stopInfinite: false,
            selected: [],
        }
    },
    mounted(){
        window.addEventListener("scroll", this.handleScroll)
    },
    unmounted() {
        window.removeEventListener("scroll", this.handleScroll)
    },
    async fetch(){
        await this.$getNotifications().then(response=>{
            this.notifications = response.data.data
            this.stopInfinite = this.notifications.length >= response.data.total
        })
    },
    methods: {
        async handleScroll() {
            if (!this.stopInfinite && !this.loading && this.$refs.notif && Math.floor(this.$refs.notif.getBoundingClientRect().bottom) <= window.innerHeight) {
                await this.$getNotifications().then(response=>{
                    this.notifications = [...this.notifications, ...response.data.data]
                    this.loading = false
                    this.stopInfinite = this.notifications.length >= response.data.total
                })
            }
        },
        readAll(notif=null){
            if(notif?.read_at){
                this.$router.push(notif.path)
                return
            }
            
            this.$readNotifications(notif ? [notif.id] : this.selected).then(response=>{
                for(const id of this.selected){
                    const idx = this.notifications.findIndex(notif=>notif.id==id)
                    this.notifications[idx].read_at = this.$dayjs().format('Y-m-d HH:MM')
                }
                this.selected = []
            })

            if(notif){
                this.$router.push(notif.path)
            }
        },
    }
}
</script>