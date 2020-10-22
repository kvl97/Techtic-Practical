@extends('backend.layouts.default')
@section('content')

<div class="kt-content  kt-grid__item kt-grid__item--fluid kt-grid kt-grid--hor" id="kt_content">

  <!-- begin:: Subheader -->
  <div class="kt-subheader   kt-grid__item" id="kt_subheader">
    
  </div>

  <!-- end:: Subheader -->

  <!-- begin:: Content -->
  <div class="kt-container  kt-container--fluid  kt-grid__item kt-grid__item--fluid">

    <!--begin::Portlet-->
    <div class="row">
      <div class="col-lg-12">

        <!--begin::Portlet-->
        <div class="kt-portlet">
          <div class="kt-portlet__head">
            <div class="kt-portlet__head-label">
              <h3 class="kt-portlet__head-title">
                {{ $title }}
              </h3>
            </div>
          </div>

          <!--begin::Form-->
          <form class="kt-form kt-form--label-right" id="frmAdd" action="{{ ADMIN_URL }}admin-roles/add">
            <div class="kt-portlet__body">
            
              <div class="form-group row">
                <label class="col-form-label col-md-2 col-lg-2 col-sm-12">Name <span class="required">*</span></label>
                <div class="col-lg-4 col-md-6 col-sm-12">
                  <input type="text" class="form-control required" name="v_name" placeholder="Name">                  
                </div>
              </div>

              <div class="form-group row">
                <label class="col-form-label col-md-2 col-lg-2 col-sm-12">Description</label>
                <div class="col-lg-4 col-md-6 col-sm-12">
                  <input type="text" class="form-control" name="v_desc" placeholder="Description">                  
                </div>
              </div>

              <div class="form-group row user-permission-module">
                    <label class="col-form-label col-md-2 col-lg-2 col-sm-12"></label>
                    <table class="table table-striped- table-hover table-checkable dataTable no-footer dtr-inline">
                        <thead>
                            <tr>
                                <th> Module Name </th>
                                <th> Read </th>
                                <th> Add / Edit </th>
                                <th> Delete </th>
                            </tr>
                        </thead>
                        <tbody class="module_table_body">
                            @foreach($module_list as $key => $value)
                            <tr>
                              <td> <label class="">{{$value['v_name']}}</label>
                                    <input type="hidden" value="{{ $value['id'] }}" name="i_module_id[]" /> 
                              </td>
                              
                                    <td>
                                      <label class="kt-checkbox kt-checkbox--single kt-checkbox--solid ">
                                        <input type="checkbox" name="i_list[]" value="{{ $value['id'] }}" class="kt-checkable">
                                        <span></span>
                                      </label>
                                    </td>
                                
                                    <td>
                                      <label class="kt-checkbox kt-checkbox--single kt-checkbox--solid ">
                                        <input type="checkbox" name="i_add_edit[]" value="{{ $value['id'] }}" class="kt-checkable chkbox">
                                        <span></span>
                                      </label>
                                    </td>
                               
                                    @if($value['v_name'] != 'Kiosk Setting') 
                                        <td>
                                          <label class="kt-checkbox kt-checkbox--single kt-checkbox--solid ">
                                            <input type="checkbox" name="i_delete[]" value="{{ $value['id'] }}" class="kt-checkable chkbox">
                                            <span></span>
                                          </label>
                                        </td>
                                        </tr>
                                    @else
                                        <td></td>
                                    @endif
                                
                            @endforeach
                        </tbody>
                    </table>
                </div>
                

            </div>
            <div class="kt-portlet__foot">
              <div class="kt-form__actions">
                <div class="row">
                  <div class="col-lg-9 ml-lg-auto">
                    <button type="submit" class="btn btn-brand">Submit</button>
                    <a href="{{ ADMIN_URL }}admin-roles" class="btn btn-secondary"> Cancel </a>
                    
                  </div>
                </div>
              </div>
            </div>
          </form>

          <!--end::Form-->
        </div>

        <!--end::Portlet-->
      </div>
      
    </div>
  </div>

  <!-- end:: Content -->
</div>

@stop

@section('custom_js')
  <script>
    $(document).ready(function() {
       
    });    
  </script>
@stop