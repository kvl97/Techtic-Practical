@extends('frontend.layouts.default')
@section('content')

    <div class="main-content">

        <!-- contact section -->
        <section class="contact-section mt-5 mb-5">
            <div class="container">
                <div class="profile-quick-links">
                     <ul>
                        <li><a href="{{FRONTEND_URL.'my-profile'}}">My Profile</a></li>
                      
                        <li><a href="{{FRONTEND_URL.'upcoming-reservation'}}">Upcoming Reservation</a></li>
                        <li><a href="{{FRONTEND_URL.'past-reservation'}}">Past Reservation</a></li>
                        @if($records['customer_stripe_id'] != '' )
                            <li class="active"><a href="{{FRONTEND_URL}}my-card-information">Card Info</a></li>
                        @endif
                        <li><a href="{{FRONTEND_URL.'payment-history'}}">Payment History</a></li>
                    </ul>
                    @if(auth()->guard('customers')->user() && auth()->guard('customers')->user()->d_wallet_balance > 0.00)
                        <span class="sec-wallet-balance-lg"><strong>Wallet Balance:</strong> ${{ auth()->guard('customers')->user()->d_wallet_balance }}</span>
                    @endif
                </div>
                @if(auth()->guard('customers')->user() && auth()->guard('customers')->user()->d_wallet_balance > 0.00)
                    <div class="row sec-wallet-balance-sm">
                        <span><strong>Wallet Balance:</strong> ${{ auth()->guard('customers')->user()->d_wallet_balance }}</span>
                    </div>
                @endif
                <div class="contact-wrapper row">
                    <div class="contact-my-address col-md-12 col-xl-12 mt-5">
                        <div class="table-responsive">
                        <div class="align-items-center d-flex flex-column flex-sm-row justify-content-between profile-address-bar pb-4">
                            <p class="m-0">Card Information</p> 
                        </div>
                            <div class="table-responsive mt-3">
                                <div id="error_msg"></div>      
                                <table class="table table-bordered" id="fr_customer_card_table">
                                    <thead class="thed-darkslateblue">
                                        <tr>
                                            <th>Card last 4 digits</th>
                                            <th>Expiry Month </th>
                                            <th>Expiry Year</th>
                                            <th class="no-sort">Actions</th>

                                        </tr>
                                    </thead>
                                    <tbody>
                                    
                                        @if(count($customer) > 0) 
                                            @if(count($customer->sources) > 0) 

                                            @php $cards_fingerprints = []; @endphp
                                            
                                            @foreach ($customer->sources->data as $cardDetail)
                                                
                                            @if(!in_array($cardDetail['fingerprint'],$cards_fingerprints))
                                                
                                                @if($customer->default_source != $cardDetail->id)
                                                    <tr>
                                                        <td style="vertical-align: middle;">{{ 'xxxx xxxx xxxx '.$cardDetail['last4']}}</td>
                                                        <td style="vertical-align: middle;">{{ $cardDetail['exp_month'] }}</td>
                                                        <td style="vertical-align: middle;">{{ $cardDetail['exp_year'] }}</td>
                                                        <td style="vertical-align: middle;"> <a href="javascript:;" id="delete_record_card" delete-id= "{!! $records['id'] !!}" delete-url="<?php echo SITE_URL.'my-card-information/delete/'.$records['id'];?>"class="btn btn-xs add-button delete_record_card" title="Delete">Delete</a>
                                                       
                                                        <a class="btn btn-xs btn-red" delete-id= "{!! $records['id'] !!}"  href="javascript:;" delete-url="<?php echo SITE_URL.'my-card-information/set_default/'.$records['id'].'/'.$cardDetail->id?>"    id="set_default_card"  title="Set Default">Set Default</a>
                                                      
                                                        </td>
                                                    </tr>
                                                @else
                                                    <tr style="background: lightgray;">
                                                        <td style="vertical-align: middle;">{{ 'xxxx xxxx xxxx '.$cardDetail['last4']}}</td>
                                                        <td style="vertical-align: middle;">{{ $cardDetail['exp_month'] }}</td>
                                                        <td style="vertical-align: middle;">{{ $cardDetail['exp_year'] }}</td>
                                                        <td style="vertical-align: middle;"> <a href="javascript:;" id="delete_record_card" delete-id= "{!! $records['id'] !!}" delete-url="<?php echo SITE_URL.'my-card-information/delete/'.$records['id'];?>"class="btn btn-xs add-button delete_record_card" title="Delete">Delete</a>
                                                        
                                                        </td>
                                                    </tr>
                                                @endif
                                                @endif
                                                @php $cards_fingerprints[] = $cardDetail['fingerprint'] @endphp
                                            @endforeach
                                            @else
                                            <td colspan="4" style='text-align:center'>No record found</td>
                                            @endif
                                        @else
                                            <td colspan="4" style='text-align:center'>No record found</td>
                                        @endif

                                    </tbody>
                                </table>
                                <p style="font-size: 0.85rem !important">* We do not save any card information on the website. The above information are saved and fetched from the stripe payment gateway. It saves the last 4 digits of the card and expiry date when you successfully completed any payment transaction.</p>
                            </div>  
                            
                        </div>  
                    </div>
                </div>
            </div>
        </section>
    </div>
    
    <div class="modal fade bd-example-modal-sm p-md-0" id="deleteAddress" tabindex="-1" role="dialog" aria-labelledby="deleteAddress" aria-hidden="true" data-backdrop="static" data-keyboard="false">
        <div class="modal-xl modal-dialog  modal-dialog-scrollable"
            role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteAddress">Delete</h5>
                </div>
                <div class="modal-body text-md-left">
                    <div class="contestants-info mt-0">
                        Are you sure that you want to delete this Card ?
                    </div>
                    
                </div>
                <div class="modal-footer">
                        <button type="button" class="btn btn-xs btn-red" id="modal-btn-si">Ok</button>
                        <button type="button" class="btn btn-primary" id="modal-btn-no">Cancel</button>
                    </div>
            </div>
        </div>
    </div>
    <div class="modal fade bd-example-modal-sm p-md-0" id="setDefalul" tabindex="-1" role="dialog" aria-labelledby="setDefalul" aria-hidden="true" data-backdrop="static" data-keyboard="false">
        <div class="modal-xl modal-dialog modal-dialog-scrollable"
            role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="setDefalul">Set Default</h5>
                </div>
                <div class="modal-body text-md-left">
                    <div class="contestants-info mt-0">
                        Do you want to make this as a default payment card ?
                    </div>
                    
                </div>
                <div class="modal-footer">
                        <button type="button" class="btn btn-xs btn-red" id="modal-btn-set">Ok</button>
                        <button type="button" class="btn btn-primary" id="modal-btn-cencel">Cancel</button>
                    </div>
            </div>
        </div>
    </div>
