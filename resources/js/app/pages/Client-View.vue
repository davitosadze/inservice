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
                                ><b>დასახელება :</b></label
                            >
                            <label class="col-sm-3 col-form-label">{{
                                model.client_name
                            }}</label>
                        </div>

                        <div class="form-group row">
                            <label
                                class="col-sm-3 col-form-label"
                                for="formGroupExampleInput2"
                                >საიდენტიფიკაციო კოდი :</label
                            >
                            <div class="col-sm-9">
                                <label class="col-sm-3 col-form-label">{{
                                    model.identification_code
                                }}</label>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label
                                class="col-sm-3 col-form-label"
                                for="formGroupExampleInput"
                                >იურიდიული მისამართი :</label
                            >
                            <label class="col-sm-3 col-form-label">{{
                                model.legal_address
                            }}</label>
                        </div>
                        <div class="form-group row">
                            <label
                                class="col-sm-3 col-form-label"
                                for="formGroupExampleInput"
                                >მომსახურების შიდა სახელი :</label
                            >

                            <div class="col-sm-9">
                                <label class="col-sm-3 col-form-label">{{
                                    model.service_name
                                }}</label>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label
                                class="col-sm-3 col-form-label"
                                for="formGroupExampleInput"
                                >კონტრაქტის მომსახურების ტიპი :</label
                            >

                            <div class="col-sm-9">
                                <label class="col-sm-3 col-form-label">{{
                                    model.contract_service_type
                                }}</label>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label
                                class="col-sm-3 col-form-label"
                                for="formGroupExampleInput"
                                >კონტრაქტის დაწყების თარიღი :</label
                            >

                            <div class="col-sm-9">
                                <label class="col-sm-3 col-form-label">{{
                                    model.contract_start_date
                                }}</label>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label
                                class="col-sm-3 col-form-label"
                                for="formGroupExampleInput"
                                >კონტრაქტის დასრულების თარიღი :</label
                            >
                            <div class="col-sm-9">
                                <label class="col-sm-3 col-form-label">{{
                                    model.contract_end_date
                                }}</label>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label
                                class="col-sm-3 col-form-label"
                                for="formGroupExampleInput"
                                >კონტრაქტის პერიოდი / დღე :</label
                            >

                            <div class="col-sm-9">
                                <span>{{ contractPeriod }}</span>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label
                                class="col-sm-3 col-form-label"
                                for="formGroupExampleInput"
                                >კონტრაქტის ნარჩენი დღეები :</label
                            >

                            <div class="col-sm-9">
                                <span>{{ daysLeft }}</span>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label
                                class="col-sm-3 col-form-label"
                                for="formGroupExampleInput"
                                >კონტრაქტის სტატუსი :</label
                            >

                            <div class="col-sm-9">
                                {{ contractStatus }}
                            </div>
                        </div>

                        <div class="form-group row">
                            <label
                                class="col-sm-3 col-form-label"
                                for="formGroupExampleInput"
                                >გარანტიის დაწყების თარიღი :</label
                            >
                            <div class="col-sm-9">
                                <label class="col-sm-3 col-form-label">{{
                                    model.guarantee_start_date
                                }}</label>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label
                                class="col-sm-3 col-form-label"
                                for="formGroupExampleInput"
                                >გარანტიის დასრულების თარიღი :</label
                            >

                            <div class="col-sm-9">
                                <label class="col-sm-3 col-form-label">{{
                                    model.guarantee_end_date
                                }}</label>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label
                                class="col-sm-3 col-form-label"
                                for="formGroupExampleInput"
                                >გარანტიის პერიოდი / დღე :</label
                            >

                            <div class="col-sm-9">
                                <span>{{ guaranteePeriod }}</span>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label
                                class="col-sm-3 col-form-label"
                                for="formGroupExampleInput"
                                >გარანტიის ნარჩენი დღეები :</label
                            >

                            <div class="col-sm-9">
                                <span>{{ daysLeftGuarantee }}</span>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label
                                class="col-sm-3 col-form-label"
                                for="formGroupExampleInput"
                                >გარანტიის სტატუსი :</label
                            >

                            <div class="col-sm-9">
                                {{ guaranteeStatus }}
                            </div>
                        </div>

                        <div class="form-group row">
                            <label
                                class="col-sm-3 col-form-label"
                                for="formGroupExampleInput"
                                >კლიენტის მაიდენთიფიცირებელი კოდი :</label
                            >

                            <div class="col-sm-9">
                                <label class="col-sm-3 col-form-label">{{
                                    model.client_identification_code
                                }}</label>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label
                                class="col-sm-3 col-form-label"
                                for="formGroupExampleInput"
                                >საკონტაქტო პირი :</label
                            >
                            <div class="col-sm-9">
                                <label class="col-sm-3 col-form-label">{{
                                    model.contact_name
                                }}</label>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label
                                class="col-sm-3 col-form-label"
                                for="formGroupExampleInput"
                                >საკონტაქტო პირის ნომერი :</label
                            >

                            <div class="col-sm-9">
                                <label class="col-sm-3 col-form-label">{{
                                    model.contact_number
                                }}</label>
                            </div>
                        </div>

                        <div v-if="can('კლიენტის სრულად ნახვა')">
                            <div v-if="this.model.id" class="step2">
                                <hr />
                                <div class="row">
                                    <div class="col-12">
                                        <p class="lead">ანგარიში</p>
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label
                                        class="col-sm-4 col-form-label"
                                        for="formGroupExampleInput"
                                        >კონტრაქტის ჯამური ღირებულება:</label
                                    >

                                    <div class="col-sm-3">
                                        <label
                                            class="col-sm-3 col-form-label"
                                            >{{ model.total }}</label
                                        >
                                    </div>
                                </div>

                                <div v-if="model.id" class="upload">
                                    <upload-component
                                        :input_id="`totalFiles${this.model.id}`"
                                        upload_type="client_total_files"
                                        :model="this.model"
                                        :viewonly="true"
                                        :files="this.model.total_files"
                                    ></upload-component>
                                </div>
                                <div v-else>
                                    <input
                                        type="file"
                                        @change="onChange"
                                        multiple
                                    />
                                </div>
                                <hr />
                                <div class="row">
                                    <div class="col-12 table-responsive">
                                        <p class="lead">
                                            შეთანხმებული დოკუმენტების თანხა
                                        </p>

                                        <table
                                            class="table table-striped"
                                            style="
                                                background: #edebe4;
                                                color: #fff;
                                            "
                                        >
                                            <thead>
                                                <tr>
                                                    <th rowspan="2">თანხა</th>
                                                    <th
                                                        v-if="this.model.id"
                                                        rowspan="2"
                                                    >
                                                        ფაილები
                                                    </th>
                                                </tr>
                                            </thead>

                                            <tbody>
                                            <draggable
                                                v-model="expenses"
                                                tag="tbody"
                                                item-key="id"
                                                :disabled="step"
                                            >
                                                <template #item="{ element }">
                                                    <request-single-expense
                                                        @action_focus="
                                                            try_focus
                                                        "
                                                        @action_blur="try_blur"
                                                        @action_remove="remove"
                                                        :keys="keys"
                                                        :viewonly="true"
                                                        :item="element"
                                                    />
                                                </template>
                                            </draggable>
                                            <tr class="calculator"></tr>
                                        </tbody>
                                        </table>
                                    </div>
                                    <!-- /.col -->
                                </div>

                                <hr />

                                <div class="form-group row">
                                    <label
                                        class="col-sm-4 col-form-label"
                                        for="formGroupExampleInput"
                                        >ნარჩენი ასათვისებელი თანხა:</label
                                    >

                                    <span id="result">
                                        <label
                                            class="text-dark col-form-label"
                                            >{{ totalQty }}</label
                                        >
                                    </span>
                                </div>

                                <div v-if="model.id" class="upload">
                                    <upload-component
                                        :input_id="`additionalFiles${this.model.id}`"
                                        upload_type="client_additional_files"
                                        :model="this.model"
                                        :viewonly="true"
                                        :files="this.model.additional_files"
                                    ></upload-component>
                                </div>
                            </div>
                        </div>

                        <div
                            v-if="can('კლიენტის რედაქტირება')"
                            class="row no-print"
                        >
                            <div class="col-12">
                                <button
                                    :disabled="v$.model.$invalid"
                                    @click="send"
                                    type="button"
                                    class="btn btn-success float-right"
                                >
                                    <i class="far fa-credit-card"></i> შენახვა
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import Util from "Util";
import draggable from "../vendors/vuedraggable/src/vuedraggable";
import useVuelidate from "@vuelidate/core";
import { required, maxLength } from "@vuelidate/validators";
import FileUpload from "vue-upload-component";
import { ref } from "vue";
import UploadComponent from "../components/UploadComponent.vue";

