<template>
    <div class="container">
        <div class="row pt-50 mt-4">
            <div class="col-lg-4 ">
                <div class="panel-subhead mt-4">
                    <div class="icon-subhead">
                        <img src="/img/icon/ic_user.png">
                    </div>
                    <div class="title-subhead">
                        <label>Consultant Submission</label>
                    </div>
                </div>
                <div class="card p-0 pt-1 mt-4">
                    <nav-setting></nav-setting>
                 </div>
            </div>
            <div class="col-md-8">
                <div class="card p-4 pt-0" style="margin-top: 60px;min-height: 250px;">
                    <div class="form-group px-2 pt-4 d-flex justify-content-between">
                        <h4 class="title-head"><strong>Submission History</strong></h4>
                        <NuxtLink v-if="!haveWaitingSubmission" class="pt-3" style="color: #45A1EF;" to="/profile/verification/submission">
                            <i class="fas fa-plus"></i> New Submission
                        </NuxtLink>
                    </div>
                    <hr class="px-2" style="border: 1px solid #F0F0F0;">

                    <div class="card border d-flex justify-content-between cursor p-3" v-for="(sub, idx) in submissions" :key="idx">
                        <dl>
                            <dt>Tanggal Pengajuan :</dt>
                            <dd class="pt-2"> {{ $dayjs(sub.trx_date).format("D MMMM YYYY pukul HH:MM") }}</dd>
                        </dl> 
                        <dl>
                            <dt>Status Pengajuan :</dt>
                            <dd>
                                <span class="label " :class="statuses[sub.status]">{{ sub.status.toUpperCase() }}</span>
                            </dd>
                        </dl> 
                    </div>
                    <div v-if="submissions.length<=0">
                        <p class="text-center">No Data!</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
<style scoped>
.border{
    border: 1px solid #E6E6E6;
    border-radius: 4px;
}
</style>

<script>
    export default {
        layout: "default",
        middleware: ['authenticated', 'completed'],
        head: {
            title: "Verifikasi Data",
        },
        data() {
            return {
                statuses : {
                    waiting: 'label-warning',
                    approved: 'label-success',
                    rejected: 'label-danger'
                },
                submissions: [],
            }
        },
        computed: {
            haveWaitingSubmission(){
                let status = this.$pluck(this.submissions, 'status')

                return status.includes('waiting') || status.includes('approved')
            }
        },
        async mounted() {
            await this.$getConsultantSubmission().then(response=>{
                this.submissions = response.data.data
            })
        },
    }
</script>