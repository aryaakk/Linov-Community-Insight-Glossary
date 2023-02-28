<template>
    <div>
        <modal-basic id="syllabus" title="Add Syllabus">
            <form @submit.prevent="submit">
                <div class="mb-3">
                    <label class="form-label">Title</label>
                    <input
                        v-model="form.title"
                        type="text"
                        class="form-control"
                        required
                        placeholder="Input title"
                    />
                </div>
                <div class="mb-3">
                    <label class="form-label">SubTitle</label>
                    <input
                        v-model="form.sub_title"
                        type="text"
                        class="form-control"
                        required
                        placeholder="Input subtitle"
                    />
                </div>
                <div class="mb-3">
                    <label class="form-label">Number Sequence</label>
                    <input
                        v-model="form.seq"
                        type="number"
                        class="form-control"
                        required
                        placeholder="Input sequence"
                    />
                </div>
                <div class="py-3 text-end">
                    <button type="button" ref="expclose" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </form>
        </modal-basic>
    </div>
</template>
<script>
    export default {
        name: 'modals-syllabus',
        props: ['data'],
        data() {
            return {
                form: this.data
            }
        },
        watch: {
            'data': function(value) {
                this.form = value
            },
        },
        methods:{
            async submit(){
                let data, status
                if(this.form?.id){
                     await this.$updateSyllabus(this.form, this.form.id).then(response=>{
                        data = response.data
                        status = response.status
                     })
                }else{
                     await this.$postSyllabus(this.form).then(response=>{
                        data = response.data
                        status = response.status
                     })
                }
                if(status==200||status==201){
                    this.$refs.expclose.click();
                    this.$emit('onSuccess', {is_edit: Boolean(this.form?.id), value: data, idx:this.form.idx, idx2: this.form.idx2})
                    this.form = {
                        title: null,
                        sub_title: null,
                        seq: null,
                    }
                }
            }
        }
    }
</script>