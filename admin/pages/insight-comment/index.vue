<template>
    <div>
        <confirm-basic ref="confirm" title="Apakah yakin?"  @confirm="doAction" :modal="$refs.confirm"/>
        <div class="card">
                <basic-table 
                title="Insight Comments" 
                :data="datas.data" 
                :columns="columns" 
                :total="datas.total"
                :params="params"
                @onChange="loadData"
                @onSearch="Search"
                @onCheckbox="(select)=>{selected=select}">
                    <template v-slot:head-right>
                        <form @submit.prevent="$toggleModal($refs.confirm.$el)" class="d-flex">
                            <select v-model="action" name="name" class="form-select">
                                <option value="">Pilih</option>
                                <option value="reject">Tolak</option>
                                <option value="approved">Setujui</option>
                                <option value="spam">Tandai Sebagai Spam</option>
                                <option value="delete">Hapus</option>
                            </select>&nbsp;
                            <button class="btn btn-primary px-2">Apply</button>
                        </form>
                    </template>
                    <template v-slot:status="{ data, item }">
                        <span class="badge me-1" :class="`bg-label-${colors[item]}`">
                            {{ item }}
                        </span>
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
            label: 'Date',
            orderable: true,
            searchable: true
        },
        {
            key: 'comment',
            label: 'Comment',
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
            width: 100,
            key: 'option',
            label: 'Options',
            slot: 'option'
        }
    ];

    export default {
      head: {
        title:"Insight Comments",
      },
      layout: "authenticated",
      data() {
        return {
            datas: [],
            columns: columns,
            selected: [],
            action: '',
            colors: {
                reject: 'danger',
                approved: 'success',
                pending: 'info',
                spam : 'warning'
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
            const {data} = await this.$getInsComment({params: params})
            this.datas = data
        },
        async doAction(){
            await this.$doInsComment({ids: this.selected}, this.action).then(response=>{
                this.$toast.success("Data berhasil di "+this.action);
                this.loadData(this.params)
            })
        },
        async Search(params){
            params = {...params, ...{perpage: 10, page:1}}
            const {data} = await this.$getInsComment({params: params})
            this.datas = data
        }
      }
    };
</script>