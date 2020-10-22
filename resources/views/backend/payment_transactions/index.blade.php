@extends('backend.layouts.default')
@section('content')

<div class="kt-content  kt-grid__item kt-grid__item--fluid kt-grid kt-grid--hor" id="kt_content">

  <!-- begin:: Subheader -->
  <div class="kt-subheader   kt-grid__item" id="kt_subheader">

  </div>

  <!-- end:: Subheader -->

  <!-- begin:: Content -->
  <div class="kt-container  kt-container--fluid  kt-grid__item kt-grid__item--fluid">

    <!--Begin::Dashboard 1-->

    <!--Begin::Row-->
    <div class="kt-portlet kt-portlet--mobile">
        <div class="kt-portlet__head kt-portlet__head--lg">
            <div class="kt-portlet__head-label">
                <span class="kt-portlet__head-icon">
                    <i class="kt-menu__link-icon  fa fa-list-alt"></i>
                </span>
                <h3 class="kt-portlet__head-title">
                    {{ $title }}
                </h3>
                
              </div>
        </div>
      <div class="kt-portlet__body">
      <div class="actions" style="text-align: right;">
          <div class="table-actions-wrapper">
              <button class="btn btn-brand kt-btn btn-sm kt-btn--icon mb-2 filter-submit" id="filter-search"><span><i class="la la-search"></i><span>Search</span></span></button> &nbsp;
              <button class="btn btn-secondary kt-btn btn-sm kt-btn--icon mt-0 mb-2 filter-cancel" id="filter-reset"><span><i class="la la-close"></i><span>Reset</span></span></button>
          </div>     
      </div>
        <!-- <div class="table-bulk-action kt-hide">
          <label class="kt-mr-20" style="display: inline;">Bulk Action</label>
          <select class="form-control form-control-sm form-filter kt-input table-group-action-input" title="Select Action" name="bulk_action" style="width: 150px;display: inline;">
            <option value="">Select Action</option>
            <option value="Delete">Delete</option>
          </select>
          <a href="javascript:;" type="button" class="btn btn-brand kt-btn btn-sm kt-btn--icon table-group-action-submit" id="bulk_action_submit"><i class="fa fa-check"></i> Submit</a>
          <input type="hidden"  class="table-group-action-url" value="<?php echo ADMIN_URL.'payment-transactions/bulk-action';?>"/>
        </div> -->
        <!--begin: Datatable -->
        <table class="table table-striped- table-bordered table-hover table-checkable" id="datatable_ajax">
            <thead>
                <tr>
                   
                    <th>Customer Name</th>
                    <th>Reservation No.</th>
                    <th>Date</th>
                    <th>Stripe Tx ID</th>
                    <th style="text-align: right;">Amount</th>
                    <th>Type</th>
                    <th class="no-sort">Status</th>
                </tr>
            </thead>
            <thead>
                <tr class="filter">
                   
                    <td><input type="text" class="form-control form-control-sm form-filter kt-input" name="v_customer_name"></td>
                    <td><input type="text" class="form-control form-control-sm form-filter kt-input" name="v_reservation_number"></td>
                    <td>
                      <input type="text" class="form-control form-control-sm form-filter kt-input date_picker" name="d_start_date" placeholder="Start Date" onblur="$(this).attr('readonly','readonly');" readonly="readonly" />
                      <br>
                      <input type="text" class="form-control form-control-sm form-filter kt-input date_picker" name="d_end_date" placeholder="End Date" onblur="$(this).attr('readonly','readonly');" readonly="readonly">
                    </td>
                    <td>
                      <input type="text" class="form-control form-control-sm form-filter kt-input" name="v_stripe_payment_id" placeholder="Stripe Tx ID">
                    </td>
                    <td><input type="text" class="form-control form-control-sm form-filter kt-input" name="d_amount">
                        <button class="d-none btn btn-brand kt-btn btn-sm kt-btn--icon mb-2   filter-submit"><span><i class="la la-search"></i><span>Search</span></span></button> &nbsp;
                        <button class="d-none btn btn-secondary kt-btn btn-sm kt-btn--icon mt-0 mb-2 filter-cancel"><span><i class="la la-close"></i><span>Reset</span></span></button>
                    </td>
                    <td>
                      <select class="form-control form-control-sm form-filter kt-input" title="Select" name="e_type">
                        <option value="">Select</option>
                        <option value="Booked">Booked</option>
                        <option value="Refunded">Refunded</option>
                        <option value="Booked-Wallet">Booked-Wallet</option>
                      </select>
                    </td>
                    <td>
                      <select class="form-control form-control-sm form-filter kt-input" title="Select" name="e_status">
                        <option value="">Select</option>
                        <option value="Success">Success</option>
                        <option value="Failed">Failed</option>
                      </select>
                        
                    </td>
                    
                </tr>
            </thead>
        </table>

        <!--end: Datatable -->
      </div>
    </div>

    <!--End::Row-->


    <!--End::Dashboard 1-->
  </div>

  <!-- end:: Content -->
</div>

@stop

@section('custom_js')
  <script>
    $(document).ready(function() {

      // $('[data-toggle="tooltip"]').tooltip({'placement': 'left'});
      
      var date = new Date();
      date.setDate(date.getDate());
      $('.date_picker').datepicker({
          format: 'mm/dd/yyyy',
          autoclose: true,
          todayHighlight: true,
          orientation: "bottom auto"
      }).on('changeDate', function(e) {
          //$('.date_picker').datepicker('destroy');
          $('.date_picker').trigger('blur');
      });

      @if(Session::has('success-message'))
        toastr.success('{{ Session::get('success-message') }}');
      @endif

      var url = ADMIN_URL + 'payment-transactions/list-ajax';
      var order =  [2, 'desc'];
      DataTables.init('#datatable_ajax', url, order);

      $('#filter-search').click(function() {
            $('input[name="d_amount"]').next().click();
        });
        $('#filter-reset').click(function() {
            $('input[name="d_amount"]').next().next().click();
        });
    });

  </script>
@stop
