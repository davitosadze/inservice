<template>
	<input class="floating-input form-control" :class="{ 'is-invalid': getModelName.$errors.length }" v-bind="$attrs" type="text" :value="modelValue" @input="updateValue">
 	<label class="floating-label">{{title}}</label>

 	<div class="invalid-feedback" v-if="getModelName.maxLength && getModelName.maxLength.$invalid">დატოვეთ ცარიელი!</div>
	<div class="invalid-feedback" v-if="getModelName.required && getModelName.required.$invalid">ჩაწერეთ!</div>

</template>

<script>
	export default {
		props: ['modelValue', 'v$', 'modelName', 'title', 'special'],
		methods: {
			updateValue (event) {
				this.$emit('update:modelValue', event.target.value)
			}
		},
		computed: {
			getModelName () {
				return (this.special) ? this.v$.m.json[this.modelName] : this.v$.m[this.modelName]
			}
		}
	}
</script>