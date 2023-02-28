<template>
    <div>
        <div class="card">
                <basic-table 
                title="Events" 
                :data="datas.data" 
                :columns="columns" 
                :total="datas.total"
                :params="params"
                @onChange="loadData"
                @onSearch="Search"
                @onCellClick="(data)=>{ $router.push(`/events/${data.id}`) }" 
                @onCheckbox="(select)=>{selected=select}">
                    <!-- <template v-slot:head-left>
                        <button class="btn btn-primary px-2"><i class='bx bx-filter-alt' ></i></button>
                    </template> -->
                    <template v-slot:head-right>
                        <nuxt-link to="/events/create">
                        <button class="btn btn-info px-2"><i class='bx bx-plus'></i> Add New Event</button>
                        </nuxt-link>
                        <button class="btn btn-danger px-2" v-show="selected.length>0" @click="Delete"><i class='bx bx-trash'></i></button>
                    </template>
                    <template v-slot:title="{ data, item }">
                        <div style="min-width: 180px;white-space: normal !important;">{{ item }}</div>
                    </template>
                    <template v-slot:category="{ data, item }">
                        <span class="badge me-1" :style="{'background-color': item.color_bg, 'color':item.color}">
                            {{ item.name }}</span>
                    </template>
                    <template v-slot:status="{ data, item }">
                        <span class="badge bg-label-success me-1">{{ item }}</span>
                    </template>
                    <template v-slot:option="{ data, item }">
                        <nuxt-link :to="`/events/${data.id}`"><i class='bx bx-show'></i> Detail</nuxt-link><br>
                        <nuxt-link :to="`/events/${data.id}/edit`"><i class='bx bx-edit-alt'></i> Edit</nuxt-link><br>
                        <nuxt-link :to="`/events/${data.id}/syllabus`"><i class='bx bx-book-open'></i> Syllabus</nuxt-link>
                    </template>
                </basic-table>
         </div>
    </div>
</template>

<script>
    const columns = [
        {
            key: 'title',
            label: 'Title',
            searchable: true,
            orderable: true,
            slot: 'title'
        },
        {
            key: 'type',
            label: 'Type',
            orderable: true,
            searchable: true
        },
        {
            key: 'level',
            label: 'Level',
            orderable: true,
            searchable: true
        },
        {
            key: 'category',
            label: 'Category',
            searchable: false,
            slot: 'category'
        },
        {
            key: 'published_date',
            label: 'Publish At',
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
        title: "Master Event",
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
            const {data} = await this.$getEvents({params: params})
            this.datas = data
        },
        async Delete(){
            const {status} = await this.$deleteTrainEvents(this.selected)
            if(status==204){
                this.selected=[]
                this.$toast.success("Master data berhasil dihapus!");
                this.loadData(this.params)
            }
        },
        async Search(params){
            params = {...params, ...{perpage: 10, page:1}}
            const {data} = await this.$getEvents({params: params})
            this.datas = data
        }
      }
    };
</script>