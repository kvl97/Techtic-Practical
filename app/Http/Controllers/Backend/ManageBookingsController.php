<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Admin;
use App\Models\LineRun;
use App\Models\Reservations;
use App\Models\FleetManager;
use App\Models\ReservationLeg;
use App\Models\SystemSettings;
use App\Models\EmailTemplate;
use Carbon\Carbon;
use Mail, Session, Redirect, Validator, DB, Hash;

class ManageBookingsController extends BaseController {

    public function getIndex(Request $request) {
        $inputs = $request->all();
        $today = date('Y-m-d');
        
        $systemSettings = SystemSettings::first();
        $recordColor = (array)json_decode($systemSettings['kiosk_params']);
        foreach ($recordColor['colors'] as $key => $value) {
            $first_param = array('r','g','b');
            $second_param = str_split(ltrim($value,'#'),2);
            $arrHexCode = array_combine($first_param, $second_param);
            if(((hexdec($arrHexCode['r']) * 0.299) + (hexdec($arrHexCode['g'])*0.587) + (hexdec($arrHexCode['b'])*0.114)) > 186 ){
                $strTextColor[] = '#000000'; #dark text;
            } else {
                $strTextColor[] = '#ffffff'; #light text;
            }
        }    

        if($inputs) {
            if(isset($inputs['d_run_date']) && $inputs['d_run_date'] != '') {
                $unassignedCount = $this->unassignedReservationCount(date('Y-m-d', strtotime($inputs['d_run_date'])));
                $arrLineRuns = LineRun::select('linerun.id', 'linerun.d_run_date', 'linerun.i_origin_service_area_id', 'linerun.i_dest_service_area_id', 'linerun.t_scheduled_arr_time', 'linerun.e_run_status', 'linerun.i_vehicle_id', 'linerun.i_driver_id', 'linerun.e_service_type', 'linerun.i_num_available', 'linerun.i_num_total', DB::raw('IFNULL((select sum(i_total_num_passengers) from reservations as res inner join reservation_leg res_leg on res.id=res_leg.i_reservation_id where (res.e_reservation_status="Booked" OR res.e_reservation_status="Pending Payment") and res.deleted_at is null and res_leg.deleted_at is null and res_leg.i_run_id = linerun.id),0) as i_num_booked_seats'), 'admin.id as driver_id', 'admin.v_dispatch_name')->where(DB::raw('DATE(d_run_date)'), date('Y-m-d', strtotime($inputs['d_run_date'])))->with(['GeoOriginServiceArea' => function($q) {
                    $q->select('id', 'v_area_label');
                }, 'GeoDestServiceArea' => function($q1) {
                    $q1->select('id', 'v_area_label');
                }, 'VehicleFleet' => function($q2) {
                    $q2->select('id', 'v_vehicle_code');
                }])->join('admin','admin.id','=','linerun.i_driver_id')->whereHas('VehicleFleet')->orderBy('t_scheduled_arr_time', 'ASC')->orderBy('v_dispatch_name', 'ASC')->get()->toArray();
                
                return View('backend.bookings.search_data', array('lineRuns' => $arrLineRuns, 'recordColor' => $recordColor, 'strTextColor' => $strTextColor, 'unassignedCount' => $unassignedCount));
            }
        }
        
        $arrDrivers = Admin::where(['i_role_id' => 6, 'e_status' => 'Active'])->orderBy('v_dispatch_name')->pluck('v_dispatch_name', 'id')->toArray();
        $arrVehicles = FleetManager::where(['e_vehicle_status' => 'Ready'])->orderBy('v_vehicle_code')->pluck('v_vehicle_code', 'id')->toArray();
        
        return View('backend.bookings.index', array('title' => 'Dispatch', 'today' => $today, 'arrDrivers' => $arrDrivers, 'arrVehicles' => $arrVehicles));
    }

    public function unassignedReservationCount($date) {
        $unassignedCount = Reservations::with(['PickupCity', 'DropOffCity','ReservAtionLeg'])->where(DB::raw('DATE(d_travel_date)'), $date)->whereIn('e_reservation_status', array('Hold', 'Callback'))->orWhere(function($q) use($date) {
            $q->whereIN('e_reservation_status', array('Booked', 'Request Confirmed'))->where('e_shuttle_type', 'Private')->where(DB::raw('DATE(d_travel_date)'), $date);
        })->count();

        return $unassignedCount;
    }

