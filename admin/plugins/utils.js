import Vue from 'vue'

Vue.prototype.$currency = (number) =>{
  if(number==null || number=='' || number==undefined){
      return 'Rp. 0';
  }
  return new Intl.NumberFormat('id-ID', {style: 'currency',currency: 'IDR', maximumFractionDigits: 0}).format(parseFloat(number))
}

Vue.prototype.$getKeys = function(objects, key){
    return Object.entries(objects).map(([_key, value]) => value[key])
};

Vue.prototype.$sizeOf = function(file){
    const size = file?.size || 0;
    if(size < 1000000){
       return Math.floor(size/1000) + 'KB';
    }else{
      return Math.floor(size/1000000) + 'MB';  
    }
}

Vue.prototype.$imageUrl = (image) =>{
    return URL.createObjectURL(image)
}

Vue.prototype.$errorIn = (errors, key) =>{
    const idx = Object.keys(errors).filter(_key=>_key.includes(key))[0]
    return errors?.[idx]?.[0]
}

Vue.prototype.$slugable = (strText)=>{
    return strText.toLowerCase()
            .replace(/ /g, '-')
            .replace(/[^\w-]+/g, '');
}

Vue.prototype.$getNestedObject= (object, string)=>{
    string = string.replace(/\[(\w+)\]/g, '.$1');
    string = string.replace(/^\./, '');
    let a = string.split('.');
    for (let i = 0, n = a.length; i < n; ++i) {
      let k = a[i];
      if (k in object) {
        object = object[k];
      } else {
        return;
      }
    }
    return object;
}

Vue.prototype.$isNestedObject= (key)=>{
  return key.split('.').length > 1
}

Vue.prototype.$orderAsc =(data, key)=>{
    return data.sort(function (a, b) {
        return a[key] - b[key];
    });
}

Vue.prototype.$pluck = (arr, key) => arr.map(i => i[key])

Vue.prototype.$toggleModal = (element) => {
  if(!element?.classList){
    console.log(element)
    return
  }
  element.classList.toggle('d-block')
}