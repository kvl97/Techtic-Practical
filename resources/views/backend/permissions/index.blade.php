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
                <span class="kt-portlet__head-icon">
                    <i class="kt-font-brand fa fa-users-cog"></i>
                </span>
              <h3 class="kt-portlet__head-title">
                {{ $title }}
              </h3>
            </div>
          </div>

          <!--begin::Form-->
          <form class="kt-form kt-form--label-right" id="frmAdd" action="{{ ADMIN_URL }}permission/add">
            <div class="kt-portlet__body">
                <div class="form-group row">
                    <label class="col-form-label col-md-2 col-lg-2 col-sm-12">Roles <span class="required">*</span></label>
                    <div class="col-lg-4 col-md-12 col-sm-12">
                        <select class="form-control required" name="i_role_id" id="i_role_id" placeholder="Roles">
                            <option value="">Select Roles</option>
                            @foreach ($roles_list as $index => $val)
                                <option value="{{ $val->id }}">{{ $val->v_name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="form-group row user-permission-module" style="display:none">
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
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="kt-portlet__foot">
              <div class="kt-form__actions">
                <div class="row">
                  <div class="col-lg-9 ml-lg-auto">
                    <button type="submit" class="btn btn-brand">Submit</button>
                    <a href="{{ ADMIN_URL }}dashboard" class="btn btn-secondary"> Cancel </a>

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
        @if(Session::has('success-message'))
            toastr.success('{{ Session::get('success-message') }}');
        @endif

        var $chkboxes = $('.chkbox');
        var lastChecked = null;

        $chkboxes.click(function(e) {
            if(!lastChecked) {
                lastChecked = this;
                return;
            }

            if(e.shiftKey) {
                var start = $chkboxes.index(this);
                var end = $chkboxes.index(lastChecked);

                var checked = $chkboxes.slice(Math.min(start,end), Math.max(start,end)+ 1).is(":checked")
                if (lastChecked.checked) {
                    $chkboxes.slice(Math.min(start,end), Math.max(start,end)+ 1).attr('checked','checked');
                } else {
                    $chkboxes.slice(Math.min(start,end), Math.max(start,end)+ 1).removeAttr('checked');
                }
                $.uniform.update($('.chkbox'));

            }

            lastChecked = this;
        });

        $('#i_role_id').change(function() {
            var role_id = $(this).val();
            var url = ADMIN_URL + 'get-permission/' + role_id;
            $.post(url, function (data) {
                var permission = create_html_module(data.module_list, data.permission_list);
                $('.user-permission-module').show();
                $('.module_table_body').html(permission);
            });
        })
        function create_html_module(module_list, permission_list) {
            var permission_html = ''
            $.each( module_list, function( key, value ) {
                permission_html += '<tr><td> ' + value.v_name + '<input type="hidden" value="' + value.id + '" name="i_module_id[]" /> </td>';
                if(permission_list[value.id] && permission_list[value.id].i_list == 1) {
                    permission_html += '<td><label class="kt-checkbox kt-checkbox--single kt-checkbox--solid "><input type="checkbox" name="i_list[]" value="' + value.id + '" class="kt-checkable chkbox" checked="checked"><span></span></label></td>';
                } else {
                    permission_html += '<td><label class="kt-checkbox kt-checkbox--single kt-checkbox--solid "><input type="checkbox" name="i_list[]" value="' + value.id + '" class="kt-checkable"><span></span></label></td>';
                }

                if(permission_list[value.id] && permission_list[value.id].i_add_edit == 1) {
                    permission_html += '<td><label class="kt-checkbox kt-checkbox--single kt-checkbox--solid "><input type="checkbox" name="i_add_edit[]" value="' + value.id + '" class="kt-checkable chkbox" checked="checked"><span></span></label></td>';
                } else {
                    permission_html += '<td><label class="kt-checkbox kt-checkbox--single kt-checkbox--solid "><input type="checkbox" name="i_add_edit[]" value="' + value.id + '" class="kt-checkable chkbox"><span></span></label></td>';
                }

                if(permission_list[value.id] && permission_list[value.id].i_delete == 1 && value.v_name != 'Kiosk Setting') {
                    permission_html += '<td><label class="kt-checkbox kt-checkbox--single kt-checkbox--solid "><input type="checkbox" name="i_delete[]" value="' + value.id + '" class="kt-checkable chkbox" checked="checked"><span></span></label></td></tr>';
                } else {
                    if(value.v_name != 'Kiosk Setting') {
                        permission_html += '<td><label class="kt-checkbox kt-checkbox--single kt-checkbox--solid "><input type="checkbox" name="i_delete[]" value="' + value.id + '" class="kt-checkable chkbox"><span></span></label></td></tr>';
                    } else {
                        permission_html += '<td></td>';
                    }
                }
            });
            return permission_html;
        }
    });
  </script>
@stop
