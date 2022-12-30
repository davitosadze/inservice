<template>

	 <div class="container-fluid">


        <div class="row">
          <div class="col-12">
            


            <!-- Main content -->
            <div class="invoice p-3 mb-3">
              <p class="lead">მყიდველი</p>
              <div class="row invoice-info">
                
                <div class="col-sm-4 invoice-col">
                  <address>
                    <input type="hidden" v-model="m.purchaser">

                    <b>სახელი :</b> {{m.purchaser ? m.purchaser.name : ''}} <br>
                    <b>სუბიექტის სახელი :</b> {{m.purchaser ? m.purchaser.subj_name : ''}} <br>
                    <b>სუბიექტის მისამართი :</b> {{m.purchaser ? m.purchaser.subj_address : ''}} <br>
                    Phone: (555) 539-1037<br>
                    Email: john.doe@example.com
                  </address>
                </div>
                <!-- /.col -->
                <div class="col-sm-4 invoice-col">
                  <b>Invoice #007612</b><br>
                  <br>
                  <b>Order ID:</b> 4F3S8J<br>
                  <b>Payment Due:</b> 2/22/2017<br>
                  <b>Account:</b> 968-34567
                </div>
                <!-- /.col -->
              </div>
               <button :disabled="m.purchaser" type="button" @click="setter('purchasers')" class="btn btn-sm btn-outline-success"><i class="fas fa-shield-alt"></i> დამატება </button>
             
              <!-- /.row -->
<hr>
              <!-- Table row -->
              <div class="row">
                <div class="col-12 table-responsive">
                  <p class="lead">პროდუქტი</p>

                  <table class="table table-striped" style="background: #edebe4; ">
                    <thead>
                    <tr>
                      <th style="width: 17%;">დასახელება</th>
                      <th>აღწერა</th>
                      <th>ერთეული</th>
                      <th>რაოდენობა</th>
                      <th style="width: 10%;">ფასი</th>
                      <th style="width: 10%;">ხელ.ფასი</th>
                      <th style="width: 10%;">ღირებულება</th>
                      <th>action</th>
                    </tr>
                    </thead>
                    <tbody>

                    <tr v-for="(item, index) in m.category_attributes">

                      <td>
                        <input class="form-control" type="text" v-model="item.category.name">
                      </td>
                      <td>
                        <input class="form-control" type="text" v-model="item.name">
                      </td>
                      <td>
                        <input class="form-control" type="text" v-model="item.item">
                      </td>
                      <td>
                        <input class="form-control" min="1" type="number" 
                          :value="item.pivot.qty" @input="event => price(event, item)" @click="e => e.target.select()">
                      </td>
                      <td>
                        <input class="form-control" name="price" type="text" 
                          v-model="item.pivot.price" @input="event => price(event, item, true)" @click="e => e.target.select()">
                      </td>
                      <td>
                        <input class="form-control" name="service_price" type="text" 
                        v-model="item.pivot.service_price" @input="event => price(event, item, true)" @click="e => e.target.select()">
                      </td>
                       <td>
                        <input class="form-control" type="text" readonly :value="item.pivot.calc">
                      </td>
                      <td>
                        <button @click="event => remove(event, item)">delete</button>
                      </td>
                    </tr>

                    <tr class="calculator">
                        <th colspan="4">დაჯამება : </th>
                        <th>
                          <input class="form-control" type="text" readonly v-model="agr.price">
                        </th>
                        <td>
                          <input class="form-control" type="text" readonly v-model="agr.service_price">
                        </td>
                        <td>
                          <input class="form-control" type="text" readonly v-model="agr.calc">
                        </td>
                        <td></td>
                      </tr>

                    </tbody>
                  </table>
                  <button type="button" @click="setter('categories')" class="btn btn-sm btn-outline-success"><i class="fas fa-shield-alt"></i> დამატება </button>
                </div>
                <!-- /.col -->
                 
              </div>
              <!-- /.row -->
