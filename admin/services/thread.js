export default ($axios) => {
    return {
       getReportThread: async  (param) => {
        return await $axios.get(`api/threads/reported`, param)
       },
       getReportThreadChart: async  (id) => {
        return await $axios.get(`api/threads/${id}/chart`)
       },
       getReportThreadDetail: async  (id, param) => {
        return await $axios.get(`api/threads/${id}/reporters`, param)
       },
       getOneThread: async  (id) => {
        return await $axios.get(`api/threads/${id}/show`)
       },
       toggleVisibleThread: async  (id) => {
        return await $axios.get(`api/threads/${id}/visibility`)
       },
    }
}