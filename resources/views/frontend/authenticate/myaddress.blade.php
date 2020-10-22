@extends('frontend.layouts.default')
@section('content')

    
<div class="main-content">

    <!-- contact section -->
    <section class="my-address mt-5 mb-5">
        <div class="container">
            <div class="profile-quick-links" id="mainMenu">
                <ul class="nav nav-pills"  id="myTab">
                    <li><a href="{{FRONTEND_URL}}my-profile" class="customnavbar">My Profile</a></li>
                    <li class="active"> <a href="{{FRONTEND_URL}}my-address" class="customnavbar">Addresses</a></li>
                    <li><a href="{{FRONTEND_URL.'upcoming-reservation'}}">Upcoming Reservation</a></li>
                    <li><a href="{{FRONTEND_URL.'past-reservation'}}">Past Reservation</a></li>
                    <li><a href="{{FRONTEND_URL}}my-card-information">Card information</a></li>
                </ul>
            </div>
            <div class="contact-wrapper row">
                <div class="contact-my-address col-md-12 col-xl-12 mt-4">
                   
                    <div class="col-md-12  pl-10 pr-10" id="addressSucccess">
                    </div>
                    <div class="table-responsive">   
                        <div class="align-items-center d-flex flex-column flex-sm-row justify-content-between profile-address-bar pb-4">
                            <p class="m-0">My Addresses</p> 
                            <a href="{{ SITE_URL }}my-card-information/add" class="btn btn-xs btn-red ml-2 add-button"  data-toggle="modal" data-target="#kt_modal_address">Add New Address</a>
                        </div>
                        <table class="table table-bordered" id="addressTable">
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
                            
                            @if(!empty($address_record))
                                @foreach ($address_record as $records)    
                                    <tr>
                                        <td class="td-edit-cust-address" data-id="{{ $records['id'] }}">{{ $records['v_address_label'] }}</td>
                                        <td class="td-edit-cust-address" data-id="{{ $records['id'] }}">{{ $records['v_street'] }}</td>
                                        <td class="td-edit-cust-address" data-id="{{ $records['id'] }}">{{ $records['v_city'] }}</td>
                                        <td class="td-edit-cust-address" data-id="{{ $records['id'] }}">{{ $records['v_state'] }}</td>
                                        <td class="td-edit-cust-address" data-id="{{ $records['id'] }}">{{ $records['v_country'] }}</td> 
                                        <td class="td-edit-cust-address" data-id="{{ $records['id'] }}">{{ $records['v_postal_code'] }}</td>
                                        <td>
                                            <a class="edit_button"
                                                data-toggle="kt_modal_address_edit" data-target="#kt_modal_address_edit"
                                                data-address-label ="<?php echo $records['v_address_label'];?>"
                                                data-street ="<?php echo $records['v_street'];?>"
                                                data-city = "<?php echo $records['v_city'];?>"
                                                data-state = "<?php echo $records['v_state'];?>"
                                                data-country = "<?php echo $records['v_country'];?>"
                                                data-postcode = "<?php echo $records['v_postal_code'];?>"
                                                data-id="<?php echo $records['id']; ?>"  href="javascript:;" title="Edit">
                                                <i class="icon icon-edit"></i></a> 
                                                    &nbsp;&nbsp;
                                            <a href="javascript:;" id="delete_record_address" delete-id="{!! $records['id'] !!}" class="delete_record_address" title="Delete"><i class="icon icon-delete"></i></a>
                                        </td>
                                    </tr>
                                @endforeach
                            @else
                                <tr>
                                    <td colspan="7" style="text-align:center">Record not found</td>
                                </tr>
                            @endif
                            </tbody>
                        </table>
                    </div> 
                        
                </div>  
            </div>
        </div>
    </section>
</div>

