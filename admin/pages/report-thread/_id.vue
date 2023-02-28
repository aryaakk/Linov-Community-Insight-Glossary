<template>
        <div>
        <div class="card p-3">
            <div class="card-header">
                <h4>Detail Report Thread</h4>
            </div>
            <hr>
            <div class="card-body">
                <div>
                    <Bar :chart-data="chartData" :chart-options="chartOptions" :height="400" />
                </div>
                <div class="detail-data pt-4 px-3">
                    <table class="w-100">
                    <tr>
                        <td colspan="2" ><h6>Judul Thread</h6></td>
                    </tr>
                    <tr>
                        <td class="pb-3">{{ thread.title }}</td>
                    </tr>
                    <tr>
                        <td colspan="2" ><h6>Content Thread</h6></td>
                    </tr>
                    <tr>
                        <td class="pb-3">{{ thread.description }}</td>
                    </tr>
                    <tr>
                        <td colspan="2" ><h6>Author</h6></td>
                    </tr>
                    <tr>
                        <td class="pb-3">{{ thread.author?.name }}</td>
                    </tr>
                    <tr>
                        <td colspan="2" ><h6>Create At</h6></td>
                    </tr>
                    <tr>
                        <td class="pb-3">{{ thread.human_date }}</td>
                    </tr>
                    <tr>
                        <td colspan="2" ><h6>Link Thread</h6></td>
                    </tr>
                    <tr>
                        <td class="pb-3">
                            <a :href="`http://community.linovhr.com/threads/${thread.id}`" target="_blank">{{ `http://community.linovhr.com/threads/${thread.id}` }}</a>
                        </td>
                    </tr>
                    </table>
                </div>
                <div v-if="datas.data">
                    <basic-table 
                    title="Detail Reporter"
                    :data="datas.data" 
                    :columns="columns" 
                    :total="datas.total"
                    :params="params"
                    :config="{checkbox: false}"
                    @onChange="loadData"
                    @onSearch="Search">
                    <template v-slot:created_at="{ data, item }">
                        {{ $dayjs(item).format('DD MMM YYYY HH:MM') }}
                    </template>
                </basic-table>
                </div>
            </div>
        </div>
    </div>
</template>
<style>
    .detail-data h6{
        border-bottom: 1px solid #E6E6E6;
        margin-right: 20px;
        padding-bottom: 6px;
    }
</style>
<script>
import { Bar } from 'vue-chartjs/legacy'
import {
  Chart as ChartJS,
  Title,
  Tooltip,
  Legend,
  BarElement,
  CategoryScale,
  LinearScale
} from 'chart.js'
ChartJS.register(Title, Tooltip, Legend, BarElement, CategoryScale, LinearScale)

const columns = [
        {
            key: 'name',
            label: 'Name',
            orderable: true,
            searchable: true
        },
        {
            key: 'created_at',
            label: 'Report Date',
            orderable: true,
            searchable: false,
            slot: 'created_at'
        },
        {
            key: 'level',
            label: 'Level',
            orderable: true,
            searchable: true
        },
        {
            key: 'reason',
            label: 'Reason',
            orderable: false,
            searchable: true
        },
        {
            key: 'option',
            label: 'Option',
            slot: 'option'
        }
]
export default {
      head: {
        title: "Detail Report Thread",
      },
      layout: "authenticated",
      components: {
            Bar
      },
      data() {
        return {
            id: this.$router.currentRoute.params.id,
            chartOptions: {
                responsive: true,
                maintainAspectRatio: false
            },
            chartData: {
                labels: [],
                datasets: [
                    {
                        label: 'Report Data',
                        backgroundColor: '#D3EBFF',
                        data: []
                    }
                ]
            },
            thread: {},
            columns: columns,
            datas: [],
            params : {
                perpage: 10,
                page: 1
            }
        }
      },
      async mounted(){
        await this.$getReportThreadChart(this.id).then(response=>{
            const data = response.data
            this.chartData.labels = data.labels
            this.chartData.datasets[0].data = data.datas
        })
        await this.$getOneThread(this.id).then(response=>{
            this.thread = response.data
        })
        this.loadData(this.params)
      },
      methods: {
        async loadData(params){
            this.params = params
            const {data} = await this.$getReportThreadDetail(this.id, {params: params})
            this.datas = data
        },
        async Search(params){
            params = {...params, ...{perpage: 10, page:1}}
            const {data} = await this.$getReportThreadDetail(this.id, {params: params})
            this.datas = data
        }
      }
}
</script>