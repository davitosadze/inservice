<template>
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <!-- Main content -->
                <div class="invoice p-3 mb-3">
                    <div style="">
                        <div class="form-group row">
                            <label
                                for="staticEmail"
                                class="col-sm-3 col-form-label"
                                ><b>დასახელება:</b></label
                            >
                            <div class="col-sm-9">
                                <input
                                    class="form-control"
                                    type="text"
                                    v-model="v$.model.$model.title"
                                />
                            </div>
                        </div>
                    </div>
                    <hr />

                    <p class="lead">მყიდველი</p>
                    <div class="row invoice-info">
                        <div class="col-sm-8 invoice-col">
                            <address>
                                <input type="hidden" v-model="m.purchaser" />

                                <div
                                    class="row mb-2"
                                    style="align-items: center"
                                >
                                    <div class="col-4">
                                        <b>კლიენტის სახელი :</b>
                                    </div>
                                    <div class="col-8">
                                        <input
                                            class="form-control"
                                            type="text"
                                            v-model="
                                                v$.model.purchaser.$model.name
                                            "
                                        />
                                    </div>
                                </div>

                                <div
                                    class="row mb-2"
                                    style="align-items: center"
                                >
                                    <div class="col-4">
                                        <b>დამატებითი სახელი :</b>
                                    </div>
                                    <div class="col-8">
                                        <input
                                            class="form-control"
                                            type="text"
                                            v-model="
                                                v$.model.purchaser.$model
                                                    .subj_name
                                            "
                                        />
                                    </div>
                                </div>

                                <div
                                    class="row mb-2"
                                    style="align-items: center"
                                >
                                    <div class="col-4">
                                        <b>კლიენტის მისამართი :</b>
                                    </div>
                                    <div class="col-8">
                                        <input
                                            class="form-control"
                                            type="text"
                                            v-model="
                                                v$.model.purchaser.$model
                                                    .subj_address
                                            "
                                        />
                                    </div>
                                </div>

                                <div
                                    class="row mb-2"
                                    style="align-items: center"
                                >
                                    <div class="col-4">
                                        <b>საიდენთიფიკაციო კოდი :</b>
                                    </div>
                                    <div class="col-8">
                                        <input
                                            class="form-control"
                                            type="text"
                                            v-model="
                                                v$.model.purchaser.$model
                                                    .identification_num
                                            "
                                        />
                                    </div>
                                </div>

                                <!-- Phone: (555) 539-1037<br>
                    Email: john.doe@example.com -->
                            </address>
                        </div>
                        <!-- /.col -->
                        <div
                            class="col-sm-4 invoice-col"
                            style="
                                align-items: flex-end;
                                display: flex;
                                flex-direction: column;
                                text-align: right;
                            "
                        >
                            <div>
                                <b>Invoice {{ model.uuid }}</b
                                ><br />
                            </div>
                            <div v-if="model.parent">
                                <b>დაკოპირებულია:</b> {{ model.parent.uuid }}
                            </div>
                        </div>
                        <!-- /.col -->
                    </div>
                    <button
                        :disabled="m.purchaser.id && model.id"
                        type="button"
                        @click="setter('purchasers')"
                        class="btn btn-sm btn-outline-success"
                    >
                        <i class="fas fa-shield-alt"></i> დამატება
                    </button>
                    <!-- /.row -->

                    <div class="row">
                        <div
                            class="col text-end d-flex align-items-center justify-content-end"
                        >
                            <label for="count" class="mr-2"
                                >საგარანტიო ვადა:
                            </label>
                            <input
                                type="number"
                                id="count"
                                class="form-control form-control-sm w-auto"
                                v-model="v$.model.$model.warranty_period"
                                style="max-width: 80px"
                            />
                        </div>
                    </div>

                    <hr />

                    <!-- Table row -->
                    <div class="row">
                        <div class="col-12 table-responsive">
                            <p class="lead">მასალები</p>

                            <table class="table table-striped" style="background: #edebe4; color: #fff">
  <thead>
    <tr>
      <th style="width: 30%">დასახელება</th>
      <th style="width: 20%">აღწერა</th>
      <th>ერთეული</th>
      <th>რაოდენობა</th>
      <th style="text-align: center">მასალის ფასი</th>
      <th style="text-align: center">მომსახურების ფასი</th>
      <th>ჯამი</th>
      <th>ქმედება</th>
    </tr>
  </thead>

  <draggable
    v-model="m.category_attributes"
    tag="tbody"
    item-key="id"
    :disabled="step"
  >
    <template #item="{ element }">
      <request-single-attribute
        @action_focus="try_focus"
        @action_blur="try_blur"
        @action_price="price"
        @action_remove="remove"
        :keys="keys"
        :join-in-tree="attributeJoinedTree(element)"
        :item="element"
        :model="m"
      />
    </template>
  </draggable>

  <tbody>
    <tr class="calculator">
      <th colspan="4">დაჯამება :</th>
      <th>
        <input
          class="form-control"
          type="text"
          readonly
          v-model="agr.price"
        />
      </th>
      <td>
        <input
          class="form-control"
          type="text"
          readonly
          v-model="agr.service_price"
        />
      </td>
      <td>
        <input
          class="form-control"
          type="text"
          readonly
          v-model="agr.calc"
        />
      </td>
      <td></td>
    </tr>
  </tbody>
