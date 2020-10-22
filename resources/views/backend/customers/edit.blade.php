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
                Edit Customer
              </h3>
            </div>
          </div>

          <!--begin::Form-->
          <div class="kt-portlet__body">
            <ul class="nav nav-tabs" role="tablist">
                <li class="nav-item">
                    <a class="nav-link active" data-toggle="tab" href="#kt_tabs_personal_info" data-target="#kt_tabs_personal_info">Personal Info.</a>
                </li>
                
                <!-- <li class="nav-item">
                    <a class="nav-link" data-toggle="tab" href="#kt_tabs_personal_address"> Address Info.</a>
                </li>    -->
                @if(isset($permission) && ((isset($permission[20]['i_list']) && $permission[20]['i_list'] == 1) || (isset($permission[20]['i_add_edit']) && $permission[20]['i_add_edit'] == 1) || (isset($permission[20]['i_delete']) && $permission[20]['i_delete'] == 1)))
                  <li class="nav-item">
                      <a class="nav-link" data-toggle="tab" href="#kt_tabs_reservation"> Reservation</a>
                  </li>   
                @endif
                
            </ul>

            <div class="tab-content">
                <div class="tab-pane active" id="kt_tabs_personal_info" role="tabpanel">
                  <form class="kt-form kt-form--label-right" id="frmEdit" action="{{ ADMIN_URL }}customers/edit/{{ $record->id }}">
                      <div class="form-group row">
                        <label class="col-form-label col-md-2 col-lg-2 col-sm-12">First Name <span class="required">*</span></label>
                        <div class="col-lg-4 col-md-6 col-sm-12">
                          <input type="text" class="form-control required" name="v_firstname" placeholder="First Name" value="{{ $record->v_firstname }}">
                        </div>
                      </div>

                      <div class="form-group row">
                        <label class="col-form-label col-md-2 col-lg-2 col-sm-12">Last Name <span class="required">*</span></label>
                        <div class="col-lg-4 col-md-6 col-sm-12">
                          <input type="text" class="form-control required" name="v_lastname" placeholder="Last Name" value="{{ $record->v_lastname }}">
                        </div>
                      </div>

                      <!-- <div class="form-group row">
                        <label class="col-form-label col-md-2 col-lg-2 col-sm-12">User Type <span class="required">*</span></label>
                        <div class="col-lg-4 col-md-6 col-sm-12">
                          <select class="form-control required" name="e_user_type" placeholder="User Type">
                            <option value="">Select</option>
                            <option value="Customer" {{ $record->e_user_type == 'Customer' ? 'selected=""' : '' }}>Customer</option>
                          </select> 
                        </div>
                      </div> -->

                      <div class="form-group row">
                        <label class="col-form-label col-md-2 col-lg-2 col-sm-12">Email</label>
                        <div class="col-lg-4 col-md-6 col-sm-12">
                          <input type="text" class="form-control email" name="v_email" placeholder="Email" value="{{ $record->v_email }}"> 
                          <span id="v_email_error" class="exist_label" style="display:none;">Email id already exists.</span>                    
                        </div>
                      </div>

                      <div class="form-group row">
                        <label class="col-form-label col-md-2 col-lg-2 col-sm-12">Password</label>
                        <div class="col-lg-4 col-md-6 col-sm-12">
                          <input type="password" class="form-control validate_password" name="password" id="password" placeholder="Password" >                  
                        </div>
                      </div>

                      <div class="form-group row">
                        <label class="col-form-label col-md-2 col-lg-2 col-sm-12">Confirm Password</label>
                        <div class="col-lg-4 col-md-6 col-sm-12">
                          <input type="password" class="form-control" name="cpassword" id="cpassword" placeholder="Confirm Password" equalTo="password">                  
                        </div>
                      </div>
                      
                      <div class="form-group row">
                        <label class="col-form-label col-md-2 col-lg-2 col-sm-12">Gender <span class="required">*</span></label>
                        <div class="col-lg-4 col-md-6 col-sm-12 radio-list">
                          <div class="required-radio-btn">
                            <label class="gender-radio" for="male">    
                              <input class="required-least-one-radio" type="radio" name="e_gender" id="male" groupid="gender" value="Male" {{ ($record->e_gender == "Male") ? 'checked="checked"' : "" }}> Male
                            </label>
                            
                            <label class="gender-radio" for="female">
                              <input class="required-least-one-radio" type="radio" name="e_gender" id="female" groupid="gender"  value="Female" {{ ($record->e_gender == "Female") ? 'checked="checked"' : "" }}> Female
                            </label> 
                            
                            <label class="gender-radio" for="other">
                              <input class="required-least-one-radio" type="radio" name="e_gender" id="other" groupid="gender" value="Other" {{ ($record->e_gender == "Other") ? 'checked="checked"' : "" }} > Other
                            </label>    
                            <span class="check"></span>   
                          </div>     
                        </div>
                      </div>

                      <div class="form-group row">
                        <label class="col-form-label col-md-2 col-lg-2 col-sm-12">Date of Birth</label>
                        <div class="col-lg-4 col-md-6 col-sm-12">
                          <input type="text" class="form-control date_picker" name="d_dob" placeholder="Date of Birth" value="{{ isset($record->d_dob) ? date('m/d/Y',strtotime($record->d_dob)) : ''}}" onblur="$(this).attr('readonly','readonly');" readonly="readonly">                  
                        </div>
                      </div>
                      
                      <div class="form-group row">
                        <label class="col-form-label col-md-2 col-lg-2 col-sm-12">Cell Number</label>
                        <div class="col-lg-4 col-md-6 col-sm-12">
                          <input type="text" class="form-control phone" name="v_phone" placeholder="Cell Number" value="{{ $record->v_phone }}">                  
                        </div>
                      </div>
                      <div class="form-group row">
                        <label class="col-form-label col-md-2 col-lg-2 col-sm-12">Landline Number</label>
                        <div class="col-lg-4 col-md-6 col-sm-12">
                          <input type="text" class="form-control" name="v_landline_number" placeholder="Landline Number" value="{{ $record->v_landline_number }}">                  
                        </div>
                      </div>

                      <div class="form-group row">
                        <label class="col-form-label col-md-2 col-lg-2 col-sm-12">Status <span class="required">*</span></label>
                        <div class="col-lg-4 col-md-6 col-sm-12 form-group-sub">
                          <select class="form-control required" name="e_status" placeholder="Status">
                            <option value="">Select</option>
                            <option value="Active" {{ $record->e_status == 'Active' ? 'selected=""' : '' }}>Active</option>
                            <option value="Inactive" {{ $record->e_status == 'Inactive' ? 'selected=""' : '' }}>Inactive</option>
                          </select>                  
                        </div>
                      </div>   

                      <div class="kt-portlet__foot">
                        <div class="kt-form__actions">
                          <div class="row">
                            <div class="col-lg-9 ml-lg-auto">
                              <button type="submit" class="btn btn-brand">Submit</button>
                              <a href="{{ ADMIN_URL }}customers" class="btn btn-secondary"> Cancel </a>
                            </div>
                          </div>
                        </div>
                      </div>
                  </form>
                </div>

                <!-- <div class="tab-pane" id="kt_tabs_personal_address" role="tabpanel">
                    <div class="kt-portlet__head kt-portlet__head--lg">
                        <div class="kt-portlet__head-label">
                            
                        </div>
                        
                            <div class="kt-portlet__head-toolbar">
                                <div class="kt-portlet__head-wrapper">
                                    <div class="kt-portlet__head-actions">
                                        <a href="javascript:;" class="btn btn-brand btn-elevate btn-icon-sm" data-toggle="modal" data-target="#kt_modal_address">
                                            <i class="la la-plus"></i>
                                            Add Address
                                        </a>
                                    </div>
                                </div>
                            </div>
                        
                    </div>
                    <table class="table table-bordered" id="customer_address_table">
                      <thead>
                          <tr> 
                              <th>Label</th>
                              <th>Street</th>
                              <th>City</th>
                              <th>State</th>
                              <th>Country</th>
                              <th>Postal Code</th>
                              <th>Actions</th>
                          </tr>
                      </thead>  
                      <tbody>    
                          @foreach ($address_record as $records)    
                            <tr>
                                <td class="td-edit-cust-address" data-id="{{ $records['id'] }}">{{ $records['v_address_label'] }}</td>
                                <td class="td-edit-cust-address" data-id="{{ $records['id'] }}">{{ $records['v_street'] }}</td>
                                <td class="td-edit-cust-address" data-id="{{ $records['id'] }}">{{ $records['v_city'] }}</td>
                                <td class="td-edit-cust-address" data-id="{{ $records['id'] }}">{{ $records['v_state'] }}</td>
                                <td class="td-edit-cust-address" data-id="{{ $records['id'] }}">{{ $records['v_country'] }}</td> 
                                <td class="td-edit-cust-address" data-id="{{ $records['id'] }}">{{ $records['v_postal_code'] }}</td>
                                <td>
                                    <a class="btn btn-sm btn-clean btn-icon btn-icon-lg edit" rel=" {{ $records['id'] }}" href="javascript:;" title="Edit"><i class="la la-edit"></i> </a> 

                                    <a title="Delete" id="delete_record_address" class="btn btn-sm btn-clean btn-icon btn-icon-lg delete_record_address" href="javascript:;" rel=" {{ $records['id'] }}" delete-url="<?php echo ADMIN_URL.'customers-address/delete/'.$records['id'];?>"><i class="la la-trash"></i></a>
                                </td>
                            </tr>
                          @endforeach
                      </tbody>
                    </table> 
                </div> -->

                <div class="tab-pane" id="kt_tabs_reservation" role="tabpanel">
                  <div class="kt-portlet kt-portlet--mobile">
                    <div class="kt-portlet__head kt-portlet__head--lg">
                        <div class="kt-portlet__head-label">
                            <span class="kt-portlet__head-icon">
                                <i class="kt-font-brand fa fa-list-alt"></i>
                            </span>
                            <h3 class="kt-portlet__head-title">
                                Reservation
                            </h3>
                            </div>
                            @if(isset($permission) && isset($permission[20]['i_add_edit']) && $permission[20]['i_add_edit'] == 1)
                            <div class="kt-portlet__head-toolbar">
                              <div class="kt-portlet__head-wrapper">
                                  <div class="kt-portlet__head-actions">
                                      <a href="{{ ADMIN_URL }}reservations/add/customer/{{ $record->id }}" class="btn btn-brand btn-elevate btn-icon-sm">
                                          <i class="la la-plus"></i>
                                          Add New Reservation
                                      </a>
                                  </div>
                              </div>
                            </div>
                            @endif
                    </div>
                    <div class="kt-portlet__body">
                      @if(isset($permission) && isset($permission[20]['i_delete']) && $permission[20]['i_delete'] == 1)
                      <div class="table-bulk-action kt-hide">
                        <label class="kt-mr-10" style="display: inline;">Bulk Action</label>
                        <select class="form-control form-control-sm form-filter kt-input table-group-action-input" title="Select Action" name="bulk_action" style="width: 116px;display: inline;">
                          <option value="">Select Action</option>
                          <option value="Delete">Delete</option>
                        </select>
                        <a href="javascript:;" type="button" class="btn btn-brand kt-btn btn-sm kt-btn--icon table-group-action-submit submit-btn" id="bulk_action_submit"><i class="fa fa-check"></i> Submit</a>
                        <input type="hidden"  class="table-group-action-url" value="<?php echo ADMIN_URL.'reservations/bulk-action';?>"/>
                      </div>
                      @endif
                      <!--begin: Datatable -->

                      <table class="table table-striped- table-bordered table-hover table-checkable" id="datatable_ajax">
                          <thead>
                              <tr>
                                  <!-- @if(isset($permission) && isset($permission[20]['i_delete']) && $permission[20]['i_delete'] == 1) -->
                                      <th class="no-sort first-col-fix-width remove_disable_sorting_class_listing" style="width: 30px; cursor: none !important;">
                                      <label class="kt-checkbox kt-checkbox--single kt-checkbox--solid">
                                          <input type="checkbox" value="" class="kt-group-checkable">
                                          <span></span>
                                      </label>
                                      </th>
                                  <!-- @endif -->
                                  <th style="width:100px">Reservation Number</th>
                                  <th>Category</th>
                                  <th>Origin Point</th>
                                  <th>Destination Point</th>
                                  <th>Class Type</th>
                                  <th>Travel Date</th>
                                  <!-- <th>Return Date</th> -->
                                  <th>Total Passengers</th>
                                  <th>Status</th>
                                  <th class="no-sort last">Actions</th>
                              </tr>
                          </thead>
                          <thead>
                              <tr class="filter">
                                  @if(isset($permission) && isset($permission[20]['i_delete']) && $permission[20]['i_delete'] == 1)
                                      <td></td>
                                  @endif
                                  <td><input type="text" class="form-control form-control-sm form-filter kt-input" name="v_reservation_number" placeholder="Reservation Number"></td>
                                  <td>
                                    <select class="form-control form-control-sm form-filter" name="i_reservation_category_id" placeholder="Category">
                                            <option value="">Select</option>
                                            @if(count($reservation_category) > 0)
                                                @foreach($reservation_category as $val)
                                                    <option value="{{ $val['id'] }}">{{ $val['v_label'] }}</option>
                                                @endforeach
                                            @endif  
                                        </select>  
                                  </td>  
                                  <td>
                                    <input type="text" class="form-control form-control-sm form-filter kt-input" name="i_origin_point_id" placeholder="Origin Point">
                                    
                                  </td>
                                  <td>
                                    <input type="text" class="form-control form-control-sm form-filter kt-input" name="i_destination_point_id" placeholder="Destination Point">
                                    
                                  </td>
                                  <td>
                                    <select class="form-control orm-control-sm form-filter " name="e_class_type" placeholder="Class Type">
                                        <option value="">Select</option> 
                                        <option value="OW">One Way</option>
                                        <option value="RT">Round Trip</option>       
                                    </select>
                                  </td>

                                  <td>
                                    <input type="text" class="form-control form-control-sm form-filter kt-input date_picker_reservation" name="departureStartDate" placeholder="Start Date" onblur="$(this).attr('readonly','readonly');" readonly="readonly" />
                                    <br>
                                    <input type="text" class="form-control form-control-sm form-filter kt-input date_picker_reservation" name="departureEndDate" placeholder="End Date" onblur="$(this).attr('readonly','readonly');" readonly="readonly">
                                  </td>

                                  <!-- <td>
                                    <input type="text" class="form-control form-control-sm form-filter kt-input date_picker_reservation" name="returnStartDate" placeholder="Start Date" onblur="$(this).attr('readonly','readonly');" readonly="readonly">
                                    <br>
                                    <input type="text" class="form-control form-control-sm form-filter kt-input date_picker_reservation" name="returnEndDate" placeholder="End Date" onblur="$(this).attr('readonly','readonly');" readonly="readonly">
                                  </td> -->

                                  <td><input type="text" class="form-control form-control-sm form-filter kt-input" name="i_total_num_passengers" placeholder="Total Passengers"></td>
                                  <td>
                                    <select class="form-control form-control-sm form-filter" name="e_reservation_status" placeholder="Status">
                                          <option value="">Select</option> 
                                          <option value="Quote">Quote</option>
                                          <option value="New">New</option>
                                          <option value="Provisional">Provisional</option>
                                          <option value="Processing">Processing</option>
                                          <option value="Confirmed">Confirmed</option>
                                          <option value="Pending">Pending</option>
                                          <option value="Finalized">Finalized</option>
                                          <option value="Dispatched">Dispatched</option>
                                          <option value="Active">Active</option>
                                          <option value="Completed">Completed</option>
                                          <option value="Cancelled">Cancelled</option>
                                          <option value="Refunded">Refunded</option>
                                          <option value="Voucher">Voucher</option>
                                      </select>   
                                  </td>
                                  <td>
                                      <button class="btn btn-brand kt-btn btn-sm kt-btn--icon mb-2 filter-submit search-btn"><span><i class="la la-search"></i><span>Search</span></span></button> &nbsp;
                                      <button class="btn btn-secondary kt-btn btn-sm kt-btn--icon mt-0 mb-2 filter-cancel reset-btn search-btn"><span><i class="la la-close"></i><span>Reset</span></span></button>
                                  </td>
                              </tr>
                          </thead>

                          <input type="hidden" id="cust_id" value="{{ $record->id }}" />
                      </table>

                      <!--end: Datatable -->
                    </div>
                  </div>
                </div>
            </div>
         

          <!--end::Form-->
        </div>

        <!--end::Portlet-->
      </div>
      
    </div>
  </div>

  <!-- end:: Content -->

    <div class="modal fade" id="kt_modal_address" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Add Address</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    </button>
                </div>
                <div class="row add-address-content">
                  <div class="modal-body">
                    <form action="" enctype="multipart/form-data" class="form-horizontal" id="add_cust_address" method="POST">
                        
                        <div class="row add_address_popup">
                        <input type="hidden" name="current_customer_id" id="current_customer_id" value="{{$record['id']}}"/>
                            <div class="col-md-6 col-lg-6 col-sm-12">
                                <div class="form-group row">
                                    <label class="col-form-label col-md-4 col-lg-4 col-sm-12">Address Label: <span class="required">*</span></label>
                                    <div class="col-md-7 col-lg-7 col-sm-12">
                                      <input type="text" class="form-control required" name="v_address_label" placeholder="Address Label">                  
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label class="col-form-label col-md-4 col-lg-4 col-sm-12">City: <span class="required">*</span></label>
                                    <div class="col-md-7 col-lg-7 col-sm-12">
                                      <input type="text" class="form-control required" name="v_city" placeholder="City">                  
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label class="col-form-label col-md-4 col-lg-4 col-sm-12">State: <span class="required">*</span></label>
                                    <div class="col-md-7 col-lg-7 col-sm-12">
                                      <input type="text" class="form-control required" name="v_state" placeholder="State">                  
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 col-lg-6 col-sm-12">
                                
                                <div class="form-group row">
                                    <label class="col-form-label col-md-4 col-lg-4 col-sm-12">Street: <span class="required">*</span></label>
                                    <div class="col-md-7 col-lg-7 col-sm-12">
                                      <input type="text" class="form-control required" name="v_street" placeholder="Street">                  
                                    </div>
                                </div>
                                
                                <div class="form-group row">
                                    <label class="col-form-label col-md-4 col-lg-4 col-sm-12">Country: <span class="required">*</span></label>
                                    <div class="col-md-7 col-lg-7 col-sm-12">
                                      <input type="text" class="form-control required" name="v_country" placeholder="Country">                  
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label class="col-form-label col-md-4 col-lg-4 col-sm-12">Postal Code: <span class="required">*</span></label>
                                    <div class="col-md-7 col-lg-7 col-sm-12">
                                      <input type="text" class="form-control required" name="v_postal_code" placeholder="Postal Code">                  
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary add_address_save_popup">Save</button>
                        </div>
                    </form>
                  </div>
                </div>
            </div>
        </div>
    </div>

