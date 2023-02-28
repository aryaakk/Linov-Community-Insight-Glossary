export default ($axios) => {
    return {
       getProviders: async  (param) => {
          return await $axios.get('api/provider', param)
       },
       getOneProviders: async  (param) => {
         return await $axios.get('api/provider/'+param)
       },
       postProviders: async  (param) => {
         return await $axios.post('api/provider', param)
       },
       updateProviders: async  (param, id) => {
         return await $axios.post('api/provider/'+id, param)
       },
       deleteProviders: async  (param) => {
         return await $axios.post('api/provider/deletes',param)
       },
    }
}