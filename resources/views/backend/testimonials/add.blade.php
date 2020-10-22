@extends('backend.layouts.default')
@section('content')

<div class="kt-content  kt-grid__item kt-grid__item--fluid kt-grid kt-grid--hor" id="kt_content">

    <div class="kt-subheader   kt-grid__item" id="kt_subheader">
    </div>
    <div class="kt-container  kt-container--fluid  kt-grid__item kt-grid__item--fluid">
        <div class="row">
            <div class="col-lg-12">
                <div class="kt-portlet">
                    <div class="kt-portlet__head">
                        <div class="kt-portlet__head-label">
                            <h3 class="kt-portlet__head-title">
                            {{$title}}
                            </h3>
                        </div>
                    </div>
                    <form class="kt-form kt-form--label-right" id="frmAdd" action="{{ ADMIN_URL }}testimonials/add">
                        <div class="kt-portlet__body">
                            <div class="form-group row">
                                <label class="col-form-label col-md-2 col-lg-2 col-sm-12">Title <span class="required">*</span></label>
                                <div class="col-lg-4 col-md-6 col-sm-12">
                                    <input type="text" class="form-control required" name="v_title" placeholder="Title">                  
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-form-label col-md-2 col-lg-2 col-sm-12">Comment <span class="required">*</span></label>
                                <div class="col-lg-4 col-md-6 col-sm-12">
                                    <textarea type="text" class="form-control required"  name="t_comment" placeholder="Comment" rows="5"></textarea>                 
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-form-label col-md-2 col-lg-2 col-sm-12">Client Name<span class="required">*</span></label>
                            
                                <div class="col-lg-4 col-md-6 col-sm-12">
                                    <input type="text" class="form-control required" name="v_client_name" placeholder="Client Name">                  
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-form-label col-md-2 col-lg-2 col-sm-12">Status <span class="required">*</span></label>
                                <div class="col-lg-4 col-md-6 col-sm-12 form-group-sub">
                                    <select class="form-control required" name="e_status" placeholder="Status">
                                        <option value="">Select</option>
                                        <option value="Active">Active</option>
                                        <option value="Inactive">Inactive</option>
                                    </select>                  
                                </div>
                            </div>   
                        </div>
                        <div class="kt-portlet__foot">
                            <div class="kt-form__actions">
                                <div class="row">
                                    <div class="col-lg-9 ml-lg-auto">
                                        <button type="submit" class="btn btn-brand">Submit</button>
                                        <a href="{{ ADMIN_URL }}testimonials" class="btn btn-secondary"> Cancel </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    
</div>
@stop

@section('custom_js')
    <script>
        $(document).ready(function() {
        
        });    
    </script>
@stop