</div>

@stop

@section('custom_js')
  <script>
    $(document).ready(function() {

      $('.date_picker_reservation').datepicker({
          format: 'mm/dd/yyyy',
          autoclose: true,
          //orientation: "bottom auto",
          todayHighlight: true,
      });

      var url = window.location.href;        
      if(url.indexOf("#") <= 0) {
          var activeTab = 'kt_tabs_personal_info';
      } else {
          var activeTab = url.substring(url.indexOf("#") + 1);
      }
      $(".nav-item li").removeClass("active"); 
      $('a[href="#'+ activeTab +'"]').tab('show');

      var date = new Date();
      date.setDate(date.getDate());
      $('.date_picker').datepicker({
          format: 'mm/dd/yyyy',
          autoclose: true,
          endDate: date,
          todayHighlight: true,
          //orientation: "bottom auto"
      }).on('changeDate', function(e) {
          //$('.date_picker').datepicker('destroy');
          $('.date_picker').trigger('blur');
      });

      TableEditable.init('#customer_address_table');

      /* $('#add_cust_address').on("submit", function(e) {

        $('#kt_tabs_personal_info').removeClass('active');
        $('#kt_tabs_personal_address').addClass('active');
        $('#kt_tabs_personal_address').trigger('click');

        var record_id = $('#current_customer_id').val();
        if(!form_valid('#add_cust_address')) {
              return false
        }else {
          e.preventDefault();
          var form = new FormData($('#add_cust_address')[0]);
              $.ajax({
              type: 'POST',
              url: "{{ ADMIN_URL.'customers-address/add'}}",
              data: form,
              cache: false,
              contentType: false,
              processData: false,
              success: function(data) {
                swal.fire({
                  position: 'middle',
                  type: "success",
                  title: 'Your record has been saved',
                  showConfirmButton: false,
                  timer: 5000,
                });
                window.location.assign(ADMIN_URL + 'customers/edit/'+record_id+'#kt_tabs_personal_address');
                window.location.reload();
              }
          });
        }
      });

      $('#customer_address_table').on('click', ".delete_record_address", function (e) {
        var url = $(this).attr('delete-url');
        var arrId = $(this).attr('rel');
        var record_id = $('#current_customer_id').val();

        swal.fire({
          title: 'Are you sure You want to delete this record?',
          text: '',
          type: 'warning',
          showCancelButton: true,
          confirmButtonText: 'Delete',
          cancelButtonText: 'Cancel',
          reverseButtons: true
        }).then(function (result) {
          if (result.value) {
            console.log("url", url, "arrId", arrId)
            $.ajax({
              url: url,
              type: 'get',
              success: function (data) {
                swal.fire(
                  'Deleted!',
                  'Your record has been deleted.',
                  'success',
                  'false'
                )
                window.location.assign(ADMIN_URL + 'customers/edit/'+record_id+'#kt_tabs_personal_address');
                window.location.reload();
              }
            });

          }
        });
      }); */

      $('#frmEdit').on("submit", function(e) {
        $('#kt_tabs_personal_info').addClass('active');
        // $('#kt_tabs_personal_address').removeClass('active');
        $('#kt_tabs_reservation').removeClass('active');
        $('#kt_tabs_personal_info').trigger('click');
      });

    });    

    //3rd tab - Customer Reservation
    var cusromer_id = $('#cust_id').val();
    var url = ADMIN_URL + 'customers-reservation/list-ajax/' + cusromer_id;
    var order =  [0, 'desc'];
    DataTables.init('#datatable_ajax', url, order);

    $("#frmAdd_cust_reservation").submit(function() {
      $('#kt_tabs_personal_info').removeClass('active');
      // $('#kt_tabs_personal_address').removeClass('active');
      $('#kt_tabs_reservation').addClass('active');
      $('#kt_tabs_reservation').trigger('click');
    });
  </script>
@stop