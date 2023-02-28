export default ($axios) => {
    return {
       getState: async  (param) => {
          return await $axios.get('api/state', param)
       },
       getOneState: async  (param) => {
         return await $axios.get('api/state/'+param)
       },
       postState: async  (param) => {
         return await $axios.post('api/state', param)
       },
       updateState: async  (param, id) => {
         return await $axios.put('api/state/'+id, param)
       },
       deleteState: async  (param) => {
         return await $axios.post('api/state/deletes',param)
       },
    }
}