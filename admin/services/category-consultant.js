export default ($axios) => {
    return {
       getCatConsultant: async  (param) => {
          return await $axios.get('api/category/consultant', param)
       },
       getOneCatConsultant: async  (param) => {
         return await $axios.get('api/category/consultant/'+param)
       },
       postCatConsultant: async  (param) => {
         return await $axios.post('api/category/consultant', param)
       },
       updateCatConsultant: async  (param, id) => {
         return await $axios.put('api/category/consultant/'+id, param)
       },
       deleteCatConsultant: async  (param) => {
         return await $axios.post('api/category/consultant/deletes',param)
       },
    }
}