<template>
    <div class="table-responsive text-nowrap px-3">
        <h5 class="card-header text-uppercase">{{ title }}</h5>
        <div class="row">
            <div class="p-4 col-md-8">
                <form @submit.prevent="doSearch" class="d-flex">
                <div class="input-group input-group-merge">
                    <span class="input-group-text" :class="!search ? 'search' : ''" ><i class="bx bx-search"></i></span>
                    <input type="text" ref="search" class="form-control" :class="!search ? 'search' : ''" @focus="search=true" @blur="search=false" placeholder="Search...">
                </div>
                </form>
                <div class="px-2">
                <slot name="head-left"></slot>
                </div>
            </div>
            <div class="p-4 col-md-4 text-end">
                <slot name="head-right"></slot>
            </div>
        </div>
        <div class="table-basic">
        <table class="table ">
        <thead>
            <tr>
            <th width="15" v-if="conf.checkbox"><input type="checkbox" ref="allcheck" @click="toggleCheckIds" class="form-check-input" style="height: 18px;width: 18px;"></th>
            <th width="15" class="px-1">No</th>
            <th v-for="(column, idx) in columns" :key="column.key" :width="column.width">
                <span>{{ column.label }}</span>
                <span class="table-icon" v-if="column.orderable" v-on:click="doOrder(column.key)">
                    <i class='bx' :class="orders[column.key]=='asc' ? 'bx-sort-down' : 'bx-sort-up'"></i>
                </span>
            </th>
            <th v-if="!keys.includes('option')">Option</th>
            </tr>
        </thead>
        <tbody class="table-border-bottom-0">
            <tr v-for="(data, idx) in data" :key="`col_${idx}`">
            <td v-if="conf.checkbox"><input type="checkbox" class="form-check-input" v-model="selected" :value="data.id"></td>
            <td class="px-1"><strong>{{ parseInt(idx)+1+pageNumber }}.</strong></td>
            <td v-for="(key, idx) in keys" :key="`data_${key}_${idx}`">
                <slot
                :name="key"
                :item="$isNestedObject(key) ? $getNestedObject(data, key) : data[key]"
                :data="data">
                {{ $isNestedObject(key) ? $getNestedObject(data, key) : data[key] }}
                </slot>
            </td>
            <td v-if="!keys.includes('option')" width="10" class="pointer text-end" @click="$emit('onCellClick', data)">
                <span class="link"><i class='bx bx-edit-alt'></i> Edit</span>
            </td>
            </tr>
            <tr v-if="data && data.length<=0">
                <td colspan="100%" class="text-center"><h6>No data :'(</h6></td>
            </tr>
        </tbody>
        </table>
        </div>
        <pagination v-if="total" :perpage="params.perpage" :total="total" @pageChange="updateParam"></pagination>
    </div>
</template>
<style>
    .search{
        background: #F4F4F7 !important;
    }
</style>
<script>
    export default {
        name: "basic-table",
        props: ['data', 'columns', 'title', 'config','params','total', 'othercolumns'],
        computed: {
            keys: function(){
                return this.$getKeys(this.columns, 'key')
            },
            pageNumber: function(){
                return parseInt(this.params.page)>1 ? parseInt(this.params.perpage)*parseInt(this.params.page)-parseInt(this.params.perpage) : 0
            }
        },
        watch: {
            selected: function (val) {
                this.$emit('onCheckbox', val)
            },
        },
        data(){
            return {
                search: false,
                selected : [],
                orders : [],
                conf: {
                    checkbox: typeof this.config?.checkbox!='undefined' ? this.config?.checkbox : true,
                    number: this.config?.numbering,
                }
            }
        },
        methods: {
            toggleCheckIds(){
                if(this.$refs.allcheck.checked){
                    this.selected = this.$getKeys(this.data, 'id')
                }else{
                    this.selected =[]
                }
            },
            doSearch(){
                const params = {
                    search  : this.$refs.search.value,
                    columns: [...this.$getKeys(this.columns.filter(column=>column.searchable),'key'),...this.othercolumns||[]]
                }

                this.$emit('onSearch',params)
            },
            doOrder(key){
                if(!this.orders[key]){
                    this.orders =[]
                }
                this.orders[key] = this.orders[key]=='asc' ? 'desc' : 'asc'
                let orders = []
                for(const key in this.orders){
                    orders.push(`${key}:${this.orders[key]}`)
                }
                this.$emit('onChange', {...this.params, ...{orders: orders.toString(), page: 1}})
            },
            updateParam(params){
                this.$emit('onChange', {...this.params, ...params})
            }
        }
    }
</script>