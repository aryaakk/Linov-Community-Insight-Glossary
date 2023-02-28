<template>
    <div>
        <div class="card p-3">
            <div class="card-header">
                <h4>Detail Order</h4>
                <hr>
            </div>
            <div class="card-body detail-data">
                <table class="w-100">
                    <tr>
                        <td colspan="2" ><h6>Nama</h6></td>
                    </tr>
                    <tr>
                        <td class="pb-3">{{ order.name}}</td>
                    </tr>
                    <tr>
                        <td width="50%" ><h6>Email</h6></td>
                        <td width="50%" ><h6>Phone Number</h6></td>
                    </tr>
                    <tr>
                        <td class="pb-3">{{ order.email }}</td>
                        <td class="pb-3">{{ order.phone }}</td>
                    </tr>
                    <tr>
                        <td width="50%" ><h6>Company</h6></td>
                        <td width="50%" ><h6>Position</h6></td>
                    </tr>
                    <tr>
                        <td class="pb-3">{{ order.company }}</td>
                        <td class="pb-3">{{ order.position }}</td>
                    </tr>
                    <tr>
                        <td colspan="2" ><h6>Mendapatkan Informasi melalui</h6></td>
                    </tr>
                    <tr>
                        <td class="pb-3">{{ order.known_from }}</td>
                    </tr>
                    <tr>
                        <td width="50%" ><h6>Title</h6></td>
                        <td width="50%" ><h6>Activity</h6></td>
                    </tr>
                    <tr>
                        <td class="pb-3">{{ order.title }}</td>
                        <td class="pb-3">{{ order.type }}</td>
                    </tr>
                    <tr>
                        <td colspan="2" ><h6>Location/Via</h6></td>
                    </tr>
                    <tr>
                        <td class="pb-3">{{ order.location }}</td>
                    </tr>
                    <tr>
                        <td width="50%" ><h6>Price</h6></td>
                        <td width="50%" ><h6>Discount</h6></td>
                    </tr>
                    <tr>
                        <td class="pb-3">{{ $currency(order.price) }}</td>
                        <td class="pb-3">{{ $currency(order.discount) }}</td>
                    </tr>
                    <tr>
                        <td width="50%" ><h6>Quantity</h6></td>
                        <td width="50%" ><h6>Total</h6></td>
                    </tr>
                    <tr>
                        <td class="pb-3">{{ order.quantity }}</td>
                        <td class="pb-3">{{ order.total_price }}</td>
                    </tr>
                    <tr>
                        <td width="50%" ><h6>Start Date</h6></td>
                        <td width="50%" ><h6>End Date</h6></td>
                    </tr>
                    <tr>
                        <td class="pb-3">{{ order.start_date }}</td>
                        <td class="pb-3">{{ order.end_date }}</td>
                    </tr>
                    <tr>
                        <td colspan="2" ><h6>Nama Pemilik Rekening</h6></td>
                    </tr>
                    <tr>
                        <td class="pb-3">{{ order.account_name }}</td>
                    </tr>
                    <tr>
                        <td width="50%" ><h6>No Rekening</h6></td>
                        <td width="50%" ><h6>Bank</h6></td>
                    </tr>
                    <tr>
                        <td class="pb-3">{{ order.account_bank }}</td>
                        <td class="pb-3">{{ order.bank_name }}</td>
                    </tr>
                    <tr>
                        <td colspan="2" ><h6>Order Status: &nbsp;<span class="badge " :class="order.status=='paid'? 'bg-success': 'bg-warning'">{{ order?.status ? order?.status.toUpperCase() : null }}</span></h6></td>
                    </tr>
                    <tr>
                        <td colspan="2" ><h6>Bukti Pembayaran</h6></td>
                    </tr>
                    <tr>
                        <td class="pb-3"><a :href="order.tf_upload_url" target="_blank">{{order.tf_upload}}</a></td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
</template>
<style>
    .detail-data h6{
        border-bottom: 1px solid #E6E6E6;
        margin-right: 20px;
        padding-bottom: 6px;
    }
</style>
<script>
export default {
    head: {
    title: "Detail Orders",
    },
    layout: "authenticated",
    data() {
        return {
            order: {}
        }
    },
    async mounted(){
        await this.$getOneOrders(this.$router.currentRoute.params.id).then(response=>{
            this.order = response.data
        })
    }
}
</script>