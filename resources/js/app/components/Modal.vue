<template>
	<div class="modal-mask">
      <div class="modal-wrapper">
        <div class="modal-container">

          <h3>აირჩიეთ {{selector == "purchasers" ? 'მყიდველი' : 'ატრიბუტი'}}</h3>

           <div class="form-group" v-for="(item, index ) in categories" :key="index" @change="redirect">
              <v-select v-if="selector == 'purchasers'" v-model="item.selected" :options="item.res" :get-option-label="(i) => i ? i.name + ' / ' + i.subj_name : ''" @option:selected="(e) => try_input(e)">
                <template #option="i">
                  <h3 style="margin: 0; font-size: 1.2em;">{{ i.name }}</h3>
                  <em>{{ i.subj_name }} {{ i.subj_address }}</em>
                </template>
              </v-select>
           	 <select v-else title="აირჩიეთ" placeholder="აირჩიეთ" v-model="item.selected" class="form-control" :index="index">
			         <option value="" disabled selected>--- აირჩიეთ ---</option>
			         <option v-if="item" v-for="(i, index2) in item.res" :value="json(i)" :key="index2">
                  {{selector == "purchasers" ? i.name + ' / ' + i.subj_name : i.name}}</option>
			       </select>
           </div>
             
          

              <button :disabled="!selected" type="button" class="btn btn-sm btn-success mr-1" @click="exit">
                არჩევა
              </button>

              <button type="button" class="btn btn-sm btn-warning mr-1" @click="exitEnd">
                დახურვა
              </button>

      
              <a v-if="selector == 'purchasers'" :href="setting.url.nested.edit" class="btn btn-sm btn-primary">მომხმარებლის დამატება</a>
           
         
        </div>
      </div>
    </div>
</template>

<script>

  import Util from 'Util'

  export default {
    props:['categories', 'selector', 'setting'],
    created() {},
    data () {
      return {
         categoryIndex: [],
         selected: null
    	}
    },
    computed: {},
    methods: {

      try_input (value) {
        this.selected = value
      },

      json(i) {
        return JSON.stringify(i)
      },

      fromJson(i) {
        return JSON.parse(i)
      },

      exitEnd () {
        this.$parent.$parent.showModal = false;
      },

      exit() {
        if (this.selector == 'purchasers') return this.$emit('exit', this.selected, this.selector)

        if ((this.selected.category_attributes && this.selected.category_attributes.length) || this.selected.category_type) {
          this.$toast.error("არჩეული ატრიბუტი განეკუთნება კატეგორიის ტიპს!", { position: 'top-right', furation: 7000 })
        } else {
          let tree = new Util.Tree(this.categoryIndex.category_attributes)
          let treeName = tree.getParents(this.selected)
          this.selected.category_name = treeName

          this.$emit('exit', this.selected, this.selector)
        }
      },

      purchiser () {

      },

      category () {
      	if (event.target.value) {
          let res = this.fromJson(event.target.value);
          let nextIndex = Number(event.target.getAttribute('index')) +1;
          let index = Number(event.target.getAttribute('index'));


           if (res.category_attributes) this.categoryIndex = res



           let result = (index > 0) ? res.nested : res.category_attributes


			
            this.categories.splice(nextIndex, this.categories.length)
          

          if (result.length) {

            this.categories[nextIndex] = { selected: '', res: result }
          }

          this.selected = res

        

        }
      },

      redirect(event) {
        if (this.selector == 'categories') {
        	this.category()
        } else if (this.selector == 'purchasers') {
        	this.selected = this.fromJson(event.target.value)
        }
      }
      
    }
  }
</script>

<style scoped>
.modal-mask {
  position: fixed;
  z-index: 10;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  background-color: rgba(0, 0, 0, 0.5);
  display: table;
  transition: opacity 0.3s ease;
}

.modal-wrapper {
  display: table-cell;
  vertical-align: middle;
}

.modal-container {
  width: 50%;
  margin: 0px auto;
  padding: 20px 30px;
  min-height: 30vh;
  background-color: #fff;
  border-radius: 2px;
  box-shadow: 0 2px 8px rgba(0, 0, 0, 0.33);
  transition: all 0.3s ease;
  font-family: Helvetica, Arial, sans-serif;
}

.modal-header h3 {
  margin-top: 0;
  color: #42b983;
  font-size: 17px;
}

.modal-body {
  margin: 20px 0;
}

.modal-default-button {
  float: right;
}
</style>