<template>
    <div>
        <div class="card">
                <basic-table 
                title="Data Trainers" 
                :data="datas.data" 
                :columns="columns" 
                :total="datas.total"
                :params="params"
                @onChange="loadData"
                @onSearch="Search"
                @onCellClick="(data)=>{ $router.push(`/trainers/${data.id}`) }" 
                @onCheckbox="(select)=>{selected=select}">
                    <!-- <template v-slot:head-left>
                        <button class="btn btn-primary px-2"><i class='bx bx-filter-alt' ></i></button>
                    </template> -->
                    <template v-slot:head-right>
                        <nuxt-link to="/trainers/create">
                        <button class="btn btn-info px-2"><i class='bx bx-plus'></i> Add New Trainers</button>
                        </nuxt-link>
                        <button class="btn btn-danger px-2" v-show="selected.length>0" @click="Delete"><i class='bx bx-trash'></i></button>
                    </template>
                    <template v-slot:name="{ data, item }">
                        <div class="d-flex">
                            <img :src="data.photo_url" class="rounded-circle" style="width: 30px;height:30px;object-fit: cover;">&nbsp;
                            <strong>{{ item }}</strong>
                        </div>
                    </template>
                    <template v-slot:is_active="{ data, item }">
                        <span class="badge bg-label-success me-1" v-if="item=='1'">Active</span>
                        <span class="badge bg-label-danger me-1" v-else>InActive</span>
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
            slot: 'name',
            orderable: true,
            searchable: true
        },
        {
            key: 'company',
            label: 'Company',
            orderable: true,
            searchable: true
        },
        {
            key: 'industry_name',
            label: 'Industri',
            searchable: true
        },
        {
            width: 100,
            key: 'is_active',
            label: 'Status',
            slot: 'status'
        }
    ];

    export default {
      head: {
        title: "Master Trainer",
      },
      layout: "authenticated",
      data() {
        return {
            datas: [],
            columns: columns,
            selected: [],
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
            const {data} = await this.$getTrainers({params: params})
            this.datas = data
        },
        async Delete(){
            const {status} = await this.$deleteTrainers(this.selected)
            if(status==204){
                this.selected=[]
                this.$toast.success("Master data berhasil dihapus!");
                this.loadData(this.params)
            }
        },
        async Search(params){
            params = {...params, ...{perpage: 10, page:1}}
            const {data} = await this.$getTrainers({params: params})
            this.datas = data
        }
      }
    };
</script>