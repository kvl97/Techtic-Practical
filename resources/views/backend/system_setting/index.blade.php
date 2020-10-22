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
                    <i class="kt-font-brand fa fa-cog"></i>
                </span>
                <h3 class="kt-portlet__head-title">
                    {{ $title }}
                </h3>
            </div>
        </div>
      <div class="kt-portlet__body">
        <div class="row">
            @if(isset($permission) && isset($permission[13]['i_add_edit']) && $permission[13]['i_add_edit'] == 1)
            <div class="col-lg-4">
                <div class="card-box">
                    <form id="form-KioskColor" action="{{ ADMIN_URL }}settings" role="form" method="post" onsubmit="return false;">
                        <div class="row">
                            <?php
                                $color_list = json_decode($colors->kiosk_params);
                                //pr($color_list); exit;
                            ?>
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label for="setColor1" class="control-label kiosk-color-label">Color #1</label>
                                    <div class="input-group ">
                                        <span class="input-group-addon bg-primary text-white"><i class="fa fa-paint-brush"></i></span>
                                        <input type="color" class="form-control" name="colors[]" id="setColor1" value="{{$color_list->colors[0]}}" onchange="changeColor()">
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label for="setColor1" class="control-label kiosk-color-label">Color #2</label>
                                    <div class="input-group ">
                                        <span class="input-group-addon bg-primary text-white"><i class="fa fa-paint-brush"></i></span>
                                        <input type="color" class="form-control" name="colors[]" id="setColor2" value="{{$color_list->colors[1]}}" onchange="changeColor()">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label for="setColor1" class="control-label kiosk-color-label">Color #3</label>
                                    <div class="input-group  ">
                                        <span class="input-group-addon bg-primary  text-white"><i class="fa fa-paint-brush"></i></span>
                                        <input type="color" class="form-control" name="colors[]" id="setColor3" value="{{$color_list->colors[2]}}" onchange="changeColor()">
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label for="setColor1" class="control-label kiosk-color-label">Color #4</label>
                                    <div class="input-group  ">
                                        <span class="input-group-addon bg-primary  text-white"><i class="fa fa-paint-brush"></i></span>
                                        <input type="color" class="form-control" name="colors[]" id="setColor4" value="{{$color_list->colors[3]}}" onchange="changeColor()">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label for="setColor1" class="control-label kiosk-color-label">Color #5</label>
                                    <div class="input-group  ">
                                        <span class="input-group-addon bg-primary  text-white"><i class="fa fa-paint-brush"></i></span>
                                        <input type="color" class="form-control" name="colors[]" id="setColor5" value="{{$color_list->colors[4]}}" onchange="changeColor()">
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label for="setColor1" class="control-label kiosk-color-label">Color #6</label>
                                    <div class="input-group  ">
                                        <span class="input-group-addon bg-primary  text-white"><i class="fa fa-paint-brush"></i></span>
                                        <input type="color" class="form-control" name="colors[]" id="setColor6" value="{{$color_list->colors[5]}}" onchange="changeColor()">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-12 kt-pull-right submit-btn-kiosk">
                                <button type="submit" class="btn btn-primary waves-effect waves-light" id="submitForm" onClick="saveColor(1)">Save</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            @endif
            @if(isset($permission) && isset($permission[19]['i_list']) && $permission[19]['i_list'] == 1)
            <div class="col-lg-8">
                <div class="card-box">
                    <h4 class="kiosk-header-title">SEA Kiosk</h4>
                    <div class="row" style="padding-bottom:20px;">
                        <div class="col-sm-12 col-md-12 col-lg-12">
                            <div class="row" id="sampleColor1" style="">
                                <div class="col-sm-4 col-md-4 col-lg-4">
                                    <h1 class="sample-color-h1">R01</h1>
                                </div>
                                <div class="col-sm-8 col-md-8 col-lg-8">
                                    <h3 class="sample-color-h3 color1" >Departure Time: 16:00</h3>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row" style="padding-bottom:20px;">
                        <div class="col-sm-12 col-md-12 col-lg-12">
                            <div class="row" id="sampleColor2" style="">
                                <div class="col-sm-4 col-md-4 col-lg-4">
                                    <h1 class="sample-color-h1">R02</h1>
                                </div>
                                <div class="col-sm-8 col-md-8 col-lg-8">
                                    <h3 class="sample-color-h3 color2" >Departure Time: 16:00</h3>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row" style="padding-bottom:20px;">
                        <div class="col-sm-12 col-md-12 col-lg-12">
                            <div class="row" id="sampleColor3" style="">
                                <div class="col-sm-4 col-md-4 col-lg-4">
                                    <h1 class="sample-color-h1">R03</h1>
                                </div>
                                <div class="col-sm-8 col-md-8 col-lg-8">
                                    <h3 class="sample-color-h3 color3" >Departure Time: 16:00</h3>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row" style="padding-bottom:20px;">
                        <div class="col-sm-12 col-md-12 col-lg-12">
                            <div class="row" id="sampleColor4" style="">
                                <div class="col-sm-4 col-md-4 col-lg-4">
                                    <h1 class="sample-color-h1">R04</h1>
                                </div>
                                <div class="col-sm-8 col-md-8 col-lg-8">
                                    <h3 class="sample-color-h3 color4" >Departure Time: 16:00</h3>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row" style="padding-bottom:20px;">
                        <div class="col-sm-12 col-md-12 col-lg-12">
                            <div class="row" id="sampleColor5" style="">
                                <div class="col-sm-4 col-md-4 col-lg-4">
                                    <h1 class="sample-color-h1">R05</h1>
                                </div>
                                <div class="col-sm-8 col-md-8 col-lg-8">
                                    <h3 class="sample-color-h3 color5" >Departure Time: 16:00</h3>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row" style="padding-bottom:20px;">
                        <div class="col-sm-12 col-md-12 col-lg-12">
                            <div class="row" id="sampleColor6" >
                                <div class="col-sm-4 col-md-4 col-lg-4">
                                    <h1 class="sample-color-h1">R06</h1>
                                </div>
                                <div class="col-sm-8 col-md-8 col-lg-8">
                                    <h3 class="sample-color-h3 color6" >Departure Time: 16:00</h3>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @endif
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
        //setTimeout(function(){
            @if(Session::has('success-message-kiosk'))
                toastr.success('{{ Session::get('success-message-kiosk') }}');
            @endif
        //},1000);

        saveColor();
    });

    function changeColor() {

        var color1 = $('#setColor1').val();
        var color2 = $('#setColor2').val();
        var color3 = $('#setColor3').val();
        var color4 = $('#setColor4').val();
        var color5 = $('#setColor5').val();
        var color6 = $('#setColor6').val();

        $('#sampleColor1').css("background-color",color1);
        $('#sampleColor2').css("background-color",color2);
        $('#sampleColor3').css("background-color",color3);
        $('#sampleColor4').css("background-color",color4);
        $('#sampleColor5').css("background-color",color5);
        $('#sampleColor6').css("background-color",color6);
    }

    function saveColor(flag=0) {

        var url = ADMIN_URL + 'settings';
        var color1 = $('#setColor1').val();
        var color2 = $('#setColor2').val();
        var color3 = $('#setColor3').val();
        var color4 = $('#setColor4').val();
        var color5 = $('#setColor5').val();
        var color6 = $('#setColor6').val();
        var color_array = [color1, color2, color3, color4, color5, color6];
        var submit = 0;
        if(flag == 1) {
            submit = 1;
        }
        $.ajax({
            url: url,
            type: 'post',
            data: {colors : color_array, submit : submit},
            success: function () {
                if(flag == 1) {
                    window.location.href=window.location.href;
                }
            }
        });

        /*var d = new Date(); // for now
        var hours = d.getHours();
        var minutes = d.getMinutes();

        $('.color1').html('Departure Time: ' + hours + ':' + minutes);
        $('.color2').html('Departure Time: ' + hours + ':' + minutes);
        $('.color3').html('Departure Time: ' + hours + ':' + minutes);
        $('.color4').html('Departure Time: ' + hours + ':' + minutes);
        $('.color5').html('Departure Time: ' + hours + ':' + minutes);
        $('.color6').html('Departure Time: ' + hours + ':' + minutes);*/

        $('#sampleColor1').css("background-color",color1);
        $('#sampleColor2').css("background-color",color2);
        $('#sampleColor3').css("background-color",color3);
        $('#sampleColor4').css("background-color",color4);
        $('#sampleColor5').css("background-color",color5);
        $('#sampleColor6').css("background-color",color6);
    }

  </script>
@stop