</table>

                            <button
                                type="button"
                                @click="setter('categories')"
                                class="btn btn-sm btn-outline-success mr-1"
                            >
                                <i class="fas fa-shield-alt"></i> დამატება
                            </button>

                            <button
                                type="button"
                                @click="findSpecialAtribute('new')"
                                class="btn btn-sm btn-outline-warning"
                            >
                                <i class="fas fa-shield-alt"></i> ახალი
                            </button>
                        </div>
                        <!-- /.col -->
                    </div>
                    <!-- /.row -->
                    <hr />

                    <!-- /.row -->
                    <hr />

                    <!-- this row will not appear when printing -->
                    <div class="row no-print">
                        <div class="col-12">
                            <a rel="" @click="exit" class="btn btn-warning mr-1"
                                >გასვლა</a
                            >
                            <a
                                rel=""
                                target="_blank"
                                @click="(e) => remove(e, model, true)"
                                class="btn btn-danger"
                                >წაშლა</a
                            >

                            <button
                                :disabled="v$.m.$invalid || v$.model.$invalid"
                                @click="send"
                                type="button"
                                class="btn btn-success float-right"
                            >
                                <i class="far fa-credit-card"></i> შენახვა
                            </button>
                            <button
                                :disabled="v$.m.$invalid || v$.model.$invalid"
                                @click="(e) => gadawera(e, model, true)"
                                type="button"
                                class="btn btn-primary float-right"
                                style="margin-right: 5px"
                            >
                                <i class="far fa-copy"></i> გადაწერა
                            </button>
                        </div>
                    </div>

                    <hr />

                    <h3>კალკულატორი</h3>
                    <hr />
                    <div class="overflow-x-auto">
                        <table class="table-auto border-collapse border border-gray-400 w-full">
  <thead>
    <tr class="bg-gray-200">
      <th class="border border-gray-400 p-2">ტიპი</th>
      <th class="border border-gray-400 p-2">შესასყიდი მასალის ფასი</th>
      <th class="border border-gray-400 p-2">ფასნამატი</th>
      <th class="border border-gray-400 p-2">გასაყიდი ჯამი</th>
      <th class="border border-gray-400 p-2">
        ფასდაკლება პროცენტულად (არაუმეტეს 7%)
      </th>
      <th class="border border-gray-400 p-2">ფასდაკლების თანხა</th>
      <th class="border border-gray-400 p-2">სრულყოფილი გასაყიდი ფასი</th>
    </tr>
  </thead>
  <tbody>
    <tr>
      <td>
        <select v-model="calculatorType" id="calculatorType" class="form-control">
          <option value="0">
            ფინანსური ოპერაცია რომლის დროსაც დღგ-ს ვითვლით (მასალა)
          </option>
          <option value="1">
            ფინანსური ოპერაცია რომლის დროსაც მხოლოდ დღგ-ს ვიხდით (მეწარმე)
          </option>
          <option value="2">
            ფინანსური ოპერაცია რომლის დროსაც საშემოსავლოს და დღგ-ს ვიხდით (ფიზ. პირი)
          </option>
        </select>
      </td>
      <td class="border border-gray-400 p-2 bg-gray-300">
        <input type="number" v-model.number="minPayment" class="w-full text-center" />
      </td>
      <td class="border border-gray-400 p-2 bg-gray-300">
        {{ priceIncrease }}
      </td>
      <td class="border border-gray-400 p-2">{{ sellSum }}</td>
      <td class="border border-gray-400 p-2">
        <input
          type="number"
          v-model.number="discountPercentage"
          @input="validateDiscount"
          class="w-full text-center"
        />
      </td>
      <td class="border border-gray-400 p-2">{{ discountAmount }}</td>
      <td class="border border-gray-400 p-2">{{ finalPrice }} ლარი</td>
    </tr>
  </tbody>
