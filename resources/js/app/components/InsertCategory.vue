<template>

	 <div class="card card-primary card-outline card-tabs">
      <div class="card-body">
        <div class="row">

          <div class="col-12 col-md-12 col-lg-8 order-2 order-md-1">
            <div class="tab-content" id="custom-tabs-three-tabContent">
              <div class="form-group">
                <label for="formGroupExampleInput">დასახელება</label>
                <input :class="{ 'is-invalid': v$.m.name.$errors.length }" type="text" v-model="v$.m.name.$model" class="form-control" id="formGroupExampleInput" placeholder="">
                <div class="invalid-feedback" v-if="!v$.m.name.required.$response">ჩაწერეთ სახელი!</div>
              </div>
              <div class="form-group">
                <label for="formGroupExampleInput2">აღწერა</label>
                <textarea :class="{ 'is-invalid': v$.m.description.$errors.length }" v-model="v$.m.description.$model" class="form-control" id="exampleFormControlTextarea1" rows="5"></textarea>
                <div class="invalid-feedback" v-if="!v$.m.description.required.$response">ჩაწერეთ კატეგორიის აღწერა!</div>
              </div>
               <div class="form-group">
                <label for="formGroupExampleInput">ერთეული</label>
                <input :class="{ 'is-invalid': v$.m.type.$errors.length }" v-model="v$.m.type.$model" type="text" class="form-control" id="formGroupExampleInput" placeholder="">
                <div class="invalid-feedback" v-if="!v$.m.type.required.$response">ჩაწერეთ ერთეულის დასხელება!</div>
              </div>

              <button v-if="model.id" @click="redirect(attrsLink)" type="button" class="btn btn-warning  btn-block" style="margin-right: 5px;">
                ატრიბუტები
              </button>

            </div>       
          </div>

          <div class="col-12 col-md-12 col-lg-4 order-1 order-md-2">
            <div class="form-group"></div>

            <button @click="exit" type="button" class="btn btn-danger  btn-block" style="margin-right: 5px;">
                <i class="far fa-window-close"></i> გასვლა
            </button>

            <button :disabled="v$.$errors.length" @click="send" type="" class="btn btn-success  btn-block">
                <i class="far fa-paper-plane"></i> გაგზავნა
            </button>
          </div>
        </div>        
      </div>
    </div>

</template>

<script>
  import Util from 'Util'
  import useVuelidate from '@vuelidate/core'
  import { required } from '@vuelidate/validators'

  export default {
    props:['user', 'url', 'model', 'additional', 'setting'],
    data () {
      return {
        m: this.model
      }
    },
    setup () {
      return { v$ : useVuelidate() }
    },
    mounted() {
    },
    computed: {
      attrsLink() {
        return this.setting.url.nested.attributes.replace('api/', '').replace('__category__', this.model.id);
      },
      category () {
        return this.setting.url.request.index.replace('api/', '')
      }
    },
    validations () {
      return {
        m : {
          name: { required },
          type: { required },
          description: { required }
        }
      }
    },
    methods: {

      redirect(link) {
        return location.href = link
      },



      send(e) {
        e.preventDefault();

        let action = document.querySelector('form#render').getAttribute('action')
        let token = document.querySelector('meta[name="csrf-token"').getAttribute('content')

        this.v$.m.$touch()

        this.$http.post(action, this.m, {
          "Content-Type": "application/json",
          'X-CSRF-TOKEN': token
        })
        .then(async response => {

          Util.useSwall(response).then((result) => {

            if (this.model.id == null && response.data.success == true) {
              window.location.replace(window.location.href.replace('new', response.data.result.id));
            } else if (result.value) {
              window.location.replace(this.setting.url.request.index.replace('api/', ''))
            }

            if (!response.data.success) {
              response.data.errs.map(item => this.$toast.error(item, { position: 'top-right', duration: 7000 }))
            }

          })

        })
        .catch((e) => {
          Util.useSwall().then((result) => {
            this.$toast.error(e.response.statusText, { position: 'top-right', duration: 7000 })
          })
        });

        return false
      },
      exit () {
        return window.location.href = this.category
      }
    }
  } 
</script>