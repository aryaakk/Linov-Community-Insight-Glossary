<template>
    <div id="skills" class="modal fade" tabindex="-1" role="dialog">
            <div class="modal-dialog" role="document">
            <div class="modal-content border-modal">
            <div class="modal-header header-delete">
                Tambah Skill
            </div>
            <form @submit.prevent="submit">
            <div class="modal-body">
            <div class="mb-3">
                <label class="form-label">Cari Skills</label>
                <client-only>
                <multiselect openDirection="bottom" 
                @input="InputSkill"
                :options="skills"
                placeholder="Search skills"
                tag-placeholder="Add this skills"
                :taggable="true"
                @tag="(tag)=>{ this.skills.push(tag);InputSkill(tag)}"
                />
                </client-only>
            </div>
            </div>
            <div>
                <p>{{ skillList.length }} Skills terpilih</p>
                <div>
                    <span class="px-1" v-for="(skill, idx) in skillList">
                        <span class="badge bg-secondary" style="font-size: 15px;">
                        {{ skill }} <span class="px-1 cursor"><i class='fas fa-times' @click="skillList.splice(idx, 1)"></i></span>
                        </span>
                    </span>
                </div>
            </div>
            <div class="card-footer text-right">
                <button ref="educlose" type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                <button type="submit" class="btn btn-primary">Simpan</button>
            </div>
            </form>
        </div>
        </div>
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
                this.skills = this.$pluck(response.data,'name')
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
                this.skillList =[]
            }
        }
    }
</script>