@section('custom_js')
<script src="{{ asset('frontend/assets/js/login-frontend.js') }}"></script>

<script>
    $(document).ready(function() {

        $(document).on('click','#delete_record_card',function(e) {
        var that = this;
            modalConfirm(function(confirm){
                if(confirm){
                    var deleteId =  $(that).attr('delete-id');    
                    var url =  $(that).attr('delete-url');  
                    $.ajax({
                        type: 'POST',
                        url: url,
                        cache: false,
                        contentType: false,
                        processData: false,
                        success: function(resultData) {
                            console.log(resultData);
                            
                            if(resultData == 'TRUE') {
                                $("#deleteAddress").modal('hide');
                                
                                $("#fr_customer_card_table").load(location.href + " #fr_customer_card_table"); 
                                $("#error_msg").html('<div class="alert alert-success alert-dismissible" role="alert"><span type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></span>Your card delete successfully.</div>');
                                $("html, body").animate({
                                    scrollTop: 0
                                }, 1000);
                               
                            } 
                        }
                    }); 
                }else {
                    $("#deleteAddress").modal('hide');
                
                }
            });
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
        $(document).on('click','#set_default_card',function(e) {
        var that = this;
            modalSetConfirm(function(confirm){
                if(confirm){
                    var deleteId =  $(that).attr('delete-id');    
                    var url =  $(that).attr('delete-url');  
                    $.ajax({
                        type: 'POST',
                        url: url,
                        cache: false,
                        contentType: false,
                        processData: false,
                        success: function(resultData) {
                            
                            
                            if(resultData == 'TRUE') {
                                $("#setDefalul").modal('hide');
                                
                                $("#fr_customer_card_table").load(location.href + " #fr_customer_card_table"); 
                                $("#error_msg").html('<div class="alert alert-success alert-dismissible" role="alert"><span type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></span>Your default payment card set successfully..</div>');
                                $("html, body").animate({
                                    scrollTop: 0
                                }, 1000);
                               
                            } 
                        }
                    }); 
                }else {
                    $("#setDefalul").modal('hide');
                
                }
            });
        });
        var modalSetConfirm = function(callback) {  
            $("#setDefalul").modal('show');
            $("#modal-btn-set").on("click", function(){
                callback(true);
                $("#mi-modal_set").modal('hide');
            });
        
            $("#modal-btn-cencel").on("click", function(){
                callback(false);
                $("#mi-modal_set").modal('hide');
            });
        };
        
    });
</script>
@stop

@stop
