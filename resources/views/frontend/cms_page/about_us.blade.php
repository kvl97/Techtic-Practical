@extends('frontend.layouts.default')
@section('content')



{!! $contant_of_aboutUs['t_content'] !!}


@section('custom_js')
<script>
    $(document).ready(function() {
        KTFrontend.init();
    });
</script>
@stop

@stop