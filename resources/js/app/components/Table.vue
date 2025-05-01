<template>
    <div v-if="this.setting.model == 'invoices'">
        <hr />
        <Datepicker
            :value="date"
            v-model="dates"
            @update:modelValue="handleDate"
            range
            :enable-time-picker="false"
            :partial-range="false"
        />
        <hr />
    </div>
    <div>
        <ag-grid-vue
            style="height: 77vh; width: 100%"
            class="ag-theme-quartz"
            :columnDefs="columnDefs"
            :defaultColDef="defaultColDef"
            :rowData="rowData"
            :paginationPageSize="17"
            :pagination="true"
            :columnTypes="columnTypes"
            :rowClassRules="rowClassRules"
            :tooltipShowDelay="tooltipShowDelay"
            :tooltipHideDelay="tooltipHideDelay"
            @grid-ready="onGridReady"
            @cell-clicked="onCellClicked"
        >
        </ag-grid-vue>
    </div>
</template>

<script>
import { addDays, isDate, subDays } from "date-fns";
import Util from "Util";
import Datepicker from "@vuepic/vue-datepicker";
import "@vuepic/vue-datepicker/dist/main.css";
import { ref } from "vue";

import "ag-grid-community/styles/ag-grid.css"; // Mandatory CSS required by the grid
import "ag-grid-community/styles/ag-theme-quartz.css"; // Optional Theme applied to the grid
import { AgGridVue } from "ag-grid-vue3"; // Vue Data Grid Component

function actionCellRenderer(params) {
    let eGui = document.createElement("div");

    eGui.innerHTML = `
			<i style="cursor:pointer; color:green; font-size:1.2em; margin-right:0.3em;" data-action="edit" class="fas fa-edit"></i>
			<i style="cursor:pointer; font-size:1.2em; margin-right:0.3em;" data-action="gadawera" class="far fa-copy"></i>
			<i style="cursor:pointer; color:red; font-size:1.2em;" data-action="delete" class="fas fa-trash"></i>
		`;
    return eGui;
}

function alterRenderer(params) {
    let eGui = document.createElement("div");
    eGui.innerHTML = `
			<i style="cursor:pointer; color:green; font-size:1.2em; margin-right:0.3em;" data-action="edit" class="fas fa-edit"></i>
			<i style="cursor:pointer; color:red; font-size:1.2em;" data-action="delete" class="fas fa-trash"></i>
		`;
    return eGui;
}

function alterRendererWithView(params) {
    let eGui = document.createElement("div");

    if ($can("კლიენტის რედაქტირება")) {
        eGui.innerHTML = `
			<i style="cursor:pointer; color:green; font-size:1.2em; margin-right:0.3em;" data-action="view" class="fas fa-eye"></i>
			<i id="gela" style="cursor:pointer; color:green; font-size:1.2em; margin-right:0.3em;" data-action="edit" class="fas fa-edit"></i>
			<i style="cursor:pointer; color:red; font-size:1.2em;" data-action="delete" class="fas fa-trash"></i>
		`;
    } else {
        eGui.innerHTML = `
			<i style="cursor:pointer; color:green; font-size:1.2em; margin-right:0.3em;" data-action="view" class="fas fa-eye"></i>
			<i style="cursor:pointer; color:red; font-size:1.2em;" data-action="delete" class="fas fa-trash"></i>
		`;
    }
    return eGui;
}

function alterRendererPurchasers(params) {
    let eGui = document.createElement("div");

    if ($can("მყიდველის ნახვა")) {
        eGui.innerHTML = `
        <i style="cursor:pointer; color:green; font-size:1.2em; margin-right:0.3em;" data-action="view" class="fas fa-eye"></i>
 		`;
    }

    if ($can("მყიდველის რედაქტირება")) {
        eGui.innerHTML += `
 			<i id="gela" style="cursor:pointer; color:green; font-size:1.2em; margin-right:0.3em;" data-action="edit" class="fas fa-edit"></i>
 		`;
    }

    if ($can("მყიდველის წაშლა")) {
        eGui.innerHTML += `
 			<i style="cursor:pointer; color:red; font-size:1.2em;" data-action="delete" class="fas fa-trash"></i>
		`;
    }
    return eGui;
}

