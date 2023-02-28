<template>
    <div :class="$screen.width<=990 ? 'd-flex justify-content-between' : ''">
        <div id="modal-calendar" class="modal fade" tabindex="-1" role="dialog">
            <div class="modal-dialog" role="document">
                <div class="modal-content border-modal">
                   <div class="modal-body">
                    <calendar :events="events" @monthChange="loadCalender"/>
                   </div>
                </div>
            </div>
        </div>
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
            <div v-if="classes && classes.length>0">
                <small><strong>CLASS</strong></small>
                <ul class="nav checkbox-menu pt-2">
                    <li v-for="(cls, idx) in classes" :key="idx" class="py-2">
                        <label style="font-weight: 500;padding-top: 5px;text-transform:capitalize;">
                            <input type="checkbox" :id="`class_${idx}`" :value="cls" v-model="params.class" @change="doClass(cls)"> {{ cls }}
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
            <div>
                <small><strong>SCHEDULE</strong></small>
                <div class="pt-3">
                <button data-toggle="modal" data-target="#modal-calendar" class="btn btn-secondary btn-block" style="background: #E1F1FC;color: #41A7EB;"><i class="fas fa-calendar mr-1"></i> Show Schedule</button>
                </div>
            </div>
            </div>
        </div>
    </div>
</template>
<script>
    export default {
        name: 'nav-training',
        props: ['sorts', 'categories','classes'],
        data() {
            return {
                params: {
                    filter: [],
                    sort: [],
                    class: [],
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
            doClass(value){
                this.params.class = this.params.class.filter(data=>data==value)
                this.$emit('onFilter', {...this.params,...{class:value}})
            },
            async loadCalender(param){
                await this.$getCalendar(param).then(response=>{
                    this.events = response.data
                })
            }
        }
    }
</script>