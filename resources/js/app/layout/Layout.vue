<template> <component :is="name" v-bind="$props" :model="m" ></component> </template>

<script> 
	export default { 
		props:["name", 'model'],
		data () {
			return {
				m: {}
			}
		},
		created () {
			this.m = (sessionStorage.getItem('model')) ? JSON.parse(sessionStorage.getItem('model')) : this.$props.model
		},
		mounted () {
			sessionStorage.removeItem('model')
		}
	} 
</script>