</table>

                    </div>
                </div>
                <!-- /.invoice -->
            </div>
            <!-- /.col -->
        </div>
        <!-- /.row -->

        <modal
            v-if="showModal"
            @exit="try_exit"
            :categories="selectBuilder"
            :selector="selector"
            :setting="setting"
        ></modal>
    </div>
</template>

<script>
import Util from "Util";
import useVuelidate from "@vuelidate/core";
import { required, maxLength } from "@vuelidate/validators";
import draggable from "../vendors/vuedraggable/src/vuedraggable";

export default {
    props: ["user", "model", "setting", "additional"],
    components: {
        draggable,
    },
    setup(props) {
        return { v$: useVuelidate() };
    },
    created() {
        this.m = this.attributeInit;

        // console.log('this.additional', this.additional)

        // console.log('test', this.recurcive(this.test, 3))
        // console.log('p1: 0, p2: 0', this.calculate)
        // this.c = this.calculate
        // console.log('this.c', this.c)

        // let initReporteValue = Util.initReporteValues(this.names, this.m);

        // console.log('test', initReporteValue)
        // console.log('test2', Util.itemReportValue(30, this.m, initReporteValue))

        this.calculator.percenters = this.percenters;
        this.calculator.inputs = this.initReporteValues;

        this.keys = ["price", "service_price", "calc"];

        if (!this.model.purchaser)
            this.model.purchaser = {
                name: "",
                subj_name: "",
                subj_address: "",
                identification_num: "",
            };
    },
    mounted() {
        if (this.m.category_attributes.length && Util._isEmpty(this.agr)) {
            this.agr = Util.agr(this.m.category_attributes);
        }
        this.v$.model.$touch();
        this.v$.m.$touch();
    },
    data() {
        return {
            num1: 0,
            num2: 0,
            result: null,
            showModal: false,
            selector: "",
            step: false,
            minPayment: 0,
            markup: 0,
            discountPercentage: 0,
            calculatorType: "0",
            agr: {},
            m: {},
            c: [],

            calculator: {
                names: [
                    "მასალის ტრანსპორტირების ჯამი :",
                    "ზედნადები ხარჯი :",
                    "მოგება :",
                    "გაუთველისწინებელი ხარჯი :",
                    "დღგ :",
                ],
            },
            keys: [],

            selectBuilder: [],
        };
    },
    watch: {
        "m.category_attributes": {
            handler(newValue, prevValue) {
                if (newValue && newValue.length && prevValue) {
                    this.agr = Util.agr(newValue, true);
                }
            },
            deep: true,
            immediate: true,
        },
        agr: {
            handler(newValue, prevValue) {
                if (!Util._isEmpty(newValue) && prevValue) {
                    this.recurcive(this.calculator.inputs, newValue.calc);
                }
            },
            deep: true,
            immediate: true,
        },
        "calculator.inputs": {
            handler(newValue, prevValue) {
                if (newValue && prevValue && newValue.length) {
                    newValue.map((i) => {
                        this.m[i.inputName] = i.value;
                    });

                    this.m.category_attributes.map((attribute) => {
                        this.keys.map((key) => {
                            if (attribute.pivot[key]) {
                                attribute.pivot[key] = Util.itemReportValue(
                                    attribute[key],
                                    this.initReporteValues
                                );
                            }
                        });
                    });
                }
            },
            deep: true,
            immediate: true,
        },
    },
    validations() {
        let validateRule = {
            m: {
                category_attributes: { required },
            },
            model: {
                title: { required },
                warranty_period: { required },
                purchaser: {
                    name: { required },
                    subj_name: { required },
                    subj_address: { required },
                    identification_num: { required },
                },
            },
        };
        return validateRule;
    },
    computed: {
        finalPrice() {
            return (
                Number(this.sellSum) -
                Number(this.sellSum * (this.discountPercentage / 100))
            ).toFixed(2);
        },
        withAdditionalCosts() {
            let extraPercentage = 0;

            if (this.calculatorType === "1") {
                extraPercentage = 18;
            } else if (this.calculatorType === "2") {
                extraPercentage = 47.5;
            }

            if (this.calculatorType === "0") {
                return this.minPayment;
            }

            return this.minPayment + (this.minPayment * extraPercentage) / 100;
        },

        priceIncrease() {
            return (
                this.minPayment
                    ? this.withAdditionalCosts *
                      (this.additional.price_increase / 100)
                    : 0
            ).toFixed(2);
        },
        sellSum() {
            return (
                Number(this.withAdditionalCosts) + Number(this.priceIncrease)
            ).toFixed(2);
        },
        discountAmount() {
            return Number(
                this.sellSum * (this.discountPercentage / 100)
            ).toFixed(2);
        },
        attributeJoinedTree: (app) => (item) => {
            return item.category_id
                ? app.additional.categories.find(
                      (i) => i.id == item.category_id
                  ).category_attributes
                : [];
        },

        initReporteValues() {
            return Array.from(
                { length: this.calculator.names.length },
                (_, i) => {
                    let title = this.calculator.names[i];
                    let inputName = "p" + (i + 1);
                    let value = this.m[inputName];

                    return { title, inputName, value };
                }
            );
        },

        percenters() {
            return Array.from(
                { length: this.calculator.names.length },
                (_, i) => {
                    return { p1: 0, p2: 0 };
                }
            );
        },

        attributeInit() {
            this.model.category_attributes =
                this.model.category_attributes.reduce((acumu, res) => {
                    let init = res;
                    let hasSpecial = this.model.special.find(
                        (s) => s.category_attribute_id === res.id
                    );
                    if (hasSpecial) {
                        init.isSpecial = true;
                    }
                    if (hasSpecial && !res.pivot.is_special) {
                        res.pivot.price = hasSpecial.json.price;
                        res.pivot.is_special = true;
                        res.pivot.service_price = hasSpecial.json.service_price;
                        res.pivot.calc = this.specNum(
                            res.pivot.qty *
                                (parseFloat(res.pivot.price) +
                                    parseFloat(res.pivot.service_price))
                        );
                        init = { ...hasSpecial.json, pivot: res.pivot };
                    }
                    (acumu || []).push(init);
                    return acumu;
                }, []);
            return this.model;
        },
    },
    methods: {
        validateDiscount() {
            const maxDiscount = this.calculatorType === "0" ? 7 : 15;
            if (this.discountPercentage > maxDiscount) {
                this.discountPercentage = maxDiscount;
                alert(
                    "პროცენტულობა არ უნდა იყოს " + maxDiscount + "% -ზე მეტი"
                );
            }
        },
        addNumbers() {
            this.result = parseFloat(this.num1) + parseFloat(this.num2);
        },
        recurcive(arr, price, index = 0) {
            if (arr[index] != undefined) {
                this.calculator.percenters[index].p1 = this.specNum(
                    (arr[index].value * price) / 100
                );
                this.calculator.percenters[index].p2 = this.specNum(
                    price + this.calculator.percenters[index].p1
                );

                let nextPrice = this.calculator.percenters[index].p2;

                index = index + 1;
                return this.recurcive(arr, nextPrice, index);
            } else {
                return arr;
            }
        },

        specNum(num) {
            if (!num) return 0.0;
            let number = Math.ceil(parseFloat(num) * 100) / 100;
            // return parseFloat(new Intl.NumberFormat('en-Us', {
            //   minimumFractionDigits: 2,
            //   maximumFractionDigits: 2
            // }).format(num));
            return parseFloat(number);
        },

        price(event, price, alter = false) {
            if (!event.target.value) {
                price.pivot[event.target.getAttribute("name")] = 0;
            }
            if (!alter) price.pivot.qty = Number(event.target.value);
            price.pivot.calc = this.specNum(
                price.pivot.qty *
                    (parseFloat(price.pivot.price) +
                        parseFloat(price.pivot.service_price))
            );
        },

        removeRequest(model, setting, callback, isReserve = null) {
            let isNew = isReserve ? model.id : model.pivot.id;
            let invoice = this.m.id ? this.m.id : !!this.m.id;

            if (!isNew) {
                return callback();
            }

            let action = document
                .querySelector("form#render")
                .getAttribute("action");
            let token = document
                .querySelector('meta[name="csrf-token"')
                .getAttribute("content");

            return this.$http
                .delete(
                    `${setting.url.nested.destroy.replace("__id__", isNew)}`,
                    { data: { reserve: isReserve, invoice } },
                    {
                        "Content-Type": "application/json",
                        "X-CSRF-TOKEN": token,
                    }
                )
                .then(async (response) => {
                    if (response.data.success == true) {
                        callback();
                    } else {
                        response.data.errs.map((item) =>
                            this.$toast.error(item, {
                                position: "top-right",
                                duration: 7000,
                            })
                        );
                    }
                })
                .catch((e) => {
                    if (e.response.statusText)
                        this.$toast.error(e.response.statusText, {
                            position: "top-right",
                            duration: 7000,
                        });
                });
        },

        remove(e, model, isReserve) {
            e.preventDefault();
            return Util.useSwall2(model).then((result) => {
                if (result.isConfirmed) {
                    this.removeRequest(
                        model || null,
                        this.setting,
                        () => {
                            if (!isReserve) {
                                let index =
                                    this.m.category_attributes.findIndex(
                                        (i) => i.uuid == model.uuid
                                    );
                                this.m.category_attributes.splice(index, 1);
                            }

                            Swal.fire(
                                "წაშლა!",
                                "წაშლა შესრრულდა წარმატებით.",
                                "success"
                            ).then(() => {
                                if (isReserve) {
                                    window.location.replace(
                                        this.setting.url.request.index.replace(
                                            "api/",
                                            ""
                                        )
                                    );
                                }
                            });
                        },
                        isReserve
                    );
                }
            });
        },

        findSpecialAtribute(res) {
            let existInModel = this.m.category_attributes.find(
                (i) => i.id == res.id
            );
            let existInSpeciail = this.m.special.find(
                (i) => i.category_attribute_id == res.id
            );
            if (res == "new") {
                // this.m.category_attributes.push({
                //   category_id: null,
                //   name: '',
                //   pivot:[],
                //   nested:[],
                //   price:0,
                //   service_price:0,
                //   pivot: {
                //     qty: 1,
                //     price: '',
                //     service_price: '',
                //     calc:  '',
                //     evaluation_calc:  ''
                //   }
                // })

                this.m.category_attributes.push({
                    category_id: null,
                    name: "",
                    pivot: [],
                    nested: [],
                    price: 0,
                    item: "",
                    uuid: Util.uuid(),
                    parent_uuid: null,
                    category_type: 0,
                    service_price: 0,
                    pivot: {
                        qty: 1,
                        price: "",
                        service_price: "",
                        calc: "",
                        evaluation_calc: "",
                    },
                });
            } else if (existInModel) {
                existInModel.pivot.qty = existInModel.pivot.qty + 1;
                // existInModel.pivot.price = existInModel.price * existInModel.pivot.qty;
                existInModel.pivot.calc = this.specNum(
                    existInModel.pivot.qty *
                        (parseFloat(existInModel.pivot.price) +
                            parseFloat(existInModel.pivot.service_price))
                );
                existInModel.pivot.evaluation_calc = Util.itemReportValue(
                    existInModel.pivot.calc,
                    this.initReporteValues
                );
            } else if (existInSpeciail) {
                existInSpeciail = existInSpeciail.json;
                existInSpeciail.pivot = {
                    qty: 1,
                    price: Util.itemReportValue(
                        existInSpeciail.price,
                        this.initReporteValues
                    ),
                    is_special: true,
                    category_attribute_id: res.id,
                    service_price: Util.itemReportValue(
                        existInSpeciail.service_price,
                        this.initReporteValues
                    ),
                    calc: this.specNum(
                        existInSpeciail.service_price + existInSpeciail.price
                    ),
                    evaluation_calc: Util.itemReportValue(
                        this.specNum(
                            existInSpeciail.service_price +
                                existInSpeciail.price
                        ),
                        this.initReporteValues
                    ),
                };
                this.m.category_attributes.push(existInSpeciail);
            } else {
                this.m.category_attributes.push({
                    ...res,
                    pivot: {
                        qty: 1,
                        price: Util.itemReportValue(
                            res.price,
                            this.initReporteValues
                        ),
                        category_attribute_id: res.id,
                        service_price: Util.itemReportValue(
                            res.service_price,
                            this.initReporteValues
                        ),
                        calc: this.specNum(res.service_price + res.price),
                        evaluation_calc: Util.itemReportValue(
                            this.specNum(res.service_price + res.price),
                            this.initReporteValues
                        ),
                    },
                });
            }
        },

        try_exit(res, selector) {
            this.showModal = false;

            if (selector == "purchasers") {
                this.model.purchaser = res;
                this.model.special = res.special_attributes;
            } else if (selector == "categories") {
                this.findSpecialAtribute(res);
            }
        },

        setter(name) {
            this.selectBuilder = [{ selected: "", res: this.additional[name] }];
            this.selector = name;
            this.showModal = true;
        },

        send(e) {
            e.preventDefault();

            let action = document
                .querySelector("form#render")
                .getAttribute("action");
            let token = document
                .querySelector('meta[name="csrf-token"')
                .getAttribute("content");

            let filter = this.model.category_attributes
                .map(({ nested, ...rest }) => rest)
                .map((item, index) => {
                    item.pivot.sort = index + 1;
                    return item;
                });
            this.model.category_attributes = filter;

            // this.model
            //this.$http.post(action, this.model, {"Content-Type": "application/json",'X-CSRF-TOKEN': token })

            this.$http
                .post(action, this.model, {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": token,
                })
                .then(async (response) => {
                    Util.useSwall(response).then((result) => {
                        if (result.value) {
                            window.location.replace(
                                this.setting.url.request.index.replace(
                                    "api/",
                                    ""
                                )
                            );
                        } else if (
                            this.model.id == null &&
                            response.data.success == true
                        ) {
                            window.location.replace(
                                window.location.href.replace(
                                    "new",
                                    response.data.result.id
                                )
                            );
                        }

                        if (!response.data.success) {
                            if (response.data.errs)
                                response.data.errs.map((item) =>
                                    this.$toast.error(item, {
                                        position: "top-right",
                                        duration: 7000,
                                    })
                                );
                        }
                    });
                })
                .catch((e) => {
                    this.$toast.error(e.response.statusText, {
                        position: "top-right",
                        duration: 7000,
                    });
                });

            return false;
        },

        gadawera(e, model, isReserve) {
            let session = [{ ...this.model }]
                .map(({ purchaser, purchaser_id, special, ...rest }) => rest)
                .find((_, index) => index == 0);

            delete session.id;
            session.special = [];
            session.purchaser = {};
            session.parent_uuid = model.uuid;
            session.category_attributes.map((i) => {
                delete i.pivot.id;
                delete i.pivot.attributable_id;
                return i;
            });

            sessionStorage.setItem("model", JSON.stringify(session));
            window.location.href = this.setting.url.request.edit;
        },

        try_focus() {
            this.step = true;
        },

        try_blur() {
            this.step = false;
        },

        exit() {
            return (window.location.href =
                this.setting.url.request.index.replace("api/", ""));
        },
    },
};
</script>

<style>
table tr > td,
table tr > th {
    vertical-align: middle;
}

.table thead th {
    vertical-align: middle;
    padding: 3px;
    background: gray;
    border: 1px solid white;
    text-align: center;
}

.table td {
    cursor: move;
}

.table td,
.table th {
    vertical-align: middle;
    padding: 3px;
}

.table td input,
.table th input {
    padding: 3px;
}

.calculator {
    color: black;
}

table.table-striped {
    /*border-top-color: #000; 
    border-top-width: 1; 
    border-top-style: solid;*/
    border: 3px solid gray;
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

table {
    border-collapse: collapse;
    width: 100%;
}
</style>

<!-- this.model.category_attributes.map(res => 
  ({pivot: res.pivot, ...this.model.special.find(s => s.category_attribute_id === res.id).json } || res)
); -->
