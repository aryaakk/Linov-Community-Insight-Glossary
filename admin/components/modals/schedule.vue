<template>
    <div>
        <modal-basic id="schedule" title="Add Schedule">
            <form @submit.prevent="submit" class="row">
                <div class="mb-3 col-6">
                    <label class="form-label">Tanggal Mulai</label>
                    <input
                        type="date"
                        class="form-control"
                        placeholder="Tanggal Mulai"
                        v-model="form.date"
                    />
                </div>
                <div class="mb-3 col-6">
                    <label class="form-label">Tanggal Akhir</label>
                    <input
                        type="date"
                        class="form-control"
                        placeholder="Tanggal Akhir"
                        v-model="form.end_date"
                    />
                </div>
                <div class="mb-3 col-6">
                    <label class="form-label">Waktu Mulai</label>
                    <input
                        type="time"
                        class="form-control"
                        placeholder="Waktu Mulai"
                        v-model="form.start_hour"
                    />
                </div>
                <div class="mb-3 col-6">
                    <label class="form-label">Waktu Akhir</label>
                    <input
                        type="time"
                        class="form-control"
                        placeholder="Waktu Akhir"
                        v-model="form.end_hour"
                    />
                </div>
                <div class="py-3 text-end">
                    <button type="button" ref="schclose" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </form>
        </modal-basic>
    </div>
</template>
<script>
    export default {
        name: 'modals-social-media',
        props: ['data', 'idx'],
        data() {
            return {
                form: {
                    date:null,
                    end_date: null,
                    start_hour: null,
                    end_hour: null,
                },
                socials: []
            }
        },
        watch: {
            'idx': function(value) {
                this.form = Object.assign({},this.data[value])
            },
        },
        methods: {
            submit(){
                this.$refs.schclose.click();
                this.$emit('getSchedule', Object.assign({},this.form))
                this.form = {
                    date:null,
                    end_date: null,
                    start_hour: null,
                    end_hour: null,
                }
            },
        }
    }
</script>