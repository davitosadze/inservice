<template>
	<div>
          <div class="row invoice-info">
                
                <div class="col-sm-4 invoice-col">
                  <address>

                    <b>კატეგორიის სახელი :</b> {{m.json.category.name}} <br>
                    <b>ერთეული :</b> {{m.json.category.type}} <br>
                  </address>
                </div>
                
              </div>
              <hr/>
        <div  class="form-row">
          <div class="col">
          	<div class="form-group">
              <input-alter :special="true" type="text" title="დასახელება" model-name="name" :v$="v$" v-model="v$.m.json.name.$model" />
              </div>
          </div>
          <div class="col">
            <div class="form-group">
            <input-alter :special="true" type="text" title="ცალი" model-name="item" :v$="v$" v-model="v$.m.json.item.$model" />
             </div>
          </div>
          <div class="col">
            <div class="form-group">
            <input-alter :special="true" type="text" title="ფასი" model-name="price" :v$="v$" v-model="v$.m.json.price.$model" />
            </div>
          </div>
          <div class="col">
            <div class="form-group">
             <input-alter :special="true" type="text" title="მომსახურების ფასი" model-name="service_price" :v$="v$" v-model="v$.m.json.service_price.$model" />
            </div>
          </div>

            <div class="form-group">
             <button @click="remove($event, m)" type="button" class="btn btn-danger">წაშლა</button>
            </div>
          
        </div>
        </div>
</template>


<script>

  import Util from 'Util'
  import { reactive } from 'vue';
  import useVuelidate from '@vuelidate/core'
  import { required, maxLength } from '@vuelidate/validators'

  export default {
  	props:['attr', 'model'],
    mounted() {
    	// this.m.category_attribute_id = this.m.id
    	// this.m.json = JSON.stringify(m)
    	// this.m.purchaser_id = this.model.id

    	console.log('attr', this.attr)

    	this.v$.$touch()
    },
    data () {
      return {
      	m: {}
      }
    },
    setup (props) {
		return { v$: useVuelidate(), m: reactive(props.attr) }
    },
    validations () {
      let validateRule = {
        m: {
        	json: {
	          name: { required },
	          item: { required },
	          price: { required },
	          service_price: { required }
	        }
        }
      }
      return validateRule
    },
    computed: {
    },
    methods: {

      json(i) {},

      fromJson(i) {},

      exit() {},

      purchiser () {},

      category () {},

      redirect(event) {},

      addCategory () {},


      remove (e, model) {
        e.preventDefault();
        return  Util.useSwall2(model).then((result) => {
          if (result.isConfirmed) {
    			this.$emit('remove', model)
              // this.tree.splice(this.tree.findIndex(i => i.id == model.id), 1)
              // Swal.fire('წაშლა!', 'წაშლა შესრრულდა წარმატებით.', 'success')
            
          }
        })
      },
      
    }
  }
</script>