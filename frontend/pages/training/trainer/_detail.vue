<template>
    <div class="pt-100">
       <div class="container" v-if="trainer">
        <div class="row mt-5 p-0">
            <div class="col-md-3">
                <client-only>
                    <nav-training :sorts="[]" :categories="[]"></nav-training>
                </client-only>
            </div>
            <div class="col-md-9">
                <div class="panel-subhead py-4 d-flex justify-content-between">
                    <div class="d-flex">
                        <div class="icon-subhead">
                            <img src="/img/icon/ic_user.png">
                        </div>
                        <div class="title-subhead">
                            <label>Trainer Profile</label>
                        </div>
                    </div>
                </div>
                <div class="card p-5" ref="content">
                    <div class="d-flex justify-content-between">
                        <div class="d-flex">
                            <img :src="trainer.photo_url" style="max-width: 100px;">
                            <div class="px-3">
                                <h4>{{ trainer.name }}</h4>
                                <p>{{ trainer.motto }}</p>
                                <div >
                                    <a :href="social.url" target="_blank" v-for="(social, idx) in trainer.socials" :key="idx"><img :src="social.icon" width="30"></a>
                                </div>
                            </div>
                        </div>
                        <div>
                            <button @click="printTrainer" class="btn-sm btn-secondary" style="background: #E1F1FC;color: #41A7EB;">
                            <i class="fas fa-download"></i> &nbsp;Download CV
                            </button>
                        </div>
                    </div>
                    <div class="py-4">
                        <h5 class="mt-5"><strong>About Me</strong></h5>
                        <p>{{ trainer.description }}</p>
                        <h5 class="mt-5"><strong>Personal Experience</strong></h5>
                        <div>
                            <table class="w-100">
                                <tbody>
                                <tr :style="{background: (idx+1)%2==0 ? '#F0F0F0' : ''}" v-for="(exp, idx) in trainer.experiences" :key="idx">
                                    <td class="py-2 text-center" width="60px" style="vertical-align: center;">
                                        <button class="px-2" style="background: #E6E6E6;border: 0px;border-radius: 4px;">
                                        <small><i class="fas fa-minus"></i></small>
                                        </button>
                                    </td>
                                    <td class="py-2">
                                    <p><strong>{{ exp.name }}</strong></p>
                                    <span>{{ $dayjs(exp.start_date).format('MMM YYYY') }} - {{ exp.is_current==='1' ? 'Present' : $dayjs(exp.end_date).format('MMM YYYY') }}</span><br>
                                    <span>{{ exp.city_name }}, {{ exp.state_name }}, {{ exp.country_name }}</span>
                                    </td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                        <h5 class="mt-5"><strong>Educations</strong></h5>
                        <div>
                            <table class="w-100">
                                <tbody>
                                <tr v-for="(edu, idx) in trainer.educations" :key="idx">
                                    <td class="py-2 text-center" width="60px" style="vertical-align: center;">
                                        <button class="px-2 " style="background: #E6E6E6;border: 0px;border-radius: 4px;">
                                        <small><i class="fas fa-minus"></i></small>
                                        </button>
                                    </td>
                                    <td class="py-2">
                                    <p><strong>{{ edu.university_name ? edu.university_name : edu.other_university }}</strong></p>
                                    <span>{{ $dayjs(edu.start_date).format('MMM YYYY') }} - {{ edu.is_current==='1' ? 'Present' : $dayjs(edu.end_date).format('MMM YYYY') }}</span><br>
                                    <span>Jakarta Selatan, Jakarta Raya, Indonesia</span>
                                    </td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                        <h5 class="mt-5"><strong>Skills</strong></h5>
                        <div>
                            <span class="px-1" v-for="(skill, idx) in trainer.skills" :key="idx">
                                <span class="label label-default" style="font-size: 14px;">{{ skill }}</span>
                            </span>
                        </div>
                        <h5 class="mt-5"><strong>Sertification & License</strong></h5>
                        <div>
                            <table class="w-100">
                                <tbody>
                                <tr v-for="(cert, idx) in trainer.certificates" :key="idx">
                                    <td class="py-2 text-center" width="60px" style="vertical-align: center;">
                                        <button class="px-2 " style="background: #E6E6E6;border: 0px;border-radius: 4px;">
                                        <small><i class="fas fa-minus"></i></small>
                                        </button>
                                    </td>
                                    <td class="py-2">
                                    <p><strong>{{ cert.title }}</strong> <strong>({{ cert.organization }})</strong></p>
                                    <span>{{ $dayjs(cert.start_date).format('MMM YYYY') }} - {{ cert.is_novalidate ? 'No Date' : $dayjs(cert.end_date).format('MMM YYYY') }}</span><br>
                                    </td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                        <h5 class="mt-5"><strong>Course</strong></h5>
                        <div class="row px-2">
                            <panel-training v-for="(training, idx) in courses" :key="idx" :data="training"></panel-training>
                        </div>
                        <h5 class="mt-5"><strong>Provider</strong></h5>
                        <div class="row px-4">
                            <panel-provider :data="provider" v-for="(provider, idx) in trainer.providers" :key="idx"></panel-provider>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        </div>
    </div>
</template>

<script>
    export default {
        head() {
            return {
                title: `Profile Trainer - ${this.trainer?.name}`,
            }
        },
        layout: "default-training",
        data() {
            return {
                trainer: null,
                courses: [],
            }
        },
        async fetch(){
            await this.$getOneTrainer({id: this.$route.params.detail}).then(response => {
                this.trainer = response.data
            }).catch(error=>{
                return this.$nuxt.error({ statusCode: error.response.status, message:  error.response.statusText })
            })
            await this.$getTrainerCourse({id: this.$route.params.detail}).then(response => {
                this.courses = response.data
            })
        },
        methods: {
            printTrainer() {
                    var a = window.open('', '', 'height=1000, width=1000');
                    a.document.write('<html>');
                    a.document.write('<body >');
                    a.document.write(this.$refs.content.innerHTML);
                    a.document.write('</body></html>');
                    a.document.close();
                    a.print();
            }
        }
    }
</script>