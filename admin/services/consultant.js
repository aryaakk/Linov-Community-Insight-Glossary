export default ($axios) => {
    return {
        getConsultants: async  (param) => {
            return await $axios.get('api/consultant', param)
        },
        postConsultant: async  (param) => {
            return await $axios.post('api/consultant', param)
        },
        updateConsultant: async (param, id) =>{
            return await $axios.post('api/consultant/'+id, param)
        },
        getOneConsultant: async  (id) => {
            return await $axios.get('api/profile/user/'+id)
        },
        getSubmissions: async  (param) => {
            return await $axios.get('api/consultant/submissions', param)
        },
        getOneSubmission: async  (id) => {
            return await $axios.get('api/consultant/submission/'+id)
        },
        verifySubmission: async  (param, id) => {
            return await $axios.post('api/consultant/submission/'+id, param)
        },
    }
}