export default ($axios) => {
    return {
        getRegCountry: async  (param) => {
            return await $axios.get('api/regions')
        },
        getRegState: async  (param) => {
            return await $axios.get(`api/regions/${param}`)
        },
        getRegCity: async  (param) => {
            return await $axios.get(`api/regions/${param.country_id}/${param.state_id}`)
        }
    }
}