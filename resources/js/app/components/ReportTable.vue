<template>
	<ag-grid-vue
    style="width: 100%; height: 77vh"
    class="ag-theme-alpine"
    :columnDefs="columnDefs"
    :defaultColDef="defaultColDef"
    :rowData="rowData"
    :paginationPageSize="17"
    :pagination="true"
    :columnTypes="columnTypes"
		:tooltipShowDelay="tooltipShowDelay"
		:tooltipHideDelay="tooltipHideDelay"
    @grid-ready="onGridReady"
    @cell-clicked="onCellClicked"
  >
  </ag-grid-vue>
</template>

<script>
	import Util from 'Util'

	import "ag-grid-community/dist/styles/ag-grid.css"
	import "ag-grid-community/dist/styles/ag-theme-alpine.css"

	import { AgGridVue } from "ag-grid-vue3";

	function actionCellRenderer(params) {

		let eGui = document.createElement('div')

		eGui.innerHTML = `
			<i style="cursor:pointer; color:green; font-size:1.2em; margin-right:0.3em;" data-action="edit" class="fas fa-edit"></i>
			<i style="cursor:pointer; font-size:1.2em; margin-right:0.3em;" data-action="gadawera" class="far fa-copy"></i>
			<i style="cursor:pointer; color:red; font-size:1.2em;" data-action="delete" class="fas fa-trash"></i>
		`;
		return eGui;
	}

	function alterRenderer(params) {

		let eGui = document.createElement('div')

		eGui.innerHTML = `
			<i style="cursor:pointer; color:green; font-size:1.2em; margin-right:0.3em;" data-action="edit" class="fas fa-edit"></i>
			<i style="cursor:pointer; color:red; font-size:1.2em;" data-action="delete" class="fas fa-trash"></i>
		`;
		return eGui;
	}

	function actionCellRenderer2(params) {

		let eGui = document.createElement('div')

		// <i style="cursor:pointer; color:green; font-size:1.2em; margin-right:0.3em;" data-action="excel" class="fas fa-file-excel"></i>

		eGui.innerHTML = `
			<i style="cursor:pointer; font-size:1.2em; margin-right:0.3em; color:red;" data-action="pdf" class="fas fa-file-pdf"></i>
		`;
		return eGui;
	}

	app.component('Inter', {
		  template: `
		      <div class="custom-tooltip" style="border: 1px solid cornflowerblue" v-bind:style="{ backgroundColor: color }">
		          <p><span></span>{{ data.title }}</p>
		      </div>
		    `,
		  data: function () {
		    return {
		      color: null,
		      title: null
		    };
		  },
		  beforeMount() {
		    this.data = this.params.api.getDisplayedRowAtIndex(
		      this.params.rowIndex
		    ).data;
		    this.color = this.params.color || 'white';
		  },
	});


	export default {
		props:['user', 'additional', 'name', 'setting', 'model'],
		components: { AgGridVue },
		data: () => {
			return {
				rowData: null,
				gridApi: null,
				columnApi: null
			}
		},
		setup (props) {
			let is_table_advanced = [
			{
						headerName: 'ნახვა',
						headerClass: 'text-center', 
						maxWidth: 117, 
						filter: false, 
						cellStyle: { textAlign: 'center'},
						cellRenderer: actionCellRenderer2, editable: false, colId: 'gadawera' 
					},{ 
						headerName: 'ქმედება', 
						headerClass: 'text-center', 
						maxWidth: 100, 
						filter: false, 
						cellStyle: { textAlign: 'center'}, 
						cellRenderer: alterRenderer, editable: false, colId: 'action' 
					}]

	    return {
	    	defaultColDef: {
					flex: 1,
					// width: 100,
					filter: true,
					floatingFilter: true,
					sortable: true,
					resizable: true,
					tooltipComponent: 'Inter'
				},
				tooltipShowDelay: 1000,
				tooltipHideDelay: 3000,
				columnDefs: [...props.setting.columns, ...is_table_advanced],
				columnTypes: {
		      nonEditableColumn: { editable: false },
		      dateColumn: {
		        // specify we want to use the date filter
		        filter: 'agDateColumnFilter',
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
                const dateParts = dateAsString.split('/');

                const month = Number(dateParts[0]) - 1;
                const day = Number(dateParts[1]);
                const year = Number(dateParts[2].split(' ')[0]);
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
		    }
			}
		},
		mounted() {
			// params.api.setRowData(this.model)
			console.log("model", this.model)
		},
		methods: {

			removeRequest (id, setting, callback) {

			  let token = document.querySelector('meta[name="csrf-token"').getAttribute('content')

			  return this.$http.delete(setting.url.request.destroy.replace('new', id), { id }, {
			    "Content-Type": "application/json",
			    'X-CSRF-TOKEN': token
			  })
			  .then(async response => {
			    if (response.data.success == true) {
			      callback()
			    } else {
			    	if(response.data.errs.length) response.data.errs.map(item => this.$toast.error(item, { position: 'top-right', duration: 7000 }))
			    }
			  })
			  .catch(function (e) {
			    if (e.response.statusText) this.$toast.error(e.response.statusText, { position: 'top-right', duration: 7000 })
			  });
			},

	    remove (parems, id, remove) {

	      return  Util.useSwall2([]).then((result) => {
	        if (result.isConfirmed) {

	            this.removeRequest(id, this.setting, () => {
	            	this.gridApi.updateRowData({ remove: [remove]})
	              Swal.fire('წაშლა!', 'წაშლა შესრრულდა წარმატებით.', 'success')
	          })
	        }
	      })
	    },

			onGridReady(params) {
			   this.gridApi = params.api;
			   this.columnApi = params.columnApi;

			   params.api.setRowData(JSON.parse(JSON.stringify(this.model)))

			   // this.gridApi.sizeColumnsToFit()

			 //   const updateData = (res) => params.api.setRowData(res);

				// this.$http.get(this.setting.url.request.index).then(response => response.data).then(response =>updateData(response))
				// .catch(function (error) {
				// 	console.log(error);
				// });

			},

			onCellClicked(params) {

				if (params.column.colId == "action") {
					let action = params.event.target.dataset.action;

					if (action === 'edit') { // params.event.target.dataset.action
						let redirect = this.setting.url.request.edit.replace('new', params.data.id)
						window.location.href = redirect;
					} else

						if (action === 'gadawera') {

						window.location.href = this.setting.url.request.edit
					} else

						if (action === 'delete') {
						this.remove(params, params.data.id, params.node.data)
					}
				} else if (params.column.colId == "gadawera") {
					let action = params.event.target.dataset.action;

					if (action == 'pdf') {
						window.open(this.setting.url.request.show.replace("new", params.data.id), '_blank')
					}
				}

			}
		}
	}
</script>


<style>
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