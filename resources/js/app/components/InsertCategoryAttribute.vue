<template>

  <div class="card card-primary card-outline card-tabs">
    <div class="card-body">
              <div class="row invoice-info">
                
                <div class="col-sm-4 invoice-col">
                  <address>

                    <b>კატეგორიის სახელი :</b> {{additional.category.name}} <br>
                    <b>ერთეული :</b> {{additional.category.type}} <br>
                  </address>
                </div>
                
              </div>
              <hr/>

      <div class="row">

        <div class="col">

          <ul class="starter">

            <div class="mb-3">
              <button @click="add" type="button" class="btn btn-success"><i class="fas fa-plus"> დამატება</i></button>
            </div>
            <hr/>

            <template v-for="(item, index) in treeData" :key="index">
              <TreeItem :setting="setting" class="item" :model="item" :tree="item.nested.length ? item.nested : treeData" :id="index"> </TreeItem>
            </template>
          </ul>
          
          <hr/>

          <button @click="exit" type="button" class="btn btn-danger" style="margin-right: 5px;">
              <i class="far fa-window-close"></i> გასვლა
          </button>

          <button :disabled="v$.$invalid" @click="send" type="" class="btn btn-success">
              <i class="far fa-paper-plane"></i> გაგზავნა
          </button>
          
        </div>

        
      </div>        
    </div>
  </div>

</template>

<script>
  import Util from 'Util'
  import TreeItem from './TreeItem.vue'
  import useVuelidate from '@vuelidate/core'

  export default {
    props:['user', 'url', 'model', 'additional', 'setting'],
    components: {
      TreeItem
    },
    data() {
      return {
        treeData: []
      }
    },
    setup (props) {
      console.log('props', props)
      return { v$ : useVuelidate() }
    },
    validations () {
      return {
        treeData : {}
      }
    },
    computed: {
      category () {
        return this.setting.url.request.exit.replace('new', this.additional.category.id)
      }
    },
    created() {
      if (!this.model.length) {
        this.model.push(Util.instance(this.model, true, this.additional))
      }
      this.treeData = this.model
    },
    mounted() {
      console.log("model", this.model);
    },
    methods: {

      exit () {
        return window.location.href = this.category
      },
      
      add () {
        // let lastIndex = this.treeData.slice(-1)[0].id + 1;
        this.treeData.push(Util.instance(this.model, true, this.additional))
      },

      send(e) {

       e.preventDefault();

       // console.log('Util.exit(this.model)', Util.exit(this.model))

        let action = document.querySelector('form#render').getAttribute('action')
        let token = document.querySelector('meta[name="csrf-token"').getAttribute('content')

        this.v$.treeData.$touch()

        this.$http.post(action, Util.exit(this.model), {
          "Content-Type": "application/json",
          'X-CSRF-TOKEN': token
        })
        .then(async response => {

          Util.useSwall(response).then((result) => {
            
            if (!response.data.success) {
              if (response.data.errs) response.data.errs.map(item => this.$toast.error(item, { position: 'top-right', duration: 7000 }))
            }

            if (result.value) {
              window.location.replace(this.setting.url.request.index.replace('api/', ''))
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
  .starter {
    margin: 0;
    padding: 0;
  }
</style>