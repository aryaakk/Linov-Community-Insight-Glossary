export default ($axios) => {
    return {
       getPositions: async  (param) => {
          return await $axios.get('api/positions', param)
       },
       getOnePositions: async  (param) => {
         return await $axios.get('api/positions/'+param)
       },
       postPositions: async  (param) => {
         return await $axios.post('api/positions', param)
       },
       updatePositions: async  (param, id) => {
         return await $axios.put('api/positions/'+id, param)
       },
       deletePositions: async  (param) => {
         return await $axios.post('api/positions/deletes',param)
       },
    }
}