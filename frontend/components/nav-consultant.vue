<template>
    <div :class="$screen.width<=990 ? 'd-flex justify-content-between' : ''">
        <div v-if="$screen.width>990" class="panel-subhead py-4">
            <div class="icon-subhead">
                <img src="/img/icon/ic_filter.png">
            </div>
            <div class="title-subhead">
                <label>Filters</label>
            </div>
        </div>
        <div v-if="$screen.width<=990" class="search-training" style="width:65%">
            <input type="search" v-model="params.search" @keyup.enter="$emit('onFilter', params)" class="form-control" placeholder="Search...">
            <span @onclick="$emit('onFilter', params)"><i class="fas fa-search"></i></span>
        </div>
        <div class="dropdown">
            <button v-if="$screen.width<=990" type="button" class="btn btn-secondary btn-corner  py-2 dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Filter
                <i class="fa far fa-filter"></i>
            </button>
            <div class="card" :class="$screen.width<=990? 'dropdown-menu training-filter': ''">
            <div v-if="$screen.width>990" class="search-training">
                <input type="search" v-model="params.search" @keyup.enter="$emit('onFilter', params)" class="form-control" placeholder="Search...">
                <span @onclick="$emit('onFilter', params)"><i class="fas fa-search"></i></span>
            </div>
            <hr>
            <div v-if="sorts && sorts.length>0">
                <small><strong>SORT</strong></small>
                <ul class="nav checkbox-menu pt-2">
                    <li v-for="(sort, idx) in sorts" :key="idx" class="py-2">
                        <label style="font-weight: 500;padding-top: 5px;text-transform:capitalize;">
                            <input type="checkbox" :id="`sort_${idx}`" :value="sort" v-model="params.sort" @change="doSort(sort)"> {{ sort }}
                            <span class="checkmark"></span>
                        </label>
                    </li>
                </ul>
                <hr>
            </div>
            <div v-if="categories && categories.length>0">
                <small><strong>CATEGORY</strong></small>
                <ul class="nav checkbox-menu pt-2">
                    <li v-for="(category, idx) in categories" :key="idx" class="py-2">
                        <label style="font-weight: 500;padding-top: 5px;">
                            <input type="checkbox" :id="`filter_${idx}`" v-model="params.filter" :value="category.id" @change="$emit('onFilter', params)"> {{ category.name }}
                            <span class="checkmark"></span>
                        </label>
                    </li>
                </ul>
                <hr>
            </div>
            </div>
        </div>
    </div>
</template>
<script>
    export default {
        name: 'nav-training',
        props: ['sorts', 'categories'],
        data() {
            return {
                params: {
                    filter: [],
                    sort: [],
                    search : '',
                },
                events: []
            }
        },
        mounted(){

        },
        methods: {
            doSort(value){
                this.params.sort = this.params.sort.filter(data=>data==value)
                this.$emit('onFilter', {...this.params,...{sort:value}})
            },
        }
    }
</script>