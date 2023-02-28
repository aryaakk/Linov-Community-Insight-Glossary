<template>
    <div>
        <div class="card">
                <basic-table 
                title="Report Thread" 
                :data="datas.data" 
                :columns="columns" 
                :total="datas.total"
                :params="params"
                :config="{checkbox: false}"
                @onChange="loadData"
                @onSearch="Search"
                @onCellClick="(data)=>{ $router.push(`/report-thread/${data.id}`) }" 
                @onCheckbox="(select)=>{selected=select}">
                    <!-- <template v-slot:head-left>
                        <button class="btn btn-primary px-2"><i class='bx bx-filter-alt' ></i></button>
                    </template> -->
                    <template v-slot:head-right>

                    </template>
                    <template v-slot:created_at="{ data, item }">
                        {{ $dayjs(item).format('DD MMM YYYY HH:MM') }}
                    </template>
                    <template v-slot:deleted_at="{ data, item }">
                        <a href="#" @click="toggleVisibility(data.id)" v-if="!item" class="badge me-1" :class="`bg-label-info`"><i class='bx bx-world'></i>&nbsp; Published</a>
                        <a href="#" @click="toggleVisibility(data.id)" v-else class="badge me-1" :class="`bg-label-warning`">
                        <i class='bx bx-low-vision'></i>&nbsp;Unpublished</a>
                    </template>
                    <template v-slot:option="{ data, item }">
                        <nuxt-link :to="`/report-thread/${data.id}`"><i class='bx bx-show'></i> Detail</nuxt-link><br>
                    </template>
                </basic-table>
         </div>
    </div>
</template>

<script>
    const columns = [
        {
            key: 'title',
            label: 'Thread',
            orderable: true,
            searchable: true
        },
        {
            key: 'name',
            label: 'User',
            orderable: true,
            searchable: true
        },
        {
            key: 'created_at',
            label: 'Tanggal Posting',
            orderable: true,
            searchable: true,
            slot: 'created_at'
        },
        {
            key: 'total_report',
            label: 'Total Report',
            orderable: true,
            searchable: true
        },
        {
            width: 100,
            key: 'deleted_at',
            label: 'Status',
            slot: 'deleted_at'
        },
        {
            key: 'option',
            label: 'Option',
            slot: 'option'
        }
    ];

    export default {
      head: {
        title: "Report Thread",
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
            const {data} = await this.$getReportThread({params: params})
            this.datas = data
        },
        async Search(params){
            params = {...params, ...{perpage: 10, page:1}}
            const {data} = await this.$getReportThread({params: params})
            this.datas = data
        },
        async toggleVisibility(id){
            if(!confirm("Are you sure to change visibility?")){
                return
            }
            await this.$toggleVisibleThread(id)
            this.loadData(this.params)
        }
      }
    };
</script>