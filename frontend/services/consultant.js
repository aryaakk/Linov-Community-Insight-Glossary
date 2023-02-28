export default ($axios) => {
    return {
        getConsultants: async  (param) => {
            return await $axios.get('api/consultant/list', param)
        },
        submitConsultant : async (data) => {
            return await $axios.post('api/consultant/verify', data)
        },
        getConsultantSubmission: async  (param) => {
            return await $axios.get('api/consultant/submission', param)
        },
        getConsultantCategory: async  (param) => {
            return await $axios.get('api/consultant/category', param)
        },
    }
}