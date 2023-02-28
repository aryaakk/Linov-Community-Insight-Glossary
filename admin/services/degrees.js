export default ($axios) => {
    return {
       getDegrees: async  (param) => {
          return await $axios.get('api/degrees', param)
       },
       getOneDegrees: async  (param) => {
         return await $axios.get('api/degrees/'+param)
       },
       postDegrees: async  (param) => {
         return await $axios.post('api/degrees', param)
       },
       updateDegrees: async  (param, id) => {
         return await $axios.put('api/degrees/'+id, param)
       },
       deleteDegrees: async  (param) => {
         return await $axios.post('api/degrees/deletes',param)
       },
    }
}