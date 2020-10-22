<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\GeoPoint;
use App\Models\GeoServiceArea;
use App\Models\GeoPointType;
use App\Models\GeoCities;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Mail, Session, Redirect, Validator, DB, Hash;
use Illuminate\Validation\Rule;

class LocationController extends BaseController {

    public function getIndex() {
        $service_area = GeoServiceArea::select('id', 'v_area_label')->get()->ToArray();
        $city_list = GeoCities::where('e_status', 'Active')->select('id', 'v_city')->get()->ToArray();
        $county_list = GeoCities::where('e_status', 'Active')->select('v_county')->groupBy('v_county')->get()->ToArray();
        return View('backend.location.index', array('title' => "Location", 'service_area' => $service_area, 'city_list' => $city_list, 'county_list' => $county_list));
    }

    public function anyListAjax(Request $request) {
        $data = $request->all();

        $sortColumn = array('','i_service_area_id', 'v_label', 'v_street1', 'v_city', 'v_county', 'v_postal_code', 'e_service_type');
        $query = GeoPoint::with(['GeoServiceArea', 'GeoCities' => function($q) {
            $q->where('e_status', 'Active')->select('id', 'v_city', 'v_county');
        }]);

        if (isset($data['i_service_area_id']) && $data['i_service_area_id'] != '') {
            $query = $query->WhereHas('GeoServiceArea', function($q) use($data){
                $q->where('geo_service_area.id', '=', $data['i_service_area_id']);
            });
        }
        if (isset($data['v_label']) && $data['v_label'] != '') {
            $query = $query->where('v_label', 'LIKE', '%' . $data['v_label'] . '%');
        }
        if (isset($data['v_street1']) && $data['v_street1'] != '') {
            $query = $query->where('v_street1', 'LIKE', '%' . $data['v_street1'] . '%');
        }
        if (isset($data['v_city']) && $data['v_city'] != '') {
            // $query = $query->where('v_city', 'LIKE', '%' . $data['v_city'] . '%');
            $query = $query->WhereHas('GeoCities', function($q) use($data){
                $q->where('geo_cities.id', '=', $data['v_city']);
            });
        }
        if (isset($data['v_county']) && $data['v_county'] != '') {
            // $query = $query->where('v_county', 'LIKE', '%' . $data['v_county'] . '%');
            $query = $query->WhereHas('GeoCities', function($q) use($data){
                $q->where('geo_cities.v_county', 'LIKE', '%' . $data['v_county'] . '%');
            });
        }
        if (isset($data['v_postal_code']) && $data['v_postal_code'] != '') {
            $query = $query->where('v_postal_code', 'LIKE', '%' . $data['v_postal_code'] . '%');
        }
        if (isset($data['e_service_type']) && $data['e_service_type'] != '') {
            $query = $query->where('e_service_type', 'LIKE', '%' . $data['e_service_type'] . '%');
        }

        $rec_per_page = REC_PER_PAGE;
        if (isset($data['length'])) {
            if ($data['length'] == '-1') {
                $rec_per_page = '';
            } else {
                $rec_per_page = $data['length'];
            }
        }

        $sort_order = $data['order']['0']['dir'];
        $order_field = $sortColumn[$data['order']['0']['column']];
        if ($sort_order != '' && $order_field != '') {
            if($order_field == 'v_area_label') {
                $query = $query->join('geo_service_area','geo_service_area.id','=','i_service_area_id')->orderBy('geo_service_area.v_firstname',$sort_order);
            } else if($order_field == 'v_city') {
                $query = $query->join('geo_cities','geo_cities.id','=','i_city_id')->orderBy('geo_cities.v_city',$sort_order);
            } else if($order_field == 'v_county') {
                $query = $query->join('geo_cities','geo_cities.id','=','i_city_id')->orderBy('geo_cities.v_county',$sort_order);
            } else {
                $query = $query->orderBy($order_field, $sort_order);
            }
        } else {
            $query = $query->orderBy('v_label', 'desc');
        }
        $users = $query->paginate($rec_per_page);
        ;
        $arrUsers = $users->toArray();
        $data = array();
        // pr($arrUsers['data']); exit;
        foreach ($arrUsers['data'] as $key => $val) {
            $index = 0;
            if(isset($this->permission) && isset($this->permission[21]['i_delete']) && $this->permission[21]['i_delete'] == 1) {
                $data[$key][$index++] = '<label class="kt-checkbox kt-checkbox--single kt-checkbox--solid "><input type="checkbox" name="id[]" value="' . $val['id'] . '" class="kt-checkable"><span></span></label>';
            }
           
            $data[$key][$index++] = $val['geo_service_area']['v_area_label'];
            $data[$key][$index++] = $val['v_label'];
            $data[$key][$index++] = $val['v_street1'];
            $data[$key][$index++] = $val['geo_cities']['v_city'];
            $data[$key][$index++] = $val['geo_cities']['v_county'];
            $data[$key][$index++] = $val['v_postal_code'];
            $data[$key][$index++] = $val['e_service_type'];
            $action = '';

            if(isset($this->permission) && isset($this->permission[21]['i_add_edit']) && $this->permission[21]['i_add_edit'] == 1) {
                $action = '<a class="btn btn-sm btn-clean btn-icon btn-icon-lg" rel="' . $val['id'] . '" href="' . ADMIN_URL . 'location/edit/' . $val['id'] . '" title="edit"><i class="la la-edit"></i> </a>';
            }

            if(isset($this->permission) &&  isset($this->permission[21]['i_delete']) && $this->permission[21]['i_delete'] == 1) {
                $action .= '<a title="Delete" id="delete_record" class="btn btn-sm btn-clean btn-icon btn-icon-lg delete_record" href="javascript:;" rel="' . $val['id'] . '" delete-url="' . ADMIN_URL . 'location/delete/' . $val['id'] . '"><i class="la la-trash"></i></a>';
            }

            if(isset($this->permission) &&  isset($this->permission[21]['i_list']) && $this->permission[21]['i_list'] == 1) {
                $action .= '<a title="View" id="view_record" class="btn btn-sm btn-clean btn-icon btn-icon-lg view_record" rel="' . $val['id'] . '" href="' . ADMIN_URL . 'location/view/' . $val['id'] . '"><i class="la la-eye"></i></a>';
            }

            $data[$key][$index++] = $action;
        }
        $return_data['data'] = $data;
        $return_data['iTotalRecords'] = $arrUsers['total'];
        $return_data['iTotalDisplayRecords'] = $arrUsers['total'];
        $return_data['data_array'] = $arrUsers['data'];
        return $return_data;
    }

