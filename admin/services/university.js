export default ($axios) => {
    return {
       getUniversity: async  (param) => {
          return await $axios.get('api/university', param)
       },
       getOneUniversity: async  (param) => {
         return await $axios.get('api/university/'+param)
       },
       postUniversity: async  (param) => {
         return await $axios.post('api/university', param)
       },
       updateUniversity: async  (param, id) => {
         return await $axios.put('api/university/'+id, param)
       },
       deleteUniversity: async  (param) => {
         return await $axios.post('api/university/deletes',param)
       },
    }
}