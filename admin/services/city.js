export default ($axios) => {
    return {
       getCity: async  (param) => {
          return await $axios.get('api/city', param)
       },
       getOneCity: async  (param) => {
         return await $axios.get('api/city/'+param)
       },
       postCity: async  (param) => {
         return await $axios.post('api/city', param)
       },
       updateCity: async  (param, id) => {
         return await $axios.put('api/city/'+id, param)
       },
       deleteCity: async  (param) => {
         return await $axios.post('api/city/deletes',param)
       },
    }
}