<div class="modal fade bd-example-modal-sm p-md-0" id="kt_modal_address" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Add New Address</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                </button>
            </div>
            <div class="row add-address-content">
                <div class="modal-body">
                    <form id="saveAddressFrm" class="custom-form"  method="POST" >
                        <div class="row ml-1 mr-1">
                            <div class="col-sm-6 form-group">
                                <label class="form-label" for="firstname">Address Label</label>
                                <input class="form-input required" name="v_address_label" type="text" id="v_address_label" err-msg="Address Label"> 
                            </div>
                        
                            <div class="col-sm-6 form-group">
                                <label class="form-label" for="lastname">City</label>
                                <input class="form-input required"  name="v_city" type="text" id="v_city" err-msg="City">
                            </div>
                            
                            <div class="col-sm-6 form-group">
                                <label class="form-label" for="lastname">State</label>
                                <input class="form-input required"  name="v_state" type="text" id="v_state" err-msg="State">
                            </div>
                            <div class="col-sm-6 form-group">
                                <label class="form-label" for="firstname">Street</label>
                                <input class="form-input required" name="v_street" type="text" id="v_street" err-msg="Street"> 
                            </div>
                            <div class="col-sm-6 form-group">
                                <label class="form-label" for="firstname">Country</label>
                                <input class="form-input required" name="v_country" type="text" id="v_country" err-msg="Country"> 
                            </div>
                            <div class="col-sm-6 form-group">
                                <label class="form-label" for="lastname">Postal Code</label>
                                <input class="form-input required"  name="v_postal_code" type="text" id="v_postal_code" err-msg="Postal Code">
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-xs btn-red add_address_save_popup">Save</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade bd-example-modal-sm p-md-0" id="deleteAddress" tabindex="-1" role="dialog" aria-labelledby="deleteAddress" aria-hidden="true" data-backdrop="static" data-keyboard="false">
    <div class="modal-xl modal-dialog modal-dialog-scrollable"
        role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteAddress">Delete</h5>
            </div>
            <div class="modal-body text-md-left">
                <div class="contestants-info mt-0">
                    Are you sure that you want to delete this address ?
                </div>
                
            </div>
            <div class="modal-footer">
                    <button type="button" class="btn btn-xs btn-red" id="modal-btn-si">Ok</button>
                    <button type="button" class="btn btn-primary" id="modal-btn-no">Cancel</button>
                </div>
        </div>
    </div>
</div>

<div class="modal fade bd-example-modal-sm p-md-0" id="kt_modal_address_edit" tabindex="-1" role="dialog" aria-labelledby="kt_modal_address_edit" aria-hidden="true" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="kt_modal_address_edit">Edit Address</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                </button>
            </div>
            <div class="row add-address-content">
                <div class="modal-body">
                <form id="editAddressFrm" class="custom-form"  method="POST" >
                    <div class="row ml-1 mr-1">
                        <div class="col-sm-6 form-group">
                            <label class="form-label" for="firstname">Address Label</label>
                            <input class="form-control" id="editid" type="hidden" name="edit_id">

                            <input class="form-input required" name="v_address_label" type="text" id="v_address_label_edit" err-msg="Address Label"> 
                        </div>
                    
                        <div class="col-sm-6 form-group">
                            <label class="form-label" for="lastname">City</label>
                            <input class="form-input required"  name="v_city" type="text" id="v_city_edit" err-msg="City">
                        </div>
                        
                        <div class="col-sm-6 form-group">
                            <label class="form-label" for="lastname">State</label>
                            <input class="form-input required"  name="v_state" type="text" id="v_state_edit" err-msg="State">
                        </div>
                        <div class="col-sm-6 form-group">
                            <label class="form-label" for="firstname">Street</label>
                            <input class="form-input required" name="v_street" type="text" id="v_street_edit" err-msg="Street"> 
                        </div>
                        <div class="col-sm-6 form-group">
                            <label class="form-label" for="firstname">Country</label>
                            <input class="form-input required" name="v_country" type="text" id="v_country_edit" err-msg="Country"> 
                        </div>
                        <div class="col-sm-6 form-group">
                            <label class="form-label" for="lastname">Postal Code</label>
                            <input class="form-input required"  name="v_postal_code" type="text" id="v_postal_code_edit" err-msg="Postal Code">
                        </div>
                    </div>
                  
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-xs btn-red add_address_save_popup">Save</button>
                    </div>
                </form>
                </div>
            </div>
        </div>
    </div>
</div>   

