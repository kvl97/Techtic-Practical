var project_array;
var TableAjax = function (url) {
    
    var initPickers = function () {
        //init date pickers
        
    }
    

    var handleRecords = function (tableId, url, order_array, rec_per_page) {
        if (order_array == '') {
            order_array = [0, "asc"];
        }
        
        var grid = new Datatable();
        var table = $('#'+tableId); // actual table jquery object
        var bPaginate = true;

        if($("#"+tableId).hasClass('no-pagination')) {
            bPaginate = false;
        }
        var options = {
            src: $("#"+tableId),
            onSuccess: function (grid) {

                // execute some code after table records loaded
            },
            onError: function (grid) {
                // execute some code on network or other general error  
            },
            loadingMessage: 'Loading...',
            dataTable: {// here you can define a typical datatable settings from http://datatables.net/usage/options 

                // Uncomment below line("dom" parameter) to fix the dropdown overflow issue in the datatable cells. The default datatable layout
                // setup uses scrollable div(table-scrollable) with overflow:auto to enable vertical scroll(see: scripts/datatable.js). 
                // So when dropdowns used the scrollable div should be removed. 
                //"dom": "<'row'<'col-md-8 col-sm-12'pli><'col-md-4 col-sm-12'<'table-group-actions pull-right'>>r>t<'row'<'col-md-8 col-sm-12'pli><'col-md-4 col-sm-12'>>",
                "bStateSave": true, // save datatable state(pagination, sort, etc) in cookie.
                "lengthMenu": [
                    [10, 20, 50, 100, 150, 500],
                    [10, 20, 50, 100, 150, 500] // change per page values here
                ],
                "pageLength": rec_per_page, // default record count per page
                "ajax": {
                    "url": url, // ajax source
                },
                "columnDefs": [{
                        "targets": 'no-sort',
                        "orderable": false,
                    }],
                'displayStart': 0,
                "fnRowCallback": function (nRow, aData, iDisplayIndex, iDisplayIndexFull) {
                    // Bold the grade for all 'A' grade browsers
                    /* setTimeout(function () {
                        $(nRow).find('.make-switch').bootstrapSwitch();
                    }, 1); */
                    var dataGradeField = $(nRow).find('a').attr('data-grade'); 
                    if(dataGradeField == 'FALSE') {
                        $(nRow).addClass('grade-tr-background');
                    }
                    
                },
                "order": order_array, // set first column as a default sort by asc            
                "bPaginate": bPaginate,    
                "footerCallback": function (row, data, start, end, display) {
                    if($(row).hasClass('total-row')) {
                        var api = this.api(), data;                    
                        var columns = api.column()[0];
                        var recordSum = grid.getRecordSum();
                        columns.forEach(function(val, index) { 
                            var field_id = $(api.column(index).footer()).attr('fieldid');
                            if($(api.column(index).header()).hasClass('digit_field') && recordSum[field_id] !== undefined) {
                                $(api.column(index).footer()).text(((recordSum[field_id] === null || recordSum[field_id]  == '') ? 0 : recordSum[field_id]));
                            }
                        });
                    }
                }   
            }
        }

        if(tableId == 'datatable_dashboard_task' || tableId == 'datatable_dashboard_work' || tableId == 'datatable_dashboard_ppmtask') {
            options['dataTable']['bPaginate'] = false;
            options['dataTable']['bInfo'] = false;             
        }

        grid.init(options);

        
        // handle group actionsubmit button click
        grid.getTableWrapper().on('click', '.table-group-action-submit', function (e) {
            e.preventDefault();
            var action = $(".table-group-action-input", grid.getTableWrapper());
            var url = $(".table-group-action-url", grid.getTableWrapper()).val();
            if (action.val() != "" && grid.getSelectedRowsCount() > 0) {
                var send_data = {};
                send_data.action = action.val();
                send_data['ids'] = grid.getSelectedRows();
                var send_data_action = send_data.action.toLowerCase();
                
                bootbox.confirm('Are you sure you want to '+ send_data_action + ' this record? ', function(confirmed) {
                    if (confirmed) {
                        $.post(url, send_data, function (data) {
                            if (data == 'TRUE') {                            
                                if (send_data_action == 'active') {
                                    send_data_action = 'actived';
                                } else if (send_data_action == 'inactive') {
                                    send_data_action = 'inactived';
                                } else if (send_data_action == 'approved') {
                                    send_data_action = 'approved';
                                } else {
                                    send_data_action = 'deleted';
                                }
                                
                                toastr.success('Record has been ' + send_data_action + ' successfully.');
                                
                                $.each(send_data['ids'], function (i, id) {
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
                                    } else if (send_data.action == 'Approved') {
                                        $('tbody > tr > td > .status_' + id, table).text(send_data.action);
                                        //$('#datatable_ajax').DataTable().ajax.reload();
                                    } else {
                                        var id_arr = id.split('~');
                                        if (id_arr.length > 1) {
                                            id = id_arr[1];
                                        }
                                        $('tbody > tr > td  .delete_' + id, table).closest('tr').fadeOut(1500, function () {
                                            $(this).closest('tr').remove();
                                            //$('#datatable_ajax').DataTable().ajax.reload();
                                            if ($("#datatable_ajax tbody > tr").length <= 1) {
                                                $(".filter-submit").trigger("click");
                                            }
                                        });
                                    }
                                });
                                $('#datatable_ajax').DataTable().ajax.reload();
                                setTimeout(function () {
                                    $('.alert-success').fadeOut(4000);
                                }, 3000);
                                var set = $('tbody > tr > td:nth-child(1) input[type="checkbox"]', table);
                                var checked = $(this).is(":checked");
                                $(set).each(function () {
                                    $(this).attr("checked", false);
                                });
                                $('.table-group-action-input').val('');
                                $('.group-checkable').attr("checked", false);
                                $.uniform.update(set, table);
                                $.uniform.update($('.group-checkable', table));                                
                            }
                        });
                    }
                }); 
                
                
            } else if (action.val() == "") {
                toastr.error('Please select an action.');

            } else if (grid.getSelectedRowsCount() === 0) {
                toastr.error('No record selected.');

            }
        });

        $(".form-filter").val('');
        // handle group actionsubmit button click
        $(document).on('click', '#export_to_excel', function (e) {
            var a = grid.getDataTable().ajax.params();
                // var a = $("#datatable_ajax").DataTable().ajax.params(),
            a = $.param(a),
            e = $(this).attr("action-url");
            
            window.location.href = e + "?" + a

            
        });

        $(document).on('click', '#download_doc', function (e) {
            var a = grid.getDataTable().ajax.params();
                // var a = $("#datatable_ajax").DataTable().ajax.params(),
            a = $.param(a),
            e = $(this).attr("action-url");
            window.location.href = e + "?" + a

            
        });
    }
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    return {

        //main function to initiate the module
        init: function (tableId, url, order_array, rec_per_page) {
            initPickers();
           
            if (order_array == undefined) {
                order_array = '';
            }
            if (rec_per_page == undefined) {
                rec_per_page = 10;
            }
            if(tableId == undefined || tableId == '') {
                tableId = 'datatable_ajax';
            }
            
            handleRecords(tableId, url, order_array, rec_per_page);
        }
    };

}();