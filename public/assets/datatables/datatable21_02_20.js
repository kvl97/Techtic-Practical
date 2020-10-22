"use strict";

var DataTables = function () {
	var tableOptions; // main options
	var dataTable; // datatable object
	var table; // actual table jquery object
	var tableContainer; // actual table container object
	var tableWrapper; // actual table wrapper jquery object
	var tableInitialized = false;
	var ajaxParams = {}; // set filter mode
	var the;

	$.fn.dataTable.Api.register('column().title()', function () {
		return $(this.header()).text().trim();
	});

	return {

		//main function to initiate the module
		init: function (tableId, url) {
			the = this
			table = $(tableId);
			dataTable = $(tableId).DataTable({
				responsive: true,

				// Pagination settings
				dom: `<'row'<'col-sm-12'tr>>
				<'row'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7 dataTables_pager'lp>>`,
				// read more: https://datatables.net/examples/basic_init/dom.html
				filterApplyAction: "filter",
				filterCancelAction: "filter_cancel",
				lengthMenu: [5, 10, 25, 50],

				pageLength: 10,
				columnDefs: [{
					"targets": 'no-sort',
					"orderable": false,
				}],
				language: {
					'lengthMenu': 'Display _MENU_',
				},

				searchDelay: 500,
				processing: true,
				serverSide: true,
				ajax: {
					url: url,
					type: 'POST',
					timeout: 20000,
					data: function(data) { // add request parameters before submit
						data['page'] = parseInt((data['start']) / (data['length']) + 1);

						$.each(ajaxParams, function(key, value) {
								data[key] = value;
						});
					}
					
				},
				/* columns: [
					{data: 'RecordID'},
					{data: 'OrderID'},
					{data: 'Country'},
					{data: 'ShipCity'},
					{data: 'CompanyAgent'},
					{data: 'ShipDate'},
					{data: 'Status'},
					{data: 'Type'},
					{data: 'Actions', responsivePriority: -1},
				], */
				initComplete: function () {
					var thisTable = this;
				/* 	var rowFilter = $(tableId).find('.search-btn');

					console.log(table.ajax);

					$(tableId).on('click', '.search-btn', function (e) {
						console.log('teste');
						table.submitFilter()
					})
 */
					/* $(tableId).on('click', 'reset-btn', function(e) {
						e.preventDefault();
						$(rowFilter).find('.kt-input').each(function(i) {
							$(this).val('');
							table.column($(this).data('col-index')).search('', false, false);
						});
						table.table().draw();
					}); */

					// hide search column for responsive table
					/* var hideSearchColumnResponsive = function () {
						thisTable.api().columns().every(function () {
							var column = this
							if (column.responsiveHidden()) {
								$(rowFilter).find('th').eq(column.index()).show();
							} else {
								$(rowFilter).find('th').eq(column.index()).hide();
							}
						})
					}; */

					// init on datatable load
					// hideSearchColumnResponsive();
					// recheck on window resize
					// window.onresize = hideSearchColumnResponsive;

					$('#kt_datepicker_1,#kt_datepicker_2').datepicker();
				}
			});

			// handle filter submit button click
			table.on('click', '.filter-submit', function (e) {
				e.preventDefault();
				the.submitFilter();
			});

			table.on('keyup', '.filter input[type="text"]', function (e) {
				var unicode = e.keyCode;
				if (unicode == 13) {
					e.preventDefault();
					the.submitFilter();
				}
			});

			// handle filter cancel button click
			table.on('click', '.filter-cancel', function (e) {			
				e.preventDefault();
				the.resetFilter();
			});
			table.on('click', ".delete_record", function(e) {
				var url = $(this).attr('delete-url');
				var arrId = $(this).attr('rel');
				the.anyDeleteRecords(url,arrId);
			});

			// handle group checkboxes check/uncheck
            $('.group-checkable', table).change(function() {
                var set = $('tbody > tr > td:nth-child(1) input[type="checkbox"]', table);
                var checked = $(this).is(":checked");
                $(set).each(function() {
                    $(this).attr("checked", checked);
                });
                $.uniform.update(set);
                countSelectedRecords();
            });
		},
		submitFilter: function () {
			the.setAjaxParam("action", 'filter');

			// get all typeable inputs
			$('textarea.form-filter, select.form-filter, input.form-filter:not([type="radio"],[type="checkbox"])', table).each(function () {
				the.setAjaxParam($(this).attr("name"), $(this).val());
			});

			// get all checkboxes
			$('input.form-filter[type="checkbox"]:checked', table).each(function () {
				the.addAjaxParam($(this).attr("name"), $(this).val());
			});

			// get all radio buttons
			$('input.form-filter[type="radio"]:checked', table).each(function () {
				the.setAjaxParam($(this).attr("name"), $(this).val());
			});

			dataTable.ajax.reload();
		},

		resetFilter: function () {
			$('textarea.form-filter, select.form-filter, input.form-filter', table).each(function () {
				$(this).val("");				
			});
			$('input.form-filter[type="checkbox"]', table).each(function () {
				$(this).attr("checked", false);
			});

			the.clearAjaxParams();
			the.addAjaxParam("action", 'filter_cancel');
			dataTable.ajax.reload();
		},
		getSelectedRowsCount: function () {
			return $('tbody > tr > td:nth-child(1) input[type="checkbox"]:checked', table).size();
		},

		getSelectedRows: function () {
			var rows = [];
			$('tbody > tr > td:nth-child(1) input[type="checkbox"]:checked', table).each(function () {
				rows.push($(this).val());
			});

			return rows;
		},

		setAjaxParam: function (name, value) {
			ajaxParams[name] = value;
		},

		addAjaxParam: function (name, value) {
			if (!ajaxParams[name]) {
				ajaxParams[name] = [];
			}

			var skip = false;
			for (var i = 0; i < (ajaxParams[name]).length; i++) { // check for duplicates
				if (ajaxParams[name][i] === value) {
					skip = true;
				}
			}

			if (skip === false) {
				ajaxParams[name].push(value);
			}
		},

		clearAjaxParams: function (name, value) {
			ajaxParams = {};
		},

		getDataTable: function () {
			return dataTable;
		},

		getTableWrapper: function () {
			return tableWrapper;
		},

		gettableContainer: function () {
			return tableContainer;
		},

		getTable: function () {
			return table;
		},
		getAjaxParams: function () {
			//ajaxParams['page'] = ajaxParams['export_page'];
			//ajaxParams['length'] = ajaxParams['export_length'];
			return ajaxParams;
		},
		anyDeleteRecords: function(url,arrId) {
			swal.fire({
                title: 'Are you sure You want to delete this record?',
                text: '',
                type: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Delete',
                cancelButtonText: 'Cancel',
                reverseButtons: true
            }).then(function(result){
                if (result.value) {
					console.log("url",url,"arrId",arrId)
					$.ajax({
						url: url,
						type: 'get',
						success:function(data){ 
							swal.fire(
								'Deleted!',
								'Your record has been deleted.',
								'success'
							)
							$('#datatable_ajax').DataTable().ajax.reload();
						}
					});
                    
                } 
			});
		}

	};

}();