    public function getReservationsData(Request $request) {
        
        $inputs = $request->all();
        if(isset($inputs['lineRunId']) && $inputs['lineRunId'] != '') {
            $lineRunId = $inputs['lineRunId'];
            $arrReservations = Reservations::with(['PickupCity', 'DropOffCity','ReservAtionLeg' => function($q) use($lineRunId) {
                $q->where('i_run_id', $lineRunId);
            }])->whereHas('ReservAtionLeg', function($q) use($lineRunId) {
                $q->where('i_run_id', $lineRunId);
            })->whereIn('e_reservation_status', array('Booked', 'Pending Payment'))->get()->toArray();
            
            return View('backend.bookings.reservations_data', array('reservations' => $arrReservations));
        }
    }

    public function getunassignedReservationsData(Request $request) {
        
        $inputs = $request->all();
        $date = date('Y-m-d', strtotime($inputs['date']));
        $arrReservations = Reservations::with(['PickupCity', 'DropOffCity','ReservAtionLeg'])->where(DB::raw('DATE(d_travel_date)'), $date)->whereIn('e_reservation_status', array('Hold', 'Callback'))->orWhere(function($q) use($date) {
            $q->whereIN('e_reservation_status', array('Booked', 'Request Confirmed'))->where('e_shuttle_type', 'Private')->where(DB::raw('DATE(d_travel_date)'), $date);
        })->get()->toArray();
        
        return View('backend.bookings.reservations_data', array('reservations' => $arrReservations));
    }


    public function anyAssignLineRun(Request $request) {
        $inputs = $request->all();
        $conFirmLinerun = ReservationLeg::where('i_reservation_id',$inputs['reservation_id'])->first();
        $reservation_record = Reservations::find($inputs['reservation_id']);
        
        if(empty($conFirmLinerun)) {
            $conFirmLinerun = new ReservationLeg;
            $conFirmLinerun->created_at = Carbon::now();
            $conFirmLinerun->i_reservation_id = $inputs['reservation_id'];
            $conFirmLinerun->e_status = 'Quote';
            $conFirmLinerun->d_travel_date = $reservation_record['d_travel_date'];
        } 
        $conFirmLinerun->i_run_id = $inputs['line_run_id'];                
        $conFirmLinerun->save(); 
        
        if($reservation_record->e_reservation_status == 'Hold') {
            $reservation_record->e_reservation_status = 'Booked';
            $reservation_record->save();
        } else if($reservation_record->e_reservation_status == 'Callback') {
            $reservation_record->e_reservation_status = 'Pending Payment';
            $reservation_record->save();
        } else if($reservation_record->e_reservation_status == 'Request Confirmed') {
            $reservation_record->e_reservation_status = 'Pending Payment';
            $reservation_record->save();
        }
        $systemSettings = SystemSettings::first();
        $recordColor = (array)json_decode($systemSettings['kiosk_params']);
        foreach ($recordColor['colors'] as $key => $value) {
            $first_param = array('r','g','b');
            $second_param = str_split(ltrim($value,'#'),2);
            $arrHexCode = array_combine($first_param, $second_param);
            if(((hexdec($arrHexCode['r']) * 0.299) + (hexdec($arrHexCode['g'])*0.587) + (hexdec($arrHexCode['b'])*0.114)) > 186 ){
                $strTextColor[] = '#000000'; #dark text;
            } else {
                $strTextColor[] = '#ffffff'; #light text;
            }
        } 

        $arrLineRuns = LineRun::select('linerun.id', 'linerun.d_run_date', 'linerun.i_origin_service_area_id', 'linerun.i_dest_service_area_id', 'linerun.t_scheduled_arr_time', 'linerun.e_run_status', 'linerun.i_vehicle_id', 'linerun.i_driver_id', 'linerun.e_service_type', 'linerun.i_num_available', 'linerun.i_num_total', DB::raw('IFNULL((select sum(i_total_num_passengers) from reservations as res inner join reservation_leg res_leg on res.id=res_leg.i_reservation_id where (res.e_reservation_status="Booked" OR res.e_reservation_status="Pending Payment") and res.deleted_at is null and res_leg.deleted_at is null and res_leg.i_run_id = linerun.id),0) as i_num_booked_seats'), 'admin.id as driver_id', 'admin.v_dispatch_name')->where(DB::raw('DATE(d_run_date)'), date('Y-m-d', strtotime($inputs['selected_date'])))->with(['GeoOriginServiceArea' => function($q) {
            $q->select('id', 'v_area_label');
        }, 'GeoDestServiceArea' => function($q1) {
            $q1->select('id', 'v_area_label');
        }, 'VehicleFleet' => function($q2) {
            $q2->select('id', 'v_vehicle_code');
        }])->join('admin','admin.id','=','linerun.i_driver_id')->whereHas('VehicleFleet')->orderBy('t_scheduled_arr_time', 'ASC')->orderBy('v_dispatch_name', 'ASC')->get()->toArray();

        $unassignedCount = $this->unassignedReservationCount(date('Y-m-d', strtotime($inputs['selected_date'])));

        $lineRunData = View('backend.bookings.search_data', array('lineRuns' => $arrLineRuns, 'recordColor' => $recordColor, 'strTextColor' => $strTextColor, 'unassignedCount' => $unassignedCount))->render();

        
        return response()->json([
            'status' => 'TRUE',
            'lineRunData' => $lineRunData,
        ]);
    }

