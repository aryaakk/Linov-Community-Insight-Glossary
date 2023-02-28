<template>
        <div>
        <div class="card p-3">
            <div class="card-header d-flex justify-content-between">
                <h4>Detail Verifikasi</h4>
                <div v-if="submission.status=='waiting'">
                    <button @click="verifyData('rejected')" class="btn btn-danger"><i class="bx bx-x"></i> Reject</button>
                    <button @click="verifyData('approved')" class="btn btn-primary"><i class="bx bx-check"></i> Verifikasi</button>
                </div>
            </div>
            <hr>
            <div class="card-body detail-data">
                <table class="w-100">
                    <tr>
                        <td colspan="2" ><h6>Nama Lengkap</h6></td>
                    </tr>
                    <tr>
                        <td class="pb-3">{{ submission.name }}</td>
                    </tr>
                    <tr>
                        <td><h6>Email</h6></td>
                        <td><h6>Phone Number</h6></td>
                    </tr>
                    <tr>
                        <td class="pb-3">{{ submission.email }}</td>
                        <td class="pb-3">{{ submission.phone }}</td>
                    </tr>
                    <tr>
                        <td><h6>Country</h6></td>
                        <td><h6>Province</h6></td>
                    </tr>
                    <tr>
                        <td class="pb-3">{{ submission.country_name }}</td>
                        <td class="pb-3">{{ submission.state_name }}</td>
                    </tr>
                    <tr>
                        <td><h6>City</h6></td>
                        <td><h6>Postal Code</h6></td>
                    </tr>
                    <tr>
                        <td class="pb-3">{{ submission.city_name }}</td>
                        <td class="pb-3">{{ submission.postal_code }}</td>
                    </tr>
                    <tr>
                        <td colspan="2"><h6>Spesialisasi / bidang yang diajukan</h6></td>
                    </tr>
                    <tr>
                        <td class="pb-3">{{ submission.categories ? $pluck(submission.categories, 'name').toString() : '' }}</td>
                    </tr>
                    <tr>
                        <td colspan="2"><h6>Alasan Pengajuan</h6></td>
                    </tr>
                    <tr>
                        <td class="pb-3">{{ submission.reason }}</td>
                    </tr>
                    <tr>
                        <td colspan="2"><h6>Experiences</h6></td>
                    </tr>
                    <tr>
                        <td class="pb-3">
                            <ul class="list-group">
                                <li class="list-group-item" v-for="(exp, idx) in submission.experiences" :key="idx">
                                    <div>
                                        <h5>{{ exp.company}}</h5>
                                        <span>{{ exp.position}}</span><br>
                                        <small>{{ $dayjs(exp.start_date).format('MMM-YYYY') }}&nbsp;-&nbsp;{{ $dayjs(exp.end_date).format('MMM-YYYY') }}</small>
                                    </div>
                                </li>
                            </ul>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="2"><h6>Educations</h6></td>
                    </tr>
                    <tr>
                        <td class="pb-3">
                            <ul class="list-group">
                                <li class="list-group-item" v-for="(edu, idx) in submission.educations" :key="idx">
                                    <div>
                                        <h5>{{ (edu.university_id?.name ? edu.university_id?.name : edu.university_name) || edu.other_university }}</h5>
                                        <span>
                                            {{  (edu.major_id?.name ? edu.major_id?.name : edu.major_name) || edu.other_major }} - 
                                            {{  (edu.title_degree_id?.degree ? edu.title_degree_id?.degree : edu.degree_name) || edu.other_title }}</span><br>
                                        <small>{{ $dayjs(edu.start_date).format('MMM-YYYY') }}&nbsp;-&nbsp;{{ $dayjs(edu.end_date).format('MMM-YYYY') }}</small>
                                    </div>
                                </li>
                            </ul>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="2"><h6>Skills</h6></td>
                    </tr>
                    <tr>
                        <td class="pb-3">
                            {{  submission.skills }}
                        </span>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="2"><h6>Certifications & Licenses</h6></td>
                    </tr>
                    <tr>
                        <td class="pb-3">
                            <ul class="list-group">
                                <li class="list-group-item" v-for="(cert, idx) in submission.certificates" :key="idx">
                                    <div>
                                        <h5>{{ cert.title }} - {{ cert.organization }}</h5>
                                        <span>{{  cert.link  }}</span><br>
                                        <small>{{ $dayjs(cert.start_date).format('MMM-YYYY') }}&nbsp;-&nbsp;{{ $dayjs(cert.end_date).format('MMM-YYYY') }}</small>
                                    </div>
                                </li>
                            </ul>
                        </td>
                    </tr>
                    <tr v-for="(file, idx) in submission.attachments">
                        <td colspan="2" class="pt-3">
                            <h6>{{ fileTitles[file.category] }}</h6>
                            <a :href="file.file_url" target="_blank">{{ file.file_attach }}</a>
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
        title: "Detail Training",
      },
      layout: "authenticated",
      data() {
        return {
            id: this.$router.currentRoute.params.id,
            fileTitles:{
                '1': 'Curiculum Vitae', 
                '2': 'KTP Scan',
                '3': 'Surat Keterangan/ Ijazah',
                '4': 'Pass Foto'
            },
            submission: {},
        }
      },
      async mounted(){
        await this.$getOneSubmission(this.id).then(response=>{
            this.submission = response.data
        })
      },
      methods:{
        verifyData(status){
            if(!confirm(`Are you sure to ${status}?`)){
                return
            }

            this.$verifySubmission({'status': status}, this.id).then(response=>{
                if(response.status==200){
                    this.$toast.success(`Data berhasil ${status=='approved'? 'di Approve' : 'di Reject'}!`);
                    this.$router.push(`/verification`)
                }
            })
        }
      }
}
</script>