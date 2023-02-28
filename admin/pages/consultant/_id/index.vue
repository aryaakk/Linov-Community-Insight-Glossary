<template>
    <div>
        <div class="card mb-4">
            <div class="card-header">
                <h6 >Detail Consultant</h6>
                <hr>
            </div>
            <div class="card-body detail-data">
                <table class="w-100">
                    <tr>
                        <td><h6>Verification Status</h6></td>
                        <td>:&nbsp;<span class="badge bg-success">{{  form.is_consultant=='1'?'Approve':'Waiting' }}</span></td>
                    </tr>
                    <tr>
                        <td><h6>Active Status</h6></td>
                        <td>:&nbsp;<span class="badge " :class="form.is_active=='1' ? 'bg-success' : 'bg-danger'">{{  form.is_active=='1'?'Active':'Inactive' }}</span></td>
                    </tr>
                    <tr>
                        <td colspan="2" class="py-3"></td>
                    </tr>
                    <tr>
                        <td colspan="2" ><h6>Nama</h6></td>
                    </tr>
                    <tr>
                        <td class="pb-3">{{ form.name }}</td>
                    </tr>
                    <tr>
                        <td><h6>Email</h6></td>
                        <td><h6>Phone Number</h6></td>
                    </tr>
                    <tr>
                        <td class="pb-3">{{ form.email }}</td>
                        <td class="pb-3">{{ form.phone }}</td>
                    </tr>
                    <tr>
                        <td><h6>Country</h6></td>
                        <td><h6>Province</h6></td>
                    </tr>
                    <tr>
                        <td class="pb-3">{{ form.country_id?.name }}</td>
                        <td class="pb-3">{{ form.state_id?.name }}</td>
                    </tr>
                    <tr>
                        <td><h6>City</h6></td>
                        <td><h6>Postal Code</h6></td>
                    </tr>
                    <tr>
                        <td class="pb-3">{{ form.city_id?.name }}</td>
                        <td class="pb-3">{{ form.postal_code }}</td>
                    </tr>
                    <tr>
                        <td colspan="2" ><h6>Spesialisasi / Bidang yang diajukan</h6></td>
                    </tr>
                    <tr>
                        <td class="pb-3">{{ form.category_name }}</td>
                    </tr>
                    <tr>
                        <td colspan="2" ><h6>About Me</h6></td>
                    </tr>
                    <tr>
                        <td class="pb-3">{{ form.about_me }}</td>
                    </tr>
                    <tr>
                        <td colspan="2">
                            <div>
                                <h6>Social Media</h6>
                                <a :href="social.url" target="_blank" v-for="(social, idx) in form.socials||[]" :key="idx"><img :src="social.icon" width="30">{{ social.url }}</a>
                            </div>
                            <div class="py-2">
                                <h6>Experience</h6>
                                <ul class="list-group">
                                    <li class="list-group-item d-flex justify-content-between" v-for="(exp, idx) in form?.experiences||[]" :key="idx">
                                        <div>
                                            <h4><b>{{ exp.company}}</b></h4>
                                            <span><b>{{ exp.position}}</b></span><br>
                                            <small>{{ $dayjs(exp.start_date).format('MMM-YYYY') }}&nbsp;-&nbsp;{{ $dayjs(exp.end_date).format('MMM-YYYY') }}</small>
                                        </div>
                                    </li>
                                </ul>
                            </div>
                            <div class="py-2">
                                <h6>Education</h6>
                                <ul class="list-group">
                                    <li class="list-group-item d-flex justify-content-between" v-for="(edu, idx) in form?.educations||[]" :key="idx">
                                    <div>
                                        <h6 style="font-weight: 600;">{{ edu.university_id?.name || edu.university_name || edu.other_university }}</h6>
                                        <span>{{  edu.major_id?.name || edu.major_name || edu.other_major }} - {{  edu.title_degree_id?.degree || edu.degree_name || edu.other_title }}</span><br>
                                        <small>{{ $dayjs(edu.start_date).format('MMM-YYYY') }}&nbsp;-&nbsp;{{ $dayjs(edu.end_date).format('MMM-YYYY') }}</small>
                                    </div>
                                </li>
                                </ul>
                            </div>
                            <div class="py-2">
                                <h6>Certifications & Licenses</h6>
                                <ul class="list-group">
                                    <li class="list-group-item" v-for="(cert, idx) in form.certificates||[]" :key="idx">
                                        <div>
                                            <h6 style="font-weight: 600;">{{ cert.title }} - {{ cert.organization }}</h6>
                                            <span>{{  cert.link  }}</span><br>
                                            <small>{{ $dayjs(cert.start_date).format('MMM-YYYY') }}&nbsp;-&nbsp;{{ $dayjs(cert.end_date).format('MMM-YYYY') }}</small>
                                        </div>
                                    </li>
                                </ul>
                            </div>
                            <div class="py-2">
                                <h6>Skills</h6>
                                <div class="p-4" >
                                    <span class="px-1" v-for="(skill, idx) in form?.skills||[]" :key="idx">
                                        <span class="badge bg-secondary" style="font-size: 15px;">
                                        {{ skill }} <span class="px-1"></span>
                                        </span>
                                    </span>
                                </div>
                            </div>
                        </td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
</template>
<style>
    .detail-data h6{
        border-bottom: 1px solid #E6E6E6;
        margin-right: 20px;
        padding-bottom: 6px;
    }
</style>
<script>
    export default {
      head: {
        title: "Detail Consultant",
      },
      layout: "authenticated",
      data() {
        return {
            id: this.$router.currentRoute.params.id,
            form: {
                processing: false,
                errors: [],
            },
        };
      },
      async mounted(){
        const {data} = await this.$getOneConsultant(this.id)
        this.form = data
      }
    }
</script>