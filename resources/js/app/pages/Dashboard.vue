<template>
    <Datepicker
        :value="date"
        v-model="date"
        @update:modelValue="handleDate"
        range
        :enable-time-picker="false"
        :partial-range="false"
    />
    <div v-if="loading">Loading...</div>
    <div v-else>
        <div class="container mt-5 text-center">
            <!-- <GChart
                type="PieChart"
                :options="evaluation_options"
                :data="evaluations"
            /> -->
            <!-- <GChart
                type="PieChart"
                :options="invoice_options"
                :data="invoices"
            />
            <GChart
                type="PieChart"
                :options="response_options"
                :data="responses"
            /> -->

            <div class="row">
                <div class="col-sm-4">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">
                                დასრულებული გეგმიური სამუშაოები
                            </h5>
                            <h3 class="card-text">
                                {{ this.approvedServices }}
                            </h3>
                            <a href="#" class="btn btn-primary"
                                >გეგმიური სამუშაო</a
                            >
                        </div>
                    </div>
                </div>
                <div class="col-sm-4">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">დასრულებული რეაგირებები</h5>
                            <h3 class="card-text">
                                {{ this.approvedResponses }}
                            </h3>
                            <a href="#" class="btn btn-primary">რეაგირება</a>
                        </div>
                    </div>
                </div>

                <div class="col-sm-4">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">ინვოისების ღირებულება</h5>
                            <h3 class="card-text">
                                {{ parseFloat(invoiceFullPrice).toFixed(2) }}
                            </h3>
                            <a href="#" class="btn btn-primary">ლარი</a>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card mt-5 card-primary card-outline card-tabs">
                <div class="card-body">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>მყიდველი</th>
                                <th v-for="(item, index) in this.systems">
                                    {{ item.name }}
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr
                                v-for="(item, index) in filteredData"
                                :key="index"
                            >
                                <td
                                    style="max-width: 250px"
                                    v-for="(response, index) in item"
                                >
                                    {{ response === "Null" ? "0%" : response }}
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import Datepicker from "@vuepic/vue-datepicker";
import "@vuepic/vue-datepicker/dist/main.css";
import { GChart } from "vue-google-charts";

import { ref, onMounted } from "vue";

export default {
    data() {
        return {
            date: null,
            data: [],
            loading: true,
            evaluations: [],
            systems: [],
            responses: [],
            invoices: [],
            approvedServices: 0,
            invoiceFullPrice: 0,
            approvedResponses: 0,
        };
    },
    components: { Datepicker, GChart },
    computed: {
        filteredData() {
            return this.data;
        },
    },
    methods: {
        handleDate(modelData) {
            this.loading = true;
            this.evaluations = [["Evaluations", "Evaluations"]];
            this.invoices = [["Invoices", "Invoices"]];
            this.responses = [["Responses", "Responses"]];

            let statistics = [];
            const start_date = modelData[0];
            const end_date = modelData[1];
            const params = {
                start_date: start_date,
                end_date: end_date,
            };
            window.axios.get("/api/statistics", { params }).then((res) => {
                statistics = res.data;
                this.systems = statistics.systems;
                this.approvedServices = statistics.approvedServices;
                this.approvedResponses = statistics.approvedResponses;
                this.invoiceFullPrice = statistics.invoiceFullPrice;

                this.loading = false;
                statistics.customers.map((i) => {
                    this.evaluations.push([i.name, i.evaluations]);
                    this.invoices.push([i.name, i.invoices]);
                });
                statistics.responses.map((i) => {
                    this.responses.push([i.nameFormatted, i.count]);
                });
                this.data = statistics.responsesPercentage;
                console.log(this.data);
            });
        },
    },
    setup() {
        let statistics = [];
        const date = ref();

        onMounted(() => {
            const endDate = new Date();
            const startDate = new Date(
                new Date().setDate(endDate.getDate() - 60)
            );
            date.value = [startDate, endDate];
        });
        return {
            date,
            statistics,
            evaluation_options: {
                title: "განფასებები",
                width: "100%",
                height: 400,
                legend: {
                    position: "labeled",
                },
                sliceVisibilityThreshold: 1 / 10000,
            },
            invoice_options: {
                title: "ინვოისები",
                width: "100%",
                height: 400,
                legend: {
                    position: "labeled",
                },
            },
            response_options: {
                title: "რეაგირებები",
                width: "100%",
                height: 400,
                legend: {
                    position: "labeled",
                },
            },
        };
    },
    mounted() {
        this.handleDate(this.date);
    },
};
</script>
