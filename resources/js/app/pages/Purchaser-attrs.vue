<template>
  <div class="row">
    <div class="col-12 col-md-12 col-lg-8 order-2 order-md-1">
      <div class="card card-primary card-outline card-tabs">
        <div class="card-body">
          <div class="tab-content" id="custom-tabs-three-tabContent">

            <p class="lead">მყიდველი</p>
            <div class="row invoice-info">
              <div class="col-sm-4 invoice-col">
                <address>
                  <b>სახელი :</b> {{model.name}} <br>
                  <b>სუბიექტის სახელი :</b> {{ model.subj_name }} <br>
                  <b>სუბიექტის მისამართი :</b> {{model.subj_address}} <br>
                </address>
              </div>
            </div>

            <div class="form-group">
              <label>კატეგრიეები</label>
              <select title="აირჩიეთ" placeholder="აირჩიეთ" class="form-control" @change="category">
                <option value="" disabled selected>--- აირჩიეთ ---</option>
                <option v-for="(i, index2) in main" :value="json(i)" :key="index2">{{i.name}}</option>
              </select>
            </div>

            <label>ატრიბუტები</label>
              <div class="form-group" v-for="(item, index ) in attributes" :key="index" @change="redirect">
              <select title="აირჩიეთ" placeholder="აირჩიეთ" v-model="item.selected" class="form-control" :index="index">
                <option value="" disabled selected>--- აირჩიეთ ---</option>
                <option v-if="item" v-for="(i, index2) in item.res" :value="json(i)" :key="index2">{{i.name}}</option>
              </select>
            </div>

          </div>

          <button @click="addCategory" :disabled="!attributes.length" type="button"  class="btn btn-sm btn-outline-success"><i class="fas fa-shield-alt"></i> დამატება </button>

        </div>
      </div>
    </div>

    <div class="col-12 col-md-12 col-lg-4 order-1 order-md-2">

      <div class="card card-primary card-outline card-tabs">
        <div class="card-body">

          <button @click="exit" type="button" class="btn btn-danger  btn-block" style="margin-right: 5px;">
            <i class="far fa-window-close"></i> გასვლა
          </button>

          <button :disabled="v$.$invalid" @click="send" type="" class="btn btn-success  btn-block">
            <i class="far fa-paper-plane"></i> გაგზავნა
          </button>
        </div>
      </div>    

    </div>
  </div>

  <div class="card card-primary card-outline card-tabs">
    <div class="card-body">
      <template v-for="(attr, index) in m.special_attributes" :key="attr.json.id">
        <purchaser-single-attribute @remove="try_remove" :attr="attr" :model="model"></purchaser-single-attribute>
      </template>
    </div>
  </div>

</template>


<script>

  import Util from 'Util'
  import { reactive } from 'vue';
  import useVuelidate from '@vuelidate/core'
  import { required, maxLength } from '@vuelidate/validators'

  export default {
    props:['additional', 'model', 'url', 'setting', 'user', 'name'],
    data () {
      return {
         selected: null,
         main: [],
         attributes: [{ selected: '', res: [] }]
      }
    },
    setup (props) {
      return { v$: useVuelidate(), m: reactive(props.model) }
    },
    created() {
      this.main = this.additional.categories
      // this.special_attrs = this.model.special_attributes.map(i => i.json)
    },
    mounted() {
      console.log('m', this.m)
    },
    validations () {
      let validateRule = {
        m : {
          special_attributes: {}
        }
      }
      return validateRule
    },
    computed: {},
    methods: {

      exit () {
        return location.href = this.setting.url.request.exit.replace('new', this.model.id)
      },

      json(i) {
        return JSON.stringify(i)
      },

      fromJson(i) {
        return JSON.parse(i)
      },

      category () {
        if (event.target.value) {

          let res = this.fromJson(event.target.value);
          this.attributes = [{ selected: '', res: res.category_attributes }]
          console.log('res', res)

          // let nextIndex = Number(event.target.getAttribute('index')) +1;
          // let index = Number(event.target.getAttribute('index'));
          //  console.log('this.categories.length', this.categories.length)
          //  let result = (index > 0) ? res.nested : res.category_attributes
          //   this.categories.splice(nextIndex, this.categories.length)
          // if (result.length) {
          //   this.categories[nextIndex] = { selected: '', res: result }
          // }
          // this.selected = res

        }
      },

      redirect(event) {

        if (event.target.value) {
          let res = this.fromJson(event.target.value);
          let nextIndex = Number(event.target.getAttribute('index')) +1;
          let index = Number(event.target.getAttribute('index'));
          let result = (index > 0) ? res.nested : res.nested
      
          this.attributes.splice(nextIndex, this.attributes.length)
          if (result.length) {
            this.attributes[nextIndex] = { selected: '', res: result }
          }

          this.selected = res
        }
      },

      addCategory () {
        try {

          if (this.m.special_attributes.find(i => i.json.uuid == this.selected.uuid)) {
            this.$toast.error('მყიდველი უკვე სარგებლობს მოცემული შემოთავაზებით!', { position: 'top-right', duration: 7000 })
          } else if (!this.selected.category_type) {
            this.selected.isSpecial = true;
            this.m.special_attributes.splice(0,0,{ purchaser_id: this.model.id, category_attribute_id: this.selected.id, json: this.selected })
          } else {
            this.$toast.error('ატრიბუტი განეკუთნება კატეგორიის ტიპს!', { position: 'top-right', duration: 7000 })
          }
        } catch (err) {
          this.$toast.error('შეარჩიეთ ატრიბუტი!', { position: 'top-right', duration: 7000 })
        }
      },

      removeRequest (id, setting, callback) {

        if (!id) { 
          return callback() 
        }

        let action = document.querySelector('form#render').getAttribute('action')
        let token = document.querySelector('meta[name="csrf-token"').getAttribute('content')

        return this.$http.delete(setting.url.request.destroy.replace('__delete__', id), { id }, {
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

      try_remove (model) {

        this.removeRequest(model.id || null, this.setting, () => {
          this.m.special_attributes.splice(this.m.special_attributes.findIndex(i => i.category_attribute_id == model.category_attribute_id), 1)
          Swal.fire('წაშლა!', 'წაშლა შესრრულდა წარმატებით.', 'success')
        })
      },

      send(e) {

        e.preventDefault();

        let action = document.querySelector('form#render').getAttribute('action')
        let token = document.querySelector('meta[name="csrf-token"').getAttribute('content')

        let res = Util.exit(this.m.special_attributes.map(i => ({ ...i, json: JSON.stringify(i.json) })));

        this.$http.post(action, res, {
          "Content-Type": "application/json",
          'X-CSRF-TOKEN': token
        })
        .then(async response => {

          Util.useSwall(response).then((result) => {
            
            if (!response.data.success) {
              if (response.data.errs) response.data.errs.map(item => this.$toast.error(item, { position: 'top-right', duration: 7000 }))
            }

            if (result.value) {
              window.location.replace(this.setting.url.request.exit.replace('new', this.model.id))
            }

          })

        })
        .catch((e) => {
          this.$toast.error(e.response.statusText, { position: 'top-right', duration: 7000 })
        });
      }
      
    }
  }
</script>

<style>
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
</style>