    public function anyTargetPickupTime(Request $request) {
        $inputs = $request->all();
        $reservation_record = Reservations::find($inputs['reservationId']);
        $reservation_record->t_target_time = date("G:i", strtotime($inputs['targetTime']));
        if($reservation_record->save()) {
            $travelWindowText = ($inputs['switchType'] == 'Pick') ? date('H:i' , strtotime($reservation_record['t_comfortable_time'])).' - '.date('H:i' , strtotime($reservation_record['t_target_time'])).' ARR '. date('H:i' , strtotime($reservation_record['t_flight_time'])) : date('H:i' , strtotime($reservation_record['t_target_time'])).' - '.date('H:i' , strtotime($reservation_record['t_comfortable_time'])).' DEP '.date('H:i' , strtotime($reservation_record['t_flight_time']));
            $travelWindowText .= (($inputs['switchText'] == 'Amtrak') ? ' Train' : (($inputs['switchText'] == 'Greyhound') ? ' Bus' : ' '.$inputs['switchText']));
            return response()->json([
                'status' => 'TRUE',
                'targetTime' => date('H:i:s', strtotime($reservation_record->t_target_time)),
                'travelWindowText' => $travelWindowText,
            ]);
        } else {
            return response()->json([
                'status' => 'FALSE'
            ]);
        }
    }

