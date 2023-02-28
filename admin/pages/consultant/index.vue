<template>
    <div>
        <div class="card">
                <basic-table 
                title="Data Konsultan" 
                :data="datas.data" 
                :columns="columns" 
                :total="datas.total"
                :params="params"
                @onChange="loadData"
                @onSearch="Search"
                @onCellClick="(data)=>{ $router.push(`/consultant/${data.id}`) }" 
                @onCheckbox="(select)=>{selected=select}">
                    <!-- <template v-slot:head-left>
                        <button class="btn btn-primary px-2"><i class='bx bx-filter-alt' ></i></button>
                    </template> -->
                    <template v-slot:head-right>
                        <nuxt-link to="/consultant/create">
                        <button class="btn btn-info px-2"><i class='bx bx-plus'></i> Add New Consultant</button>
                        </nuxt-link>
                        <button class="btn btn-danger px-2" v-show="selected.length>0" @click="Delete"><i class='bx bx-trash'></i></button>
                    </template>
                    <template v-slot:is_active="{ data, item }">
                        <span class="badge bg-label-success me-1" v-if="item=='1'">Active</span>
                        <span class="badge bg-label-danger me-1" v-else>InActive</span>
                    </template>
                    <template v-slot:category="{ data, item }">
                        <span>{{ item?.name }}</span>
                    </template>
                    <template v-slot:option="{ data, item }">
                        <nuxt-link :to="`/consultant/${data.id}`"><i class='bx bx-show'></i> Detail</nuxt-link><br>
                        <nuxt-link :to="`/consultant/${data.id}/edit`"><i class='bx bx-edit'></i> Edit</nuxt-link><br>
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
            key: 'email',
            label: 'Email',
            orderable: true,
            searchable: true
        },
        {
            key: 'other_position',
            label: 'Position',
            orderable: false,
            searchable: false
        },
        {
            key: 'category',
            label: 'Spesialisasi',
            orderable: false,
            searchable: false,
            slot: 'category'
        },
        {
            width: 100,
            key: 'is_active',
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
        title: "Master Consultant",
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
            const {data} = await this.$getConsultants({params: params})
            this.datas = data
        },
        async Delete(){
            const {status} = await this.$deleteConsultants(this.selected)
            if(status==204){
                this.selected=[]
                this.$toast.success("Master data berhasil dihapus!");
                this.loadData(this.params)
            }
        },
        async Search(params){
            params = {...params, ...{perpage: 10, page:1}}
            const {data} = await this.$getConsultants({params: params})
            this.datas = data
        }
      }
    };
</script>