function actionCellRendererDownload(params) {
    let eGui = document.createElement("div");

    if ($can("ექსელის გადმოწერა")) {
        eGui.innerHTML = `
            <i style="cursor:pointer; font-size:1.2em; margin-right:0.3em; color:red;" data-action="excel" class="fas fa-file-excel"></i>
            <i style="cursor:pointer; font-size:1.2em; margin-right:0.3em; color:red;" data-action="pdf" class="fas fa-file-download"></i>
		`;
    } else {
        eGui.innerHTML = `
            <i style="cursor:pointer; font-size:1.2em; margin-right:0.3em; color:red;" data-action="pdf" class="fas fa-file-download"></i>
		`;
    }

    return eGui;
}

function actionCellRendererDownloadPdfOnly(params) {
    let eGui = document.createElement("div");

    eGui.innerHTML = `
            <i style="cursor:pointer; font-size:1.2em; margin-right:0.3em; color:red;" data-action="pdf" class="fas fa-file-download"></i>
		`;

    return eGui;
}

function actionCellRendererOpen(params) {
    let eGui = document.createElement("div");

    eGui.innerHTML = `
        <i style="cursor:pointer; font-size:1.2em; margin-right:0.3em; color:red;" data-action="pdf" class="fas fa-file-pdf"></i>
		`;
    return eGui;
}

function $can(permissionName) {
    return Laravel.jsPermissions.permissions.indexOf(permissionName) !== -1;
}

app.component("Inter", {
    template: `
		      <div class="custom-tooltip" style="border: 1px solid cornflowerblue" v-bind:style="{ backgroundColor: color }">
		          <p><span></span>{{ data.title }}</p>
		      </div>
		    `,
    data: function () {
        return {
            color: null,
            title: null,
        };
    },
    beforeMount() {
        this.data = this.params.api.getDisplayedRowAtIndex(
            this.params.rowIndex
        ).data;
        this.color = this.params.color || "white";
    },
});

