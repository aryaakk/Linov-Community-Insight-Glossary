<template>
        <div id="experiences" class="modal fade" tabindex="-1" role="dialog">
            <div class="modal-dialog" role="document">
            <div class="modal-content border-modal">
            <div class="modal-header header-delete">
                Tambah Pengalaman
            </div>
            <form @submit.prevent="submit">
            <div class="modal-body">
                <div class="mb-3">
                    <label class="form-label">Company Name</label>
                    <input
                        v-model="form.company"
                        type="text"
                        class="form-control"
                        required
                        placeholder="Input company"
                    />
                </div>
                <div class="mb-3">
                    <label class="form-label">Position</label>
                    <input
                        v-model="form.position"
                        type="text"
                        class="form-control"
                        required
                        placeholder="Input position"
                    />
                </div>
                <div class="mb-3 d-flex">
                    <div>
                        <label class="form-label">Tanggal Mulai</label>
                        <input
                            v-model="form.start_date"
                            type="date"
                            class="form-control"
                            required
                            placeholder="Input tgl mulai"
                        />
                    </div>
                    <div class="ml-2">
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
                        <input class="form-check-input" type="checkbox" v-model="form.is_current" id="is_current" @change="form.is_current?form.end_date=null:''">
                        <label class="form-check-label" for="is_current">
                            Is Current
                        </label>
                    </div>
                </div>
            </div>
            <div class="py-3 text-right">
                    <button type="button" ref="btnclose" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                    <button type="submit" class="btn btn-primary">Simpan</button>
            </div>
            </form>
            </div>
        </div>
    </div>
</template>
<script>
    export default {
        name: 'modals-experiences',
        props: ['idx', 'data'],
        data() {
            return {
                form: {
                    company: null,
                    position: null,
                    start_date: null,
                    end_date: null,
                    is_current: false,
                },
            }
        },
        watch: {
            'idx': function(value) {
                this.form = Object.assign({},this.data[value])
            },
        },
        methods:{
            submit(){
                this.$refs.btnclose.click();
                this.$emit('getExperience', this.form)
                this.form = {
                    company: null,
                    position: null,
                    start_date: null,
                    end_date: null,
                    is_current: false,
                }
            }
        }
    }
</script>