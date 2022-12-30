<template>
  <li :id="id" v-if="v$.m">
    <div
      :class="{ bold: isFolder }">
  
        <div class="form-row">
          <div :class="show ? 'col' : 'col-3'">
            <input class="form-control" type="hidden" :name='"send["+id+"][category_id]"' v-model="model.category_id">
            <input class="form-control" type="hidden" :name='"send["+id+"][id]"' v-model="model.id">
            <input class="form-control" type="hidden" :name='"send["+id+"][category_type]"' v-model="model.category_type">
            <input class="form-control" type="hidden" :name='"send["+id+"][uuid]"' v-model="model.uuid">
            <input class="form-control" type="hidden" :name='"send["+id+"][parent_uuid]"' v-model="model.parent_uuid">

            <input-alter type="text" title="დასახელება" model-name="name" :v$="v$" :name='"send["+id+"][name]"' v-model="v$.m.name.$model" />
          </div>
          <div v-if="show" class="col">
             <input-alter type="text" title="ცალი" model-name="item" :v$="v$" :name='"send["+id+"][item]"' v-model="v$.m.item.$model" />
          </div>

          <div v-if="show" class="col">
            <input-alter type="text" title="ფასი" model-name="price" :v$="v$" :name='"send["+id+"][price]"' v-model="v$.m.price.$model" />
          </div>
          <!-- <div v-show="alterType != 'main'" class="col">
            <input class="form-control" type="hidden" :name='"alter["+id+"][type]"' v-model="alterType">
            <input class="form-control" type="hidden" :name='"alter["+id+"][category_attribute_id]"' v-model="model.id">
            <input type="text" class="form-control" placeholder="ფასი"  :name='"alter["+id+"][invoice_price]"' v-model="alter.invoice_price">
          </div> -->

          <div v-if="show" class="col">
            
            <input-alter type="text" title="მომსახურების ფასი" model-name="service_price" :v$="v$" :name='"send["+id+"][service_price]"' v-model="v$.m.service_price.$model" />
          </div>
          <!-- <div v-show="alterType != 'main'" class="col">
            <input type="text" class="form-control" placeholder="მომსახურების ფასი" :name='"alter["+id+"][invoice_service_price]"' v-model="alter.invoice_service_price">
          </div> -->

          <div v-if="show" class="col-2" >
            <!-- <select @change="v$.m.$touch" v-model="alterType" title="აირჩიეთ" placeholder="აირჩიეთ" class="form-control">
              <option value="main">განფასება</option>
              <option value="invoice">ინვოისი</option>
            </select> -->

            <!--  <div class="form-control" style="padding: 0; border: 1px solid #3D85D8;">
              <input value="true" class="exit" :disabled="model.nested.length" @change="e => { 
                v$.m.$touch; 
                v$.m.category_type.$model = !v$.m.category_type.$model;
              }" :id="model.uuid" type="checkbox" :name='"send["+id+"][category_type]"' :checked="!v$.m.category_type.$model">
              <label class="custom-label" :for="model.uuid">ატრიბუტი</label>
            </div> -->

            <div class="form-control" style="padding: 0; border: 1px solid #3D85D8; display:flex; align-items: center; justify-content: center;">
              <div class="custom-control custom-checkbox">
                <input :disabled="model.nested.length" @change="e => { v$.m.$touch; v$.m.category_type.$model = !v$.m.category_type.$model; }" class="custom-control-input" type="checkbox" :id="model.uuid" value="true" :name='"send["+id+"][category_type]"' :checked="!v$.m.category_type.$model">
                <label style="cursor: pointer;" :for="model.uuid" class="custom-control-label">ატრიბუტი</label>
              </div>
            </div>

            
          </div>
          <div style="display: flex;" :style="{'align-items': !show ? 'flex-start': 'flex-start'}">

            <button v-if="!show" :disabled="v$.m.$invalid" @click="addChild" type="button" class="btn btn-success mr-1"><i class="fas fa-plus"></i></button>

            <span @click="toggle" @dblclick="changeType" v-if="isFolder">[{{ isOpen ? '-' : '+' }}]</span>
            <span @click="isOpen = true" @dblclick="changeType" v-else>

              <button v-if="v$.m.category_type.$model" :disabled="v$.m.$invalid" style="margin-right: 5px;" @click="add($event, model)" type="button" class="btn btn-success"><i class="fas fa-plus"> დამატება</i></button>

              <button @click="remove($event, model)" type="button" class="btn btn-danger">წაშლა</button>
            </span>
          </div>
        </div>
      
    </div>
    <ul v-show="isOpen" v-if="isFolder" style="padding-bottom: 10px;">
     
      <TreeItem
        v-if="model.nested.length"
        :setting="setting"
        class="item"
        v-for="(nested, index) in model.nested"
        :tree="tree.nested  ? tree.nested : model.nested"
        :id="id + '_' +index"
        :model="nested">
      </TreeItem>

      <li v-if="v$.m.category_type.$model" class="add" style="padding-left: 10px;">
        <!-- <button :disabled="v$.m.$invalid" @click="addChild" type="button" class="btn btn-success"><i class="fas fa-plus"> დამატება</i></button> -->
      </li>
    </ul>
  </li>