export default {
    props: ["user", "model", "setting", "additional"],
    components: {
        FileUpload,
        UploadComponent,
        draggable,
    },
    setup(props, context) {
        return {
            v$: useVuelidate(),
        };
    },
    created() {
        this.m = this.attributeInit;
    },
    mounted() {
        this.model.expenses.length ? [] : this.expenses;
        this.v$.model.$touch();
    },
    data() {
        return {
            showModal: false,
            expenses: this.model.expenses,
            selector: "",
            step: false,
            keys: [],

            selectBuilder: [],
        };
    },
    watch: {},
    validations() {
        return {
            model: {},
        };
    },
    computed: {
        attributeInit() {
            return this.model;
        },
        contractPeriod() {
            const date1 = new Date(this.model.contract_start_date);
            const date2 = new Date(this.model.contract_end_date);
            const oneDay = 1000 * 60 * 60 * 24;
            const diffInTime = date2.getTime() - date1.getTime();
            const diffInDays = Math.round(diffInTime / oneDay);
            return diffInDays ? diffInDays : 0;
        },
        guaranteePeriod() {
            const date1 = new Date(this.model.guarantee_start_date);
            const date2 = new Date(this.model.guarantee_end_date);
            const oneDay = 1000 * 60 * 60 * 24;
            const diffInTime = date2.getTime() - date1.getTime();
            const diffInDays = Math.round(diffInTime / oneDay);
            return diffInDays ? diffInDays : 0;
        },
        contractStatus() {
            const date1 = new Date();
            const date2 = new Date(this.model.contract_end_date);

            if (date1 > date2) {
                return "დასრულებული";
            }
            return "მიმდინარე";
        },

        guaranteeStatus() {
            const date1 = new Date();
            const date2 = new Date(this.model.guarantee_end_date);

            if (date1 > date2) {
                return "დასრულებული";
            }
            return "მიმდინარე";
        },
        daysLeft() {
            const date1 = new Date();
            const date2 = new Date(this.model.contract_end_date);
            const oneDay = 1000 * 60 * 60 * 24;
            const diffInTime = date2.getTime() - date1.getTime();
            const diffInDays = Math.round(diffInTime / oneDay);
            return diffInDays ? diffInDays : 0;
        },
        daysLeftGuarantee() {
            const date1 = new Date();
            const date2 = new Date(this.model.guarantee_end_date);
            const oneDay = 1000 * 60 * 60 * 24;
            const diffInTime = date2.getTime() - date1.getTime();
            const diffInDays = Math.round(diffInTime / oneDay);
            return diffInDays ? diffInDays : 0;
        },
        totalQty() {
            return (
                this.model.total -
                this.expenses?.reduce((total, item) => {
                    return total + Number(item.amount);
                }, 0)
            );
        },
    },
    methods: {
        findSpecialAtribute(res) {
            if (res == "new") {
                let action = document
                    .querySelector("form#render")
                    .getAttribute("action");
                let token = document
                    .querySelector('meta[name="csrf-token"')
                    .getAttribute("content");

                let item = { amount: 0, uuid: Util.uuid(), media: [] };
                this.$http
                    .post("/api/addClientExpense/" + this.model.id, item, {
                        "Content-Type": "multipart/form-data",
                        "X-CSRF-TOKEN": token,
                    })
                    .then(async (response) => {
                        item.id = response.data.expense.id;

                        this.expenses.push(item);
                    })
                    .catch((e) => {
                        this.$toast.error(e.response.statusText, {
                            position: "top-right",
                            duration: 7000,
                        });
                    });

                return;
            }
        },

        removeRequest(model, setting, callback, isReserve = null) {
            let isNew = isReserve ? model.id : model.id;

            if (!isNew) {
                return callback();
            }

            let token = document
                .querySelector('meta[name="csrf-token"')
                .getAttribute("content");

            return this.$http
                .delete(
                    "/api/deleteClientExpense/" + model.id,

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
                    if (e.response)
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
                                let index = this.model.expenses.findIndex(
                                    (i) => i.uuid == model.uuid
                                );
                                this.model.expenses.splice(index, 1);
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

        try_exit(res, selector) {
            this.showModal = false;
        },

        setter(name) {
            this.selectBuilder = [{ selected: "", res: this.additional[name] }];
            this.selector = name;
            this.showModal = true;
        },
        inputFilter(newFile, oldFile, prevent) {
            if (newFile && !oldFile) {
                if (
                    /(\/|^)(Thumbs\.db|desktop\.ini|\..+)$/.test(newFile.name)
                ) {
                    return prevent();
                }
                if (/\.(php5?|html?|jsx?)$/i.test(newFile.name)) {
                    return prevent();
                }
            }
        },

        send(e) {
            e.preventDefault();

            let action = document
                .querySelector("form#render")
                .getAttribute("action");
            let token = document
                .querySelector('meta[name="csrf-token"')
                .getAttribute("content");

            this.$http
                .post(action, this.model, {
                    "Content-Type": "multipart/form-data",
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
