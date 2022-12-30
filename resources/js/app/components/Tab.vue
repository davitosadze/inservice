<template>
	<div class="card" :id="'card-'+index">

    <div class="card-header" :id="`heading${index}`">
      <h5 class="mb-0">
        <button onclick="return false;" class="btn btn-link" data-toggle="collapse" :data-target="`#collapse${index}`" aria-expanded="false" aria-controls="collapseOne">
          {{`N:${Number(index) + 1}`}}
        </button>
        <button @click="e => remove (e, model.items[index] || index)" class="float-right btn btn-link">
          <i class="fas fa-trash" style="font-size: 1.2em; cursor: pointer;"></i>
        </button>
      </h5>
    </div>

    <input type="hidden" :name="`item[${index}][0][uuid]`" :value="uuid" />
    <input type="hidden" :name="`item[${index}][0][title]`" value="1" />
    <input type="hidden" :name="`item[${index}][0][value]`" value="1" />
    <input type="hidden" :name="`item[${index}][0][parent_uuid]`" />

    <div :id="`collapse${index}`" :class="`collapse  ${ index == 0 ? 'show' : '' }`" :aria-labelledby="`heading${index}`" data-parent="#accordion">

      <div class="card-body ">

        <div class="form-group " v-for="(item, i) in tablist">
          <label for="exampleInputEmail1">{{item.title}}</label>

          <input type="hidden" :name="`item[${index}][${i+1}][uuid]`" :value="item.uuid" />
          <input type="hidden" :name="`item[${index}][${i+1}][parent_uuid]`" :value="item.parent_uuid" />
          <input type="hidden" :name="`item[${index}][${i+1}][title]`" :value="item.title" />

          <textarea :name="`item[${index}][${i+1}][value]`" class="form-control">{{item.value}}</textarea>

        
          <update-media class="mt-2"  :key="index" :index="index" :each="i+1" server="/reports/uploads" :media_server="`/reports/uploads2/${item.id}`"></update-media>

          <hr/>
      </div>


      </div>
    </div>
  </div>
</template>

<script>

	import Util from 'Util'

  import { UploadMedia, UpdateMedia } from '../vendors/vue-media-upload';

  export default {
    components: {
  	'update-media' : UpdateMedia
    },
    name: 'tab',
	     props:['message', 'index', 'res', 'model'],

  	   data() {
        return {
          m:{},
          fileRecords: []
        }
      },

      computed: {
      	uuid  () {
      		return this.res.uuid ? this.res.uuid : Util.uuid();
      	},

        titles () {
          return ['კვლევის საგნის დახასიათება:', 'დაზიანების პირველადი აღწერილობა:', 'დასკვნა:']
        },

        tablist () {
          return this.res.nested ? this.res.nested : Array.from({ length: 3 }, (_, i) => ({ title: this.titles[i], uuid:  Util.uuid(), parent_uuid: this.uuid, value: '' }));
        }
      },

    	created () {
        this.m = this.model;
    		console.log('model', this.model)
    	},

    	methods: {
    		newUuid () {
      		return Util.uuid();
      	 },

         removeRequest (id, callback) {
          console.log('id', id)

        if (!id) { 
          return callback() 
        }

        let action = document.querySelector('form#render').getAttribute('action')
        let token = document.querySelector('meta[name="csrf-token"').getAttribute('content')

        return this.$http.delete('/reports/' + id, { id }, {
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
          if (e.response) this.$toast.error(e.response.statusText, { position: 'top-right', duration: 7000 })
        });
      },

      remove (e, index) {
        e.preventDefault();
        return  Util.useSwall2(this.m).then((result) => {
          if (result.isConfirmed) {
            let tabIndex = this.m.items.find(i => i.id == index.id);

            this.removeRequest(tabIndex ? tabIndex.id : null, () => {
              let tabSplace = this.m.items.findIndex(i => i.id == index.id);
              if (tabIndex) { this.m.items.splice(tabSplace, 1); document.querySelector('#card-'+tabSplace).remove() }
              else { document.querySelector('#card-'+index).remove(); }

              Swal.fire('წაშლა!', 'წაშლა შესრრულდა წარმატებით.', 'success')
            })
          }
        })

        return false;
      }
  	}


  }
	
	
</script>