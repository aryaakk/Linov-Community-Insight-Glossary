export default ($axios) => {
    return {
        getCategories: async  (param) => {
            return await $axios.get('api/types', param)
        },
    }
}