export default {
    props: ["user", "additional", "name", "setting"],
    name: "alter-table",
    components: { AgGridVue, Datepicker },
    data: () => {
        return {
            rowData: null,
            gridApi: null,
            canEdit: false,
            columnApi: null,
            rowClassRules: null,
            date: null,
            dates: [subDays(new Date(), 10), new Date()],
            isDatepickerDisabled: true,
        };
    },
    setup(props) {
        let canDownloadExcel = 0;
        if (props.setting.model == "invoices") {
            canDownloadExcel = 1;
        }

        let is_table_advanced = [];
        if (!props.setting.table_view_enabled) {
            is_table_advanced = props.setting.is_table_advanced
                ? [
                      {
                          headerName: "გადმოწერა",
                          headerClass: "text-center",
                          maxWidth: 117,
                          filter: false,
                          cellStyle: { textAlign: "center" },
                          cellRenderer: canDownloadExcel
                              ? actionCellRendererDownload
                              : actionCellRendererDownloadPdfOnly,
                          editable: false,
                          colId: "gadawera",
                      },

                      {
                          headerName: "გახსნა",
                          headerClass: "text-center",
                          maxWidth: 117,
                          filter: false,
                          cellStyle: { textAlign: "center" },
                          cellRenderer: actionCellRendererOpen,
                          editable: false,
                          colId: "gaxsna",
                      },

                      {
                          headerName: "ქმედება",
                          headerClass: "text-center",
                          maxWidth: 100,
                          filter: false,
                          cellStyle: { textAlign: "center" },
                          cellRenderer: actionCellRenderer,
                          editable: false,
                          colId: "action",
                      },
                  ]
                : [
                      {
                          headerName: "ქმედება",
                          headerClass: "text-center",
                          maxWidth: 100,
                          filter: false,
                          cellStyle: { textAlign: "center" },
                          cellRenderer: alterRenderer,
                          editable: false,
                          colId: "action",
                      },
                  ];
        } else {
            if (props.setting.is_table_advanced) {
                if (props.setting.model == "purchaser") {
                    is_table_advanced = [
                        {
                            headerName: "გადმოწერა",
                            headerClass: "text-center",
                            maxWidth: 117,
                            filter: false,
                            cellStyle: { textAlign: "center" },
                            cellRenderer: canDownloadExcel
                                ? actionCellRendererDownload
                                : actionCellRendererDownloadPdfOnly,
                            editable: false,
                            colId: "gadawera",
                        },

                        {
                            headerName: "ქმედება",
                            headerClass: "text-center",
                            maxWidth: 100,
                            filter: false,
                            cellStyle: { textAlign: "center" },
                            cellRenderer: alterRendererPurchasers,
                            editable: false,

                            colId: "action",
                        },
                    ];
                } else {
                    is_table_advanced = [
                        {
                            headerName: "გადმოწერა",
                            headerClass: "text-center",
                            maxWidth: 117,
                            filter: false,
                            cellStyle: { textAlign: "center" },
                            cellRenderer: canDownloadExcel
                                ? actionCellRendererDownload
                                : actionCellRendererDownloadPdfOnly,
                            editable: false,
                            colId: "gadawera",
                        },

                        {
                            headerName: "ქმედება",
                            headerClass: "text-center",
                            maxWidth: 100,
                            filter: false,
                            cellStyle: { textAlign: "center" },
                            cellRenderer: alterRendererWithView,
                            editable: false,

                            colId: "action",
                        },
                    ];
                }
            } else {
                is_table_advanced = [
                    {
                        headerName: "ქმედება",
                        headerClass: "text-center",
                        maxWidth: 100,
                        filter: false,
                        cellStyle: { textAlign: "center" },
                        cellRenderer: alterRendererWithView,
                        editable: false,

                        colId: "action",
                    },
                ];
            }
        }

        return {
            defaultColDef: {
                flex: 1,
                minWidth: 150, // Set a minimum width for columns
                filter: true,
                floatingFilter: true,
                sortable: true,
                resizable: true,
                tooltipComponent: "Inter",
            },
            tooltipShowDelay: 1000,
            tooltipHideDelay: 3000,
            model: "test",
            columnDefs: [...props.setting.columns, ...is_table_advanced],
            columnTypes: {
                nonEditableColumn: { editable: false },
                dateColumn: {
                    // specify we want to use the date filter
                    filter: "agDateColumnFilter",
                    // add extra parameters for the date filter
                    filterParams: {
                        // provide comparator function
                        comparator: (filterLocalDateAtMidnight, cellValue) => {
                            // In the example application, dates are stored as dd/mm/yyyy
                            // We create a Date object for comparison against the filter date
                            const dateAsString = cellValue;

                            if (dateAsString == null) {
                                return 0;
                            }

                            // In the example application, dates are stored as dd/mm/yyyy
                            // We create a Date object for comparison against the filter date
                            const dateParts = dateAsString.split("/");

                            const month = Number(dateParts[0]) - 1;
                            const day = Number(dateParts[1]);
                            const year = Number(dateParts[2].split(" ")[0]);
                            const cellDate = new Date(year, month, day);

                            // Now that both parameters are Date objects, we can compare
                            if (cellDate < filterLocalDateAtMidnight) {
                                return -1;
                            } else if (cellDate > filterLocalDateAtMidnight) {
                                return 1;
                            }
                            return 0;
                        },
                    },
                },
            },
        };
    },
    mounted() {
        this.rowClassRules = {
            "rag-on-repair": "data.on_repair == 1",
            "rag-green-arrived": "data.status == 10 || data.status == 5",
            "rag-green": "data.status == 3",
            "rag-red": "data.status == 1",
            "rag-yellow": "data.status == 2",
            "rag-gray": "data.status == 9",
        };
    },
    methods: {
        handleDate(modelData) {
            const start_date = modelData[0];
            const end_date = modelData[1];

            this.$http
                .get(this.setting.url.request.index, {
                    params: { start_date: start_date, end_date: end_date },
                })
                .then((response) => response.data)
                .then((response) => this.gridApi.setRowData(response))
                .catch(function (error) {
                    console.log(error);
                });
        },
        removeRequest(id, setting, callback) {
            let token = document
                .querySelector('meta[name="csrf-token"')
                .getAttribute("content");

            return this.$http
                .delete(
                    setting.url.request.destroy.replace("__delete__", id),
                    { id },
                    {
                        "Content-Type": "application/json",
                        "X-CSRF-TOKEN": token,
                    }
                )
                .then(async (response) => {
                    if (response.data.success == true) {
                        callback();
                    } else {
                        if (response.data.errs.length)
                            response.data.errs.map((item) =>
                                this.$toast.error(item, {
                                    position: "top-right",
                                    duration: 7000,
                                })
                            );
                    }
                })
                .catch(function (e) {
                    if (e.response && e.response.statusText)
                        this.$toast.error(e.response.statusText, {
                            position: "top-right",
                            duration: 7000,
                        });
                });
        },

        remove(parems, id, remove) {
            return Util.useSwall2([]).then((result) => {
                if (result.isConfirmed) {
                    this.removeRequest(id, this.setting, () => {
                        this.gridApi.updateRowData({ remove: [remove] });
                        Swal.fire(
                            "წაშლა!",
                            "წაშლა შესრრულდა წარმატებით.",
                            "success"
                        );
                    });
                }
            });
        },

        onGridReady(params) {
            this.gridApi = params.api;
            this.columnApi = params.columnApi;

            // this.gridApi.sizeColumnsToFit()

            const updateData = (res) => params.api.setRowData(res);

            this.$http
                .get(this.setting.url.request.index)
                .then((response) => response.data)
                .then((response) => updateData(response))
                .catch(function (error) {
                    console.log(error);
                });
        },

        onCellClicked(params) {
            if (params.column.colId == "action") {
                let action = params.event.target.dataset.action;

                if (action === "edit") {
                    // params.event.target.dataset.action
                    let redirect = this.setting.url.request.edit.replace(
                        "new",
                        params.data.id
                    );
                    window.location.href = redirect;
                } else if (action == "view") {
                    // params.event.target.dataset.action
                    let redirect = this.setting.url.request.show.replace(
                        "new",
                        params.data.id
                    );
                    window.location.href = redirect;
                } else if (action === "gadawera") {
                    // params.event.target.dataset.action
                    let session = [{ ...params.data }]
                        .map(
                            ({ purchaser, purchaser_id, special, ...rest }) =>
                                rest
                        )
                        .find((_, index) => index == 0);

                    delete session.id;
                    session.special = [];
                    session.purchaser = {};
                    session.parent_uuid = params.data.uuid;
                    session.category_attributes.map((i) => {
                        delete i.pivot.id;
                        delete i.pivot.attributable_id;
                        return i;
                    });

                    sessionStorage.setItem("model", JSON.stringify(session));
                    window.location.href = this.setting.url.request.edit;
                } else if (action === "delete") {
                    this.remove(params, params.data.id, params.node.data);
                }
            } else if (params.column.colId == "gadawera") {
                console.log(this.setting.model);
                let action = params.event.target.dataset.action;
                if (action == "excel") {
                    window.location.href =
                        this.setting.url.nested.excel.replace(
                            "__id__",
                            params.data.id
                        );
                }

                if (action == "pdf") {
                    window.open(
                        this.setting.url.nested.pdf.replace(
                            "__id__",
                            params.data.id
                        ),
                        "_blank"
                    );
                }
            } else if (params.column.colId == "gaxsna") {
                let action = params.event.target.dataset.action;

                if (action == "excel") {
                    window.location.href =
                        this.setting.url.nested.excel.replace(
                            "__id__",
                            params.data.id
                        ) + "open=1";
                }

                if (action == "pdf") {
                    window.open(
                        this.setting.url.nested.pdf.replace(
                            "__id__",
                            params.data.id
                        ) + "?open=1",
                        "_blank"
                    );
                }
            }
        },
    },
};
</script>

<style>
.rag-red {
    background-color: #ec333880 !important;
}
.rag-green {
    background-color: #6dbe4f80 !important;
}
.rag-yellow {
    background-color: #fbb22f80 !important;
}
.rag-green-arrived {
    background-color: #00c079 !important;
}

.rag-gray {
    background-color: #a9a9a9 !important;
}

.rag-on-repair {
    background-color: #a020f045 !important;
}
.ag-header-cell-menu-button {
    display: none;
}
div.ag-header-cell-label {
    text-align: center;
    justify-content: center;
}
.action-button {
    border: none;
    color: white;
    padding: 3px 12px;
    text-align: center;
    text-decoration: none;
    display: inline-block;
    font-size: 16px;
    line-height: initial;
    opacity: 0.7;
}

.action-button:hover {
    opacity: 1;
}

.action-button.edit {
    background-color: #008cba; /* Blue */
}
.action-button.update {
    background-color: #4caf50; /* Green */
}

.action-button.delete {
    background-color: #f44336; /* Red */
}

.action-button.cancel {
    background-color: #e7e7e7; /* Gray */
    color: black;
}
</style>