</template>


<script>

import Util from 'Util'
import useVuelidate from '@vuelidate/core'
import { required, maxLength } from '@vuelidate/validators'

export default {
  name: 'TreeItem', // necessary for self-reference
  props: {
    model: Object,
    tree: Array,
    category: [String, Number],
    id: [String, Number],
    setting: [Object, Array]
  },
  data() {
    return {
      isOpen: true,
      alterType: 'main'
    }
  },
  setup (props) {
    console.log('props', props.tree)
    return { v$: useVuelidate(), m: props.model }
  },
  mounted () {
    this.v$.m.$touch();
  },
  validations () {
    let validateRule = {
      m: {
        name: { required },
        item: {},
        price: {},
        nested: {},
        service_price: {},
        category_type: {}
      }
    }

    if (!this.m.category_type) {
      validateRule.m.item = {
        required
      }
      validateRule.m.price = {
        required
      }
      validateRule.m.service_price = {
        required
      }
    } else {
      validateRule.m.item = {
        maxLength: maxLength(0)
      }
      validateRule.m.price = {
        maxLength: maxLength(0)
      }
      validateRule.m.service_price = {
        maxLength: maxLength(0)
      }
    }

    return validateRule
  },
  computed: {
    show () {
      return !this.v$.m.nested.$model.length
    },
    alter () {
      return (this.model && this.model.alters && this.model.alters.length > 0) ? 
        this.model.alters.find(i=>i.category_attribute_id == this.model.id) : { invoice_price : 0, invoice_service_price: 0 }
    },
    isFolder() {
      return this.model.nested && this.model.nested.length
    }
  },
  methods: {
    getId () {
      return this.model.nested ? this.id + '_' + this.model.nested.length : this.id + '_'
    },
    toggle() {
      if (this.isFolder) {
        this.isOpen = !this.isOpen
      }
    },
    changeType() {
      if (!this.isFolder) {
        this.addChild()
        this.isOpen = true
      }
    },
    addChild() {
      this.model.nested.push(Util.instance(this.model))

      console.log('tree', this.tree)
    },

    removeRequest (id, setting, callback) {

      if (!id) { 
        return callback() 
      }

      let action = document.querySelector('form#render').getAttribute('action')
      let token = document.querySelector('meta[name="csrf-token"').getAttribute('content')

      return this.$http.delete(setting.url.request.destroy.replace('delete', id), { id }, {
        "Content-Type": "application/json",
        'X-CSRF-TOKEN': token
      })
      .then(async response => {
        if (response.data.success == true) {
          callback()
        } else {
          response.data.errs.map(item => this.$toast.error(item, { position: 'top-right', duration: 7000 }))
        }
      })
      .catch((e) => {
         this.$toast.error(e.response.statusText, { position: 'top-right', duration: 7000 })
      });
    },

    remove (e, model) {
      e.preventDefault();
      return  Util.useSwall2(model).then((result) => {
        if (result.isConfirmed) {
          this.removeRequest(model.id || null, this.setting, () => {
            this.tree.splice(this.tree.findIndex(i => i.id == model.id), 1)
            Swal.fire('წაშლა!', 'წაშლა შესრრულდა წარმატებით.', 'success')
          })
        }
      })
    },

    add(e, model) {
      console.log('Util', Util)
      model.nested = [Util.instance(this.model)]
      console.log('Util', Util)
    }
  }
}
</script>

<style lang="scss">
  .floating-input:not(:placeholder-shown) ~ .floating-label {
    top:-1rem;
  }

  .floating-label {
    color:#1e4c82;
    font-weight:normal;
    position:absolute;
    pointer-events:none;
    left:15px;
    top:0.3rem;
    padding:0 5px 5px 5px;
    background:#fff;
    transition:0.2s ease all; 
    -moz-transition:0.2s ease all; 
    -webkit-transition:0.2s ease all;
  }

  .floating-input {
    display:block;
    padding: 0 20px;
    background: #fff;
    color: #323840;
    border: 1px solid #3D85D8;
    border-radius: 4px;
    box-sizing: border-box;
    &:focus{
      outline:none;
      ~ .floating-label {
        top:-1rem;
      }
    }
  }

  ul {
    list-style: none;
  }
  .item {
    cursor: pointer;
    line-height: 1.5;
  }
  .bold {
    font-weight: bold;
  }

  .form-row {
    padding: 10px;
  }
</style>