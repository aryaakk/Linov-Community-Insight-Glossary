<template>
    <div class="pt-100">
        <div class="container p-5">
            <div class="d-flex justify-content-center p-4" style="background: #F0F0F0;">
                <div class="cursor">
                    <span class="badge-rounded">1</span> &nbsp;Detail Class
                </div>
                <div class="seperate-foot" style="margin-top: 10px;margin-right:10px;margin-left:10px;"></div>
                <div class="cursor">
                    <span class="badge-rounded badge-active">2</span>&nbsp;Waiting Order
                </div>
            </div>
            <div class="card" style="padding: 10%;padding-top: 5%;">
            <div>
                <h4 class="mt-5"><b>Total Yang Harus Dibayar</b></h4>
                <hr>
                <table class="table table-bordered w-100">
                    <tbody>
                    <tr>
                        <td class="py-4 pl-1">
                            <span class="badge badge-default">{{ order.type }}</span>&nbsp;
                            {{ order.title }} .
                            <b>(x {{ order.quantity}})</b>
                        </td>
                        <td width="10%" class="text-light">
                            <b>{{ $currency(order.price*order.quantity) }}</b>
                        </td>
                    </tr>
                    <tr style="background: #F5FAFE;">
                        <td  class="py-4 pl-1">
                            Potongan Harga
                        </td>
                        <td class="text-light">
                            <b>{{ $currency(order.discount) }}</b>
                        </td>
                    </tr>
                    <tr style="background: #E1F1FC">
                        <td class="py-4 pl-1">
                            <b>Sub Total</b>
                        </td>
                        <td class="text-light">
                            <b>{{ $currency(order.total_price) }}</b>
                        </td>
                    </tr>
                    <tr v-if="order.tf_upload">
                        <td class="py-4 pl-1"><b>Bukti Pembayaran</b></td>
                        <td class="text-light">
                            <a :href="order.tf_upload_url" target="_blank">Download</a>
                        </td>
                    </tr>
                    </tbody>
                </table>
                <h4 class="mt-5"><b>Cara Pembayaran</b></h4>
                <hr>
                <div class="text-center" style="border: 1px solid #9ED2F5;border-radius: 8px;">
                    <p class="py-3">
                        Transfer melalui ATM, Internet Banking atau Mobile Banking sejumlah {{ $currency(order.total_price) }} ke salah satu rekening berikut
                    </p>
                    <div class="py-3 d-flex justify-content-center">
                        <img src="/img/bank/bca.png" alt="">
                        <span class="pl-2">
                            No. Rek 5012914109258<br>
                            A.N PT. Linov Roket Prestasi
                        </span>
                    </div>
                    <p class="py-3">Konfirmasi pembayaran anda dengan klik pada tombol di bawah atau melalui link yang kami kirimkan ke anda</p>
                </div>
                <div class="mt-5 text-center">
                    <img v-if="tf_upload" :src="tf_upload ? $getPreview(tf_upload) : ''" class="py-3 w-100">

                    <input type="file" style="display: none;" ref="tf_upload" @change="prepareUpload">
                    <button class="btn btn-primary btn-block py-3" @click="tf_upload ? uploadFile() : $refs.tf_upload.click()">{{ tf_upload ? 'Kirim' : 'Upload' }} Bukti Pembayaran</button>
                    <button class="btn btn-default btn-block py-3 mt-4">Konfirmasi Pembayaran ke Admin</button>
                </div>
            </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        head: {
            title: 'Detail Payment Training & Event',
        },
        layout: "default-training",
        middleware: ['authenticated'],
        data() {
            return {
                order: {},
                tf_upload : null,
                errors: [],
            }
        },
        async fetch(){
            const id = this.$route.params.id;
            await this.$getOneOrder({id: id}).then(response => {
                this.order = response.data;
            }).catch(error=>{
                 return this.$nuxt.error({ statusCode: error.response.status, message:  error.response.statusText })
            });
        },
        methods: {
            prepareUpload(e) {
                this.tf_upload = e.target.files[0];
            },
            uploadFile() {
                const formData = new FormData();
                formData.append('tf_upload', this.tf_upload);
                this.$uploadOrder(formData, this.order.id).then(response => {
                    this.tf_upload = null;
                    this.$nuxt.refresh()
                }).catch(error => {
                    this.errors = error.response.data.errors;
                });
            }
        },
    }
</script>