@section('custom_js')
<script src="{{ asset('frontend/assets/js/login-frontend.js') }}"></script>
<script>
   $(document).ready(function() {
        
    $('#kt_modal_address').on('hidden.bs.modal', function(){
        console.log('asdasd');
        $('#saveAddressFrm').find('.is-invalid').removeClass('is-invalid');
        $(this).find('form')[0].reset();
    });
    
    $('.custom-form input , .custom-form select, .custom-form textarea').parents('.form-group').addClass('focused');
    KTLoginFrontend.init(); 

    var date = new Date();
    date.setDate(date.getDate());
    $('.date_picker').datepicker({
        format: 'mm/dd/yyyy',
        autoclose: true,
        endDate: date,
        todayHighlight: true,
        orientation: "bottom auto"
    }).on('changeDate', function(e) {
        $('.date_picker').trigger('blur');
    });

    $(document).on( "click", '.add-button',function(e) {
        
        $('#saveAddressFrm').find('.is-invalid').removeClass('is-invalid');
        $('#kt_modal_address').find('form')[0].reset();
        $("#kt_modal_address").modal('show');
        
    });
    
    $(document).on('submit', '#saveAddressFrm', function(event) {
       
        event.preventDefault();
        
        handleSaveaAddress();
    });

    function handleSaveaAddress() {
        if(!form_valid('#saveAddressFrm')) {
            return false;
        } else {

            var data = new FormData($('#saveAddressFrm')[0]);
        
            if(form_valid('#saveAddressFrm')) {
                $.ajax({
                    type: 'POST',
                    url: "{{FRONTEND_URL.'saveAddress'}}",
                    data: data,
                    cache : false,
                    contentType: false,
                    processData: false,
                    success: function(resultData) {
                        var resultData = JSON.parse(resultData);
                        if(resultData.status == 'TRUE') {
                            $('#addressSucccess').html('<div class="alert alert-success alert-dismissible"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a> Address add successfully </div>');
                            $('#kt_modal_address').modal('hide'); 
                            $("#addressTable").load(location.href + " #addressTable");
                        } else {
                            $('.show_errors').removeClass('d-none').addClass('d-block').html(resultData.error);
                        }
                    }
                });
            }
        }
    }

    $(document).on('submit', '#editAddressFrm', function(event) {
        event.preventDefault();
        
        handleEditaAddress();
    });

    function handleEditaAddress() {
        if(!form_valid('#editAddressFrm')) {
            return false;
        } else {
            var data = new FormData($('#editAddressFrm')[0]);
            
            if(form_valid('#editAddressFrm')) {
                $.ajax({
                    type: 'POST',
                    url: "{{FRONTEND_URL.'customers-address/edit'}}",
                    data: data,
                    cache : false,
                    contentType: false,
                    processData: false,
                    success: function(resultData) {
                        var resultData = JSON.parse(resultData);
                        if(resultData.status == 'TRUE') {
                            $('#addressSucccess').html('<div class="alert alert-success alert-dismissible"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a> Address update successfully </div>');
                            $('#kt_modal_address_edit').modal('hide'); 
                            $("#addressTable").load(location.href + " #addressTable");
                        } else {
                            $('.show_errors').removeClass('d-none').addClass('d-block').html(resultData.error);
                        }
                    }
                });
            }
        }
    }

    $(document).on( "click", '.edit_button',function(e) {
        var id = $(this).attr('data-id');
        var addressLabel = $(this).attr('data-address-label');
        var street = $(this).attr('data-street');
        var city = $(this).attr('data-city');
        var state = $(this).attr('data-state');
        var country = $(this).attr('data-country');
        var postcode = $(this).attr('data-postcode');
        
        $("#editid").val(id);
        $("#v_address_label_edit").val(addressLabel);
        $("#v_street_edit").val(street);
        $("#v_state_edit").val(state);
        $("#v_city_edit").val(city);
        $("#v_country_edit").val(country);
        $("#v_postal_code_edit").val(postcode);
        $("#kt_modal_address_edit").modal('show');
    });

    var modalConfirm = function(callback) {  
        $("#deleteAddress").modal('show');
        $("#modal-btn-si").on("click", function(){
            callback(true);
            $("#mi-modal").modal('hide');
        });
    
        $("#modal-btn-no").on("click", function(){
            callback(false);
            $("#mi-modal").modal('hide');
        });
    };

    $(document).on('click','#delete_record_address',function(e) {
        var that = this;
        modalConfirm(function(confirm){
            if(confirm){
                var deleteId =  $(that).attr('delete-id');    
                console.log(deleteId);
                $.ajax({
                    type: 'POST',
                    url: "{{FRONTEND_URL.'customers-address/delete/'}}" + deleteId,
                    cache: false,
                    contentType: false,
                    processData: false,
                    success: function(resultData) {
                        if(resultData == 'TRUE') {
                            $("#deleteAddress").modal('hide');
                            $("#addressTable").load(location.href + " #addressTable");
                        } 
                    }
                }); 
            }else {
                $("#deleteAddress").modal('hide');
               
            }
        });
    });
    });
</script>
@stop

@stop
