<template>
    <div>
        <div class="card">
                <basic-table 
                title="Data Verifikasi" 
                :data="datas.data" 
                :columns="columns" 
                :total="datas.total"
                :params="params"
                :config="{checkbox: false}"
                @onChange="loadData"
                @onSearch="Search"
                @onCellClick="(data)=>{ $router.push(`/verification/${data.id}`) }" 
                @onCheckbox="(select)=>{selected=select}">
                    <!-- <template v-slot:head-left>
                        <button class="btn btn-primary px-2"><i class='bx bx-filter-alt' ></i></button>
                    </template> -->
                    <template v-slot:head-right>

                    </template>
                    <template v-slot:status="{ data, item }">
                        <span class="badge me-1" :class="`bg-label-${statusClass[item]}`">{{ item }}</span>
                    </template>
                    <template v-slot:option="{ data, item }">
                        <nuxt-link :to="`/verification/${data.id}`"><i class='bx bx-show'></i> Detail</nuxt-link><br>
                    </template>
                </basic-table>
         </div>
    </div>
</template>

<script>
    const columns = [
        {
            key: 'name',
            label: 'Nama',
            orderable: true,
            searchable: true
        },
        {
            key: 'email',
            label: 'Email',
            orderable: true,
            searchable: true
        },
        {
            key: 'trx_date',
            label: 'Tgl Pengajuan',
            orderable: true,
            searchable: true
        },
        {
            width: 100,
            key: 'status',
            label: 'Status',
            slot: 'status'
        },
        {
            key: 'option',
            label: 'Option',
            slot: 'option'
        }
    ];

    export default {
      head: {
        title: "Verifikasi",
      },
      layout: "authenticated",
      data() {
        return {
            datas: [],
            columns: columns,
            selected: [],
            statusClass: {
                'waiting' : 'warning',
                'approved': 'success',
                'rejected': 'danger'
            },
            params : {
                perpage: 10,
                page: 1
            }
        };
      },
      mounted(){
        this.loadData(this.params)
      },
      methods: {
        async loadData(params){
            this.params = params
            const {data} = await this.$getSubmissions({params: params})
            this.datas = data
            console.log(this.datas)
        },
        async Search(params){
            params = {...params, ...{perpage: 10, page:1}}
            const {data} = await this.$getSubmissions({params: params})
            this.datas = data
        }
      }
    };
</script>