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
            <GChart
                type="PieChart"
                :options="evaluation_options"
                :data="evaluations"
            />
            <GChart
                type="PieChart"
                :options="invoice_options"
                :data="invoices"
            />
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
            loading: true,
            evaluations: [],
            invoices: [],
        };
    },
    components: { Datepicker, GChart },

    methods: {
        handleDate(modelData) {
            this.loading = true;
            this.evaluations = [["Evaluations", "Evaluations"]];
            this.invoices = [["Invoices", "Invoices"]];

            let statistics = [];
            const start_date = modelData[0];
            const end_date = modelData[1];
            const params = {
                start_date: start_date,
                end_date: end_date,
            };
            window.axios.get("/api/statistics", { params }).then((res) => {
                statistics = res.data;
                this.loading = false;
                statistics.customers.map((i) => {
                    this.evaluations.push([i.name, i.evaluations]);
                    this.invoices.push([i.name, i.invoices]);
                });
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
        };
    },
    mounted() {
        console.log(this.date);
        this.handleDate(this.date);
    },
};
</script>
