@if(isset($inputs) && $inputs['tab'] == 'location-pickup')
    <option value="">Pick up Locations</option>
    @if(count($arr_country) > 0)
        @foreach($arr_country as $k=>$v)
            <optgroup label="{{$k}}">
                @foreach($v as $key => $value)
                    <option @if(isset($location_info['home_pickup_location']) && $location_info['home_pickup_location'] == $value['id']) selected @endif value="{{$value['id']}}" location_ids="{{$value['id']}}" service_area="{{$value['i_service_area_id']}}">{{$value['v_city']}}</option>
                @endforeach
            </optgroup>
        @endforeach
    @endif
@elseif(isset($inputs) && $inputs['tab'] == 'location')
    <option value="">Drop Off Location</option>
    @if(count($arr_country) > 0)
        @foreach($arr_country as $k=>$v)
            <optgroup label="{{$k}}">
                @foreach($v as $key => $value)
                    <option @if(isset($location_info['home_dropoff_location']) && $location_info['home_dropoff_location'] == $value['id']) selected @endif value="{{$value['id']}}" location_ids="{{$value['id']}}" home_drop_off_city_ids="{{$value['v_drop_off_city_cant_be']}}" home_drop_off_city_must_be_ids="{{$value['v_drop_off_city_must_be']}}" service_area="{{$value['i_service_area_id']}}">{{$value['v_city']}}</option>
                @endforeach
            </optgroup>
        @endforeach
    @endif
@elseif(isset($inputs) && $inputs['tab'] == 'location_dropoff_rt')
    <option value="">Drop Off Location</option>
    @if(count($arr_country) > 0)
        @foreach($arr_country as $k => $v)
            <optgroup label="{{$k}}">
                @foreach($v as $key => $value)
                    <option @if(isset($location_info['home_dropoff_location_rt']) && $location_info['home_dropoff_location_rt'] == $value['id']) selected @endif value="{{$value['id']}}" location_ids="{{$value['id']}}" home_drop_off_city_ids="{{$value['v_drop_off_city_cant_be']}}" home_drop_off_city_must_be_ids="{{$value['v_drop_off_city_must_be']}}" service_area="{{$value['i_service_area_id']}}">{{$value['v_city']}}</option>
                @endforeach
            </optgroup>
        @endforeach
    @endif
@elseif(isset($inputs) && $inputs['tab'] == 'location_pickup_rt')
    <option value="">Pickup Location</option>
    @if(count($arr_country) > 0)
        @foreach($arr_country as $k => $v)
        <optgroup label="{{$k}}">
            @foreach($v as $key => $value)
                <option @if(isset($location_info['home_pickup_location_rt']) && $location_info['home_pickup_location_rt'] == $value['id']) selected @endif value="{{$value['id']}}" location_ids="{{$value['id']}}" home_drop_off_city_ids="{{$value['v_drop_off_city_cant_be']}}" home_drop_off_city_must_be_ids="{{$value['v_drop_off_city_must_be']}}" service_area="{{$value['i_service_area_id']}}">{{$value['v_city']}}</option>
            @endforeach
        </optgroup>
        @endforeach
    @endif
@elseif(isset($inputs) && $inputs['tab'] == 'select-lineruns')
    @if(count($arr_country) > 0)
        @foreach($arr_country as $k => $v)
        <optgroup label="{{$k}}">
            @foreach($v as $key => $value)
                <option value="{{$value['id']}}" location_ids="{{$value['id']}}" service_area="{{$value['i_service_area_id']}}" @if(isset($location_info['home_dropoff_location']) && $location_info['home_dropoff_location'] == $value['id']) selected @endif>{{$value['v_city']}}</option>                
            @endforeach
            </optgroup>
        @endforeach
    @endif
@elseif(isset($inputs) && $inputs['tab'] == 'select-lineruns-to-rt')
    @if(count($arr_country) > 0)
        @foreach($arr_country as $k => $v)
        <optgroup label="{{$k}}">
            @foreach($v as $key => $value)
                <option location_ids="{{$value['id']}}" service_area="{{$value['i_service_area_id']}}" @if(isset($location_info['home_dropoff_location_rt']) && $location_info['home_dropoff_location_rt'] == $value['id']) selected @endif value="{{$value['id']}}">{{$value['v_city']}}</option>
                
            @endforeach
            </optgroup>
        @endforeach
    @endif
@elseif(isset($inputs) && $inputs['tab'] == 'select-lineruns-from-rt')
    @if(count($arr_country) > 0)
        @foreach($arr_country as $k => $v)
        <optgroup label="{{$k}}">
            @foreach($v as $key => $value)
                <option location_ids="{{$value['id']}}" service_area="{{$value['i_service_area_id']}}" @if(isset($location_info['home_pickup_location_rt']) && $location_info['home_pickup_location_rt'] == $value['id']) selected @endif value="{{$value['id']}}">{{$value['v_city']}}</option>
                
            @endforeach
            </optgroup>
        @endforeach
    @endif
@else
    @if(count($arr_country) > 0)
        @foreach($arr_country as $k => $v)
        <optgroup label="{{$k}}">
            @foreach($v as $key => $value)
                <option value="{{$value['id']}}" location_ids="{{$value['id']}}" service_area="{{$value['i_service_area_id']}}" @if(isset($location_info['home_dropoff_location']) && $location_info['home_dropoff_location'] == $value['id']) selected @endif>{{$value['v_city']}}</option>
                
            @endforeach
            </optgroup>
        @endforeach
    @endif
@endif