    public function anyAdd(Request $request) {

        $inputs = $request->all();

        $record_service_area = GeoServiceArea::select('id', 'v_area_label')->orderBy('v_area_label')->get()->toArray();
        $record_point_type = GeoPointType::select('id', 'v_label')->orderBy('v_label')->get()->toArray();
        $city_list = GeoCities::where('e_status', 'Active')->select('id', 'v_city')->get()->ToArray();
        $county_list = GeoCities::where('e_status', 'Active')->select('v_county')->groupBy('v_county')->get()->ToArray();

        if ($inputs) {
            // pr($inputs); exit;
                $validator = Validator::make($request->all(), [
                    'i_service_area_id' => 'required',
                    'i_point_type_id' => 'required',
                    'v_label' => 'required',
                    'v_street1' => 'required',
                    'i_city_id' => 'required',
                    // 'v_county' => 'required',
                    'v_postal_code' => 'required',
                    'e_service_type' => 'required',  
                ]);

                if ($validator->fails()) {
                    return json_encode($validator->errors());
                } else {
                    $record = new GeoPoint;
                    $record->i_service_area_id = trim($inputs['i_service_area_id']);
                    $record->i_point_type_id = trim($inputs['i_point_type_id']);
                    $record->v_label = trim($inputs['v_label']);
                    $record->v_street1 = trim($inputs['v_street1']);
                    $record->i_city_id = trim($inputs['i_city_id']);
                    $record->v_postal_code = trim($inputs['v_postal_code']);
                    $record->e_service_type = trim($inputs['e_service_type']);

                    $get_lat_lng = array();
                    $address = $inputs['v_street1'].' '.$inputs['i_city_id'].' '.$inputs['v_postal_code'];
                    if($address != '') {			
                        $get_lat_lng = $this->fetchLatLng($inputs, $address);	
                    }
                    $record->d_geo_lat = $get_lat_lng['latitude'];
                    $record->d_geo_lon = $get_lat_lng['longitude'];

                    // pr($record); exit;
                    if ($record->save()) {
                        Session::flash('success-message', 'Location added successfully.');
                        return '';
                    }
                }
        } else {
            return View('backend.location.add', array('title' => "Add Location", 'record_service_area' => $record_service_area, 'record_point_type' => $record_point_type, 'city_list' => $city_list, 'county_list' => $county_list));
        }
    }

