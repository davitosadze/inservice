<template>
    <div>
        <!-- Loading indicator -->
        <div v-if="isLoading" class="loading-overlay">
            <div class="spinner"></div>
        </div>

        <!-- AG Grid -->
        <ag-grid-vue
            style="height: 70vh; width: 100%"
            class="ag-theme-quartz"
            :columnDefs="columnDefs"
            :defaultColDef="defaultColDef"
            :rowData="rowData"
            :pagination="false"
            :columnTypes="columnTypes"
            :rowClassRules="rowClassRules"
            :tooltipShowDelay="tooltipShowDelay"
            :tooltipHideDelay="tooltipHideDelay"
            :context="{ componentParent: this }"
            @grid-ready="onGridReady"
            @cell-clicked="onCellClicked"
            @filter-changed="onFilterChanged"
            @sort-changed="onSortChanged"
        >
        </ag-grid-vue>

        <!-- Custom Pagination -->
        <div class="pagination-container">
            <div class="pagination-info">
                Showing {{ startRecord }} - {{ endRecord }} of {{ totalRecords }} records
            </div>
            <div class="pagination-controls">
                <button
                    class="pagination-btn"
                    :disabled="currentPage === 1"
                    @click="goToPage(1)"
                >
                    &laquo;
                </button>
                <button
                    class="pagination-btn"
                    :disabled="currentPage === 1"
                    @click="goToPage(currentPage - 1)"
                >
                    &lsaquo;
                </button>

                <template v-for="page in visiblePages" :key="page">
                    <button
                        v-if="page === '...'"
                        class="pagination-btn pagination-ellipsis"
                        disabled
                    >
                        ...
                    </button>
                    <button
                        v-else
                        class="pagination-btn"
                        :class="{ active: page === currentPage }"
                        @click="goToPage(page)"
                    >
                        {{ page }}
                    </button>
                </template>

                <button
                    class="pagination-btn"
                    :disabled="currentPage === lastPage"
                    @click="goToPage(currentPage + 1)"
                >
                    &rsaquo;
                </button>
                <button
                    class="pagination-btn"
                    :disabled="currentPage === lastPage"
                    @click="goToPage(lastPage)"
                >
                    &raquo;
                </button>
            </div>
        </div>
    </div>
</template>

<script>
import "ag-grid-community/styles/ag-grid.css";
import "ag-grid-community/styles/ag-theme-quartz.css";
import { AgGridVue } from "ag-grid-vue3";

function alterRendererWithView(params) {
    let eGui = document.createElement("div");

    const modelType =
        params.context?.componentParent?.setting?.model || "responses";

    const permissions = {
        responses: {
            view: "რეაგირების ნახვა",
            edit: "რეაგირების რედაქტირება",
            delete: "რეაგირების წაშლა",
        },
        repairs: {
            view: "რემონტის ნახვა",
            edit: "რემონტის რედაქტირება",
            delete: "რემონტის წაშლა",
        },
    };

    const modelPermissions = permissions[modelType] || permissions["responses"];

    let buttonsHtml = "";

    if ($can(modelPermissions.view)) {
        buttonsHtml += `<i style="cursor:pointer; color:green; font-size:1.2em; margin-right:0.3em;" data-action="view" class="fas fa-eye"></i>`;
    }

    if ($can(modelPermissions.edit)) {
        buttonsHtml += `<i style="cursor:pointer; color:green; font-size:1.2em; margin-right:0.3em;" data-action="edit" class="fas fa-edit"></i>`;
    }

    if ($can(modelPermissions.delete)) {
        buttonsHtml += `<i style="cursor:pointer; color:red; font-size:1.2em;" data-action="delete" class="fas fa-trash"></i>`;
    }

    eGui.innerHTML = buttonsHtml;
    return eGui;
}

function $can(permissionName) {
    return Laravel.jsPermissions.permissions.indexOf(permissionName) !== -1;
}

