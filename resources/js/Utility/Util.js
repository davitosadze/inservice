let uuid = () => {
	return ([1e7]+-1e3+-4e3+-8e3+-1e11).replace(/[018]/g, c =>
	  (c ^ crypto.getRandomValues(new Uint8Array(1))[0] & 15 >> c / 4).toString(16)
	)
}

class Tree {
    constructor(data) {
        this.map = new Map;

        const recur = (node) => {
            this.map.set(node.uuid, node);
            if (node.nested) node.nested.forEach(recur);
        }

        data.forEach(recur);
    }
    getParents(item, name = '') {

        if (item.category_type) name = item.name + ' / ' + name

        if (!item.parent_uuid) {
        	name = item.category.name + " / " + name;
        	return name.trim()
        } else {
        	return this.getParents(this.map.get(item.parent_uuid), name)
        }
    }

    // getParents(secCode) {
    //     let parents = [];
    //     while (secCode) {
    //         parents.push(secCode);
    //         secCode = this.map.get(secCode).prevSec;
    //     }
    //     return parents;
    // }
}

let flatTree = (level = 0) => ({ nested = [], ...item }) => [{ ...item, level }, ...nested.map(flatTree(level + 1)).flat()]
let exit = (model) => {
    return model.map(flatTree()).flat().map(({level, created_at, updated_at, category, alters, pivot, id, ...exit }) => exit)
}

let specNum = (num) => {
        if (!num) return 0.00
        let number = Math.round( ( price + Number.EPSILON ) * 100 ) / 100;
        // return parseFloat(new Intl.NumberFormat('en-Us', {
        //   minimumFractionDigits: 2,
        //   maximumFractionDigits: 2
        // }).format(num));
        return parseFloat(number);
      }

let useSwall = (res) => {
	let icon = !res.data.success ? "error" : "success"
    let exit = {}
    if (res.data.success) {
        exit = { 
            showCancelButton: true, showConfirmButton: true, timer: false, cancelButtonText: 'გაგრძელება', confirmButtonText: 'ჩამონათვალში დაბრუნება' 
        }
    }

	return Swal.fire({
        position: 'top-end',
        icon: icon,
        title: res.data.statusText,
        showConfirmButton: false,
        timer: 2000,
        ...exit
	})
}

let useSwall2 = (res) => {
    
    return Swal.fire({
      title: 'დარწმუნებული ხარ?',
      text: "წაშლა!",
      icon: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#d33',
      cancelButtonColor: '#3085d7',
      confirmButtonText: 'შესრულება!',
      cancelButtonText: 'გაუქმება'
    })
}

let instance = (model, type = 'attr', additional = {}) => ({
    category_type: (type == 'attr') ? false : true,
    category_id: (type == 'attr') ? model.category_id : additional.category.id,
    uuid: uuid(), id: null, item: '', parent_uuid: (type == 'attr') ? model.uuid : null, name: '', nested: [], price: '', service_price: ''
})

let itemReportValue = (price, reporter, index = 0) => {
    if (reporter[index] != undefined) {
        price = price / (1 + (reporter[index].value / 100))
        index = index + 1
        return itemReportValue(price, reporter, index)
    } else {
        return Math.round( ( price + Number.EPSILON ) * 100 ) / 100; // (Math.ceil((parseFloat(price)) * 100) / 100)
    }
}

let initReporteValues = (array, model, indexer = 'p') => {
    return Array.from({ length : array.length}, (_, i) => {
      let name = array[i]
      let inputName = indexer + (i + 1)
      let value = model[inputName] ? model[inputName] : 0
      return { name: name, keyName: inputName, value: value }
    })
}

let numberFormat = (number) => {
    if (!number) return 0.00
    // return parseFloat(new Intl.NumberFormat('en-Us', {
    //   minimumFractionDigits: 2,
    //   maximumFractionDigits: 2
    // }).format(num));
    return Math.round( ( number + Number.EPSILON ) * 100 ) / 100 //parseFloat((Math.ceil((parseFloat(number)) * 100) / 100));
}

let agr = (arr, invoice = false) => {
    // agr = Object.entries(agr).map(([price, service_price]) => ({ price, service_price}))
    return arr.reduce((a, b) => {
        a['price'] = numberFormat(numberFormat((a['price'] || 0)) + numberFormat(invoice ? b.pivot.price : b.pivot.evaluation_price));
        a['calc'] = numberFormat(numberFormat((a['calc'] || 0) + parseFloat(invoice ? b.pivot.calc : b.pivot.evaluation_calc)));
        a['service_price'] = numberFormat(numberFormat((a['service_price'] || 0)) + numberFormat(invoice ? b.pivot.service_price : b.pivot.evaluation_service_price));
    return a
   }, {})
}

let _isEmpty = (subject) => {
    return _.isEmpty(subject);
}

export default {
	uuid,
	Tree,
    instance,
	useSwall,
    useSwall2,
    flatTree,
    _isEmpty,
    agr,
    exit,
    specNum,
    initReporteValues,
    itemReportValue,
    numberFormat
}