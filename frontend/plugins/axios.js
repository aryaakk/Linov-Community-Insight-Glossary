export default function ({ $axios}) {
    $axios.onRequest(config => {
        //store._vm.$nuxt.$loading.start()
        // return config
    })

    $axios.onResponse(response=>{
        //store._vm.$nuxt.$loading.finish()
        // return response
    })
  
    $axios.onError(error => {
        //store._vm.$nuxt.$loading.finish()
        // return error
    })
}