export default {
    props: ["user", "additional", "name", "setting"],
    name: "server-side-table",
    components: { AgGridVue },
    data: () => {
        return {
            rowData: [],
            gridApi: null,
            columnApi: null,
            rowClassRules: null,
            isLoading: false,
            currentPage: 1,
            lastPage: 1,
            totalRecords: 0,
            perPage: 17,
            currentFilters: {},
            currentSort: { field: 'id', order: 'desc' },
            filterDebounceTimer: null,
        };
    },
    computed: {
        startRecord() {
            if (this.totalRecords === 0) return 0;
            return (this.currentPage - 1) * this.perPage + 1;
        },
        endRecord() {
            const end = this.currentPage * this.perPage;
            return end > this.totalRecords ? this.totalRecords : end;
        },
        visiblePages() {
            const pages = [];
            const total = this.lastPage;
            const current = this.currentPage;

            if (total <= 7) {
                for (let i = 1; i <= total; i++) {
                    pages.push(i);
                }
            } else {
                pages.push(1);

                if (current > 3) {
                    pages.push('...');
                }

                let start = Math.max(2, current - 1);
                let end = Math.min(total - 1, current + 1);

                if (current <= 3) {
                    end = 4;
                }
                if (current >= total - 2) {
                    start = total - 3;
                }

                for (let i = start; i <= end; i++) {
                    pages.push(i);
                }

                if (current < total - 2) {
                    pages.push('...');
                }

                pages.push(total);
            }

            return pages;
        }
    },
    setup(props) {
        const actionColumn = {
            headerName: "ქმედება",
            headerClass: "text-center",
            maxWidth: 120,
            filter: false,
            sortable: false,
            cellStyle: { textAlign: "center" },
            cellRenderer: alterRendererWithView,
            editable: false,
            colId: "action",
        };

        return {
            defaultColDef: {
                flex: 1,
                minWidth: 150,
                filter: true,
                floatingFilter: true,
                sortable: true,
                resizable: true,
            },
            tooltipShowDelay: 1000,
            tooltipHideDelay: 3000,
            columnDefs: [...props.setting.columns, actionColumn],
            columnTypes: {
                nonEditableColumn: { editable: false },
                dateColumn: {
                    filter: "agDateColumnFilter",
                    filterParams: {
                        comparator: (filterLocalDateAtMidnight, cellValue) => {
                            const dateAsString = cellValue;
                            if (dateAsString == null) {
                                return 0;
                            }
                            const dateParts = dateAsString.split("/");
                            const month = Number(dateParts[0]) - 1;
                            const day = Number(dateParts[1]);
                            const year = Number(dateParts[2].split(" ")[0]);
                            const cellDate = new Date(year, month, day);

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
        async fetchData() {
            this.isLoading = true;

            try {
                const params = {
                    page: this.currentPage,
                    perPage: this.perPage,
                    sortField: this.currentSort.field,
                    sortOrder: this.currentSort.order,
                };

                if (Object.keys(this.currentFilters).length > 0) {
                    params.filters = JSON.stringify(this.currentFilters);
                }

                const response = await this.$http.get(this.setting.url.request.index, { params });

                this.rowData = response.data.data;
                this.totalRecords = response.data.total;
                this.currentPage = response.data.page;
                this.lastPage = response.data.lastPage;

            } catch (error) {
                console.error('Error fetching data:', error);
                this.$toast.error('შეცდომა მონაცემების ჩატვირთვისას', {
                    position: "top-right",
                    duration: 5000,
                });
            } finally {
                this.isLoading = false;
            }
        },

        goToPage(page) {
            if (page < 1 || page > this.lastPage || page === this.currentPage) {
                return;
            }
            this.currentPage = page;
            this.fetchData();
        },

        onGridReady(params) {
            this.gridApi = params.api;
            this.columnApi = params.columnApi;

            // Expose grid API to window for export functionality
            if (this.setting.model === 'responses') {
                window.responsesGridApi = params.api;
            } else if (this.setting.model === 'services') {
                window.servicesGridApi = params.api;
            } else if (this.setting.model === 'repairs') {
                window.repairsGridApi = params.api;
            }

            this.fetchData();
        },

        onFilterChanged(params) {
            // Clear existing timer
            if (this.filterDebounceTimer) {
                clearTimeout(this.filterDebounceTimer);
            }

            // Debounce filter changes
            this.filterDebounceTimer = setTimeout(() => {
                const filterModel = this.gridApi.getFilterModel();
                this.currentFilters = {};

                // Map AG Grid filter model to our API format
                for (const [field, filter] of Object.entries(filterModel)) {
                    // Find the column definition to get the actual field name
                    const colDef = this.setting.columns.find(col => col.field === field);
                    let apiField = field;

                    // Extract field from valueGetter if it exists
                    if (colDef && colDef.valueGetter) {
                        const match = colDef.valueGetter.match(/data\.(.+)/);
                        if (match) {
                            apiField = match[1];
                        }
                    }

                    this.currentFilters[apiField] = {
                        filter: filter.filter,
                        filterType: filter.filterType || 'text',
                        type: filter.type || 'contains'
                    };
                }

                this.currentPage = 1;
                this.fetchData();
            }, 300);
        },

        onSortChanged(params) {
            const sortModel = this.gridApi.getColumnState()
                .filter(col => col.sort)
                .map(col => ({
                    colId: col.colId,
                    sort: col.sort
                }));

            if (sortModel.length > 0) {
                const colDef = this.setting.columns.find(col => col.field === sortModel[0].colId);
                let sortField = sortModel[0].colId;

                // Extract field from valueGetter if it exists
                if (colDef && colDef.valueGetter) {
                    const match = colDef.valueGetter.match(/data\.(.+)/);
                    if (match) {
                        sortField = match[1];
                    }
                }

                this.currentSort = {
                    field: sortField,
                    order: sortModel[0].sort
                };
            } else {
                this.currentSort = { field: 'id', order: 'desc' };
            }

            this.currentPage = 1;
            this.fetchData();
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

        remove(params, id) {
            return Swal.fire({
                title: 'დარწმუნებული ხართ?',
                text: "ამ მოქმედების გაუქმება შეუძლებელია!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'დიახ, წაშალე!',
                cancelButtonText: 'გაუქმება'
            }).then((result) => {
                if (result.isConfirmed) {
                    this.removeRequest(id, this.setting, () => {
                        this.fetchData();
                        Swal.fire(
                            'წაშლა!',
                            'წაშლა შესრულდა წარმატებით.',
                            'success'
                        );
                    });
                }
            });
        },

        onCellClicked(params) {
            if (params.column.colId == "action") {
                let action = params.event.target.dataset.action;

                if (action === "edit") {
                    let redirect = this.setting.url.request.edit.replace(
                        "new",
                        params.data.id
                    );
                    window.location.href = redirect;
                } else if (action == "view") {
                    let redirect = this.setting.url.request.show.replace(
                        "new",
                        params.data.id
                    );
                    window.location.href = redirect;
                } else if (action === "delete") {
                    this.remove(params, params.data.id);
                }
            }
        },
    },
};
</script>

<style scoped>
.loading-overlay {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: rgba(255, 255, 255, 0.7);
    display: flex;
    justify-content: center;
    align-items: center;
    z-index: 1000;
}

.spinner {
    width: 40px;
    height: 40px;
    border: 4px solid #f3f3f3;
    border-top: 4px solid #3498db;
    border-radius: 50%;
    animation: spin 1s linear infinite;
}

@keyframes spin {
    0% { transform: rotate(0deg); }
    100% { transform: rotate(360deg); }
}

.pagination-container {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 15px 10px;
    background: #f8f9fa;
    border-top: 1px solid #dee2e6;
    flex-wrap: wrap;
    gap: 10px;
}

.pagination-info {
    color: #6c757d;
    font-size: 14px;
    white-space: nowrap;
}

.pagination-controls {
    display: flex;
    gap: 3px;
    flex-wrap: wrap;
    justify-content: center;
}

.pagination-btn {
    padding: 8px 12px;
    border: 1px solid #dee2e6;
    background: white;
    cursor: pointer;
    border-radius: 4px;
    font-size: 14px;
    transition: all 0.2s;
    min-width: 36px;
    text-align: center;
}

.pagination-btn:hover:not(:disabled) {
    background: #e9ecef;
}

.pagination-btn:disabled {
    opacity: 0.5;
    cursor: not-allowed;
}

.pagination-btn.active {
    background: #007bff;
    color: white;
    border-color: #007bff;
}

.pagination-ellipsis {
    border: none;
    background: transparent;
}

@media (max-width: 576px) {
    .pagination-container {
        flex-direction: column;
        align-items: stretch;
    }

    .pagination-info {
        text-align: center;
        font-size: 12px;
    }

    .pagination-controls {
        justify-content: center;
        overflow-x: auto;
        max-width: 100%;
        padding: 5px 0;
    }

    .pagination-btn {
        padding: 6px 10px;
        font-size: 12px;
        min-width: 32px;
        flex-shrink: 0;
    }
}
</style>

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
</style>
