<template>
    <div class="pt-100">
        <div class="container p-5">
            <div class="d-flex justify-content-center p-4" style="background: #F0F0F0;">
                <div class="cursor">
                    <span class="badge-rounded">1</span> &nbsp;Detail Class
                </div>
                <div class="seperate-foot" style="margin-top: 10px;margin-right:10px;margin-left:10px;"></div>
                <div class="cursor">
                    <span class="badge-rounded badge-active">2</span>&nbsp;Done
                </div>
            </div>
            <div class="card" style="padding: 10%;">
                <div class="text-center">
                    <img src="/img/training/success.png" alt="">
                    <h2>Selamat Pendaftaran Anda berhasil. </h2>
                    <p>Cek kotak masuk email Anda untuk melihat detail pendaftaran. Sampai bertemu di Webinar!</p>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
    export default {
        head: {
            title: 'Order Training & Event',
        },
        layout: "default-training",
        middleware: ['authenticated'],
    }
</script>