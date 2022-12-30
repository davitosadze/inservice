<template>
	<tr>

	  <td>
	    <textarea style="height: 74px;" class="form-control" v-model="item.pivot.title"></textarea>
	  </td>
	  <td>
	    <input class="form-control" type="text" v-model="item.name" @focus="focus" @blur="blur">
	  </td>
	  <td>
	    <input class="form-control" type="text" v-model="item.item" @focus="focus" @blur="blur">
	  </td>
	  <td>
	    <input class="form-control" min="1" type="number" @focus="focus" @blur="blur"
	      :value="item.pivot.qty" @input="event => trigger_price(event, item)"> <!-- @click="e => e.target.select()" -->
	  </td>
	  <td>
	    <input class="form-control" name="price" type="number" step="any" @focus="focus" @blur="blur"
	      v-model="item.pivot.price" @input="event => trigger_price(event, item, true)" >
	  </td>
	  <td v-if="model.type == 'evaluation'">
	    <input :readonly="model.type == 'evaluation'" @focus="focus" @blur="blur" v-model="item.pivot.evaluation_price" step="any"  class="form-control" name="price" type="text"  >
	  </td>
	  <td>
	    <input class="form-control" name="service_price" type="number" step="any" @focus="focus" @blur="blur"
	    v-model="item.pivot.service_price" @input="event => trigger_price(event, item, true)" >
	  </td>
	  <td v-if="model.type == 'evaluation'">
	    <input :readonly="model.type == 'evaluation'" v-model="item.pivot.evaluation_service_price" step="any" @focus="focus" @blur="blur"  class="form-control" name="price" type="text"  >
	  </td>

	   <td>
	    <input class="form-control" type="number" step="any" :readonly="model.type == 'evaluation'" :value="item.pivot.calc" @focus="focus" @blur="blur">
	  </td>
	  <td v-if="model.type == 'evaluation'">
	    <input class="form-control" type="number" step="any" readonly :value="item.pivot.evaluation_calc" @focus="focus" @blur="blur">
	  </td>

	  <td style="text-align: center;">
	  	<i style="cursor:pointer; color:red; font-size:1.2em;" @click="event => trigger_remove(event, item)" class="fas fa-trash"></i>
	  </td>
	</tr>
</template>

<script>

	import Util from 'Util'
	import { watch, computed, getCurrentInstance } from 'vue';

	export default {
		props:['item', 'joinInTree', 'keys', 'model'],
		name: 'request-single-attribute',
		created() {
			if (this.item.category) this.item.pivot.title = this.fullName(this.item)
		},
		data () {
			return {}
		},
		setup (props) {

			let instance = getCurrentInstance();
			let watcher = computed(() => JSON.parse(JSON.stringify(props.item)))

			watch(watcher, (newxValue, prevValue) => {
				// console.log('first', newxValue)

				// if (newxValue && !props.item.pivot.evaluation_calc) {
				// 	props.item.pivot.evaluation_calc = Util.itemReportValue(newxValue.pivot.calc, instance.parent.parent.proxy.initReporteValues)
				// }				

				if (newxValue && prevValue && props.model.type == 'evaluation') {



					props.keys.map(key => {
						if (newxValue.pivot[key.replace('evaluation_', '')] !== prevValue.pivot[key.replace('evaluation_', '')]) {

							if (key == "evaluation_calc") {
								props.item.pivot[key] = Util.numberFormat((newxValue.pivot.evaluation_price + newxValue.pivot.evaluation_service_price) * newxValue.pivot.qty)
							} else {
								props.item.pivot[key] = 
								Util.itemReportValue(newxValue.pivot[key.replace('evaluation_', '')], instance.parent.parent.proxy.initReporteValues)
							}
						}
					})
					
					if (newxValue.pivot.calc !== prevValue.pivot.calc) {
						// props.item.pivot.evaluation_calc = Util.itemReportValue(newxValue.pivot.calc, instance.parent.parent.proxy.initReporteValues)

						props.item.pivot.evaluation_calc = Util.numberFormat((newxValue.pivot.evaluation_price + newxValue.pivot.evaluation_service_price) * newxValue.pivot.qty)
					}

				} else if (newxValue) {
					props.keys.map(key => {
						if (!props.item.pivot[key]) {
							if (key == "evaluation_calc") {
								props.item.pivot[key] = Util.numberFormat((newxValue.pivot.evaluation_price + newxValue.pivot.evaluation_service_price) * newxValue.pivot.qty)
							} else {
								props.item.pivot[key] = 
								Util.itemReportValue(newxValue.pivot[key.replace('evaluation_', '')], instance.parent.parent.proxy.initReporteValues)
							}
						}
					})
				}

			}, {deep:  true, immediate: true })
		},
		methods: {

			focus (e) {
				if (e.target.value == 0) { e.target.select() }
				this.$emit('action_focus')
			},

			blur () {
				this.$emit('action_blur')
			},

			fullName (item) {
				let tree = new Util.Tree(this.joinInTree)
				let treeName = tree.getParents(item) || ''
				// console.log("item", item);
				return treeName
			},

			trigger_price (event, item, triger) {
				this.$emit('action_price', event, item, triger)
			},

			trigger_remove (event, item, triger) {
				this.$emit('action_remove', event, item, triger)
			}
		}
	} 
	
</script>