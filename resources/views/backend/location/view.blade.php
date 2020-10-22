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
        
        <div class="kt-portlet">
            <div class="kt-portlet__head">
                <div class="kt-portlet__head-label">
                    <h3 class="kt-portlet__head-title">
                        Location Information:
                    </h3>
                </div>
                <div class="kt-portlet__head-toolbar">
                    <div class="kt-portlet__head-wrapper">
                        <div class="kt-portlet__head-actions">
                            <a href="{{ ADMIN_URL }}location" class="btn btn btn-secondary btn-icon-sm">
                                Back To Listing
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="kt-portlet__body">
                <div class="row">
                    <div class="col-md-6 col-lg-6 col-sm-12">
                        <div class="form-group kt-mb-0 row">
                            <label class="col-form-label col-md-5 col-lg-5 col-sm-12">Lable: </label>
                            <div class="col-md-7 col-lg-7 col-sm-12">
                                <label class="kt-mt-10"> {{ $record['v_label'] }} </label>                  
                            </div>
                        </div>

                        <div class="form-group kt-mb-0 row">
                            <label class="col-form-label col-md-5 col-lg-5 col-sm-12">Service Area: </label>
                            <div class="col-md-7 col-lg-7 col-sm-12">
                                <label class="kt-mt-10"> {{ $record['geo_cities']['geo_service_area']['v_area_label'] }} </label>                  
                            </div>
                        </div>

                        <div class="form-group kt-mb-0 row">
                            <label class="col-form-label col-md-5 col-lg-5 col-sm-12">Street: </label>
                            <div class="col-md-7 col-lg-7 col-sm-12">
                                <label class="kt-mt-10"> {{ $record['v_street1'] }} </label>                  
                            </div>
                        </div>

                        <div class="form-group kt-mb-0 row">
                            <label class="col-form-label col-md-5 col-lg-5 col-sm-12">County: </label>
                            <div class="col-md-7 col-lg-7 col-sm-12">
                                <label class="kt-mt-10"> {{ $record['geo_cities']['v_county'] }} </label>                  
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6 col-lg-6 col-sm-12">

                        <div class="form-group kt-mb-0 row">
                            <label class="col-form-label col-md-5 col-lg-5 col-sm-12">Service Type: </label>
                            <div class="col-md-7 col-lg-7 col-sm-12">
                                <label class="kt-mt-10"> {{ $record['e_service_type'] }} </label>                 
                            </div>
                        </div>
                        
                        <div class="form-group kt-mb-0 row">
                            <label class="col-form-label col-md-5 col-lg-5 col-sm-12">Destination Point: </label>
                            <div class="col-md-7 col-lg-7 col-sm-12">
                                <label class="kt-mt-10"> {{ $record['geo_point_type']['v_label'] }} </label>                 
                            </div>
                        </div>

                        <div class="form-group kt-mb-0 row">
                            <label class="col-form-label col-md-5 col-lg-5 col-sm-12">City: </label>
                            <div class="col-md-7 col-lg-7 col-sm-12">
                                <label class="kt-mt-10"> {{ $record['geo_cities']['v_city'] }} </label>                 
                            </div>
                        </div>
                        
                        <div class="form-group kt-mb-0 row">
                            <label class="col-form-label col-md-5 col-lg-5 col-sm-12">Postal Code: </label>
                            <div class="col-md-7 col-lg-7 col-sm-12">
                                <label class="kt-mt-10"> {{ $record['v_postal_code'] }} </label>                 
                            </div>
                        </div>

                    </div>
                </div> 
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
      
    });

  </script>
@stop
