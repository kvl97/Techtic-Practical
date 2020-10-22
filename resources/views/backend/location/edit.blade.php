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
                Edit Location
              </h3>
            </div>
          </div>

          <!--begin::Form-->
          <form class="kt-form kt-form--label-right" id="frmEdit" action="{{ ADMIN_URL }}location/edit/{{ $record->id }}">
            <div class="kt-portlet__body">

                

                <div class="form-group row">
                    <label class="col-form-label col-md-2 col-lg-2 col-sm-12">Location Type<span class="required">*</span></label>
                    <div class="col-lg-4 col-md-6 col-sm-12">
                        <select class="form-control required i_point_type_id" name="i_point_type_id" placeholder="Location Type">
                            <option value="">Select</option>
                            @if(count($record_point_type) > 0)
                                @foreach($record_point_type as $val)
                                    <option value="{{ $val['id'] }}" {{ $record->i_point_type_id == $val['id'] ? 'selected=""' : '' }}>{{ $val['v_label'] }}</option>
                                @endforeach
                            @endif  
                        </select>         
                    </div>
                </div>

                <div class="form-group row">
                    <label class="col-form-label col-md-2 col-lg-2 col-sm-12">label <span class="required">*</span></label>
                    <div class="col-lg-4 col-md-6 col-sm-12">
                       
                        <textarea type="text" class="form-control required" name="v_label" placeholder="Label" maxlength="100"> {{ $record->v_label}} </textarea>
                    </div>
                </div>

                <div class="form-group row">
                    <label class="col-form-label col-md-2 col-lg-2 col-sm-12">Street <span class="required">*</span></label>
                    <div class="col-lg-4 col-md-6 col-sm-12">
                        <input type="text" class="form-control required" name="v_street1" placeholder="Street" value="{{ $record->v_street1}}" maxlength="100">                  
                    </div>
                </div>

                <div class="form-group row">
                    <label class="col-form-label col-md-2 col-lg-2 col-sm-12">City <span class="required">*</span></label>
                    <div class="col-lg-4 col-md-6 col-sm-12">
                        
                        <select class="form-control required i_city_id" id="i_city_id" name="i_city_id" placeholder="City">
                            <option value="">Select</option>
                            @if(count($city_list) > 0)
                                @foreach($city_list as $val)
                                    <option value="{{ $val['id'] }}" {{ $record->i_city_id == $val['id'] ? 'selected=""' : '' }}>{{ $val['v_city'] }}</option>
                                @endforeach
                            @endif  
                        </select>                 
                    </div>
                </div>  
               
                <div class="form-group row">
                    <label class="col-form-label col-md-2 col-lg-2 col-sm-12">County</label>
                    <div class="col-lg-4 col-md-6 col-sm-12">
                      <label class="kt-mt-10 v_county" id="v_county">{{ $county_service_data[0]['v_county']}}</label>
                                 
                    </div>
                </div>

                <div class="form-group row">
                    <label class="col-form-label col-md-2 col-lg-2 col-sm-12">Service Area</label>
                    <div class="col-lg-4 col-md-6 col-sm-12">
                      <label class="kt-mt-10 i_service_area_id" id="i_service_area_id">{{ $county_service_data[0]['geo_service_area']['v_area_label']}}</label>
                                       
                    </div>
                </div>

                <div class="form-group row">
                    <label class="col-form-label col-md-2 col-lg-2 col-sm-12">Postal Code </label>
                    <div class="col-lg-4 col-md-6 col-sm-12">
                        <input type="text" class="form-control" name="v_postal_code" placeholder="Postal Code" value="{{ $record->v_postal_code}}" maxlength="20">                  
                    </div>
                </div>

                <div class="form-group row ">
                    <label class="col-form-label col-md-2 col-lg-2 col-sm-12">Service Type <span class="required">*</span></label>
                    <div class="col-lg-4 col-md-6 col-sm-12 radio-btn-msg">
                        <div class="kt-radio-inline mt-10">

                          <label class="kt-radio">
                            <input type="radio" class="required-least-one-radio" name="e_service_type" value="Shared" id="shared" groupid="service_type" <?php if($record->e_service_type =='Shared') { ?> checked='checked' <?php } ?>> Shared
                          </label>

                            <label class="kt-radio">
                            <input type="radio" class="required-least-one-radio" name="e_service_type" value="Private" id="private" groupid="service_type" <?php if($record->e_service_type =='Private') { ?> checked='checked' <?php } ?> > Private
                            </label>
                            
                            <span class="check"></span>
                        </div>
                    </div>
                </div>
              
            </div>
            <div class="kt-portlet__foot">
              <div class="kt-form__actions">
                <div class="row">
                  <div class="col-lg-9 ml-lg-auto">
                    <button type="submit" class="btn btn-brand">Submit</button>
                    <a href="{{ ADMIN_URL }}location" class="btn btn-secondary"> Cancel </a>
                    
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

      $('#i_city_id').on('change', function() {
        var city_id = $('#i_city_id').val();
        url = ADMIN_URL + 'location/get-county-service-area-name';
        $.ajax({
            url: url,
            data : {id : city_id},
            type: 'post',        
            success:function(data){
              console.log(data);
              $('#v_county').text(data.v_county);
              $('#i_service_area_id').text(data.i_service_area_id);
            }
        });
      });
      
    });    

    
      
  </script>
@stop