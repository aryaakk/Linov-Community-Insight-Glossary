<template>
        <div id="educations" class="modal fade" tabindex="-1" role="dialog">
            <div class="modal-dialog" role="document">
            <div class="modal-content border-modal">
            <div class="modal-header header-delete">
                Tambah Pendidikan
            </div>
            <form @submit.prevent="submit">
            <div class="modal-body">
                <div class="mb-3">
                    <label class="form-label">Universitas</label>
                    <client-only>
                    <multiselect name="university_id" openDirection="bottom" v-model="form.university_id" track-by="id" label="name" :options="universities" 
                    tag-placeholder="Add this university" 
                    :taggable="true"
                    @tag="(tag)=>{ universities.push({name: tag});form.university_id={name: tag};}"/>
                    </client-only>
                </div>
                <div class="mb-3">
                    <label class="form-label">Jurusan</label>
                    <client-only>
                    <multiselect name="major_id" openDirection="bottom" v-model="form.major_id" track-by="id" label="name" :options="majors" 
                    tag-placeholder="Add this major" :taggable="true"
                    :clear-on-select="false"
                    @tag="(tag)=>{ majors.push({name: tag});form.major_id={name: tag}}"/>
                    </client-only>
                </div>
                <div class="mb-3">
                    <label class="form-label">Jenjang Pendidikan</label>
                    <client-only>
                    <multiselect name="title_degree_id" openDirection="bottom" v-model="form.title_degree_id" track-by="id" label="degree" :options="degrees" 
                    tag-placeholder="Add this degree" 
                    :taggable="true"
                    :clear-on-select="false"
                    @tag="(tag)=>{ degrees.push({degree: tag});form.title_degree_id={degree: tag}}"/>
                    </client-only>
                </div>
                <div class="mb-3 d-flex">
                    <div>
                        <label class="form-label">Tanggal Mulai</label>
                        <input
                            v-model="form.start_date"
                            type="date"
                            class="form-control"
                            placeholder="Input tgl mulai"
                            required
                        />
                    </div>
                    <div class="pl-2">
                        <label class="form-label">Tanggal Selesai</label>
                        <input
                            v-model="form.end_date"
                            type="date"
                            class="form-control"
                            placeholder="Input tgl mulai"
                            :disabled="form.is_current"
                        />
                    </div>
                </div>
                <div class="mb-3">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" v-model="form.is_current" id="is_current2" @change="form.is_current?form.end_date=null:''">
                        <label class="form-check-label" for="is_current2">
                            Is Current
                        </label>
                    </div>
                </div>
            </div>
            <div class="modal-footer text-right">
                    <button ref="btnclose" type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </form>
        </div>
        </div>
    </div>
</template>
<script>
    export default {
        name: 'modals-eductaions',
        props: ['idx','data'],
        data() {
            return {
                form: {
                    university_id: null,
                    major_id: null,
                    title_degree_id: null,
                    start_date: null,
                    end_date: null,
                    is_current: false,
                    other_university: null,
                    other_major: null,
                    other_title: null,
                },
                universities: [],
                majors: [],
                degrees: [],
            }
        },
        async mounted(){
            await this.$getUniversity().then(response=>{
                this.universities = response.data
            })
            await this.$getMajors().then(response=>{
                this.majors = response.data
            })
            await this.$getDegrees().then(response=>{
                this.degrees = response.data
            })
        },
        watch: {
            'idx': function(value) {
                const data = this.data[value]
                this.form = Object.assign({},data)
                this.form.university_id = this.universities.find(univ=>univ.id==data?.university_id)
                this.form.major_id = this.majors.find(major=>major.id==data?.major_id)
                this.form.title_degree_id = this.degrees.find(degree=>degree.id==data?.title_degree_id)
            },
            'form.university_id': function(value) {
                if(!value?.id){
                    this.form.other_university = value?.name
                }
            },
            'form.major_id': function(value) {
                if(!value?.id){
                    this.form.other_major = value?.name
                }
            },
            'form.title_degree_id': function(value) {
                if(!value?.id){
                    this.form.other_title = value?.degree
                }
            },
        },
        methods:{
            submit(){
                this.$refs.btnclose.click();
                this.$emit('getEducation', this.form)
                this.form = {
                        university_id: null,
                        major_id: null,
                        title_degree_id: null,
                        start_date: null,
                        end_date: null,
                        is_current: false,
                        other_university: null,
                        other_major: null,
                        other_title: null,
                }
            }
        }
    }
</script>