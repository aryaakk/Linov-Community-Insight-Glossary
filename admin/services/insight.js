export default ($axios) => {
    return {
       getInsight: async  (param) => {
          return await $axios.get('api/article', param)
       },
       getOneInsight: async  (param) => {
         return await $axios.get('api/article/'+param+'/show')
       },
       previewInsight: async  (param) => {
        return await $axios.post('api/article/preview',param)
       },
       postInsight: async  (param) => {
         return await $axios.post('api/article', param)
       },
       updateInsight: async  (param, id) => {
         return await $axios.post('api/article/'+id, param)
       },
       deleteInsight: async  (param) => {
         return await $axios.post('api/article/deletes',param)
       },
       getInsComment: async  (param) => {
        return await $axios.get('/api/article/comment', param)
       },
       doInsComment: async  (param, status) => {
        return await $axios.post(`/api/article/comment/${status}`, param)
       },
    }
}