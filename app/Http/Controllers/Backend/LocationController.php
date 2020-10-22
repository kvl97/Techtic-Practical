<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\GeoPoint;
use App\Models\GeoServiceArea;
use App\Models\GeoPointType;
use App\Models\GeoCities;
use App\Models\CustomerAddresses;
use App\Models\SystemSettings;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Mail, Session, Redirect, Validator, DB, Hash;
use Illuminate\Validation\Rule;

class LocationController extends BaseController {

    public function getIndex() {
        $service_area = GeoServiceArea::select('id', 'v_area_label')->where(['e_status' => 'Active'])->orderBy('v_area_label', 'asc')->get()->toArray();
        $city_list = GeoCities::where('e_status', 'Active')->select('id', 'v_city')->orderBy('v_city', 'asc')->get()->toArray();
        $county_list = GeoCities::where('e_status', 'Active')->select('v_county')->groupBy('v_county')->get()->toArray();
        $record_point_type = GeoPointType::select('id', 'v_label')->orderBy('v_label')->get()->toArray();
        return View('backend.location.index', array('title' => "Location", 'service_area' => $service_area, 'city_list' => $city_list, 'county_list' => $county_list, 'record_point_type' => $record_point_type));
    }

    public function anyListAjax(Request $request) {
        $data = $request->all();

        $sortColumn = array('','i_point_type_id', 'i_service_area_id', 'v_label', 'v_street1', 'v_city', 'v_county', 'v_postal_code', 'e_service_type');
        $query = GeoPoint::with(['GeoCities' => function($q) {
            $q->where('e_status', 'Active')->select('id', 'v_city', 'v_county', 'i_service_area_id');
        }, 'GeoCities.GeoServiceArea' => function($q1) {
            $q1->select('geo_service_area.id', 'geo_service_area.v_area_label');
        }, 'GeoPointType'])->select('geo_point.id', 'geo_point.v_label', 'geo_point.v_street1', 'geo_point.v_postal_code', 'geo_point.e_service_type', 'geo_point.i_city_id', 'geo_point.i_point_type_id');
        // pr($query); exit;

        if (isset($data['i_point_type_id']) && $data['i_point_type_id'] != '') {
            $query = $query->WhereHas('GeoPointType', function($q) use($data){
                $q->where('geo_point_types.id', '=', $data['i_point_type_id']);
            });
        }

        if (isset($data['i_service_area_id']) && $data['i_service_area_id'] != '') {
            $query = $query->WhereHas('GeoCities', function($q) use($data){
                $q->where('geo_cities.i_service_area_id', '=', $data['i_service_area_id']);
            });
        }
        if (isset($data['v_label']) && $data['v_label'] != '') {
            $query = $query->where('geo_point.v_label', 'LIKE', '%' . $data['v_label'] . '%');
        }
        if (isset($data['v_street1']) && $data['v_street1'] != '') {
            $query = $query->where('geo_point.v_street1', 'LIKE', '%' . $data['v_street1'] . '%');
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
            if($order_field == 'i_service_area_id') {
                $query = $query->leftjoin('geo_cities', 'geo_cities.id', 'i_city_id')->leftjoin('geo_service_area', 'geo_service_area.id', 'geo_cities.i_service_area_id')->orderBy('geo_service_area.v_area_label',$sort_order);
                /* $query = $query->join('geo_service_area','geo_service_area.id','=','geo_cities.i_service_area_id')->orderBy('geo_service_area.v_area_label',$sort_order); */
            } else if($order_field == 'v_city') {
                $query = $query->join('geo_cities','geo_cities.id','=','i_city_id')->orderBy('geo_cities.v_city',$sort_order);
            } else if($order_field == 'v_county') {
                $query = $query->join('geo_cities','geo_cities.id','=','i_city_id')->orderBy('geo_cities.v_county',$sort_order);
            }  else if($order_field == 'i_point_type_id') {
                $query = $query->join('geo_point_types','geo_point_types.id','=','i_point_type_id')->orderBy('geo_point_types.v_label',$sort_order);
            } else {
                $query = $query->orderBy($order_field, $sort_order);
            }
        } else {
            $query = $query->orderBy('v_label', 'desc');
        }
        $users = $query->paginate($rec_per_page);
        
        $arrUsers = $users->toArray();
        $data = array();
        // pr($arrUsers['data']); exit;
        foreach ($arrUsers['data'] as $key => $val) {
            $index = 0;
            if(isset($this->permission) && isset($this->permission[21]['i_delete']) && $this->permission[21]['i_delete'] == 1) {
                $data[$key][$index++] = '<label class="kt-checkbox kt-checkbox--single kt-checkbox--solid "><input type="checkbox" name="id[]" value="' . $val['id'] . '" class="kt-checkable"><span></span></label>';
            }
            $data[$key][$index++] = ($val['geo_point_type']) ? $val['geo_point_type']['v_label'] : '';
            $data[$key][$index++] = $val['geo_cities']['geo_service_area']['v_area_label'] ;
            $data[$key][$index++] = $val['v_label'];
            $data[$key][$index++] = $val['v_street1'];
            $data[$key][$index++] = isset($val['geo_cities']['v_city']) ? $val['geo_cities']['v_city'] : '';
            $data[$key][$index++] = isset($val['geo_cities']['v_county']) ? $val['geo_cities']['v_county'] : '';
            $data[$key][$index++] = $val['v_postal_code'];
            $data[$key][$index++] = $val['e_service_type'];

            $action = '';
            $action .= '<div class="d-flex">';
            if(isset($this->permission) && isset($this->permission[21]['i_add_edit']) && $this->permission[21]['i_add_edit'] == 1) {
                $action .= '<a class="btn btn-sm btn-clean btn-icon btn-icon-lg" rel="' . $val['id'] . '" href="' . ADMIN_URL . 'location/edit/' . $val['id'] . '" title="edit"><i class="la la-edit"></i> </a>';
            }

            if(isset($this->permission) &&  isset($this->permission[21]['i_delete']) && $this->permission[21]['i_delete'] == 1) {
                $action .= '<a title="Delete" id="delete_record" class="btn btn-sm btn-clean btn-icon btn-icon-lg delete_record" href="javascript:;" rel="' . $val['id'] . '" delete-url="' . ADMIN_URL . 'location/delete/' . $val['id'] . '"><i class="la la-trash"></i></a>';
            }

            /* if(isset($this->permission) &&  isset($this->permission[21]['i_list']) && $this->permission[21]['i_list'] == 1) {
                $action .= '<a title="View" id="view_record" class="btn btn-sm btn-clean btn-icon btn-icon-lg view_record" rel="' . $val['id'] . '" href="' . ADMIN_URL . 'location/view/' . $val['id'] . '"><i class="la la-eye"></i></a>';
            } */
            $action .= '</div>';
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

        $record_service_area = GeoServiceArea::select('id', 'v_area_label')->orderBy('v_area_label')->where(['e_status' => 'Active'])->get()->toArray();
        $record_point_type = GeoPointType::select('id', 'v_label')->orderBy('v_label')->get()->toArray();
        $city_list = GeoCities::where('e_status', 'Active')->select('id', 'v_city')->orderBy('v_city')->get()->toArray();
        $county_list = GeoCities::where('e_status', 'Active')->select('v_county')->groupBy('v_county')->get()->toArray();

        if ($inputs) {
            // pr($inputs); exit;
                $validator = Validator::make($request->all(), [
                    // 'i_service_area_id' => 'required',
                    'i_point_type_id' => 'required',
                    'v_label' => 'required',
                    'v_street1' => 'required',
                    'i_city_id' => 'required',
                    // 'v_county' => 'required',
                    // 'v_postal_code' => 'required',
                    'e_service_type' => 'required',  
                ]);

                if ($validator->fails()) {
                    return json_encode($validator->errors());
                } else {
                    $record = new GeoPoint;
                    // $record->i_service_area_id = trim($inputs['i_service_area_id']);
                    $record->i_point_type_id = isset($inputs['i_point_type_id']) ? trim($inputs['i_point_type_id']) : NULL;
                    $record->v_label = trim($inputs['v_label']);
                    $record->v_street1 = trim($inputs['v_street1']);
                    $record->i_city_id = trim($inputs['i_city_id']);
                    $record->v_postal_code = isset($inputs['v_postal_code']) ? trim($inputs['v_postal_code']) : NULL;
                    $record->e_service_type = trim($inputs['e_service_type']);

                    $get_lat_lng = array();
                    $address = $inputs['v_street1'].' '.$inputs['i_city_id'].' '.$inputs['v_postal_code'];
                    if($address != '') {			
                        $get_lat_lng = $this->fetchLatLng($inputs, $address);	
                    }
                    if($get_lat_lng) {
                        $record->d_geo_lat = $get_lat_lng['latitude'] ;
                        $record->d_geo_lon = $get_lat_lng['longitude'];
                    } else {
                        $record->d_geo_lat = 0;
                        $record->d_geo_lon = 0;
                    }
                    $record->v_google_place_id = isset($get_lat_lng['place_id']) ? $get_lat_lng['place_id'] : NULL;
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
        
        $record_service_area = GeoServiceArea::select('id', 'v_area_label')->orderBy('v_area_label')->where(['e_status' => 'Active'])->get()->toArray();
        $record_point_type = GeoPointType::select('id', 'v_label')->orderBy('v_label')->get()->toArray();
        $city_list = GeoCities::where('e_status', 'Active')->select('id', 'v_city')->orderBy('v_city')->get()->toArray();

        if ($inputs) {
            // pr($inputs); exit;
            
                $validator = Validator::make($request->all(), [
                    // 'i_service_area_id' => 'required',
                    'i_point_type_id' => 'required',
                    'v_label' => 'required',
                    'v_street1' => 'required',
                    'i_city_id' => 'required',
                    // 'v_country' => 'required',
                    // 'v_postal_code' => 'required',
                    'e_service_type' => 'required',  
                ]);

                if ($validator->fails()) {
                    return json_encode($validator->errors());
                } else {
                    
                    // $record->i_service_area_id = trim($inputs['i_service_area_id']);
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
                    if($get_lat_lng) {
                        $record->d_geo_lat = $get_lat_lng['latitude'] ;
                        $record->d_geo_lon = $get_lat_lng['longitude'];
                    } else {
                        $record->d_geo_lat = 0;
                        $record->d_geo_lon = 0;
                    }
                    
                    $record->v_google_place_id = isset($get_lat_lng['place_id']) ? $get_lat_lng['place_id'] : NULL;
                    
                    // pr($record); exit;
                    if ($record->save()) {
                        Session::flash('success-message', 'Location edited successfully.');
                        return '';
                    }
                }
        } else {

            $county_service_data = GeoCities::with(['GeoServiceArea'])->where('id', $record->i_city_id)->where('e_status', 'Active')->select('id', 'v_city', 'v_county', 'i_service_area_id')->get()->toArray();
            return View('backend.location.edit', array('title' => "Edit Location", 'record_service_area' => $record_service_area, 'record_point_type' => $record_point_type, 'record' => $record, 'city_list' => $city_list, 'county_service_data' => $county_service_data));
        }
    }

    public function anyView($id) {
        $record = GeoPoint::with('GeoCities', 'GeoCities.GeoServiceArea', 'GeoPointType')->find($id)->toArray();
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

    public function getCountyServiceAreaName(Request $request) {

        $inputs = $request->all();
        $return_array = array();
        $data = GeoCities::with(['GeoServiceArea'])->where('id', $inputs['id'])->where('e_status', 'Active')->select('v_county', 'i_service_area_id')->get()->toArray();
        // pr($data); exit;
        $return_array['v_county'] = $data[0]['v_county'];
		$return_array['i_service_area_id'] = $data[0]['geo_service_area']['v_area_label'];
        return $return_array;
    }
}
