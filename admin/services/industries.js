export default ($axios) => {
    return {
       getIndustries: async  (param) => {
          return await $axios.get('api/industries', param)
       },
       getOneIndustries: async  (param) => {
         return await $axios.get('api/industries/'+param)
       },
       postIndustries: async  (param) => {
         return await $axios.post('api/industries', param)
       },
       updateIndustries: async  (param, id) => {
         return await $axios.put('api/industries/'+id, param)
       },
       deleteIndustries: async  (param) => {
         return await $axios.post('api/industries/deletes',param)
       },
    }
}