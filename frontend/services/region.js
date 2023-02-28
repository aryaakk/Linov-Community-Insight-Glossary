export default ($axios) => {
    return {
       getCountry: async  (param) => {
          return await $axios.get('api/regions')
       },
       getState: async  (param) => {
        return await $axios.get(`api/regions/${param.country_id?.id}`)
       },
       getCity: async  (param) => {
        return await $axios.get(`api/regions/${param.country_id?.id}/${param.state_id?.id}`)
       }
    }
}