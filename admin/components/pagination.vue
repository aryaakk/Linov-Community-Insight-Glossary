<template>
    <div>
        <nav class="d-flex pt-4 px-3 justify-content-between">
            <div>
                <strong>Page {{ params.page }} of {{ pageCount }}</strong>
            </div>
            <ul class="pagination">
            <!-- <li class="page-item" :class="hasFirst ? 'disabled' : ''" @click="First"><span class="page-link pointer"><i class='bx bx-chevrons-left'></i></span></li> -->
            <li class="page-item" :class="hasFirst ? 'disabled' : ''" @click="Prev"><span class="page-link pointer" :class="!hasFirst? 'primary': ''">Prev</span></li>
            <li class="page-item" @click="GoTo(item.label)" :class="item.active ? 'active' : ''"  v-for="(item, idx) in items" :key="idx"><span class="page-link pointer">{{ item.label }}</span></li>
            <li class="page-item" :class="hasLast ? 'disabled' : ''" @click="Next"><span class="page-link pointer" :class="!hasLast? 'primary': ''">Next</span></li>
            <!-- <li class="page-item" :class="hasLast ? 'disabled' : ''" @click="Last"><span class="page-link pointer"><i class='bx bx-chevrons-right'></i></span></li> -->
            </ul>
            <div class="d-flex">
                <strong class="pt-2">Perpage:&nbsp;</strong>
                <div>
                    <select class="form-select" v-model="params.perpage" @change="$emit('pageChange', params)">
                        <option>10</option>
                        <option>20</option>
                        <option>40</option>
                        <option>80</option>
                        <option>100</option>
                    </select>
                </div>
            </div>
        </nav>
    </div>
</template>
<style>
    .pointer{
        cursor: pointer;
    }
    .disabled{
        opacity: .65;
    }
    .primary{
        background: #696cff;
        color: #ffff;
    }
</style>
<script>
    export default {
        name: "pagination",
        props: ['page', 'perpage', 'total'],
        computed: {
            pageCount: function () {
                let perpage = this.params.perpage || 10
                let total   = this.total || 0
                return Math.ceil(total/perpage)
            },
            items() {
                let valPrev = this.params.page > 1 ? (this.params.page - 1) : 1 
                let valNext = this.params.page < this.pageCount ? (this.params.page + 1) : this.pageCount
                let extraPrev = valPrev === 3 ? 2 : null
                let extraNext = valNext === (this.pageCount - 2) ? (this.pageCount - 1) : null
                let dotsBefore = valPrev > 3 ? 2 : null
                let dotsAfter = valNext < (this.pageCount - 2) ? (this.pageCount - 1) : null
                let output = []
                for (let i = 1; i <= this.pageCount; i += 1) {
                if ([1, this.pageCount, this.params.page, valPrev, valNext, extraPrev, extraNext, dotsBefore, dotsAfter].includes(i)) {
                    output.push({
                    label: i,
                    active: this.params.page === i,
                    disable: [dotsBefore, dotsAfter].includes(i)
                    })
                }
                }
                return output
            },
            hasFirst() {
                return (this.params.page === 1)
            },
            hasLast() {
                return (this.params.page === this.pageCount)
            },
        },
        data(){
            return {
                params: {
                    page: this.page || 1,
                    perpage: this.perpage || 10
                }
            }
        },
        methods: {
            Prev(){
                if (!this.hasFirst) {
                    this.params.page=this.params.page-1
                    this.$emit('pageChange', this.params)
                }
            },
            Next(){
                if (!this.hasLast) {
                    this.params.page=this.params.page+1
                    this.$emit('pageChange', this.params)
                }
            },
            First(){
                if (!this.hasFirst) {
                    this.params.page=1
                    this.$emit('pageChange', this.params)
                }
            },
            Last(){
                if (!this.hasLast) {
                    this.params.page=this.pageCount
                    this.$emit('pageChange', this.params)
                }
            },
            GoTo(page){
                this.params.page=page
                this.$emit('pageChange', this.params)
            },
        }
    }
</script>