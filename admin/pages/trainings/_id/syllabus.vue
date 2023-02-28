<template>
    <div>
        <syllabus :data="selSyllabus" @onSuccess="updateSyllabus"></syllabus>
        <subsyllabus :data="selSubSyllabus" @onSuccess="updateSubsyllabus"></subsyllabus>
        <div class="card p-4">
            <div class="card-head d-flex justify-content-between">
                <h4>Add Syllabus</h4>
                <button class="btn btn-info" @click="idxSyl=null" data-bs-toggle="modal" data-bs-target="#syllabus"><i class='bx bx-plus'></i> Add New Syllabus</button>
            </div>
            <hr>
            <div class="card-body">
                <div v-for="(syl, idx) in syllabus" :key="`silabus_${idx}`">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h5 class="mb-0">{{ syl.title }} <span class="btn-active" data-bs-toggle="modal" data-bs-target="#syllabus" @click="idxSyl=idx"><i class='bx bx-edit-alt'></i></span></h5>
                            <span>{{ syl.sub_title }}</span>
                        </div>
                        <div>
                            <span class="btn-active" data-bs-toggle="modal" data-bs-target="#subsyllabus" @click="idxSyl=idx;idxSub=null"><i class='bx bx-plus-circle'></i> Tambah Materi</span>
                        </div>
                    </div>
                    <ul class="list-group py-3 px-2">
                        <li class="border py-2 row" v-for="(sub, idx2) in syl?.details||[]" :key="`subsilabus_${idx2}`">
                            <div class="col-12 col-md-10 px-2">
                                <p class="mb-1"><span class="badge bg-secondary">{{ sub.icon }}</span>&nbsp;<strong>{{ sub.head }}</strong></p>
                                <p>{{ sub.description }}</p>
                            </div>
                            <div class="col-12 col-md-2 py-4 px-3 text-end">
                                <span class="btn-active" data-bs-toggle="modal" data-bs-target="#subsyllabus" @click="idxSyl=idx;idxSub=idx2"><i class='bx bx-edit-alt'></i> Edit</span>
                                <span class="btn-active" @click="removeSubdata(idx, idx2)" style="color: #E74357;"
                                ><i class='bx bx-trash'></i> Delete</span>
                            </div>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import Syllabus from '~/components/modals/syllabus.vue';
import Subsyllabus from '~/components/modals/subsyllabus.vue';
export default {
      components: { Syllabus, Subsyllabus },
      head: {
        title: "Syllabus",
      },
      layout: "authenticated",
      computed:{
        syllabusData(){
            return $orderAsc(this.syllabus)
        },
        selSyllabus(){
            return Object.assign({},{...this.syllabus[this.idxSyl],...{details: null, trx_train_event_id: this.id, idx:this.idxSyl}})
        },
        selSubSyllabus(){
            const syllabus = this.syllabus[this.idxSyl]
            console.log(syllabus)
            return Object.assign({},{...syllabus?.details?.[this.idxSub],...{syllabus_id: syllabus?.id, idx:this.idxSyl, idx2: this.idxSub}})
        }
      },
      data() {
        return {
            id: this.$router.currentRoute.params.id,
            idxSyl: null,
            idxSub: null,
            syllabus:[],
        }
      },
      async mounted(){
        this.$store.commit('SET_BREADCRUMB', `trainings/${this.id}`) 
        await this.$getAllSyllabus(this.id).then(response=>{
            this.syllabus = response.data
        })
      },
      beforeDestroy() {
        this.$store.commit('SET_BREADCRUMB', null)
      },
      methods:{
        removeSubdata(param1, param2){
            const details = this.syllabus[param1].details
            this.$deleteSyllabus(details[param2].id).then(response=>{
                details.splice(param2,1)
            })
        },
        updateSyllabus(param){
            if(param.is_edit){
                let syllabus = this.syllabus[param.idx]
                syllabus.title=param.value.title
                syllabus.sub_title=param.value.sub_title
                syllabus.seq=param.value.seq                
            }else{
                this.syllabus.push(param.value)
            }
        },
        updateSubsyllabus(param){
            if(param.is_edit){
                let syllabus = this.syllabus[param.idx].details[param.idx2]
                syllabus.head=param.value.head
                syllabus.icon=param.value.icon
                syllabus.description=param.value.description
                syllabus.seq=param.value.seq                
            }else{
                this.syllabus[param.idx]?.details.push(param.value)
            }
        }
      }
}
</script>