@extends('backend.layouts.default')
@section('content')

<div class="kt-content  kt-grid__item kt-grid__item--fluid kt-grid kt-grid--hor" id="kt_content">

  <!-- begin:: Subheader -->
  <div class="kt-subheader   kt-grid__item" id="kt_subheader">

  </div>

  <!-- end:: Subheader -->

  <!-- begin:: Content -->
  <div class="kt-container  kt-container--fluid  kt-grid__item kt-grid__item--fluid line_run">

    <!--begin::Portlet-->
    <div class="row">
      <div class="col-lg-12">

        <!--begin::Portlet-->
        <div class="kt-portlet custom_portlet">
          <div class="kt-portlet__head custom_head">
            <div class="kt-portlet__head-label">
                <span class="kt-portlet__head-icon">
                    <i class="kt-font-brand fas fa-exchange-alt"></i>
                </span>
                <h3 class="kt-portlet__head-title">
                {{$title}}
                </h3>
            </div>
            @if(isset($permission) && isset($permission[13]['i_add_edit']) && $permission[13]['i_add_edit'] == 1)
            <!-- <div class="kt-portlet__head-toolbar">
                <div class="kt-portlet__head-wrapper">
                    <div class="kt-portlet__head-actions">

                        <button type="button" class="btn btn-brand btn-elevate btn-icon-sm" data-toggle="modal" data-target="#kt_modal_4">  <i class="la la-plus"></i>Line Run</button>
                    </div>
                </div>
            </div> -->
            @endif
          </div>
          <!--begin::Form-->
        @if(isset($permission) && $permission[13]['i_add_edit'] == 1)
            <div class="row line_run_template">
                <div class="col-lg-6 col-md-12 clo-sm-12">
                    <h4 class="kt-mt-10">Peak Season</h4>
                    @foreach($arr_template['PK'] as $key_pk => $val_pk)
                        <form class="frm_line_run"  action="{{ ADMIN_URL }}line-run-templates" onsubmit="return false;">
                            <div class="kt-portlet__body custom_body">
                                <div class="row">

                                    <label class="col-form-label"><h4 class="kt-mt-25">@if(!empty($val_pk['0']) && $val_pk['0']['c_run_key']){!! $val_pk['0']['c_run_key'] !!}@endif</h4></label>

                                    <div class="col-lg-3 col-md-12 col-sm-12">
                                        <label>Arrival At SeaTac</label>
                                        <div class="input-group timepicker">

                                            <input class="form-control kt_timepicker_1_validate" data-lrid="{{ $val_pk['0']['id'] }}" name="pk_arrival_{!! $key_pk !!}" placeholder="Select time" type="text" value="@if(!empty($val_pk['0']) && $val_pk['0']['t_target_time']){!! $val_pk['0']['t_target_time'] !!}@endif">
                                            <div class="input-group-append">
                                                <span class="input-group-text clock_custom"><i class="la la-clock-o"></i></span>
                                            </div>

                                        </div>
                                    </div>

                                    <div class="">
                                        <label>&nbsp;</label>
                                        <div class="input-group timepicker">

                                        <label class="custom_arrows"><i class="fa fa-arrows-alt-h"></i></label>

                                        </div>
                                    </div>

                                    <div class="col-lg-3 col-md-12 col-sm-12">
                                        <label>Departure From SeaTac</label>
                                        <div class="input-group timepicker">

                                            <input class="form-control kt_timepicker_1_validate" data-lrid="{{ $val_pk['1']['id'] }}"  name="pk_departure_{!! $key_pk !!}" placeholder="Select time" type="text" value="@if(!empty($val_pk['1']) && $val_pk['1']['t_target_time']){!! $val_pk['1']['t_target_time'] !!}@endif">
                                            <div class="input-group-append">
                                                <span class="input-group-text clock_custom"><i class="la la-clock-o"></i></span>
                                            </div>

                                        </div>
                                    </div>
                                    <div class="col-lg-3 col-md-12 col-sm-12">
                                        <label>Status</label>
                                        <select class="form-control" name="e_status" placeholder="Status">
                                            <option value="Active" {{ $val_pk['1']['e_status'] == 'Active' ? 'selected' : '' }}>Active</option>
                                            <option value="Inactive" {{ $val_pk['1']['e_status'] == 'Inactive' ? 'selected' : '' }}>Inactive</option>
                                        </select> 
                                    </div>
                                    @if(isset($permission) && isset($permission[13]['i_add_edit']) && $permission[13]['i_add_edit'] == 1)
                                    <div class="col-lg-1 col-md-12 kt-mt-25">
                                        <button type="submit" class="btn btn-primary submitFormPk">Save</button>
                                    </div>
                                    @endif
                                </div>
                            </div>
                        </form>
                    @endforeach
                </div>
                <div class="col-lg-6 col-md-12 clo-sm-12">
                    <h4 class="kt-mt-10">Off Season</h4>
                    @foreach($arr_template['OP'] as $key_op => $val_op)
                        <form class="frm_line_run"  action="{{ ADMIN_URL }}line-run-templates" onsubmit="return false;">
                            <div class="kt-portlet__body custom_body">

                                <div class="row">

                                    <label class="col-form-label"><h4 class="kt-mt-25">@if(!empty($val_op['0']) && $val_op['0']['c_run_key']){!! $val_op['0']['c_run_key'] !!}@endif</h4></label>

                                    <div class="col-lg-3 col-md-12 col-sm-12">
                                        <label>Arrival At SeaTac</label>
                                        <div class="input-group timepicker">

                                            <input class="form-control kt_timepicker_1_validate" data-lrid="{{ $val_op['0']['id'] }}" name="op_arrival_{!! $key_op !!}" placeholder="Select time" type="text" value="@if(!empty($val_op['0']) && $val_op['0']['t_target_time']){!! $val_op['0']['t_target_time'] !!}@endif">
                                            <div class="input-group-append">
                                                <span class="input-group-text clock_custom"><i class="la la-clock-o"></i></span>
                                            </div>

                                        </div>
                                    </div>
                                    <div class="">
                                        <label>&nbsp;</label>

                                        <div class="input-group timepicker">

                                        <label class="custom_arrows"><i class="fa fa-arrows-alt-h"></i></label>

                                        </div>
                                    </div>
                                    <div class="col-lg-3 col-md-12 col-sm-12">
                                    <label>Departure From SeaTac</label>
                                        <div class="input-group timepicker">

                                            <input class="form-control kt_timepicker_1_validate"  data-lrid="{{ $val_op['1']['id'] }}" name="op_departure_{!! $key_op !!}"  placeholder="Select time" type="text"  value="@if(!empty($val_op['1']) && $val_op['1']['t_target_time']){!! $val_op['1']['t_target_time'] !!}@endif">
                                            <div class="input-group-append">
                                                <span class="input-group-text clock_custom"><i class="la la-clock-o"></i></span>
                                            </div>

                                        </div>

                                    </div>
                                    <div class="col-lg-3 col-md-12 col-sm-12">
                                        <label>Status</label>
                                        <select class="form-control" name="e_status" placeholder="Status">
                                            <option value="">Select</option>
                                            <option value="Active" {{ $val_op['1']['e_status'] == 'Active' ? 'selected' : '' }}>Active</option>
                                            <option value="Inactive" {{ $val_op['1']['e_status'] == 'Inactive' ? 'selected' : '' }}>Inactive</option>
                                        </select> 
                                    </div>
                                    @if(isset($permission) && isset($permission[13]['i_add_edit']) && $permission[13]['i_add_edit'] == 1)
                                        <div class="col-lg-1 col-md-12  kt-mt-25">
                                            <button type="submit" class="btn btn-primary submitFormOp"  name="frmFunc" value="savetemplate">Save</button>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </form>
                    @endforeach

                </div>
            </div>
        @endif
          <!--end::Form-->
        </div>

        <!--end::Portlet-->
      </div>

    </div>
  </div>

  <!-- end:: Content -->
  <div class="modal fade" id="kt_modal_4" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Add Line Run</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    </button>
                </div>
                <form action="" enctype="multipart/form-data" class="form-horizontal" id="line_run_template" method="POST">
                    <div class="modal-body">

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="recipient-name" class="form-control-label">Origin service area :</label>
                                    <select class="form-control required" name="i_origin_service_area_id" placeholder="Origin service area">
                                        <option value=""> -- Select origin service area -- </option>
                                        @foreach($geo_service_area as $key => $val)
                                            <option value="{{$val['id']}}">{{$val['v_area_label']}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="recipient-name" class="form-control-label">Arrival time:</label>
                                    <div class="input-group timepicker">
                                        <input class="form-control kt_timepicker_1_validate" name="t_target_time_arrival" placeholder="Select time" type="text" />
                                        <div class="input-group-append">
                                            <span class="input-group-text clock_custom"><i class="la la-clock-o"></i></span>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="recipient-name" class="form-control-label">Run Key :</label>
                                    <select class="form-control required" name="c_run_key" placeholder="Run Key">
                                        <option value=""> -- Select run key-- </option>
                                        <option value="A">A</option>
                                        <option value="B">B</option>
                                        <option value="C">C</option>
                                        <option value="D">D</option>
                                        <option value="E">E</option>
                                        <option value="F">F</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="recipient-name" class="form-control-label">Direction:</label>
                                    <select class="form-control required" name="e_direction" placeholder="Direction">
                                    <option value=""> -- Select direction -- </option>
                                        <option value="W">West</option>
                                        <option value="E">East</option>
                                    </select>
                                </div>

                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="recipient-name" class="form-control-label">Destination service area:</label>
                                    <select class="form-control required" name="i_dest_service_area_id" placeholder="Destination service area">
                                        <option value=""> -- Select origin service area -- </option>
                                        @foreach($geo_service_area as $key => $val)
                                            <option value="{{$val['id']}}">{{$val['v_area_label']}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="recipient-name" class="form-control-label">Departure time:</label>
                                    <div class="input-group timepicker">
                                        <input class="form-control kt_timepicker_1_validate"  name="t_target_time_departure"  placeholder="Departure time" type="text" />
                                        <div class="input-group-append">
                                            <span class="input-group-text clock_custom"><i class="la la-clock-o"></i></span>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="recipient-name" class="form-control-label">Rate season:</label>
                                    <div>
                                        <select class="form-control required" name="e_rate_season" placeholder="Rate season">
                                            <option value=""> -- Select rate season-- </option>
                                            <option value="PK">Peak Season</option>
                                            <option value="OP">Off Season</option>
                                        </select>
                                    </div>

                                </div>
                                <div class="form-group">
                                    <label for="recipient-name" class="form-control-label">Status:</label>
                                    <select class="form-control required" name="e_status" placeholder="Status">
                                        <option value="Active">Active</option>
                                        <option value="Inactive">Inactive</option>
                                    </select>
                                </div>

                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Save</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@stop

@section('custom_js')
<script>
    $(document).ready(function() {
		@if(Session::has('success-message'))
			toastr.success('{{Session::get('success-message')}}');
        @endif
    });

    $(document).on('submit', '.frm_line_run', function(event) {
        event.preventDefault();

        var action =  $(this).attr('action');
        var send_data =  [];

        $(this).find('input').each(function(){
            send_data.push({'id':$(this).attr('data-lrid'),'time':$(this).val()});
        });
        $(this).find('select').each(function(){
            send_data.push({'e_stats':$(this).val()});
        });
        $.post(action,{'data':send_data}, function(data) {
            if ($.trim(data.data) == 'TRUE') {

                toastr.success('Line Run Information has been saved successfully.');
                $("html, body").animate({
                    scrollTop: 0
                }, 600);
            }
        });
    });

    $('#line_run_template').on("submit", function(e) {

    if(!form_valid('#line_run_template')) {

        return false
   }else{

       e.preventDefault();
       var form = new FormData($('#line_run_template')[0]);
           $.ajax({
           type: 'POST',
           url: "{{ ADMIN_URL.'line-run-templates/add'}}",
           data: form,
           cache: false,
           contentType: false,
           processData: false,
           success: function(data) {
               var resultData = JSON.parse(data);
               if(resultData.status == 'success') {
                
               } else {
                   
               }
           }
       });

   }
});
</script>
@stop
