"use strict";

var DataTables = function() {
    var tableOptions; // main options
    var dataTable; // datatable object
    var table; // actual table jquery object
    var tableContainer; // actual table container object
    var tableWrapper; // actual table wrapper jquery object
    var tableInitialized = false;
    var ajaxParams = {}; // set filter mode
    var the;
    var durl;

    $.fn.dataTable.Api.register('column().title()', function() {
        return $(this.header()).text().trim();
    });

    /* var countSelectedRecords = function () {
    	var selected = $('tbody > tr > td:nth-child(1) input[type="checkbox"]:checked', table).length;
    	var text = tableOptions.dataTable.language.metronicGroupActions;
    	if (selected > 0) {
    		$('.table-group-actions > span', tableWrapper).text(text.replace("_TOTAL_", selected));
    	} else {
    		$('.table-group-actions > span', tableWrapper).text("");
    	}
    }; */

    return {

        //main function to initiate the module
        init: function(tableId, url, order) {
            the = this;
            durl = url;

            if ($.cookie(durl) !== null && $.cookie(durl) !== "" && $.cookie(durl) !== undefined) {
                var cookieData = $.cookie(durl);
                var cookieData = $.parseJSON(cookieData);
                $.each(cookieData, function(index, value) {
                    the.setAjaxParam(index, value);
                });
            }

            table = $(tableId);
            /* if (order == '') {
                order = [
                    [1, 'asc']
                ]
            } */
            console.log("order....", order);
            if (order == '' || order == undefined) {
                order = [
                    [1, 'asc']
                ]
            } else {
                order = [
                    [order[0], order[1]]
                ];
            }
            dataTable = $(tableId).DataTable({
                // responsive: true,
                // Pagination settings
                dom: `<'row'<'col-sm-12'tr>>
				<'row'<'col-sm-12 col-md-4'<'table-group-actions'>><'col-sm-12 col-md-3 text-center'i><'col-sm-12 col-md-5 dataTables_pager'lp>>`,
                filterApplyAction: "filter",
                filterCancelAction: "filter_cancel",
                lengthMenu: [5, 10, 25, 50],
                order: order,
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
                bStateSave: false,
                ajax: {
                    url: url,
                    type: 'POST',
                    timeout: 20000,
                    data: function(data) { // add request parameters before submit

                       
                        $.each(ajaxParams, function(key, value) {
                            data[key] = value;
                        });
                        data['page'] = parseInt((data['start']) / (data['length']) + 1);
                        //Set field in cookie
                        if ($.cookie(url) === null || $.cookie(url) === "") {} else {
                            $.removeCookie(url);
                        }

                        var filterData = the.getAjaxParams();
                        var cookieData = {};
                        $.each(filterData, function(index, value) {
                            if (index == 'page' || index == 'length') {
                                cookieData[index] = value;
                            }
                            if ($('[name=' + index + ']').length > 0) {
                                $('[name=' + index + ']').val(value);
                                cookieData[index] = value;
                            }
                        });
                        /* cookieData['page'] = data['page'];
                        cookieData['length'] = data['length']; */
                        $.cookie(url, JSON.stringify(cookieData));
                        setTimeout(function(){
                            $('[data-toggle="tooltip"]').tooltip({'placement': 'left'});
                        }, 1000);
                    }
                },
                initComplete: function() {
                    var thisTable = this;
                    $('#kt_datepicker_1,#kt_datepicker_2').datepicker();

                    $('.table-group-actions').html($('.table-bulk-action').html());
                    $('.table-bulk-action').remove();
                },
                'fnRowCallback': function(row, data, index) {
                    $(row).find('span.free_line_run').closest('td').addClass('hold-bg')
                }
            });

            tableWrapper = table.parents('.dataTables_wrapper');

            table.on('change', '.kt-kt-group-checkable', function() {
                var set = $(this).closest('table').find('td:first-child .kt-checkable');
                var checked = $(this).is(':checked');

                $(set).each(function() {
                    if (checked) {
                        $(this).prop('checked', true);
                        $(this).closest('tr').addClass('active');
                    } else {
                        $(this).prop('checked', false);
                        $(this).closest('tr').removeClass('active');
                    }
                });
            });

            table.on('change', 'tbody tr .kt-checkbox', function() {
                $(this).parents('tr').toggleClass('active');
            });

            // handle filter submit button click
            table.on('click', '.filter-submit', function(e) {
                e.preventDefault();
                the.submitFilter();
            });

            table.on('keyup', '.filter input[type="text"]', function(e) {
                var unicode = e.keyCode;
                if (unicode == 13) {
                    e.preventDefault();
                    the.submitFilter();
                }
            });

            // handle filter cancel button click
            table.on('click', '.filter-cancel', function(e) {
                e.preventDefault();
                the.resetFilter();
            });
            table.on('click', ".delete_record", function(e) {
                var url = $(this).attr('delete-url');
                var arrId = $(this).attr('rel');
                the.anyDeleteRecords(url, arrId);
            });

            // handle group checkboxes check/uncheck
            $('.kt-group-checkable', table).change(function() {
                var set = $('tbody > tr > td:nth-child(1) input[type="checkbox"]', table);
                var checked = $(this).is(":checked");
                $(set).each(function() {
                    $(this).attr("checked", checked);
                });
                // $.uniform.update(set);
                // countSelectedRecords();
            });

            $(document).on('click', '#export_to_excel', function (e) {
                var a = the.getDataTable().ajax.params();
                    // var a = $("#datatable_ajax").DataTable().ajax.params(),
                a = $.param(a),
                e = $(this).attr("action-url");
                
                window.location.href = e + "?" + a
            });

            tableWrapper.on('click', '#bulk_action_submit', function(e) {
                var action = tableWrapper.find('select[name=bulk_action] option:selected').text();
                var url = tableWrapper.find('.table-group-action-url').val();

                the.bulkAction(action, url);
            });
        },
        submitFilter: function() {
            the.setAjaxParam("action", 'filter');

            // get all typeable inputs
            $('textarea.form-filter, select.form-filter, input.form-filter:not([type="radio"],[type="checkbox"])', table).each(function() {
                the.setAjaxParam($(this).attr("name"), $(this).val());
            });

            // get all checkboxes
            $('input.form-filter[type="checkbox"]:checked', table).each(function() {
                the.addAjaxParam($(this).attr("name"), $(this).val());
            });

            // get all radio buttons
            $('input.form-filter[type="radio"]:checked', table).each(function() {
                the.setAjaxParam($(this).attr("name"), $(this).val());
            });

            dataTable.ajax.reload();
        },

        resetFilter: function() {
            $('textarea.form-filter, select.form-filter, input.form-filter', table).each(function() {
                $(this).val("");
            });
            $('input.form-filter[type="checkbox"]', table).each(function() {
                $(this).attr("checked", false);
            });

            the.clearAjaxParams();
            the.addAjaxParam("action", 'filter_cancel');
            dataTable.ajax.reload();
            $.removeCookie(durl);
        },
        getSelectedRowsCount: function() {
            return $('tbody > tr > td:nth-child(1) input[type="checkbox"]:checked', table).length;
        },

        getSelectedRows: function() {
            var rows = [];
            $('tbody > tr > td:nth-child(1) input[type="checkbox"]:checked', table).each(function() {
                rows.push($(this).val());
            });

            return rows;
        },

        setAjaxParam: function(name, value) {
            ajaxParams[name] = value;
        },

        addAjaxParam: function(name, value) {
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

        clearAjaxParams: function(name, value) {
            $.removeCookie(durl);
            ajaxParams = {};
        },

        getDataTable: function() {
            return dataTable;
        },

        getTableWrapper: function() {
            return tableWrapper;
        },

        gettableContainer: function() {
            return tableContainer;
        },

        getTable: function() {
            return table;
        },
        getAjaxParams: function() {
            //ajaxParams['page'] = ajaxParams['export_page'];
            //ajaxParams['length'] = ajaxParams['export_length'];
            return ajaxParams;
        },
        anyDeleteRecords: function(url, arrId) {
            swal.fire({
                title: 'Are you sure You want to delete this record?',
                text: '',
                type: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Delete',
                cancelButtonText: 'Cancel',
                reverseButtons: true
            }).then(function(result) {
                if (result.value) {
                    console.log("url", url, "arrId", arrId)
                    $.ajax({
                        url: url,
                        type: 'get',
                        success: function(data) {
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
        },
        getSelectedRowsCount: function() {
            return $('tbody > tr > td:nth-child(1) input[type="checkbox"]:checked', table).length;
        },
        getSelectedRowsIds: function() {
            var rows = [];
            $('tbody > tr > td:nth-child(1) input[type="checkbox"]:checked', table).each(function() {
                rows.push($(this).val());
            });
            return rows;
        },
        bulkAction(action, url) {
            var selectedRowCount = the.getSelectedRowsCount();
            var selectedRowIds = the.getSelectedRowsIds();


            if (action != '' && selectedRowCount > 0) {
                var send_data = {};
                send_data.action = action;
                send_data['ids'] = selectedRowIds;
                if (send_data.action == 'Delete') {
                    swal.fire({
                        title: 'Are you sure You want to delete this record?',
                        text: '',
                        type: 'warning',
                        showCancelButton: true,
                        confirmButtonText: 'Delete',
                        cancelButtonText: 'Cancel',
                        reverseButtons: true
                    }).then(function(result) {
                        if (result.value) {
                            $.post(url, send_data, function(data) {
                                if (data == 'TRUE') {
                                    var send_data_action = send_data.action.toLowerCase();
                                    if (send_data_action == 'active') {
                                        send_data_action = 'actived';
                                    } else if (send_data_action == 'inactive') {
                                        send_data_action = 'inactived';
                                    } else if (send_data_action == 'delete') {
                                        send_data_action = 'deleted';
                                    } else if (send_data_action == 'prefix' || send_data_action == 'suffix') {
                                        send_data_action = 'updated';
                                    }

                                    swal.fire('Record has been ' + send_data_action + ' successfully.')
                                    dataTable.ajax.reload();

                                    $.each(send_data['ids'], function(i, id) {
                                        if (send_data.action == 'Active') {
                                            $('tbody > tr > td > .status_' + id, table).addClass('label-success');
                                            $('tbody > tr > td > .status_' + id, table).removeClass('label-danger');
                                            $('tbody > tr > td > .status_' + id, table).text(send_data.action);

                                        } else if (send_data.action == 'Inactive') {
                                            $('tbody > tr > td > .status_' + id, table).addClass('label-danger');
                                            $('tbody > tr > td > .status_' + id, table).removeClass('label-success');
                                            $('tbody > tr > td > .status_' + id, table).text(send_data.action);

                                        } else {
                                            $('tbody > tr > td  .cheched').closest('tr').fadeOut(1500, function() {
                                                $(this).closest('tr').remove();
                                                if ($("#datatable_ajax tbody > tr").length <= 1) {
                                                    table.find(".filter-submit").trigger("click");
                                                }
                                            });
                                        }
                                    });
                                    $('#datatable_ajax').DataTable().ajax.reload();
                                    setTimeout(function() { $('.alert-success').fadeOut(4000); }, 3000);
                                    var set = $('tbody > tr > td:nth-child(1) input[type="checkbox"]', table);
                                    var checked = $(this).is(":checked");
                                    $(set).each(function() {
                                        $(this).attr("checked", false);
                                    });
                                    $('.table-group-action-input').val('');
                                    $('.kt-group-checkable').prop("checked", false);
                                }
                            });
                        }
                    });
                } else {
                    $.post(url, send_data, function(data) {
                        if (data == 'TRUE') {

                            var send_data_action = send_data.action.toLowerCase();
                            if (send_data_action == 'active') {
                                send_data_action = 'actived';
                            } else if (send_data_action == 'inactive') {
                                send_data_action = 'inactived';
                            } else if (send_data_action == 'delete') {
                                send_data_action = 'deleted';
                            } else if (send_data_action == 'prefix' || send_data_action == 'suffix') {
                                send_data_action = 'updated';
                            }

                            swal.fire('Record has been ' + send_data_action + ' successfully.');
                            dataTable.ajax.reload();

                            $.each(send_data['ids'], function(i, id) {
                                if (send_data.action == 'Active') {
                                    $('tbody > tr > td > .status_' + id, table).addClass('label-success');
                                    $('tbody > tr > td > .status_' + id, table).removeClass('label-danger');
                                    $('tbody > tr > td > .status_' + id, table).text(send_data.action);
                                    // $('#datatable_ajax').DataTable().ajax.reload();
                                } else if (send_data.action == 'Inactive') {
                                    $('tbody > tr > td > .status_' + id, table).addClass('label-danger');
                                    $('tbody > tr > td > .status_' + id, table).removeClass('label-success');
                                    $('tbody > tr > td > .status_' + id, table).text(send_data.action);
                                    //$('#datatable_ajax').DataTable().ajax.reload();
                                } else {
                                    $('tbody > tr > td  .cheched').closest('tr').fadeOut(1500, function() {
                                        $(this).closest('tr').remove();
                                        //$('#datatable_ajax').DataTable().ajax.reload();
                                        if ($("#datatable_ajax tbody > tr").length <= 1) {
                                            $(".filter-submit").trigger("click");
                                        }
                                    });
                                }
                            });
                            $('#datatable_ajax').DataTable().ajax.reload();
                            setTimeout(function() { $('.alert-success').fadeOut(4000); }, 3000);
                            var set = $('tbody > tr > td:nth-child(1) input[type="checkbox"]', table);
                            var checked = $(this).is(":checked");
                            $(set).each(function() {
                                $(this).attr("checked", false);
                                $('.kt-group-checkable').prop("checked", false);
                                /* $('.kt-group-checkable').parent().find('span').removeProp('checked'); */
                            });
                            $('.table-group-action-input').val('');
                            $('.kt-group-checkable').prop("checked", false);

                        }
                    });
                }

            } else if (action == "") {
                swal.fire('Please select an action.')
            } else if (selectedRowCount === 0) {
                swal.fire('Please select action.')
                $('.table-group-action-input').val('');
            }
        }

    };

}();