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
                Edit Email Template
              </h3>
            </div>
          </div>

          <!--begin::Form-->
          <?php //pr($record); exit; ?>
        <form class="kt-form kt-form--label-right" id="frmEdit" action="{{ ADMIN_URL }}email-template/edit/{{ $record->id }}">
            <div class="kt-portlet__body">
              <div class="form-group row">
                <label class="col-form-label col-md-2 col-lg-2 col-sm-12">Title<span class="required">*</span></label>
                <div class="col-lg-4 col-md-6 col-sm-12">
                  <input type="text" class="form-control required" name="v_template_title" placeholder="Title" value="{{ $record->v_template_title }}">         
                </div>
              </div>
              <div class="form-group row">
                <label class="col-form-label col-md-2 col-lg-2 col-sm-12">Subject<span class="required">*</span></label>
                <div class="col-lg-4 col-md-6 col-sm-12">
                  <input type="text" class="form-control required" name="v_template_subject" placeholder="Subject" value="{{ $record->v_template_subject }}">         
                </div>
              </div>
              <div class="form-group row">
                    <label class="col-form-label col-md-2 col-lg-2 col-sm-12">Email Content <span class="required">*</span></label>
                    <div class="col-lg-10 col-md-10 col-sm-12">
                        <textarea type="text" class="form-control required"  name="t_email_content" placeholder="Email Content" id="summernote">{{  $record->t_email_content }}</textarea>
                        <br>
                        <p>    
                          <span style="color:red">Note: please do not delete keywords written with [], for example. [SITE_NAME] The system will replace it with the actual value when sending an email to the end user</span>
                        </p>                 
                    </div>
                </div>
            </div>
            <div class="kt-portlet__foot">
              <div class="kt-form__actions">
                <div class="row">
                  <div class="col-lg-9 ml-lg-auto">
                    <button type="submit" class="btn btn-brand">Submit</button>
                    <a href="{{ ADMIN_URL }}email-template" class="btn btn-secondary"> Cancel </a>
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
      $('#summernote').summernote({
            height: 200,
            toolbar: [
            // [groupName, [list of button]]
            ['style', ['bold', 'italic', 'underline', 'clear']],
            ['font', ['strikethrough', 'superscript', 'subscript']],
            ['fontsize', ['fontsize']],
            ['color', ['color']],
            ['para', ['ul', 'ol', 'paragraph']],
            ['height', ['height']],
            ['insert', ['link']],
            ['view', ['fullscreen', 'codeview', 'help']],
            ]
        });
    }); 
  </script>
@stop