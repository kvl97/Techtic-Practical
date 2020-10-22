var durl='';
var TableAjax = function (url) {

    var handleRecords = function (url, order_array,pagination,info,page_menu) {
        durl = url;

        var grid = new Datatable();
        var table = $('#datatable_ajax'); // actual table jquery object
        grid.init({
            src: $("#datatable_ajax"),
            onSuccess: function (grid) {
                  //Store filter data on cookie
                if (url.indexOf("list-ajax") >= 0){
                    if ($.cookie(url) === null || $.cookie(url) === "") {} else {
                        $.removeCookie(url);
                    }

                    var filterData = grid.getAjaxParams();

                    var cookieData = {};
                    // var order_sort = grid.getOrder();

                    $.each(filterData, function(index, value) {
                        if (index == 'page' || index == 'length') {
                            cookieData[index] = value;
                        }
                        if ($('[name=' + index + ']').length > 0) {
                            $('[name=' + index + ']').val(value);
                            if ($('[name=' + index + ']').data('select2') != undefined) {
                                $('[name=' + index + ']').select2('val', value);
                            }
                            cookieData[index] = value;
                        }
                    });

                   /*  if(order_sort.length > 0){
                        cookieData['order_sort'] = {};
                        cookieData['order_sort']['column'] = order_sort[0][0];
                        cookieData['order_sort']['sort'] = order_sort[0][1];
                    } */
                    if (url.indexOf("list-ajax") >= 0){
                        $.cookie(url, JSON.stringify(cookieData));
                    }
                }
                setTimeout(function(){
                    $('.table-group-text-submit').val($('.text_search_all').val());
                    if($('a.status-1').length > 0) {
                        $('.danger-publish-remain').show();
                    } else {
                        $('.danger-publish-remain').hide();
                    }
                    if($('.editable').length > 0) {
                        /* $('.editable').editable().on('hidden', function(e, reason){
                            var locale = $(this).data('locale');
                            if(reason === 'save'){
                                $(this).removeClass('status-0').addClass('status-1');
                            }
                            if(reason === 'save' || reason === 'nochange') {
                                var $next = $(this).closest('tr').next().find('.editable.locale-'+locale);
                                setTimeout(function() {
                                    $next.editable('show');
                                }, 300);
                            }
                        }); */
                        $('.editable').editable().on('shown', function(e, reason){
                            //alert($('.editable-input').find('textarea').val().length);
                            if(!$(this).parents('tr').hasClass('parent-row')) {
                                $(this).parents('tr').addClass('parent-row');
                            }
                            $('#datatable_ajax > tbody  > tr').each(function() {
                                var curr = $(this);
                                if(!curr.hasClass('parent-row')) {
                                    curr.find('.open_popover').trigger('click');
                                }
                            });
                            $(this).parents('tr').removeClass('parent-row');
                            $(this).addClass('open_popover');
                            var locale = $(this).data('locale');
                            var engpk = $(this).data('engpk');
                            if(locale != 'en') {
                                $(this).parents('tr').find('.lang_link').each(function(i, obj) {
                                    if(!($(this).hasClass('locale-' + locale)) && $(this).hasClass('open_popover')) {
                                        $(this).trigger('click');
                                    }
                                });

                                if(!($('.'+ engpk).hasClass('trigger_click')) && !($('.'+ engpk).hasClass('open_popover'))) {
                                    $('.'+ engpk).trigger('click');
                                    $('.'+ engpk).addClass('trigger_click');
                                }

                            }
                            if(locale != 'en') {
                                if(!$(this).hasClass('trigger_click')) {
                                    $(this).addClass('trigger_click');
                                }
                            }
                            if($('.editable-input').find('textarea').val().length > 500){
                                $('.editable-input').find('textarea').css({width: '300px !important', height: '400px !important'});
                                $('.editable-input').css({width: 'auto !important', 'min-width': '500px !important'});
                                $('.editable-input').find('textarea').attr('rows',20);
                                $('.editable-input').find('textarea').attr('cols',50);
                            } else {
                                $('.editable-input').find('textarea').attr('rows',10);
                            }


                        });
                        $('.editable').editable().on('hidden', function(e, reason){
                            $(this).removeClass('open_popover');
                            $(this).parents('tr').removeClass('parent-row');
                            var locale = $(this).data('locale');
                            var engpk = $(this).data('engpk');
                            if(locale != 'en') {
                                if(($('.'+ engpk).hasClass('trigger_click')) && $(this).parents('tr').find('.open_popover').length <= 1 ) {
                                    $('.'+ engpk).trigger('click');
                                    $('.'+ engpk).removeClass('trigger_click');
                                }
                            }
                            if(reason === 'save' || reason === 'nochange') {
                                if(reason === 'save') {
                                    console.log("Fsdfwdwe");
                                    if(!$('.danger-publish-remain').is(':visible')) {
                                        console.log(reason);
                                        $('.danger-publish-remain').show();
                                    }

                                }
                            }
                        });

                    }
                },1000);
                setTimeout(function(){
                   $("a.delete-key").click(function(event){
                    event.preventDefault();
                    var row = $(this).closest('tr');
                    var url = $(this).attr('data-href');
                    var id = row.attr('id');
                    bootbox.confirm('Are you sure you want to delete this record?', function (confirmed) {
                        if(confirmed){
                            $.post( url, {id: id}, function(){
                                row.remove();
                                $('#datatable_ajax').DataTable().ajax.reload();
                            });
                        }
                    });
                    });
                },1000);
                // execute some code after table records loaded
            },
            onError: function (grid) {
                // execute some code on network or other general error
            },
            loadingMessage: 'Loading...',
            dataTable: { // here you can define a typical datatable settings from http://datatables.net/usage/options

                // Uncomment below line("dom" parameter) to fix the dropdown overflow issue in the datatable cells. The default datatable layout
                // setup uses scrollable div(table-scrollable) with overflow:auto to enable vertical scroll(see: scripts/datatable.js).
                // So when dropdowns used the scrollable div should be removed.
                //"dom": "<'row'<'col-md-8 col-sm-12'pli><'col-md-4 col-sm-12'<'table-group-actions pull-right'>>r>t<'row'<'col-md-8 col-sm-12'pli><'col-md-4 col-sm-12'>>",
               // "bStateSave": true, // save datatable state(pagination, sort, etc) in cookie.

                "lengthMenu": page_menu,
                "pageLength": pageLength, // default record count per page
                "ajax": {
                    "url": url, // ajax source
                },
                "bPaginate" : pagination,
                "bInfo" : info,
                "columnDefs":[{
                    "targets": 'no-sort',
                    "orderable": false,
                }],
                'displayStart': 0,
                "fnRowCallback": function( nRow, aData, iDisplayIndex, iDisplayIndexFull ) {
                  // Bold the grade for all 'A' grade browsers
                    setTimeout(function(){
                        $('span.total_cost').each(function() {
                            var total_cost = $(this).attr('data-rel-total');
                            switch (total_cost) {
                                case 'total_cost':
                                    $(this).closest('tr').find("td:gt(0)").remove();
                                    $(this).closest('tr').find("td:first").attr('colspan','12');
                                    break;
                            }
                        });
                        $(nRow).find('.make-switch').bootstrapSwitch();
                        $('span.data-account-expire-val').each(function() {
                            var val = $(this).attr('data-account-expire-val');
                            switch (val) {
                                case '1':
                                    $(this).closest('tr').addClass('hidden-light-red');
                                    break;
                            }
                        });
                        $('span.expire-end-date').each(function() {
                            $(this).closest('td').addClass('hidden-light-red');
                        });
                },1);
                },
                "order":  order_array ,// set first column as a default sort by asc
                "bStateSave" : true

            }
        });

        console.log("grid",grid); return false;
        grid.getTableWrapper().on('click', '.table-group-action-submit', function (e) {
            alert("data-table.js");
            var alert_message = '';
            e.preventDefault();
            var action = $(".table-group-action-input", grid.getTableWrapper());
            var url = $(".table-group-action-url", grid.getTableWrapper()).val();
            if (action.val() != "" && grid.getSelectedRowsCount() > 0) {
                 var send_data = {};
                 send_data.action = action.val();
                 send_data['ids'] = grid.getSelectedRows();
                 if(send_data.action=='Delete'){
                    bootbox.confirm('Are you sure you want to delete this record?', function (confirmed) {
                    if(confirmed){
                            $.post(url,send_data, function(data) {
                                if(data == 'TRUE') {
                                    send_data_action = send_data.action.toLowerCase();
                                        if(send_data_action == 'active') {
                                            send_data_action = 'actived';
                                        } else if(send_data_action == 'inactive') {
                                            send_data_action = 'inactived';
                                        } else if(send_data_action == 'delete') {
                                            send_data_action = 'deleted';
                                        }
                                    Metronic.alert({
                                        type: 'success',
                                        icon: 'check',
                                        message: 'Record has been '+send_data_action +' successfully.',
                                        container: grid.getTableWrapper(),
                                        place: 'prepend'
                                    });
                                    $.each(send_data['ids'], function(i,id) {
                                        if(send_data.action == 'Active'){
                                            $('tbody > tr > td > .status_' + id, table).addClass('label-success');
                                            $('tbody > tr > td > .status_' + id, table).removeClass('label-danger');
                                            $('tbody > tr > td > .status_' + id, table).text(send_data.action);
                                           // $('#datatable_ajax').DataTable().ajax.reload();
                                        } else if(send_data.action == 'Inactive'){
                                            $('tbody > tr > td > .status_' + id, table).addClass('label-danger');
                                            $('tbody > tr > td > .status_' + id, table).removeClass('label-success');
                                            $('tbody > tr > td > .status_' + id, table).text(send_data.action);
                                            //$('#datatable_ajax').DataTable().ajax.reload();
                                        } else {
                                           $('tbody > tr > td  .cheched').closest('tr').fadeOut(1500, function() {
                                                $(this).closest('tr').remove();
                                                //$('#datatable_ajax').DataTable().ajax.reload();
                                                if($("#datatable_ajax tbody > tr").length <= 1) {
                                                    $(".filter-submit").trigger( "click" );
                                                }
                                           });
                                        }
                                    });
                                    $('#datatable_ajax').DataTable().ajax.reload();
                                    setTimeout(function(){ $('.alert-success').fadeOut(4000); },3000);
                                    var set = $('tbody > tr > td:nth-child(1) input[type="checkbox"]', table);
                                    var checked = $(this).is(":checked");
                                    $(set).each(function() {
                                        $(this).attr("checked", false);
                                    });
                                    $('.table-group-action-input').val('');
                                    $('.kt-group-checkable').attr("checked", false);
                                    $.uniform.update(set, table);
                                    $.uniform.update($('.kt-group-checkable', table));
                                }
                            });
                        }
                    });
                }else{
                    $.post(url,send_data, function(data) {
                                if(data == 'TRUE') {
                                    send_data_action = send_data.action.toLowerCase();
                                        if(send_data_action == 'active') {
                                            send_data_action = 'actived';
                                        } else if(send_data_action == 'inactive') {
                                            send_data_action = 'inactived';
                                        } else if(send_data_action == 'delete') {
                                            send_data_action = 'deleted';
                                        }
                                    Metronic.alert({
                                        type: 'success',
                                        icon: 'check',
                                        message: 'Record has been '+send_data_action +' successfully.',
                                        container: grid.getTableWrapper(),
                                        place: 'prepend'
                                    });
                                    $.each(send_data['ids'], function(i,id) {
                                        if(send_data.action == 'Active'){
                                            $('tbody > tr > td > .status_' + id, table).addClass('label-success');
                                            $('tbody > tr > td > .status_' + id, table).removeClass('label-danger');
                                            $('tbody > tr > td > .status_' + id, table).text(send_data.action);
                                           // $('#datatable_ajax').DataTable().ajax.reload();
                                        } else if(send_data.action == 'Inactive'){
                                            $('tbody > tr > td > .status_' + id, table).addClass('label-danger');
                                            $('tbody > tr > td > .status_' + id, table).removeClass('label-success');
                                            $('tbody > tr > td > .status_' + id, table).text(send_data.action);
                                            //$('#datatable_ajax').DataTable().ajax.reload();
                                        } else {
                                           $('tbody > tr > td  .cheched').closest('tr').fadeOut(1500, function() {
                                                $(this).closest('tr').remove();
                                                //$('#datatable_ajax').DataTable().ajax.reload();
                                                if($("#datatable_ajax tbody > tr").length <= 1) {
                                                    $(".filter-submit").trigger( "click" );
                                                }
                                           });
                                        }
                                    });
                                    $('#datatable_ajax').DataTable().ajax.reload();
                                    setTimeout(function(){ $('.alert-success').fadeOut(4000); },3000);
                                    var set = $('tbody > tr > td:nth-child(1) input[type="checkbox"]', table);
                                    var checked = $(this).is(":checked");
                                    $(set).each(function() {
                                        $(this).attr("checked", false);
                                    });
                                    $('.table-group-action-input').val('');
                                    $('.kt-group-checkable').attr("checked", false);
                                    $.uniform.update(set, table);
                                    $.uniform.update($('.kt-group-checkable', table));
                                }
                            });
                }

            } else if (action.val() == "") {
                /*Metronic.alert({
                    type: 'danger',
                    icon: 'warning',
                    message: 'Please select an action',
                    container: grid.getTableWrapper(),
                    place: 'prepend'
                });*/
                bootbox.alert('Please select an action.');
            } else if (grid.getSelectedRowsCount() === 0) {
               /* Metronic.alert({
                    type: 'danger',
                    icon: 'warning',
                    message: 'No record selected',
                    container: grid.getTableWrapper(),
                    place: 'prepend'
                });*/
                bootbox.alert('Please select checkbox(s).');
                $('.table-group-action-input').val('');
            }
        });
    }
    return {

        //main function to initiate the module
        init: function (url, order_array,pagination,info,page_menu) {
            initPickers();

            if(order_array == undefined){
                order_array = '';
            }
            if(pagination == undefined){
                pagination = true;
            }
            if(info == undefined){
                info = true;
            }
            handleRecords(url, order_array,pagination,info,page_menu);
        }

    };
}();
