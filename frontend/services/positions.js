export default ($axios) => {
    return {
       getPositions: async  (param) => {
          return await $axios.get('api/positions', param)
       }
    }
}