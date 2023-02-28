<template>
    <div>
        <div class="card">
                <basic-table 
                title="Data Orders" 
                :config="{checkbox: false}"
                :data="datas.data" 
                :columns="columns" 
                :total="datas.total"
                :params="params"
                :othercolumns="['email', 'phone']"
                @onChange="loadData"
                @onSearch="Search">
                    <!-- <template v-slot:head-left>
                        <button class="btn btn-primary px-2"><i class='bx bx-filter-alt' ></i></button>
                    </template> -->
                    <template v-slot:head-right>
                        <export-excel :columns="column_export" :data="datas.data" :file-name="`training_event_order_${$dayjs().format('DD_MM_YYYY')}`">Export <img src="/icon/ic_excel.svg" alt=""></export-excel>
                    </template>
                    <template v-slot:title="{ data, item }">
                        <div style="min-width: 200px;white-space: normal !important;">{{ item }}</div>
                    </template>
                    <template v-slot:type_class="{ data, item }">
                        <span>{{ item=='1' ? 'Public' : 'In House' }}</span>
                    </template>
                    <template v-slot:price="{ data, item }">
                        <span>{{ $currency(item) }}</span>
                    </template>
                    <template v-slot:status="{ data, item }">
                        <span class="badge me-1" :class="item=='paid'?'bg-label-success': 'bg-label-warning'">{{ item }}</span>
                    </template>
                    <template v-slot:option="{ data, item }">
                        <nuxt-link :to="`/orders/${data.id}`"><i class='bx bx-show'></i> Detail</nuxt-link>
                    </template>
                </basic-table>
         </div>
    </div>
</template>

<script>
const columns = [
        {
            key: 'name',
            label: 'Name',
            orderable: true,
            searchable: true
        },
        {
            key: 'title',
            label: 'Title',
            orderable: true,
            searchable: true,
            slot: 'title'
        },
        {
            key: 'type_class',
            label: 'Type Class',
            slot: 'type_class'
        },
        {
            key: 'trx_date',
            label: 'Trx Date',
            orderable: true,
            searchable: true
        },
        {
            key: 'quantity',
            orderable: true,
            label: 'Qty',
        },
        {
            key: 'price',
            label: 'Price',
            orderable: true,
            slot: 'price'
        },
        {
            key: 'status',
            label: 'Status',
            slot: 'status'
        },
        {
            key: 'option',
            label: 'Option',
            slot: 'option'
        },
];

const columns2 = [
    {key: 'email', label: 'Email'},
    {key: 'phone', label: 'Phone/Wa'},
    {key: 'account_bank', label: 'No Acc'},
    {key: 'account_name', label: 'Name Acc'},
    {key: 'known_from', label: 'Known'},
    {key: 'position', label: 'Position'},
    {key: 'tf_upload_url', label: 'Upload'},
]

export default {
      head: {
        title: "Data Orders",
      },
      layout: "authenticated",
      data() {
        return {
            datas: [],
            columns: columns,
            params : {
                perpage: 10,
                page: 1
            }
        };
      },
      computed:{
        column_export: function(){
            return [...this.columns, ...columns2]
        }
      },
      mounted(){
        this.loadData(this.params)
      },
      methods: {
        async loadData(params){
            this.params = params
            const {data} = await this.$getOrders({params: params})
            this.datas = data
        },
        async Search(params){
            params = {...params, ...{perpage: 10, page:1}}
            const {data} = await this.$getOrders({params: params})
            this.datas = data
        }
      }
    };
</script>