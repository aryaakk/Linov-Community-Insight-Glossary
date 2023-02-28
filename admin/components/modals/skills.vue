<template>
    <div>
        <modal-basic id="skills" title="Add Skills">
            <form @submit.prevent="submit">
                <div class="mb-3">
                    <label class="form-label">Cari Skills</label>
                    <v-select
                        type="text"
                        placeholder="Pilih Skills"
                        label="name"
                        v-model="selected"
                        :options="skills"
                        :clearable="false"
                        :reduce="(option) => option.name"
                        :reset-on-options-change="true"
                        @input="InputSkill"
                    />
                </div>
                <div>
                    <p>{{ skillList.length }} Skills terpilih</p>
                    <div>
                        <span class="px-1" v-for="(skill, idx) in skillList">
                            <span class="badge bg-secondary">
                            {{ skill }} <span class="px-1"><i class='bx bx-x' @click="skillList.splice(idx, 1)"></i></span>
                            </span>
                        </span>
                    </div>
                </div>
                <div class="card-footer text-end">
                    <button ref="educlose" type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </form>
        </modal-basic>
    </div>
</template>
<script>
    export default {
        name: 'modals-skills',
        props: ['data'],
        data() {
            return {
                skills: [],
                selected: null,
                skillList: []
            }
        },
        watch: {
            'data': function(value) {
                this.skillList = this.data ? this.data.slice() : []
            },
        },
        async mounted(){
            await this.$getSkills().then(response=>{
                this.skills = response.data
            })
        },
        methods:{
            InputSkill(data){
                if(data!=null && !this.skillList.find(skill=>{ return skill==data})){
                    this.skillList.push(data)
                }
                this.selected=null
            },
            submit(){
                this.$refs.educlose.click();
                this.$emit('getSkills', this.skillList)
            }
        }
    }
</script>