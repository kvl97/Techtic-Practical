var TableEditable = function () {

    var handleTable = function (table_id, add_btn_id) {

        function restoreRow(oTable, nRow) {
            var aData = oTable.fnGetData(nRow);
            var jqTds = $('>td', nRow);

            for (var i = 0, iLen = jqTds.length; i < iLen; i++) {
                oTable.fnUpdate(aData[i], nRow, i, false);
            }

            oTable.fnDraw();
        }

        function editRow(oTable, nRow, newRow, edit_id) {
            var addClass = 'blue edit';
            if(newRow == 'NEW') {
                var addClass = 'green new';
            }

            if(edit_id == undefined){
                edit_id = "";
            }
            var aData = oTable.fnGetData(nRow);
            var jqTds = $('>td', nRow);
            jqTds[0].innerHTML = '<input type="text" id="v_title_edit" class="form-control" value="' + aData[0] + '">';
            jqTds[1].innerHTML = '<button class="btn default btn-xs save '+addClass+ '" rel="'+edit_id+'"><i class="fa fa-edit" ></i> Save </button> &nbsp; <button class="btn btn-xs default cancel"><i class="fa fa-close"></i> Cancel</button>';

            setTimeout(function() {
                //$("#v_title_edit").focus();
                var tmp = $("#v_title_edit").val();
                $("#v_title_edit").focus().val("").blur().focus().val(tmp);
                //$("#v_title_edit").blur().focus().val($("#v_title_edit").val());
            }, 100);
        }

        function saveRow(oTable, nRow, edit_id) {
           var jqInputs = $('input', nRow);
            oTable.fnUpdate(jqInputs[0].value, nRow, 0, false);
            oTable.fnUpdate('<a class="edit" rel="'+edit_id+'" href="javascript:;" title="Edit"><i class="fa fa-edit"></i></a><a class="delete" href="javascript:;" rel="'+edit_id+'" title="Delete"><i class="fa fa-trash-o"></i></a>', nRow, 1, false);
            oTable.fnDraw();
        }

        function cancelEditRow(oTable, nRow) {
            var jqInputs = $('input', nRow);
            oTable.fnUpdate(jqInputs[0].value, nRow, 0, false);
            oTable.fnUpdate('<a class="edit" href="javascript:;" title="Edit"><i class="fa fa-edit"></i></a><a class="delete" href="javascript:;" title="Delete"><i class="fa fa-trash-o"></i></a>', nRow, 1, false);
            oTable.fnDraw();
        }

        var table = $(table_id);

        var oTable = table.dataTable({

            // Uncomment below line("dom" parameter) to fix the dropdown overflow issue in the datatable cells. The default datatable layout
            // setup uses scrollable div(table-scrollable) with overflow:auto to enable vertical scroll(see: assets/global/plugins/datatables/plugins/bootstrap/dataTables.bootstrap.js). 
            // So when dropdowns used the scrollable div should be removed. 
            //"dom": "<'row'<'col-md-6 col-sm-12'l><'col-md-6 col-sm-12'f>r>t<'row'<'col-md-5 col-sm-12'i><'col-md-7 col-sm-12'p>>",
            "autoWidth": false,
            /*"columns": [
                {"width": "50%"},
                {"width": "50%"}
            ],*/
            "lengthMenu": [
                [5, 15, 20, -1],
                [5, 15, 20, "All"] // change per page values here
            ],
            // set the initial value
            "pageLength": 10,
            "bPaginate": false,
            "bFilter": false,

            "language": {
                "lengthMenu": " _MENU_ records"
            },
            "columnDefs": [{ // set default column settings
                'orderable': false,
                'targets': [1]
            }, {
                "searchable": true,
                "targets": [0]
            }],
            "order": [
                [0, "asc"]
            ] // set first column as a default sort by asc
        });

        var tableWrapper = $(table_id+"_wrapper");

        /* tableWrapper.find(".dataTables_length select").select2({
            showSearchInput: false 
        }); */ // initialize select2 dropdown

        var nEditing = null;
        var nNew = false;
        
        $(add_btn_id).click(function (e) {
            
            e.preventDefault();
           
            var aiNew = oTable.fnAddData(['', '']);
            var nRow = oTable.fnGetNodes(aiNew[0]);
            if (nNew && nEditing) {
                /*if (confirm("Previose row not saved. Do you want to save it ?")) {
                    saveRow(oTable, nEditing); // save
                    $(nEditing).find("td:first").html("Untitled");
                    nEditing = null;
                    nNew = false;

                } else {
                    oTable.fnDeleteRow(nEditing); // cancel
                    nEditing = null;
                    nNew = false;
                    
                    return;
                }*/
                oTable.fnDeleteRow(nEditing); // cancel
                nEditing = null;
                nNew = false;
            } else if (nNew && nEditing != nRow && !$(this).hasClass("save")) {
                restoreRow(oTable, nEditing);
            } else if( !nNew && nEditing){
                restoreRow(oTable, nEditing);
            }

            editRow(oTable, nRow, 'NEW');
            nEditing = nRow;
            nNew = true;
        });

        table.on('click', '.delete', function (e) {
            e.preventDefault();

            var el = $(this);
            var nRow = $(this).parents('tr')[0];
            var delete_id = $(this).attr('rel');

            bootbox.confirm('Are you sure you want to delete this record?', function (confirmed) {
                if(confirmed) {
                    if(el.closest("table").attr("id") == "table_designation") {
                        url = SITE_URL+"setup/delete-designation/"+delete_id;
                    } else if(el.closest("table").attr("id") == "table_phase") {
                        url = SITE_URL+"setup/delete-phase/"+delete_id;
                    } else if(el.closest("table").attr("id") == "table_expense_type") {
                        url = SITE_URL+"setup/delete-expense-type/"+delete_id;
                    } else if(el.closest("table").attr("id") == "seo_task_type_datatable_ajax") {
                        url = SITE_URL+"setup/delete-seo-task-type/"+delete_id;
                    } else {
                        url = SITE_URL+"setup/delete-technology/"+delete_id;
                    }

                    $.get(url, function (data) {
                        if(data.status == "TRUE") {
                            el.parents(".main_block").find('.alert-success').show();
                            el.parents(".main_block").find('.alert-success .message').html('Record has been deleted successfully.');
                            setTimeout(function(){ $('.alert-success').fadeOut(4000); },3000);

                            el.closest('tr').fadeOut(1500, function() {
                                // $(this).closest('tr').remove();
                                oTable.fnDeleteRow(nRow);
                                oTable.fnDraw();
                                if($("table:visible tbody > tr").length == 0) {
                                    loadSetupData();
                                }
                            });
                            
                        }
                        else {
                            if(data.error_message !== undefined) {
                                bootbox.alert(data.error_message);
                            }
                        }
                    });
                }
            });

            
        });

        table.on('click', '.cancel', function (e) {
            e.preventDefault();
            /* console.log(nNew);
            console.log(nEditing);
            console.log($(this)); */
            var nEditingRow = $(this).parents('tr')[0];
           /*  console.log(nEditingRow); */
            // && nEditing == nEditingRow
            if (nNew) {
                oTable.fnDeleteRow(nEditing);
                nEditing = null;
                nNew = false;
            } else {
                restoreRow(oTable, nEditing);
                nEditing = null;
            }
        });

        table.on('click', '.edit', function (e) {
            e.preventDefault();

            /* Get the row as a parent of the link that was clicked on */
            var nRow = $(this).parents('tr')[0];
            var edit_id = $(this).attr("rel");

            if(nNew && nEditing !== null && nEditing != nRow) {
                oTable.fnDeleteRow(nEditing);
                editRow(oTable, nRow, '', edit_id);
                nEditing = nRow;
                nNew = false;
            }else if (nEditing !== null && nEditing != nRow) {
                /* Currently editing - but not this row - restore the old before continuing to edit mode */
                restoreRow(oTable, nEditing);
                editRow(oTable, nRow, '', edit_id);
                nEditing = nRow;
            } else if (nEditing == nRow && $(this).hasClass("save")) {
                /* Editing this row and want to save it */
                /* saveRow(oTable, nEditing, edit_id);
                nEditing = null;
                alert("Updated! Do not forget to do some ajax to sync with backend :)"); */
               /*  oTable.fnDeleteRow(nEditing);
                editRow(oTable, nRow, '', edit_id);
                nEditing = nRow; */
            } else {
                /* No edit in progress - let's start one */
                editRow(oTable, nRow, '', edit_id);
                nEditing = nRow;
            }
        });

        table.on('keyup', '#v_title_edit', function (e) {
            if(e.keyCode == 13) {
                $(this).parents('tr').find(".save").trigger("click");
            }
        });

        table.on('click', '.save', function (e) {
            e.preventDefault();

            /* Get the row as a parent of the link that was clicked on */
            var el = $(this);
            var nRow = $(this).parents('tr')[0];
            var edit_id = $(this).attr("rel");

            /* Editing this row and want to save it */

            if(el.closest("table").attr("id") == "table_designation") {
                if(el.hasClass("new")) {
                    url = SITE_URL+"setup/add-designation";
                } else {
                    url = SITE_URL+"setup/edit-designation";
                }
            } else if(el.closest("table").attr("id") == "table_phase") {
                if(el.hasClass("new")) {
                    url = SITE_URL+"setup/add-phase";
                } else {
                    url = SITE_URL+"setup/edit-phase";
                }
            } else if(el.closest("table").attr("id") == "table_expense_type") {
                if(el.hasClass("new")) {
                    url = SITE_URL+"setup/add-expense-type";
                } else {
                    url = SITE_URL+"setup/edit-expense-type";
                }
            } else if(el.closest("table").attr("id") == "seo_task_type_datatable_ajax") {
                if(el.hasClass("new")) {
                    url = SITE_URL+"setup/add-seo-task-type";
                } else {
                    url = SITE_URL+"setup/edit-seo-task-type";
                }
            } else {

                if(el.hasClass("new")) {
                    url = SITE_URL+"setup/add-technology";
                } else {
                    url = SITE_URL+"setup/edit-technology";
                }
            }

            var title_edit = $.trim($("#v_title_edit").val());
            $("#v_title_edit").css("border", "1px solid #e5e5e5");
            $("#v_title_edit").closest("td").removeClass("has-error");
            $("#v_title_edit").closest("td").find(".help-block").remove();
            if(title_edit == "") {
                $("#v_title_edit").css("border-color", "#ebccd1");
                $("#v_title_edit").closest("td").addClass("has-error").append("<div class='help-block help-block-error'>Please enter Title.</div>");
                return false;
            } /* else if(!title_edit.match(/^(?=.*?[A-Za-z])/)) {
                $("#v_title_edit").css("border-color", "#ebccd1");
                $("#v_title_edit").closest("td").addClass("has-error").append("<div class='help-block help-block-error'>Please enter valid Title.</div>");
                return false;
            } */

            el.parents(".main_block").find('.alert-success').hide();
            el.parents(".main_block").find('.alert-danger').hide();

            $.post(url, {"id": edit_id, "v_title": title_edit}, function(data) {
                if(data.status == 'TRUE') {
                    el.parents(".main_block").find('.alert-success').show();
                    
                    if(el.hasClass("new")) {
                        el.parents(".main_block").find('.alert-success .message').html('Record has been addeded successfully.');
                    } else {
                        el.parents(".main_block").find('.alert-success .message').html('Record has been updated successfully.');
                    }
                    setTimeout(function(){ $('.alert-success').fadeOut(4000); },3000);

                    edit_id = data.item.id;
                    saveRow(oTable, nEditing, edit_id);
                    nEditing = null;
                    nNew = false;
                } else {
                    el.parents(".main_block").find('.alert-success').hide();
                    el.parents(".main_block").find('.alert-danger').show();
                    setTimeout(function(){ $('.alert-danger').fadeOut(4000); },3000);
                    
                    if(data.error_message !== undefined) {
                        bootbox.alert(data.error_message);
                    }

                    if(data.error != undefined) {
                        $.each(data.error, function(key, value) {
                            $("#v_title_edit").css("border-color", "#ebccd1");
                            $("#v_title_edit").closest("td").addClass("has-error").append("<div class='help-block help-block-error'>"+value[0]+"</div>");
                        });
                    }

                    if($('.has-error .form-control').length > 0) {
                        $('html, body').animate({
                            scrollTop:$('.has-error .form-control').first().offset().top - 200
                        }, 1000);
                        $('.has-error .form-control').first().focus();
                    }
                }
            });

        });
    }

    var handleCustomerAddressTable = function (table_id, add_btn_id) {
        
        var nEditing = null;
        var nNew = false;

        function restoreRow(oTable, nRow) {
            var aData = oTable.fnGetData(nRow);
            var jqTds = $('>td', nRow);

            for (var i = 0, iLen = jqTds.length; i < iLen; i++) {
                oTable.fnUpdate(aData[i], nRow, i, false);
            }

            oTable.fnDraw();
        }

        function editRow(oTable, nRow, newRow, edit_id) {
            
            if(edit_id == undefined){
                edit_id = "";
            }
            
            var data_id = $(nRow).find('.td-edit-cust-address').attr('data-id');
            var aData = oTable.fnGetData(nRow);
            var jqTds = $('>td', nRow);
            $index = 0;
            $value = 0;
            jqTds[$index++].innerHTML = '<input type="text" data-id="'+data_id+'" id="v_address_label_edit" class="form-control input-edit-custAddress" value="' + aData[$value++] + '">';
            jqTds[$index++].innerHTML = '<input type="text" data-id="'+data_id+'" id="v_street_edit" class="form-control input-edit-custAddress" value="' + aData[$value++] + '">';
            jqTds[$index++].innerHTML = '<input type="text" data-id="'+data_id+'" id="v_city_edit" class="form-control input-edit-custAddress" value="' + aData[$value++] + '">';
            jqTds[$index++].innerHTML = '<input type="text" data-id="'+data_id+'" id="v_state_edit" class="form-control input-edit-custAddress" value="' + aData[$value++] + '">';
            jqTds[$index++].innerHTML = '<input type="text" data-id="'+data_id+'" id="v_country_edit" class="form-control input-edit-custAddress" value="' + aData[$value++] + '">';
            jqTds[$index++].innerHTML = '<input type="text" data-id="'+data_id+'" id="v_postal_code_edit" class="form-control input-edit-custAddress" value="' + aData[$value++] + '">';
            /* jqTds[$index++].innerHTML = '<a class="btn btn-sm btn-clean btn-icon btn-icon-lg" rel="" href="javascript:;" title="Save"><i class="la la-check-square save" ></i>&nbsp;<i class="cancel la la-times-circle" ></i></a>';  */
            jqTds[$index++].innerHTML = '<a class="btn btn-sm btn-clean btn-icon btn-icon-lg" rel="" href="javascript:" title="Save"><i class="la la-check-square save" ></i>&nbsp;<i class="cancel la la-times-circle" ></i></a>';

            setTimeout(function() {
                
                var tmp1 = $("#v_address_label_edit").val();
                $("#v_address_label_edit").focus().val("").blur().focus().val(tmp1);
                var tmp2 = $("#v_street_edit").val();
                $("#v_street_edit").focus().val("").blur().focus().val(tmp2);
                var tmp3 = $("#v_city_edit").val();
                $("#v_city_edit").focus().val("").blur().focus().val(tmp3);
                var tmp4 = $("#v_stat_edit").val();
                $("#v_stat_edit").focus().val("").blur().focus().val(tmp4);
                var tmp5 = $("#v_country_edit").val();
                $("#v_country_edit").focus().val("").blur().focus().val(tmp5);
                var tmp6 = $("#v_postal_code_edit").val();
                $("#v_postal_code_edit").focus().val("").blur().focus().val(tmp6);
            }, 100);
        }

        function saveRow(oTable, nEditing, edit_id) {

            // console.log("test",edit_id); return false;
            var jqInputs = $('input', nEditing);
            $index = 0;
            oTable.fnUpdate(jqInputs[$index].value, nEditing, $index++, false);
            oTable.fnUpdate(jqInputs[$index].value, nEditing, $index++, false);
            oTable.fnUpdate(jqInputs[$index].value, nEditing, $index++, false);
            oTable.fnUpdate(jqInputs[$index].value, nEditing, $index++, false);
            oTable.fnUpdate(jqInputs[$index].value, nEditing, $index++, false);
            oTable.fnUpdate(jqInputs[$index].value, nEditing, $index++, false);
            /* oTable.fnUpdate('<a class="edit" rel="'+edit_id+'" href="javascript:;" title="Edit"><i class="fa fa-edit"></i></a><a class="delete" href="javascript:;" rel="'+edit_id+'" title="Delete"><i class="fa fa-trash-o"></i></a>', nRow, 1, false); */
            oTable.fnUpdate('<a class="btn btn-sm btn-clean btn-icon btn-icon-lg edit" rel="" href="javascript:" title="edit"><i class="la la-edit"></i> </a><a class="btn btn-sm btn-clean btn-icon btn-icon-lg delete_record" href="javascript:;" rel="'+edit_id+'" title="Delete"><i class="la la-trash"></i></a>', nEditing, $index++, false);
            oTable.fnDraw();
        }

        function sendDataCustomerAddress(nEditing) {
            var send_data = {};
            send_data['id'] = $(nEditing).find('.input-edit-custAddress').attr('data-id');
            send_data['v_address_label'] = $(nEditing).find('#v_address_label_edit').val(); 
            send_data['v_street'] = $(nEditing).find('#v_street_edit').val();
            send_data['v_city'] = $(nEditing).find('#v_city_edit').val();
            send_data['v_state'] = $(nEditing).find('#v_state_edit').val();
            send_data['v_country'] = $(nEditing).find('#v_country_edit').val();
            send_data['v_postal_code'] = $(nEditing).find('#v_postal_code_edit').val();
            
            return send_data;
        }

        var table = $(table_id);

        var oTable = table.dataTable({

            // Uncomment below line("dom" parameter) to fix the dropdown overflow issue in the datatable cells. The default datatable layout
            // setup uses scrollable div(table-scrollable) with overflow:auto to enable vertical scroll(see: assets/global/plugins/datatables/plugins/bootstrap/dataTables.bootstrap.js). 
            // So when dropdowns used the scrollable div should be removed. 
            //"dom": "<'row'<'col-md-6 col-sm-12'l><'col-md-6 col-sm-12'f>r>t<'row'<'col-md-5 col-sm-12'i><'col-md-7 col-sm-12'p>>",
            "autoWidth": false,
            /*"columns": [
                {"width": "50%"},
                {"width": "50%"}
            ],*/
            "lengthMenu": [
                [5, 15, 20, -1],
                [5, 15, 20, "All"] // change per page values here
            ],
            // set the initial value
            "pageLength": 10,
            "bPaginate": false,
            "bFilter": false,

            "language": {
                "lengthMenu": " _MENU_ records"
            },
            "columnDefs": [{ // set default column settings
                'orderable': false,
                'targets': [0,1,2,3,4,5,6]
            }, {
                "searchable": false,
                "targets": [0]
            }],
            /* "order": [
                [0, "asc"]
            ]  */// set first column as a default sort by asc
        });

        // var tableWrapper = $(table_id+"_wrapper");

        table.on('click', '.cancel', function (e) {
            e.preventDefault();
            
            var nEditingRow = $(this).parents('tr')[0];
           
            if (nNew) {
                oTable.fnDeleteRow(nEditing);
                nEditing = null;
                nNew = false;
            } else {
                restoreRow(oTable, nEditing);
                nEditing = null;
            }
        });

        table.on('click', '.edit', function (e) {
            e.preventDefault();

            /* Get the row as a parent of the link that was clicked on */
            var nRow = $(this).parents('tr')[0];
            var edit_id = $(this).attr("rel");

            if(nNew && nEditing !== null && nEditing != nRow) {
                oTable.fnDeleteRow(nEditing);
                editRow(oTable, nRow, '', edit_id);
                nEditing = nRow;
                nNew = false;
            }else if (nEditing !== null && nEditing != nRow) {
                /* Currently editing - but not this row - restore the old before continuing to edit mode */
                restoreRow(oTable, nEditing);
                editRow(oTable, nRow, '', edit_id);
                nEditing = nRow;
            } else if (nEditing == nRow && $(this).hasClass("save")) {
                
            } else {
                /* No edit in progress - let's start one */
                editRow(oTable, nRow, '', edit_id);
                nEditing = nRow;
            }
        });

        table.on('keyup', '.td-edit-cust-address', function (e) {
            if(e.keyCode == 13) {
                $(this).parents('tr').find(".save").trigger("click");
            }
        });

        table.on('click', '.save', function (e) {
            e.preventDefault();
            var sendData = sendDataCustomerAddress(nEditing);
            
            /*// Get the row as a parent of the link that was clicked on 
            var nRow = $(this).parents('tr')[0];
            var url = ADMIN_URL+ 'customers-address/save';
            var data = [];
            
            $(nRow).find('.input-edit-custAddress').each(function(){
                
                var custAddress_id = $(this).attr('data-id');
                var custAddress = ($(this).val() != '') ? $(this).val() : 0.00;
                data.push({'id':custAddress_id,'data':custAddress});
            });
            
            $.post(url,{'data':JSON.stringify(data)}, function(data) {
                // console.log(nRow); return false;
                saveRow(oTable, nRow);
                toastr.success('Records saved successfully');
            });*/

            $.post(ADMIN_URL+ 'customers-address/save', sendData, function (data) {
                
                if($.trim(data) == 'TRUE'){
                    
                    saveRow(oTable, nEditing);
                    // toastr.success('Records saved successfully');
                    swal.fire({
                        title: 'Records saved successfully.',
                        type: 'success',
                        timer: 10000,
                    });
                } /* else {
                   
                    var errors = "";
                    if(data.error !== undefined){
                        errors = "<ul>";
                        $.each(data.error, function(key, value){
                            errors += "<li>"+value+"</li>";
                        });
                        errors += "</ul>";
                    }
                    swal.fire({
                        title: 'Error while updating Address.',
                        type: 'error',
                        timer: 10000,
                    });
                } */
            });

        });
    }

    var handleFareTable = function (table_id) {

        function restoreRow(oTable, nRow) {
            var aData = oTable.fnGetData(nRow);
            var jqTds = $('>td', nRow);

            for (var i = 0, iLen = jqTds.length; i < iLen; i++) {
                oTable.fnUpdate(aData[i], nRow, i, false);
            }

            oTable.fnDraw();
        }

        function editRow(oTable, nRow, newRow, edit_id) {
            console.log(edit_id);
            if(edit_id == undefined){
                edit_id = "";
            }
            var aData = oTable.fnGetData(nRow);
            
            var jqTds = $('>td', nRow);
            
            jqTds[0].innerHTML = aData[0];
            var index = 1;
            $(nRow).find('.td-edit-fare').each(function(){
                var rate_id = $(this).attr('data-rateid');
                var rate_amt = $(this).text();
                jqTds[index].innerHTML = '<input type="text" data-rateid="'+rate_id+'" class="form-control input-edit-fare" value="' + rate_amt + '">';
                index++;
            });
            jqTds[index].innerHTML = '<a class="btn btn-sm btn-clean btn-icon btn-icon-lg" rel="" href="javascript:" title="Save"><i class="la la-check-square save" ></i>&nbsp;<i class="cancel la la-times-circle" ></i></a>';
        }

        function saveRow(oTable, nRow) {
           var jqInputs = $('input', nRow);
           var i=0,index = 1;
           $(nRow).find('.input-edit-fare').each(function(){
                oTable.fnUpdate((jqInputs[i].value!='') ? jqInputs[i].value : '0.00', nRow, index, false);
                index++;i++;
            });
            oTable.fnUpdate('<a class="btn btn-sm btn-clean btn-icon btn-icon-lg edit" rel="" href="javascript:" title="edit"><i class="la la-edit"></i> </a>', nRow, index, false);
            oTable.fnDraw();
        }

        var table = $(table_id);

        var oTable = table.dataTable({

            // Uncomment below line("dom" parameter) to fix the dropdown overflow issue in the datatable cells. The default datatable layout
            // setup uses scrollable div(table-scrollable) with overflow:auto to enable vertical scroll(see: assets/global/plugins/datatables/plugins/bootstrap/dataTables.bootstrap.js). 
            // So when dropdowns used the scrollable div should be removed. 
            //"dom": "<'row'<'col-md-6 col-sm-12'l><'col-md-6 col-sm-12'f>r>t<'row'<'col-md-5 col-sm-12'i><'col-md-7 col-sm-12'p>>",
            "autoWidth": false,
            /*"columns": [
                {"width": "50%"},
                {"width": "50%"}
            ],*/
            "lengthMenu": [
                [5, 15, 20, -1],
                [5, 15, 20, "All"] // change per page values here
            ],
            // set the initial value
            "pageLength": 10,
            "bPaginate": false,
            "bFilter": false,

            "language": {
                "lengthMenu": " _MENU_ records"
            },
            "columnDefs": [{ // set default column settings
                'orderable': false,
                'targets': [0,1,2,3,4,5,6,7,8,9,10,11,12,13]
            }, {
                "searchable": true,
                "targets": [0]
            }],
            "order": [
                [0, "asc"]
            ] // set first column as a default sort by asc
        });

        var nEditing = null;
        var nNew = false;

        table.on('click', '.cancel', function (e) {
            e.preventDefault();
            var nEditingRow = $(this).parents('tr')[0];
            if (nNew) {
                oTable.fnDeleteRow(nEditing);
                nEditing = null;
                nNew = false;
            } else {
                restoreRow(oTable, nEditing);
                nEditing = null;
            }
        });

        table.on('click', '.edit', function (e) {
            e.preventDefault();
            
            /* Get the row as a parent of the link that was clicked on */
            var nRow = $(this).parents('tr')[0];
            var edit_id = $(this).attr("rel");

            if(nNew && nEditing !== null && nEditing != nRow) {
                oTable.fnDeleteRow(nEditing);
                editRow(oTable, nRow, '', edit_id);
                nEditing = nRow;
                nNew = false;
            }else if (nEditing !== null && nEditing != nRow) {
                restoreRow(oTable, nEditing);
                editRow(oTable, nRow, '', edit_id);
                nEditing = nRow;
            } else if (nEditing == nRow && $(this).hasClass("save")) {
                
            } else {
                /* No edit in progress - let's start one */
                editRow(oTable, nRow, '', edit_id);
                nEditing = nRow;
            }
        });

        table.on('keyup', '.td-edit-fare', function (e) {
            if(e.keyCode == 13) {
                $(this).parents('tr').find(".save").trigger("click");
            }
        });

        table.on('click', '.save', function (e) {
            e.preventDefault();
            
            /* Get the row as a parent of the link that was clicked on */
            var nRow = $(this).parents('tr')[0];
            var url = ADMIN_URL+ 'fare-table/save';
            var data = [];
            var err_flag = true;    

            $(nRow).find('.input-edit-fare').each(function(){
                if(!$(this).val().match(/^-?(?:\d+|\d{1,3}(?:,\d{3})+)(?:\.\d+)?$/)){
                    err_flag = false;
                }
                var rate_id = $(this).attr('data-rateid');
                var rate_amt = ($(this).val() != '') ? $(this).val() : 0.00;
                data.push({'id':rate_id,'amt':rate_amt});
            });
            
            if(err_flag==false) {
                swal.fire("Please enter valid rate.");
                return;
            }

            $.post(url,{'data':JSON.stringify(data)}, function(data) {
                saveRow(oTable, nRow);
                toastr.success('Records saved successfully');
            });

        });
    }
    var handleRateTable = function(table_id){

        function restoreRow(oTable, nRow) {
            var aData = oTable.fnGetData(nRow);
            var jqTds = $('>td', nRow);

            for (var i = 0, iLen = jqTds.length; i < iLen; i++) {
                oTable.fnUpdate(aData[i], nRow, i, false);
            }

            oTable.fnDraw();
        }

        function editRow(oTable, nRow, newRow, edit_id) {
            
            if(edit_id == undefined){
                edit_id = "";
            }
            
            var data_id_foctor = $(nRow).find('.td-edit-rate').attr('data-factor-id');
            var aData = oTable.fnGetData(nRow);
            var jqTds = $('>td', nRow);
            $index = 0;
            $value = 0;
            
            jqTds[$index++].innerHTML ='<input type="text" data-id="'+data_id_foctor+'" id="e_class_type" class="form-control input-edit-custAddress" value="' + aData[$value++] + '" readonly style="border: none;padding: 0;Font : 13px Roboto, sens-serif;">';
            
            jqTds[$index++].innerHTML ='<input type="text" data-id="'+data_id_foctor+'" id="v_class_label" class="form-control input-edit-custAddress" value="' + aData[$value++] + '" readonly style="border: none;padding: 0;Font : 13px Roboto, sens-serif;">';
            jqTds[$index++].innerHTML ='<input type="text" data-id="'+data_id_foctor+'" id="v_rate_code" class="form-control input-edit-custAddress" value="' + aData[$value++] + '" readonly style="border: none;padding: 0;Font : 13px Roboto, sens-serif;">';
            jqTds[$index++].innerHTML = '<input type="text" data-id="'+data_id_foctor+'" id="d_base_rate_factor" class="form-control input-edit-custAddress" value="' + aData[$value++] + '">';
            jqTds[$index++].innerHTML = '<input type="text" data-id="'+data_id_foctor+'" id="v_tooltip_text" class="form-control input-edit-custAddress" value="' + aData[$value++] + '">';
            jqTds[$index++].innerHTML = '<a class="btn btn-sm btn-clean btn-icon btn-icon-lg" rel="" href="javascript:" title="Save"><i class="la la-check-square save" ></i></a><a class="btn btn-sm btn-clean btn-icon btn-icon-lg"><i class="cancel la la-times-circle" ></i>';

            setTimeout(function() {
                var tmp1 = $("#e_class_type").val();
                $("#e_class_type").focus().val("").blur().focus().val(tmp1);
                var tmp2 = $("#v_class_label").val();
                $("#v_class_label").focus().val("").blur().focus().val(tmp2);
                var tmp3 = $("#v_rate_code").val();
                $("#v_rate_code").focus().val("").blur().focus().val(tmp3);
                var tmp4 = $("#v_tooltip_text").val();
                $("#v_tooltip_text").focus().val("").blur().focus().val(tmp4);
                var tmp5 = $("#d_base_rate_factor").val();
                $("#d_base_rate_factor").focus().val("").blur().focus().val(tmp5);
                
               
            }, 100);
        }

        function saveRow(oTable, nEditing, edit_id) {

            // console.log("test",edit_id); return false;
            var jqInputs = $('input', nEditing);
            $index = 0;
            oTable.fnUpdate(jqInputs[$index].value, nEditing, $index++, false);
            oTable.fnUpdate(jqInputs[$index].value, nEditing, $index++, false);
            oTable.fnUpdate(jqInputs[$index].value, nEditing, $index++, false);
            oTable.fnUpdate(jqInputs[$index].value, nEditing, $index++, false);
            oTable.fnUpdate(jqInputs[$index].value, nEditing, $index++, false);
            /* oTable.fnUpdate('<a class="edit" rel="'+edit_id+'" href="javascript:;" title="Edit"><i class="fa fa-edit"></i></a><a class="delete" href="javascript:;" rel="'+edit_id+'" title="Delete"><i class="fa fa-trash-o"></i></a>', nRow, 1, false); */
            oTable.fnUpdate('<a class="btn btn-sm btn-clean btn-icon btn-icon-lg edit" rel="" href="javascript:" title="edit"><i class="la la-edit"></i> </a>', nEditing, $index++, false);
            oTable.fnDraw();
        }
        function sendDataRateTable(nEditing) {
            console.log(nEditing)
            var send_data = {};
            send_data['id'] = $(nEditing).find('.td-edit-rate').attr('data-factor-id');
            console.log(send_data['id'])
            send_data['d_base_rate_factor'] = $(nEditing).find('#d_base_rate_factor').val(); 
            send_data['v_tooltip_text'] = $(nEditing).find('#v_tooltip_text').val();
            return send_data;
        }
        var table = $(table_id);

        var oTable = table.dataTable({

            // Uncomment below line("dom" parameter) to fix the dropdown overflow issue in the datatable cells. The default datatable layout
            // setup uses scrollable div(table-scrollable) with overflow:auto to enable vertical scroll(see: assets/global/plugins/datatables/plugins/bootstrap/dataTables.bootstrap.js). 
            // So when dropdowns used the scrollable div should be removed. 
            //"dom": "<'row'<'col-md-6 col-sm-12'l><'col-md-6 col-sm-12'f>r>t<'row'<'col-md-5 col-sm-12'i><'col-md-7 col-sm-12'p>>",
            "autoWidth": false,
            /*"columns": [
                {"width": "50%"},
                {"width": "50%"}
            ],*/
            "lengthMenu": [
                [5, 15, 20, -1],
                [5, 15, 20, "All"] // change per page values here
            ],
            // set the initial value
            "pageLength": 10,
            "bPaginate": false,
            "bFilter": false,

            "language": {
                "lengthMenu": " _MENU_ records"
            },
            "columnDefs": [{ // set default column settings
                'orderable': false,
                'targets': [0,1,2,3,4,5]
            }, {
                "searchable": false,
                "targets": [0]
            }],
            /* "order": [
                [0, "asc"]
            ]  */// set first column as a default sort by asc
        });

        var nEditing = null;
        var nNew = false;

        table.on('click', '.cancel', function (e) {
            e.preventDefault();
            var nEditingRow = $(this).parents('tr')[0];
            if (nNew) {
                oTable.fnDeleteRow(nEditing);
                nEditing = null;
                nNew = false;
            } else {
                restoreRow(oTable, nEditing);
                nEditing = null;
            }
        });

        table.on('click', '.edit', function (e) {
           
            e.preventDefault();
            
            /* Get the row as a parent of the link that was clicked on */
            var nRow = $(this).parents('tr')[0];
            var edit_id = $(this).attr("rel");

            if(nNew && nEditing !== null && nEditing != nRow) {
                oTable.fnDeleteRow(nEditing);
                editRow(oTable, nRow, '', edit_id);
                nEditing = nRow;
                nNew = false;
            }else if (nEditing !== null && nEditing != nRow) {
                restoreRow(oTable, nEditing);
                editRow(oTable, nRow, '', edit_id);
                nEditing = nRow;
            } else if (nEditing == nRow && $(this).hasClass("save")) {
                
            } else {
                /* No edit in progress - let's start one */
                editRow(oTable, nRow, '', edit_id);
                nEditing = nRow;
            }
        });

        table.on('keyup', '.td-edit-fare', function (e) {
            if(e.keyCode == 13) {
                $(this).parents('tr').find(".save").trigger("click");
            }
        });

        table.on('click', '.save', function (e) {
            e.preventDefault();
            var sendData = sendDataRateTable(nEditing);
            
            $.post(ADMIN_URL+ 'rate-factor/save', sendData, function (data) {
                
                if($.trim(data) == 'TRUE'){
                    
                    saveRow(oTable, nEditing);
                    // toastr.success('Records saved successfully');
                    swal.fire({
                        title: 'Records saved successfully.',
                        type: 'success',
                        timer: 10000,
                    });
                } 
            });

        });
    }

    return {

        //main function to initiate the module
        init: function (table_id, add_btn_id) {
            if(table_id == '#fare_table') {
                handleFareTable(table_id);
            } else if(table_id == '#customer_address_table') {
                handleCustomerAddressTable(table_id);
            } else if(table_id == '#rate_table') {
                handleRateTable(table_id);
            }  else {
                handleTable(table_id, add_btn_id);
            } 
        }

    };

}();