    public function anyLineRunUpdate(Request $request) {
        $inputs = $request->all();
        $lineRun = LineRun::find($inputs['lineRunId']);
        $lineRun->e_run_status = $inputs['status'];
        $lineRun->i_driver_id = $inputs['driver'];
        $lineRun->i_vehicle_id = $inputs['vehicle'];
        $lineRun->t_scheduled_arr_time = date("G:i", strtotime($inputs['targetTime']));
        if($lineRun->save()) {
            if($inputs['status'] == 'Set') {

                $setLineRun = LineRun::where('linerun.id', $inputs['lineRunId'])->select('linerun.id', 'linerun.d_run_date', 'linerun.i_origin_service_area_id', 'linerun.i_dest_service_area_id', 'linerun.t_scheduled_arr_time', 'linerun.i_vehicle_id', 'linerun.i_driver_id', 'linerun.e_service_type', DB::raw('IFNULL((select sum(i_total_num_passengers) from reservations as res inner join reservation_leg res_leg on res.id = res_leg.i_reservation_id where res.deleted_at is null and res_leg.deleted_at is null and res_leg.i_run_id = linerun.id),0) as i_num_booked_seats'), 'admin.id as driver_id', 'admin.v_dispatch_name', 'admin.v_email')->with(['GeoOriginServiceArea' => function($q) {
                    $q->select('id', 'v_area_label');
                }, 'GeoDestServiceArea' => function($q1) {
                    $q1->select('id', 'v_area_label');
                }, 'VehicleFleet' => function($q2) {
                    $q2->select('id', 'v_vehicle_code');
                }])->join('admin','admin.id','=','linerun.i_driver_id')->first()->toArray();


                //Send email to driver
                $objEmailTemplate = EmailTemplate::find(13)->toArray();
                $strTemplate = $objEmailTemplate['t_email_content'];
                $strTemplate = str_replace('[SITE_NAME]',SITE_NAME,$strTemplate);
                $strTemplate = str_replace('[SITE_URL]',SITE_URL,$strTemplate);
                $strTemplate = str_replace('[NAME]',$setLineRun['v_dispatch_name'],$strTemplate);
                $strTemplate = str_replace('[DATE]',date('m/d/Y' , strtotime($setLineRun['d_run_date'])),$strTemplate);
                $strTemplate = str_replace('[TIME]',date('g:i A' , strtotime($setLineRun['t_scheduled_arr_time'])),$strTemplate);
                $strTemplate = str_replace('[VEHICLE]',$setLineRun['vehicle_fleet']['v_vehicle_code'],$strTemplate);
                $strTemplate = str_replace('[ORIGIN]',$setLineRun['geo_origin_service_area']['v_area_label'],$strTemplate);
                $strTemplate = str_replace('[TARGET]',$setLineRun['geo_dest_service_area']['v_area_label'],$strTemplate);
                $strTemplate = str_replace('[SERVICE]',$setLineRun['e_service_type'],$strTemplate);
                $strTemplate = str_replace('[TOTAL]',$setLineRun['i_num_booked_seats'],$strTemplate);
                $strTemplate = str_replace('[MANIFEST_URL]',ADMIN_URL.'rocket-manifest',$strTemplate);
                
                $subject = $objEmailTemplate['v_template_subject'];
                //$emailId = $setLineRun['v_email'];
                $emailId = 'testing.demo@gmail.com';
                // mail sent to user with new link
                Mail::send('emails.auth.generate-email-template', array('strTemplate' => $strTemplate), function($message) use ($emailId, $subject)
                {
                    $message->from(CONTACT_EMAIL_ID,SITE_NAME);
                    $message->to($emailId);
                    $message->replyTo(CONTACT_EMAIL_ID);
                    $message->subject($subject);
                });

                //Send email to customers
                $lineRunId = $inputs['lineRunId'];
                $reservations = Reservations::with(['Customers', 'PickupCity', 'DropOffCity','ReservAtionLeg' => function($q) use($lineRunId) {
                    $q->where('i_run_id', $lineRunId);
                }])->whereHas('ReservAtionLeg', function($q) use($lineRunId) {
                    $q->where('i_run_id', $lineRunId);
                })->get()->toArray();
                if(count($reservations) > 0) {
                    $objEmailTemplate = EmailTemplate::find(14)->toArray();
                    $strTemplate = $objEmailTemplate['t_email_content'];
                    $strTemplate = str_replace('[SITE_NAME]',SITE_NAME,$strTemplate);
                    $strTemplate = str_replace('[SITE_URL]',SITE_URL,$strTemplate);
                                        
                    $strTemplate = str_replace('[DATE]',date('m/d/Y' , strtotime($setLineRun['d_run_date'])),$strTemplate);
                    $strTemplate = str_replace('[VEHICLE]',$setLineRun['vehicle_fleet']['v_vehicle_code'],$strTemplate);
                    $strTemplate = str_replace('[DRIVER]',$setLineRun['v_dispatch_name'],$strTemplate);
                    
                    $subject = $objEmailTemplate['v_template_subject'];
                    foreach($reservations as $k => $v) {
                        $sendTemplate = $strTemplate;
                        $sendTemplate = str_replace('[NAME]',$v['v_contact_name'],$sendTemplate);
                        $sendTemplate = str_replace('[PICKUP_LOCATION]',$v['pickup_city']['v_city'].' ('.$v['pickup_city']['v_county'].')',$sendTemplate);
                        $sendTemplate = str_replace('[DROPOFF_LOCATION]',$v['drop_off_city']['v_city'].' ('.$v['drop_off_city']['v_county'].')',$sendTemplate);
                        $sendTemplate = str_replace('[TRIP_TYPE]',($v['e_class_type'] == 'RT' ? 'Round Trip' : 'One Way'),$sendTemplate);
                        $sendTemplate = str_replace('[TOTAL]',$v['i_total_num_passengers'],$sendTemplate);
                        $sendTemplate = str_replace('[PICKUP_TIME]',date('g:i A', strtotime($v['t_comfortable_time'])),$sendTemplate);
                    
                        //$emailId = $v['v_contact_email'];
                        $emailId = 'testing.demo@gmail.com';
                        $customer_email = $v['customers']['v_email'];
                        Mail::send('emails.auth.generate-email-template', array('strTemplate' => $sendTemplate), function($message) use ($subject,$emailId,$customer_email) {
                            $message->from(CONTACT_EMAIL_ID, SITE_NAME);
                            $message->to($emailId);
                            if($customer_email != $emailId) {			
                                $message->replyTo($customer_email);	
                            }
                            $message->subject($subject);
                        });
                    }
                }
            }

            return response()->json([
                'status' => 'TRUE',
                'lineRunStatus' => $lineRun->e_run_status,
                'lineRunDriver' => $lineRun->i_driver_id,
                'lineRunVehicle' => $lineRun->i_vehicle_id,
                'lineRunTime' => date('H:i:s', strtotime($lineRun->t_scheduled_arr_time)),
            ]);
        } else {
            return response()->json([
                'status' => 'FALSE'
            ]);
        }
    }
}

