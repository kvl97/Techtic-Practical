
@if(isset($inputs) && $inputs['tab'] == 'location')

<select id="reservation_from_dropoff_location" class="coll_exp_outgroup location_select required" placeholder="Drop Off Location" name="reservation_from_dropoff_location">
    <option value="">Drop Off Location</option>
    @foreach($arr_country as $k=>$v)
        <optgroup label="{{$k}}">
            @foreach($v as $key => $value)
               
                <option @if(isset($location_info['drop_location']) && $location_info['drop_location']==$value['v_city']) selected @endif location_ids="{{$value['id']}}" home_drop_off_city_ids="{{$value['v_drop_off_city_cant_be']}}" home_drop_off_city_must_be_ids="{{$value['v_drop_off_city_must_be']}}" service_area="{{$value['i_service_area_id']}}">{{$value['v_city']}}</option>
            @endforeach
        </optgroup>
    @endforeach
</select>

@else

<label class="label-select">To</label>
<select class="form-control coll_exp_outgroup location_select required" placeholder="Drop Off Location" name="drop_location" id="some">
    @foreach($arr_country as $k=>$v)
    <optgroup label="{{$k}}">
        @foreach($v as $key => $value)
            <option location_ids="{{$value['id']}}" service_area_rt="{{$value['i_service_area_id']}}" <?php if(isset($location_info['drop_location']) && $value['id'] == $location_info['drop_location']){ echo 'selected';} ?>>{{$value['v_city']}}</option>
            
        @endforeach
        </optgroup>
    @endforeach
</select>

@endif