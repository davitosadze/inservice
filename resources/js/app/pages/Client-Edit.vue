<template>
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <!-- Main content -->

                <div class="invoice p-3 mb-3">
                    <div style="">
                        <form @submit.prevent="registerClient">
                            <div class="form-group row">
                                <!-- Email Input -->
                                <div class="col-md-4">
                                    <label for="email" class="col-form-label"
                                        ><b>იმეილი:</b></label
                                    >
                                    <input
                                        id="email"
                                        v-model="email"
                                        class="form-control"
                                        type="email"
                                        placeholder="შეიყვანეთ იმეილი"
                                        required
                                    />
                                </div>

                                <!-- Password Input -->
                                <div class="col-md-3">
                                    <label for="password" class="col-form-label"
                                        ><b>პაროლი:</b></label
                                    >
                                    <input
                                        id="password"
                                        v-model="password"
                                        class="form-control"
                                        type="password"
                                        placeholder="შეიყვანეთ პაროლი"
                                        required
                                    />
                                </div>

                                <!-- Confirm Password Input -->
                                <div class="col-md-3">
                                    <label
                                        for="confirm_password"
                                        class="col-form-label"
                                        ><b>გაიმეორეთ პაროლი:</b></label
                                    >
                                    <input
                                        id="confirm_password"
                                        v-model="confirmPassword"
                                        class="form-control"
                                        type="password"
                                        placeholder="გაიმეორეთ პაროლი"
                                        required
                                    />
                                </div>

                                <!-- Submit Button -->
                                <div class="col-md-2 d-flex align-items-end">
                                    <button
                                        type="submit"
                                        class="btn btn-success w-100"
                                    >
                                        შენახვა
                                    </button>
                                </div>
                            </div>
                        </form>

                        <div class="form-group row">
                            <label for="branchs" class="col-sm-3 col-form-label"
                                >ფილიალები:</label
                            >
                            <div class="col-sm-9">
                                <select
                                    class="form-control"
                                    v-model="this.model.purchaser"
                                >
                                    <option
                                        :key="branch"
                                        :value="branch"
                                        v-for="branch in branchs"
                                    >
                                        {{ branch }}
                                    </option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group row align-items-center">
                            <div class="col-sm-3">
                                <input
                                    type="checkbox"
                                    v-model="toggles.incidents_by_numbers"
                                />
                                <label
                                    class="col-form-label ml-3 font-weight-medium"
                                    >დაფიქსირებული ინციდენტების რაოდენობა
                                    რიცხვების მიხედვით</label
                                >
                            </div>
                            <div class="col-sm-3">
                                <input
                                    type="checkbox"
                                    v-model="
                                        toggles.incidents_closed_with_reaction
                                    "
                                />
                                <label
                                    class="col-form-label ml-3 font-weight-medium"
                                    >რეაგირებით ჩახურული და რემონტზე
                                    გადაცემული</label
                                >
                            </div>
                            <div class="col-sm-3">
                                <input
                                    type="checkbox"
                                    v-model="toggles.incidents_by_fields"
                                />
                                <label
                                    class="col-form-label ml-3 font-weight-medium"
                                    >დაფიქსირებული ინციდენტები სფეროების
                                    მიხედვით</label
                                >
                            </div>
                            <div class="col-sm-3">
                                <input
                                    type="checkbox"
                                    v-model="toggles.incidents_by_branches"
                                />
                                <label
                                    class="col-form-label ml-3 font-weight-medium"
                                    >დაფიქსირებული ინციდენტები ფილიალების
                                    მიხედვით</label
                                >
                            </div>
                            <div class="col-sm-3 mt-2">
                                <input
                                    type="checkbox"
                                    v-model="toggles.incidents_by_regions"
                                />
                                <label
                                    class="col-form-label ml-3 font-weight-medium"
                                    >დაფიქსირებული ინციდენტები რეგიონის
                                    მიხედვით</label
                                >
                            </div>
                        </div>

                        <hr />

                        <div class="form-group row align-items-center">
                            <div class="col-sm-3 d-flex align-items-center">
                                <input
                                    type="checkbox"
                                    v-model="toggles.client_name"
                                    class="mr-2"
                                />
                                <label class="col-form-label font-weight-medium"
                                    >დასახელება :</label
                                >
                            </div>
                            <div class="col-sm-9">
                                <input
                                    class="form-control"
                                    type="text"
                                    v-model="model.client_name"
                                />
                            </div>
                        </div>

                        <div class="form-group row align-items-center">
                            <div class="col-sm-3 d-flex align-items-center">
                                <input
                                    type="checkbox"
                                    v-model="toggles.identification_code"
                                    class="mr-2"
                                />
                                <label class="col-form-label font-weight-medium"
                                    >საიდენტიფიკაციო კოდი :</label
                                >
                            </div>
                            <div class="col-sm-9">
                                <input
                                    class="form-control"
                                    type="text"
                                    v-model="model.identification_code"
                                />
                            </div>
                        </div>

                        <div class="form-group row align-items-center">
                            <div class="col-sm-3 d-flex align-items-center">
                                <input
                                    type="checkbox"
                                    v-model="toggles.legal_address"
                                    class="mr-2"
                                />
                                <label class="col-form-label font-weight-medium"
                                    >იურიდიული მისამართი :</label
                                >
                            </div>
                            <div class="col-sm-9">
                                <input
                                    class="form-control"
                                    type="text"
                                    v-model="model.legal_address"
                                />
                            </div>
                        </div>

                        <div class="form-group row align-items-center">
                            <div class="col-sm-3 d-flex align-items-center">
                                <input
                                    type="checkbox"
                                    v-model="toggles.service_name"
                                    class="mr-2"
                                />
                                <label class="col-form-label font-weight-medium"
                                    >მომსახურების შიდა სახელი :</label
                                >
                            </div>
                            <div class="col-sm-9">
                                <input
                                    class="form-control"
                                    type="text"
                                    v-model="model.service_name"
                                />
                            </div>
                        </div>

                        <div class="form-group row align-items-center">
                            <div class="col-sm-3 d-flex align-items-center">
                                <input
                                    type="checkbox"
                                    v-model="toggles.contract_service_type"
                                    class="mr-2"
                                />
                                <label class="col-form-label font-weight-medium"
                                    >კონტრაქტის მომსახურების ტიპი :</label
                                >
                            </div>
                            <div class="col-sm-9">
                                <input
                                    class="form-control"
                                    type="text"
                                    v-model="model.contract_service_type"
                                />
                            </div>
                        </div>

                        <div class="form-group row align-items-center">
                            <div class="col-sm-3 d-flex align-items-center">
                                <input
                                    type="checkbox"
                                    v-model="toggles.contract_start_date"
                                    class="mr-2"
                                />
                                <label class="col-form-label font-weight-medium"
                                    >კონტრაქტის დაწყების თარიღი :</label
                                >
                            </div>
                            <div class="col-sm-9">
                                <input
                                    class="form-control"
                                    type="date"
                                    v-model="model.contract_start_date"
                                />
                            </div>
                        </div>

                        <div class="form-group row align-items-center">
                            <div class="col-sm-3 d-flex align-items-center">
                                <input
                                    type="checkbox"
                                    v-model="toggles.contract_end_date"
                                    class="mr-2"
                                />
                                <label class="col-form-label font-weight-medium"
                                    >კონტრაქტის დასრულების თარიღი :</label
                                >
                            </div>
                            <div class="col-sm-9">
                                <input
                                    class="form-control"
                                    type="date"
                                    v-model="model.contract_end_date"
                                />
                            </div>
                        </div>

                        <div class="form-group row align-items-center">
                            <div class="col-sm-3">
                                <input
                                    type="checkbox"
                                    v-model="toggles.contractPeriod"
                                />
                                <label
                                    class="col-form-label ml-3 font-weight-medium"
                                    >კონტრაქტის პერიოდი / დღე :</label
                                >
                            </div>
                            <div class="col-sm-9">
                                <span>{{ contractPeriod }}</span>
                            </div>
                        </div>

                        <div class="form-group row align-items-center">
                            <div class="col-sm-3">
                                <input
                                    type="checkbox"
                                    v-model="toggles.daysLeft"
                                />
                                <label
                                    class="col-form-label ml-3 font-weight-medium"
                                    >კონტრაქტის ნარჩენი დღეები :</label
                                >
                            </div>
                            <div class="col-sm-9">
                                <span>{{ daysLeft }}</span>
                            </div>
                        </div>

                        <div class="form-group row align-items-center">
                            <div class="col-sm-3">
                                <input
                                    type="checkbox"
                                    v-model="toggles.contractStatus"
                                />
                                <label
                                    class="col-form-label ml-3 font-weight-medium"
                                    >კონტრაქტის სტატუსი :</label
                                >
                            </div>
                            <div class="col-sm-9">
                                {{ contractStatus }}
                            </div>
                        </div>

                        <div class="form-group row align-items-center">
                            <div class="col-sm-3">
                                <input
                                    type="checkbox"
                                    v-model="toggles.guarantee_start_date"
                                />
                                <label
                                    class="col-form-label ml-3 font-weight-medium"
                                    >გარანტიის დაწყების თარიღი :</label
                                >
                            </div>
                            <div class="col-sm-9">
                                <input
                                    v-model="model.guarantee_start_date"
                                    type="date"
                                    class="form-control"
                                />
                            </div>
                        </div>

                        <div class="form-group row align-items-center">
                            <div class="col-sm-3">
                                <input
                                    type="checkbox"
                                    v-model="toggles.guarantee_end_date"
                                />
                                <label
                                    class="col-form-label ml-3 font-weight-medium"
                                    >გარანტიის დასრულების თარიღი :</label
                                >
                            </div>
                            <div class="col-sm-9">
                                <input
                                    v-model="model.guarantee_end_date"
                                    type="date"
                                    class="form-control"
                                />
                            </div>
                        </div>

                        <div class="form-group row align-items-center">
                            <div class="col-sm-3">
                                <input
                                    type="checkbox"
                                    v-model="toggles.guaranteePeriod"
                                />
                                <label
                                    class="col-form-label ml-3 font-weight-medium"
                                    >გარანტიის პერიოდი / დღე :</label
                                >
                            </div>
                            <div class="col-sm-9">
                                <span>{{ guaranteePeriod }}</span>
                            </div>
                        </div>

                        <div class="form-group row align-items-center">
                            <div class="col-sm-3">
                                <input
                                    type="checkbox"
                                    v-model="toggles.daysLeftGuarantee"
                                />
                                <label
                                    class="col-form-label ml-3 font-weight-medium"
                                    >გარანტიის ნარჩენი დღეები :</label
                                >
                            </div>
                            <div class="col-sm-9">
                                <span>{{ daysLeftGuarantee }}</span>
                            </div>
                        </div>

                        <div class="form-group row align-items-center">
                            <div class="col-sm-3">
                                <input
                                    type="checkbox"
                                    v-model="toggles.guaranteeStatus"
                                />
                                <label
                                    class="col-form-label ml-3 font-weight-medium"
                                    >გარანტიის სტატუსი :</label
                                >
                            </div>
                            <div class="col-sm-9">
                                {{ guaranteeStatus }}
                            </div>
                        </div>

                        <div class="form-group row align-items-center">
                            <div class="col-sm-3">
                                <input
                                    type="checkbox"
                                    v-model="toggles.client_identification_code"
                                />
                                <label
                                    class="col-form-label ml-3 font-weight-medium"
                                    >კლიენტის მაიდენთიფიცირებელი კოდი :</label
                                >
                            </div>
                            <div class="col-sm-9">
                                <input
                                    v-model="model.client_identification_code"
                                    type="text"
                                    class="form-control"
                                />
                            </div>
                        </div>

                        <div class="form-group row align-items-center">
                            <div class="col-sm-3 d-flex align-items-center">
                                <input
                                    type="checkbox"
                                    v-model="toggles.contact_name"
                                    class="mr-2"
                                />
                                <label class="col-form-label font-weight-medium"
                                    >საკონტაქტო პირი :</label
                                >
                            </div>
                            <div class="col-sm-9">
                                <input
                                    class="form-control"
                                    type="text"
                                    v-model="model.contact_name"
                                />
                            </div>
                        </div>

                        <div class="form-group row align-items-center">
                            <div class="col-sm-3 d-flex align-items-center">
                                <input
                                    type="checkbox"
                                    v-model="toggles.contact_number"
                                    class="mr-2"
                                />
                                <label class="col-form-label font-weight-medium"
                                    >საკონტაქტო პირის ნომერი :</label
                                >
                            </div>
                            <div class="col-sm-9">
                                <input
                                    class="form-control"
                                    type="text"
                                    v-model="model.contact_number"
                                />
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

                                    <div class="col-sm-4">
                                        <input
                                            v-model="model.total"
                                            type="text"
                                            class="form-control"
                                            id="formGroupExampleInput"
                                            placeholder=""
                                        />
                                    </div>
                                </div>

                                <div v-if="model.id" class="upload">
                                    <upload-component
                                        :input_id="`totalFiles${this.model.id}`"
                                        upload_type="client_total_files"
                                        :model="this.model"
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
                                                    <th rowspan="2">ქმედება</th>
                                                </tr>
                                            </thead>
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
                                                        :viewonly="false"
                                                        :keys="keys"
                                                        :item="element"
                                                    />
                                                </template>
                                            </draggable>
                                            <tr class="calculator"></tr>
                                        </table>

                                        <button
                                            type="button"
                                            @click="findSpecialAtribute('new')"
                                            class="btn btn-sm btn-outline-warning"
                                        >
                                            <i class="fas fa-shield-alt"></i>
                                            ახალი
                                        </button>
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

                                    <span id="result">{{ totalQty }}</span>
                                </div>

                                <div v-if="model.id" class="upload">
                                    <upload-component
                                        :input_id="`additionalFiles${this.model.id}`"
                                        upload_type="client_additional_files"
                                        :model="this.model"
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
import "select2/dist/css/select2.css";
import $ from "jquery";
import "select2";

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
        console.log(this.additional);
        this.initializeSelect2();

        if (!this.can("კლიენტის რედაქტირება")) {
            console.log(this.setting.url.request);
            let redirect = this.setting.url.request.show.replace(
                "new",
                this.model.id
            );
            window.location.href = redirect;
        }
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
            email: this.model?.user?.email || "",
            password: "",
            confirmPassword: "",

            branchs: this.additional.purchasers,
            selectedBranchs: [],

            selectBuilder: [],
            toggles: {
                client_name: this.model.toggles?.client_name,
                identification_code: this.model.toggles?.identification_code,
                legal_address: this.model.toggles?.legal_address,
                service_name: this.model.toggles?.service_name,
                contract_service_type:
                    this.model.toggles?.contract_service_type,
                contract_start_date: this.model.toggles?.contract_start_date,
                contract_end_date: this.model.toggles?.contract_end_date,
                contractPeriod: this.model.toggles?.contractPeriod,
                daysLeft: this.model.toggles?.daysLeft,
                contractStatus: this.model.toggles?.contractStatus,
                guarantee_start_date: this.model.toggles?.guarantee_start_date,
                guarantee_end_date: this.model.toggles?.guarantee_end_date,
                guaranteePeriod: this.model.toggles?.guaranteePeriod,
                daysLeftGuarantee: this.model.toggles?.daysLeftGuarantee,
                guaranteeStatus: this.model.toggles?.guaranteeStatus,
                client_identification_code:
                    this.model.toggles?.client_identification_code,
                contact_name: this.model.toggles?.contact_name,
                contact_number: this.model.toggles?.contact_number,
                incidents_by_numbers: this.model.toggles?.incidents_by_numbers,
                incidents_closed_with_reaction:
                    this.model.toggles?.incidents_closed_with_reaction,
                incidents_by_fields: this.model.toggles?.incidents_by_fields,
                incidents_by_branches:
                    this.model.toggles?.incidents_by_branches,
                incidents_by_regions: this.model.toggles?.incidents_by_regions,
            },
        };
    },
    watch: {
        // Watch for any changes in the selected service and reflect them in the select2 dropdown
        selectedService(newValue) {
            $("#branchs").val(newValue).trigger("change");
        },
    },
    beforeDestroy() {
        // Clean up select2 when the component is destroyed
        $("#branchs").select2("destroy");
    },
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
        initializeSelect2() {
            $("#branchs").select2();

            this.$nextTick(() => {
                $("#branchs").val(this.selectedService).trigger("change");
            });
        },
        async registerClient() {
            if (this.password !== this.confirmPassword) {
                alert("Passwords do not match!");
                return;
            }

            try {
                const response = await fetch(
                    "/api/clients/registerClient/" + this.model.id,
                    {
                        method: "POST",
                        headers: { "Content-Type": "application/json" },
                        body: JSON.stringify({
                            email: this.email,
                            password: this.password,
                        }),
                    }
                );

                const result = await response.json();
                alert("წარმატებით განახლდა!");
            } catch (error) {
                alert("პრობლემა განახლების დროს!");
            }
        },
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

            let formData = {
                ...this.model,
                toggles: this.toggles,
            };

            this.$http
                .post(action, formData, {
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
