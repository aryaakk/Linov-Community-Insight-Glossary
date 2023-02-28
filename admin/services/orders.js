export default ($axios) => {
    return {
       getOrders: async  (param) => {
          return await $axios.get('api/order', param)
       },
       getOneOrders: async  (param) => {
         return await $axios.get('api/order/'+param)
      },
    }
}