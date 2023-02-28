import Vue from 'vue'

Vue.prototype.$api = 'api';
Vue.prototype.$slugable = (strText)=>{
    return strText.toLowerCase()
            .replace(/ /g, '-')
            .replace(/[^\w-]+/g, '');
}
Vue.prototype.$currency = (number) =>{
    if(number==null || number=='' || number==undefined){
        return 'Rp. 0';
    }
    return new Intl.NumberFormat('id-ID', {style: 'currency',currency: 'IDR', maximumFractionDigits: 0}).format(parseFloat(number))
}

Vue.prototype.$screen = Vue.observable({
    width: process.client ? window.innerWidth : 0,
    height: process.client ? window.innerHeight : 0
});

if (process.client) {
    window.addEventListener('resize', () => {
        Vue.prototype.$screen.width = window.innerWidth;
        Vue.prototype.$screen.height = window.innerHeight;
    });
}

Vue.prototype.$_errors = (errors, key) =>{
    const idx = Object.keys(errors).filter(_key=>_key.includes(key))[0]
    return errors?.[idx]?.[0]
}
Vue.prototype.$_btoa = (str) =>{
    return Buffer.from(str).toString('base64');
}

Vue.prototype.$_atob = (str) =>{
    return Buffer.from(str, 'base64').toString();
}
Vue.prototype.$getPreview = (image) =>{
    return URL.createObjectURL(image)
}

Vue.prototype.$pluck = (arr, key) => arr.map(i => i[key])