<hr>
              <div class="row">
                
                <div class="col-12">
                  <p class="lead">ანგარიში</p>

                  <div class="table-responsive">
                    <table class="table">

                      <tr v-for="(input, index) in c">
                        <th style="width:30%"> {{input.name}} </th>
                        <th>
                          <input type="number" min="0" @input="e => { recurcive(c, agr.calc) }" v-model="m[input.keyName]">
                        </th>
                        <td style="margin:0; padding: 0; width: 17%;">
                          <table class="table" style="margin:0; padding: 0;">
                            <tr>
                              <td style="margin:0; padding: 3px; text-align: right;">{{input.parcer.p1}}</td>
                             </tr>
                             <tr>
                              <td style="margin:0; padding: 3px; text-align: right;">{{input.parcer.p2}}</td>
                             </tr>
                          </table>
                        </td>
                      </tr>

                    </table>
                  </div>
                </div>
                <!-- /.col -->
              </div>
              <!-- /.row -->
<hr>
              <!-- this row will not appear when printing -->
              <div class="row no-print">
                <div class="col-12">
                  <a href="invoice-print.html" rel="noopener" target="_blank" class="btn btn-default"><i class="fas fa-print"></i> Print</a>
                  <button @click="send" type="button" class="btn btn-success float-right"><i class="far fa-credit-card"></i> Submit
                    Payment
                  </button>
                  <button type="button" class="btn btn-primary float-right" style="margin-right: 5px;">
                    <i class="fas fa-download"></i> Generate PDF
                  </button>
                </div>
              </div>
            </div>
            <!-- /.invoice -->
          </div><!-- /.col -->
        </div><!-- /.row -->

        <modal v-if="showModal" @exit="exit" :categories="selectBuilder" :selector="selector"></modal>
   </div>

</template>

