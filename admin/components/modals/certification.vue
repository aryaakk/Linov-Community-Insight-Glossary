<template>
    <div>
        <modal-basic id="certificate" title="Add Certification">
            <form @submit.prevent="submit">
                <div class="mb-3">
                    <label class="form-label">Judul</label>
                    <input
                        v-model="form.title"
                        type="text"
                        class="form-control"
                        required
                        placeholder="Input company"
                    />
                </div>
                <div class="mb-3">
                    <label class="form-label">Organisasi Penerbit</label>
                    <input
                        v-model="form.organization"
                        type="text"
                        class="form-control"
                        required
                        placeholder="Input organisasi"
                    />
                </div>
                <div class="mb-3">
                    <label class="form-label">Link Sertifikat</label>
                    <input
                        v-model="form.link"
                        type="text"
                        class="form-control"
                        required
                        placeholder="Input sertifikat"
                    />
                </div>
                <div class="mb-3 row">
                    <div class="col-6">
                        <label class="form-label">Tanggal Terbit</label>
                        <input
                            v-model="form.start_date"
                            type="date"
                            class="form-control"
                            required
                            placeholder="Input tgl terbit"
                        />
                    </div>
                    <div class="col-6">
                        <label class="form-label">Tanggal Kadaluarsa</label>
                        <input
                            v-model="form.end_date"
                            type="date"
                            class="form-control"
                            placeholder="Input tgl kadaluarsa"
                            :disabled="form.is_novalidate"
                        />
                    </div>
                </div>
                <div class="mb-3">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" v-model="form.is_novalidate" id="is_novalidate" @change="form.is_novalidate?form.end_date=null:''">
                        <label class="form-check-label" for="is_novalidate">
                            Is Current
                        </label>
                    </div>
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
        name: 'modals-certificate',
        props: ['idx', 'data'],
        data() {
            return {
                form: {
                    title: null,
                    organization: null,
                    link: null,
                    start_date: null,
                    end_date: null,
                    is_novalidate: false,
                },
                locations: []
            }
        },
        watch: {
            'idx': function(value) {
                this.form = Object.assign({},this.data[value])
            },
        },
        methods:{
            submit(){
                this.$refs.expclose.click();
                this.$emit('getCertificate', this.form)
                this.form = {
                    title: null,
                    organization: null,
                    link: null,
                    start_date: null,
                    end_date: null,
                    is_novalidate: false,
                }
            }
        }
    }
</script>