    public function anyEdit(Request $request, $id) {
        // pr($id); exit;
        $inputs = $request->all();
        $record = GeoPoint::find($id);
        $record_service_area = GeoServiceArea::select('id', 'v_area_label')->orderBy('v_area_label')->get()->toArray();
        $record_point_type = GeoPointType::select('id', 'v_label')->orderBy('v_label')->get()->toArray();

        if ($inputs) {
            // pr($inputs); exit;
            
                $validator = Validator::make($request->all(), [
                    'i_service_area_id' => 'required',
                    'i_point_type_id' => 'required',
                    'v_label' => 'required',
                    'v_street1' => 'required',
                    'v_city' => 'required',
                    'v_country' => 'required',
                    'v_postal_code' => 'required',
                    'e_service_type' => 'required',  
                ]);

                if ($validator->fails()) {
                    return json_encode($validator->errors());
                } else {
                    
                    $record->i_service_area_id = trim($inputs['i_service_area_id']);
                    $record->i_point_type_id = trim($inputs['i_point_type_id']);
                    $record->v_label = trim($inputs['v_label']);
                    $record->v_street1 = trim($inputs['v_street1']);
                    $record->v_city = trim($inputs['v_city']);
                    $record->v_country = trim($inputs['v_country']);
                    $record->v_postal_code = trim($inputs['v_postal_code']);
                    $record->e_service_type = trim($inputs['e_service_type']);

                    $get_lat_lng = array();
                    $address = $inputs['v_street1'].' '.$inputs['v_city'].' '.$inputs['v_country'].' '.$inputs['v_postal_code'];
                    if($address != '') {			
                        $get_lat_lng = $this->fetchLatLng($inputs, $address);	
                    }
                    $record->d_geo_lat = $get_lat_lng['latitude'];
                    $record->d_geo_lon = $get_lat_lng['longitude'];

                    // pr($record); exit;
                    if ($record->save()) {
                        Session::flash('success-message', 'Location edited successfully.');
                        return '';
                    }
                }
        } else {
            return View('backend.location.edit', array('title' => "Edit Location", 'record_service_area' => $record_service_area, 'record_point_type' => $record_point_type, 'record' => $record));
        }
    }

    public function anyView($id) {
        $record = GeoPoint::with('GeoServiceArea', 'GeoPointType')->find($id)->toArray();
        // pr($record); exit;
        
        return View('backend.location.view', array('title' => 'View Reservations', 'record' => $record));
    }

    public function getDelete($id) {
        $record = GeoPoint::find($id);
        if (!empty($record)) {
            if ($record->delete()) {
                return 'TRUE';
            } else {
                return 'FALSE';
            }
        } else {
            return 'FALSE';
        }
    }

    public function postBulkAction(Request $request) {
        $data = $request->all();
        if (count($data) > 0) {
            if ($data['action'] == 'Delete') {
                $user_data = GeoPoint::whereIn('id', array_values($data['ids']))->get();
                if ($user_data) {
                    foreach ($user_data as $data) {

                        $data->save();
                        $data->delete();
                    }
                    return 'TRUE';
                } else {
                    return 'FALSE';
                }
            }
        }
    }

    public function fetchLatLng($request, $address = '') {		
		
        $input = $request;	
        $inputedfullAddress = '';
		if($address != '') {
			$inputedfullAddress = strtolower($address);
        }		
       
		$return_array = array();
		if(isset($input) && count($input)) {
			$full_address = strtolower($inputedfullAddress);
			if($full_address != '') {
                $fullAddress = urlencode($full_address);
                $CacheGooglemaps = GeoPoint::Select('d_geo_lat','d_geo_lon')->first();
                
				if(($CacheGooglemaps->d_geo_lat != '' && $CacheGooglemaps->d_geo_lon != '')){
                    $latitude = $CacheGooglemaps->d_geo_lat;
					$longitude = $CacheGooglemaps->d_geo_lon;
					$return_array['latitude'] = $latitude;
					$return_array['longitude'] = $longitude;
				} else {	
                    			
					// &key='.GOOGLE_KEY
					//  $url = 'https://maps.google.com/maps/api/geocode/xml?address=' . $fullAddress . '&sensor=false&
					// key='.GOOGLE_KEY_PICKER; 
					$url = 'https://maps.google.com/maps/api/geocode/xml?address=' . $fullAddress . '&key=AIzaSyBc56olF_nFukXoWaumWyT8iy-s5CUe4eA';
					$xml = simplexml_load_file($url);	
                    $arr = json_decode( json_encode($xml) , 1);		
                    		
					if($arr['status'] == 'OK'){																			
						if(isset($arr['result'][0])) {						
							$latitude = $arr['result'][0]['geometry']['location']['lat'];					
							$longitude = $arr['result'][0]['geometry']['location']['lng'];									
						} else {						
							$latitude = $arr['result']['geometry']['location']['lat'];					
							$longitude = $arr['result']['geometry']['location']['lng'];									
						}
						
						$google_map_obj = new CustomerAddresses();
						$google_map_obj->d_geo_lat = $latitude;
						$google_map_obj->d_geo_lon = $longitude;
						$return_array['latitude'] = $latitude;
						$return_array['longitude'] = $longitude;					
					}
				}			
			} else {
				$return_array['latitude'] = '';
				$return_array['longitude'] = '';
			}
        }		
        // pr($return_array); exit;			
		return $return_array;
    }
}
