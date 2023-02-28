<template>
  <div>
    <div class="card">
      <basic-table
        title="Data Insight Glossary"
        :data="datas.data"
        :columns="columns"
        :total="datas.total"
        :params="params"
        @onChange="loadData"
        @onSearch="Search"
        @onCellClick="
          (data) => {
            $router.push(`/insight/${data.id}`);
          }
        "
        @onCheckbox="
          (select) => {
            selected = select;
          }
        "
      >
        <!-- <template v-slot:head-left>
                        <button class="btn btn-primary px-2"><i class='bx bx-filter-alt' ></i></button>
                    </template> -->
        <template v-slot:head-right>
          <nuxt-link to="/insight/create">
            <button class="btn btn-info px-2">
              <i class="bx bx-plus"></i> Add New Insight
            </button>
          </nuxt-link>
          <button
            class="btn btn-danger px-2"
            v-show="selected.length > 0"
            @click="Delete"
          >
            <i class="bx bx-trash"></i>
          </button>
        </template>
        <template v-slot:title="{ data, item }">
          <div style="min-width: 280px; white-space: normal !important">
            {{ item }}
          </div>
        </template>
        <template v-slot:category="{ data, item }">
          <div style="white-space: normal !important">
            {{ item ? item.toString() : "" }}
          </div>
        </template>
        <template v-slot:trx_code="{ data, item }">
          <span
            class="badge me-1"
            :class="item == 'ART-PRE' ? 'bg-label-info' : 'bg-label-success'"
          >
            {{ item == "ART-PRE" ? "Limited" : "Public" }}</span
          >
        </template>
        <template v-slot:status="{ data, item }">
          <span
            class="badge me-1"
            :class="
              item == 'published' ? 'bg-label-success' : 'bg-label-warning'
            "
            >{{ item }}</span
          >
        </template>
      </basic-table>
    </div>
  </div>
</template>

<script>
const columns = [
  {
    key: "title",
    label: "Title",
    slot: "title",
    orderable: true,
    searchable: true,
  },
  {
    key: "category",
    label: "Category",
    slot: "category",
    orderable: true,
    searchable: true,
  },
  {
    key: "published_date",
    label: "Publish At",
    orderable: true,
    searchable: true,
  },
  {
    key: "author.name",
    label: "Author",
  },
  {
    width: 80,
    key: "trx_code",
    label: "Visiblity",
    slot: "trx_code",
  },
  {
    width: 80,
    key: "status",
    label: "Status",
    slot: "status",
  },
];

export default {
  head: {
    title: "Master Insight",
  },
  layout: "authenticated",
  data() {
    return {
      datas: [],
      columns: columns,
      selected: [],
      params: {
        perpage: 10,
        page: 1,
      },
    };
  },
  mounted() {
    this.loadData(this.params);
  },
  methods: {
    async loadData(params) {
      this.params = params;
      const { data } = await this.$getInsight({ params: params });
      this.datas = data;
    },
    async Delete() {
      const { status } = await this.$deleteInsight(this.selected);
      if (status == 204) {
        this.selected = [];
        this.$toast.success("Master data berhasil dihapus!");
        this.loadData(this.params);
      }
    },
    async Search(params) {
      params = { ...params, ...{ perpage: 10, page: 1 } };
      const { data } = await this.$getInsight({ params: params });
      this.datas = data;
    },
  },
};
</script>