<script>

  export default {
    props:['user', 'model', 'links', 'additional'],
    created() {
      this.m = this.model
      console.log("model", this.model)

      // console.log('test', this.recurcive(this.test, 3))
      console.log('p1: 0, p2: 0', this.calculate)
      this.c = this.calculate
    },
    data () {
      return {
        showModal: false,
        selector: "",

        agr: {},
        m: {},
        c: [],

        names: ['მასალის ტრანსპორტირების ჯამი :', 'ზედნადები ხარჯი :', 'მოგება :', 'გაუთველისწინებელი ხარჯი :', 'დღგ :'],

        selectBuilder: []
      }
    },
    watch: {
      'm.category_attributes': {
        handler (newValue, prevValue) {
          if (newValue && newValue.length) {
            let agr = newValue.reduce((a, b) => {

            a['price'] = (a['price'] || 0) + this.specNum(parseFloat(b.pivot.price));
            a['service_price'] = (a['service_price'] || 0) + this.specNum(parseFloat(b.pivot.service_price));
            a['calc'] = this.specNum((a['calc'] || 0) + this.specNum(parseFloat(b.pivot.calc)));

            return a
           }, {})

            // agr = Object.entries(agr).map(([price, service_price]) => ({ price, service_price}))

            this.agr = agr;
          } else {
            this.agr = {price: 0, service_price: 0, calc: 0}
          }
        },
        deep: true,
        immediate: true
      },
      agr: {
        handler (newValue, prevValue) {

          if (!this._isEmpty(newValue)) {
            this.recurcive(this.c.length ? this.c : this.calculate, newValue.calc)
          } else {
            this.recurcive(this.c, 0)
          }
        },
        deep: true,
        immediate: true
      }
    },
    computed: {
      calculate () {
        return Array.from({ length : 5}, (_, i) => {
          let name = this.names[i]
          let index = "p" + (i+1)
          let value = this.m[index]
          return { name: name, keyName: index, value: value, parcer: { p1: 0, p2: 0 } }
        })
      }
    },
    methods: {

      _isEmpty(subject) {
        return _.isEmpty(subject);
      },

      recurcive (arr, price, index = 0) {
        console.log('1')
        if (arr[index] != undefined) {
          arr[index].parcer.p1 = this.specNum((this.m[arr[index].keyName] * price) / 100)
          arr[index].parcer.p2 = this.specNum(price + arr[index].parcer.p1)
          let nextPrice = arr[index].parcer.p2

          console.log('nextPrice', nextPrice)

          index = index + 1
          return this.recurcive(arr, nextPrice, index)
        } else {
          return arr;
        }
      },

      specNum (num) {
        if (!num) return 0.00
        let number = (Math.ceil((parseFloat(num)) * 100) / 100)
        // return parseFloat(new Intl.NumberFormat('en-Us', {
        //   minimumFractionDigits: 2,
        //   maximumFractionDigits: 2
        // }).format(num));
        return parseFloat(number);
      },

      price (event, price, alter = false) {

          if (!event.target.value) {
            price.pivot[event.target.getAttribute('name')] = 0
          }
        
          if (!alter) {
            if (Number(event.target.value) > 0) {
              // this.model.pivot[price.id].qty = Number(event.target.value)
              let targetValue = Number(event.target.value)
              let _price = (Number(event.target.value) >= price.pivot.qty) ? parseFloat(price.pivot.price) : parseFloat(price.price)
              let _service_price = (Number(event.target.value) >= price.pivot.qty) ? parseFloat(price.pivot.service_price) : parseFloat(price.service_price)

              if ((price.pivot.qty >= Number(event.target.value))) {
                price.pivot.price = parseFloat(price.price)
                price.pivot.service_price = parseFloat(price.service_price)
              }

              // price.pivot.price = this.specNum(targetValue * _price)
              // price.pivot.service_price = this.specNum(targetValue * _service_price)
              price.pivot.calc = this.specNum(targetValue * (_price + _service_price))

              price.pivot.qty = Number(event.target.value)
            }

            // console.log("model", model)
          } else {
            price.pivot.calc = this.specNum(price.pivot.qty * (parseFloat(price.pivot.price) + parseFloat(price.pivot.service_price)))
          }
        
      },

      remove (event, price) {
        let index = this.m.category_attributes.findIndex(i => i.id == price.id);
        this.m.category_attributes.splice(index, 1)
      },

      exit (res, selector) {

        this.showModal = false

        if (selector == "purchasers") {
          this.model.purchaser = res
        } else if (selector == "categories") {

          // if (this.model.pivot[res.id]) {
          //   this.model.pivot[res.id]['qty'] = {qty: res.qty} : {qty: 1}
          // } else {
          //   this.model.pivot[res.id] = [];
          //   this.model.pivot[res.id]['qty'] = {qty: res.qty} : {qty: 1}
          // }

          // this.model.pivot[res.id] = res.qty ? {qty: res.qty} : {qty: 1}
        
          let find = this.m.category_attributes.find(i => i.id == res.id)
          if (find) {
            find.pivot.qty = find.pivot.qty + 1;
            find.pivot.price = res.price * find.pivot.qty;
          } else {
            this.m.category_attributes.push({
              ...res, 
              pivot: { qty: 1, price: res.price, service_price: res.service_price, category_id: res.id, calc:  (res.service_price + res.price)}
            })
          }
        }

        console.log("model", this.model)
      },

      setter (name) {
        this.selectBuilder = [{ selected: '', res: this.additional[name] }]
        this.selector = name;
        this.showModal = true
      },

      send(e) {
        e.preventDefault();

        // delete(this.model.category_attributes)
        // delete(this.model.pivot)
        // delete(this.model.purchaser)

          this.$http.post(
            document.querySelector('form#render').getAttribute('action'), 
            this.model, {
              "Content-Type": "application/json", 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"').getAttribute('content')
            }).then(async response => {

    
               console.log("response", response);
              }).catch(function (error) {
              console.log(error);
            });
       
        return false
      }
    }
  } 
</script>

<style>
  table tr > td, table tr > th {
    vertical-align: middle;
  }

  .table td, .table th {
    vertical-align: middle;
  }

  table.table-striped {
    border-top-color: #000; 
    border-top-width: 1; 
    border-top-style: solid;
  }

  table.table-striped tr.calculator {
    background-color: #fff;

    border-bottom-color: #000; 
    border-bottom-width: 1; 
    border-bottom-style: solid;

    border-top-color: #000; 
    border-top-width: 1; 
    border-top-style: solid;
  }
</style>