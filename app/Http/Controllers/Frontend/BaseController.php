<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use DB,Auth,View, DateTime, DateTimeZone;

use Illuminate\Support\Str;
use App\Models\SystemSettings;
use App\Models\Blog;
use App\Models\GeoPoint;
use App\Models\GeoCities;
use App\Models\Reservations;
use App\Models\ReservationLeg;
use App\Models\Transactions;
use App\Models\FareTable;
use App\Models\SystemResCategory;


class BaseController extends Controller {

    public function __construct() {
        // $geo_point_location = GeoCities::where('i_service_area_id','!=',6)->select('id','v_county','v_city','i_service_area_id','v_drop_off_city_cant_be','v_drop_off_city_must_be')->orderBy('i_order', 'ASC')->get()->toArray();

        $geo_point_location = GeoCities::whereIn('i_service_area_id',function($q){
            $q->select('i_origin_service_area_id')
            ->from(with(new FareTable)->getTable());
        })->select('id','v_county','v_city','i_service_area_id','v_drop_off_city_cant_be','v_drop_off_city_must_be')->orderBy('i_order', 'ASC')->where('e_only_private','0')->get()->toArray();
      
        $arr_country = [];
        foreach($geo_point_location as $k => $v) {
            $arr_country[$v['v_county']][] = $v;
        }
        /* $footer_contact_link = CmsPages::where(['e_show_in_footer'=> 'Yes'])->orderby('v_title','asc')->get(); */
        
        $footer_contact = SystemSettings ::first();
        View::share(array('footer_contact'=>$footer_contact,'arr_country'=>$arr_country));
    }

    public function saveImage($base64img, $path) {
        $v_random_image = time() . '-' . str::random(6) . '.png';
        $tmpFile = $v_random_image;
        if (strpos($base64img, 'data:image/jpeg;base64,') !== false) {
            $base64img = str_replace('data:image/jpeg;base64,', '', $base64img);
        }
        if (strpos($base64img, 'data:image/png;base64,') !== false) {
            $base64img = str_replace('data:image/png;base64,', '', $base64img);
        }
        if (strpos($base64img, 'data:image/webp;base64,') !== false) {
            $base64img = str_replace('data:image/webp;base64,', '', $base64img);
        }

        if (strpos($base64img, 'data:image/jpg;base64,') !== false) {
            $base64img = str_replace('data:image/jpg;base64,', '', $base64img);
        }
        if (strpos($base64img, 'data:image/gif;base64,') !== false) {
            $base64img = str_replace('data:image/gif;base64,', '', $base64img);
        }
        $data = base64_decode($base64img);
        $file = $path . $tmpFile;
        file_put_contents($file, $data);

        return $tmpFile;
    }

    /* For Crop Image Start */
    public function crop($file_thumb, $x, $y, $w, $h) {
        $targ_w = $targ_h = 550;
        $original_info = getimagesize($file_thumb);
        $type = $original_info['mime'];

        if ($type == 'image/jpeg' || $type == 'image/jpg') {
            $img_r = imagecreatefromjpeg($file_thumb);
        }
        if ($type == 'image/png') {
            $img_r = imagecreatefrompng($file_thumb);
        }
        if ($type == 'image/gif') {
            $img_r = imagecreatefromgif($file_thumb);
        }

        $dst_r = imagecreatetruecolor($targ_w, $targ_h);

        imagecopyresampled($dst_r, $img_r, 0, 0, intval($x), intval($y), $targ_w, $targ_h, intval($w), intval($h));
        header("Content-type: image/jpg");

        if ($type == 'image/jpeg' || $type == 'image/jpg') {
            imagejpeg($dst_r, $file_thumb);
        }
        if ($type == 'image/png') {
            imagepng($dst_r, $file_thumb);
        }
        if ($type == 'image/gif') {
            imagegif($dst_r, $file_thumb);
        }
    }

    public function saveImageCrop($base64img, $path, $crop_options, $thumbImageWidth = 0, $thumbImageHeight = 0) {
        $imagequality = 100;
        $split = explode('/', $base64img);
        $type = $split[1];
        $type = explode(";", $type);

        $v_random_image = time() . '-' . str_random(6) . '.' . $type[0];
        $tmpFile = $v_random_image;

        $base64imgOri = $base64img;

        if (strpos($base64img, 'data:image/jpeg;base64,') !== false) {
            $base64img = str_replace('data:image/jpeg;base64,', '', $base64img);
        }
        if (strpos($base64img, 'data:image/png;base64,') !== false) {
            $base64img = str_replace('data:image/png;base64,', '', $base64img);
        }
        if (strpos($base64img, 'data:image/webp;base64,') !== false) {
            $base64img = str_replace('data:image/webp;base64,', '', $base64img);
        }

        if (strpos($base64img, 'data:image/jpg;base64,') !== false) {
            $base64img = str_replace('data:image/jpg;base64,', '', $base64img);
        }
        if (strpos($base64img, 'data:image/gif;base64,') !== false) {
            $base64img = str_replace('data:image/gif;base64,', '', $base64img);
        }
        $data = base64_decode($base64img);
        $file = $path . 'original/' . $tmpFile;
        file_put_contents($file, $data);

        $filename = SITE_URL . $file;

        list($width, $height) = getimagesize($filename);
        $crop_options['imageOriginalWidth'] = $width;
        $crop_options['imageOriginalHeight'] = $height;


        $info = @exif_read_data($filename);

        if (!isset($info) || $info == "") {
            $info['MimeType'] = 'image/' . $type[0];
        }

        $crop_options['top'] = (($crop_options['imageHeight'] * $thumbImageHeight) / $crop_options['height'] * $crop_options['top']) / $crop_options['imageHeight'];

        $crop_options['left'] = (($crop_options['imageWidth'] * $thumbImageWidth) / $crop_options['width'] * $crop_options['left']) / $crop_options['imageWidth'];

        $crop_options['imageWidth'] = ($crop_options['imageWidth'] * $thumbImageWidth) / $crop_options['width'];
        $crop_options['imageHeight'] = ($crop_options['imageHeight'] * $thumbImageHeight) / $crop_options['height'];
        $crop_options['height'] = $thumbImageHeight;
        $crop_options['width'] = $thumbImageWidth;


        if (isset($info['MimeType'])) {
            $file = $path . $tmpFile;
            if ($info['MimeType'] == 'image/jpeg' || $info['MimeType'] == 'image/jpg') {
                $src_img = imageCreateFromJpeg($filename);
            } elseif ($info['MimeType'] == 'image/png') {
                $src_img = imageCreateFromPng($filename);
            } elseif ($info['MimeType'] == 'image/webp') {
                $src_img = imagecreatefromwebp($filename);
            } elseif ($info['MimeType'] == 'image/gif') {
                $src_img = imageCreateFromGif($filename);
            }
            $resizedImage = imagecreatetruecolor($crop_options['imageWidth'], $crop_options['imageHeight']);
            imagecopyresampled($resizedImage, $src_img, 0, 0, 0, 0, $crop_options['imageWidth'], $crop_options['imageHeight'], $crop_options['imageOriginalWidth'], $crop_options['imageOriginalHeight']);

            $finalImage = imagecreatetruecolor($crop_options['width'], $crop_options['height']);

            imagecopyresampled($finalImage, $resizedImage, 0, 0, $crop_options['left'], $crop_options['top'], $crop_options['width'], $crop_options['height'], $crop_options['width'], $crop_options['height']);

            if ($info['MimeType'] == 'image/jpeg' || $info['MimeType'] == 'image/jpg') {
                imagejpeg($finalImage, $file, $imagequality);
            } elseif ($info['MimeType'] == 'image/png') {
                imagepng($finalImage, $file, 9);
            } elseif ($info['MimeType'] == 'image/webp') {
                imagewebp($finalImage, $file, $imagequality);
            } elseif ($info['MimeType'] == 'image/gif') {
                imagegif($finalImage, $file);
            }
        }
        return $tmpFile;
    }

    public function create_thumbnail($base64img, $tmpFile, $path, $crop_options, $thumbImageWidth = 0, $thumbImageHeight = 0) {
        $imagequality = 90;
        $split = explode('/', $base64img);
        $type = $split[1];
        $type = explode(";", $type);

        $v_random_image = time() . '-' . str_random(6) . '.' . $type[0];

        $base64imgOri = $base64img;

        if (strpos($base64img, 'data:image/jpeg;base64,') !== false) {
            $base64img = str_replace('data:image/jpeg;base64,', '', $base64img);
        }
        if (strpos($base64img, 'data:image/png;base64,') !== false) {
            $base64img = str_replace('data:image/png;base64,', '', $base64img);
        }
        if (strpos($base64img, 'data:image/webp;base64,') !== false) {
            $base64img = str_replace('data:image/webp;base64,', '', $base64img);
        }

        if (strpos($base64img, 'data:image/jpg;base64,') !== false) {
            $base64img = str_replace('data:image/jpg;base64,', '', $base64img);
        }
        if (strpos($base64img, 'data:image/gif;base64,') !== false) {
            $base64img = str_replace('data:image/gif;base64,', '', $base64img);
        }
        $data = base64_decode($base64img);
        $file = $path . $tmpFile;
        file_put_contents($file, $data);

        $filename = ADMIN_URL . $file;

        list($width, $height) = getimagesize($filename);

        $info = @exif_read_data($filename);

        if (!isset($info) || $info == "") {
            $info['MimeType'] = 'image/' . $type[0];
        }

        $crop_options['top'] = (($crop_options['imageHeight'] * $thumbImageHeight) / $crop_options['height'] * $crop_options['top']) / $crop_options['imageHeight'];

        $crop_options['left'] = (($crop_options['imageWidth'] * $thumbImageWidth) / $crop_options['width'] * $crop_options['left']) / $crop_options['imageWidth'];

        $crop_options['imageWidth'] = ($crop_options['imageWidth'] * $thumbImageWidth) / $crop_options['width'];
        $crop_options['imageHeight'] = ($crop_options['imageHeight'] * $thumbImageHeight) / $crop_options['height'];
        $crop_options['height'] = $thumbImageHeight;
        $crop_options['width'] = $thumbImageWidth;
        if (isset($info['MimeType'])) {
            $file = $path . $tmpFile;
            if ($info['MimeType'] == 'image/jpeg' || $info['MimeType'] == 'image/jpg') {
                $src_img = imageCreateFromJpeg($filename);
            } elseif ($info['MimeType'] == 'image/png') {
                $src_img = imageCreateFromPng($filename);
            } elseif ($info['MimeType'] == 'image/webp') {
                $src_img = imagecreatefromwebp($filename);
            } elseif ($info['MimeType'] == 'image/gif') {
                $src_img = imageCreateFromGif($filename);
            }
            $resizedImage = imagecreatetruecolor($crop_options['imageWidth'], $crop_options['imageHeight']);
            imagecopyresampled($resizedImage, $src_img, 0, 0, 0, 0, $crop_options['imageWidth'], $crop_options['imageHeight'], $crop_options['imageOriginalWidth'], $crop_options['imageOriginalHeight']);

            $finalImage = imagecreatetruecolor($crop_options['width'], $crop_options['height']);

            imagecopyresampled($finalImage, $resizedImage, 0, 0, $crop_options['left'], $crop_options['top'], $crop_options['width'], $crop_options['height'], $crop_options['width'], $crop_options['height']);

            if ($info['MimeType'] == 'image/jpeg' || $info['MimeType'] == 'image/jpg') {
                imagejpeg($finalImage, $file, $imagequality);
            } elseif ($info['MimeType'] == 'image/png') {
                imagepng($finalImage, $file, 9);
            } elseif ($info['MimeType'] == 'image/webp') {
                imagewebp($finalImage, $file, $imagequality);
            } elseif ($info['MimeType'] == 'image/gif') {
                imagegif($finalImage, $file);
            }
        }
    }

    public function cropImages($base64img, $x, $y, $w, $h, $path, $thumb_path) {

        $v_random_image = time() . '-' . Str::random(6);

        $base64img = substr(strstr($base64img, ','), 1);
        $tmpFile = $v_random_image . '.png';

        $targ_w = $targ_h = 150;
        $jpeg_quality = 90;
        $img_src = base64_decode($base64img);
        $file = $path . $tmpFile;
        file_put_contents($file, $img_src);

        $img_r = imagecreatefromstring($img_src);
        $dst_r = ImageCreateTrueColor($targ_w, $targ_h);
        imagecopyresampled($dst_r, $img_r, 0, 0, $x, $y, $targ_w, $targ_h, $w, $h);
        //header('Content-type: image/png');
        ob_start();

        imagejpeg($dst_r, null, $jpeg_quality);
        $image_data = ob_get_contents();
        ob_end_clean();
        $fileThumb = $thumb_path . $tmpFile;


        file_put_contents($fileThumb, $image_data);

        return $tmpFile;
    }

    public function arrStates(){
        return [
            'Alabama' => 'AL',
			'Alaska' => 'AK',
			'Arizona' => 'AZ',
			'Arkansas' => 'AR',
			'California' => 'CA',
			'Colorado' => 'CO',
			'Connecticut' => 'CT',
			'Delaware' => 'DE',
			'Florida' => 'FL',
			'Georgia' => 'GA',
			'Hawaii' => 'HI',
			'Idaho' => 'ID',
			'Illinois' => 'IL',
			'Indiana' => 'IN',
			'Iowa' => 'IA',
			'Kansas' => 'KS',
			'Kentucky' => 'KY',
			'Louisiana' => 'LA',
			'Maine' => 'ME',
			'Maryland' => 'MD',
			'Massachusetts' => 'MA',
			'Michigan' => 'MI',
			'Minnesota' => 'MN',
			'Mississippi' => 'MS',
			'Missouri' => 'MO',
			'Montana' => 'MT',
			'Nebraska' => 'NE',
			'Nevada' => 'NV',
			'New Hampshire' => 'NH',
			'New Jersey' => 'NJ',
			'New Mexico' => 'NM',
			'New York' => 'NY',
			'North Carolina' => 'NC',
			'North Dakota' => 'ND',
			'Ohio' => 'OH',
			'Oklahoma' => 'OK',
			'Oregon' => 'OR',
			'Pennsylvania' => 'PA',
			'Rhode Island' => 'RI',
			'South Carolina' => 'SC',
			'South Dakota' => 'SD',
			'Tennessee' => 'TN',
			'Texas' => 'TX',
			'Utah' => 'UT',
			'Vermont' => 'VT',
			'Virginia' => 'VA',
			'Washington' => 'WA',
			'West Virginia' => 'WV',
			'Wisconsin' => 'WI',
        ];
    }

    public function fetchLatLng($request, $address = '') {		
        
        // pr($address); exit;
       $inputedfullAddress = '';
		if($address != '') {
			$inputedfullAddress = strtolower($address);
        }		
       
		$return_array = array();
		
        $full_address = strtolower($inputedfullAddress);
        if($full_address != '') {
            $fullAddress = urlencode($full_address);
                
                // &key='.GOOGLE_KEY
                //  $url = 'https://maps.google.com/maps/api/geocode/xml?address=' . $fullAddress . '&sensor=false&
                // key='.GOOGLE_KEY_PICKER; 
                $url = 'https://maps.google.com/maps/api/geocode/xml?address=' . $fullAddress . '&key='.GOOGLE_MAP_KEY;
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
                    
                    $return_array['latitude'] = $latitude;
                    $return_array['longitude'] = $longitude;
                   
                }
          			
        } else {
            $return_array['latitude'] = '';
            $return_array['longitude'] = '';
            
        }
        		
        // pr($return_array); 	
		return $return_array;
    }

    public function traveller_type() {
        return array('adult','senior','military','child','infant');
    }

    public function isCancelButtonShow($id) {
        $reservation_record = Reservations::select('d_travel_date','e_reservation_status')->where(['id' => $id])->first();
        
        $return = false;
        if($reservation_record && $reservation_record->e_reservation_status=='Booked'){
            $selected_linerun = ReservationLeg::select('id','i_run_id')->with('LineRune')->where('i_reservation_id',$id)->first();
            if($selected_linerun) {
                $selected_linerun = $selected_linerun->toArray();
                $cancel_compare_time = strtotime($selected_linerun['line_rune']['d_run_date']." ".$selected_linerun['line_rune']['t_scheduled_arr_time']);
            } else {
                $cancel_compare_time = strtotime($reservation_record['d_travel_date']);
            }

            $payment_info = Transactions::where('i_reservation_id',$id)->where('e_type','Booked')->orderBy('created_at','DESC')->first();
            if($payment_info && $payment_info->e_status=="Success" && strtotime(date('Y-m-d')) <= $cancel_compare_time) {
                $return = true;
            }
            if($reservation_record->d_discount_price == $reservation_record->d_total_fare && strtotime(date('Y-m-d')) <= strtotime($reservation_record->d_travel_date)) {
                $return = true;
            }
        }

        return $return;
    }

    public function checkRefundAvailability($id) {
        $reservation_record = Reservations::select('d_travel_date','e_reservation_status')->where(['id' => $id])->first();
        $payment_info = Transactions::where('i_reservation_id',$id)->whereIn('e_type',['Booked','Booked-Wallet'])->orderBy('created_at','DESC')->first();
        $return = 0;

        if($reservation_record && $payment_info && $payment_info->e_status=="Success"){

            // Calculate difference between payment time and current time
            $payment_time = strtotime(date('Y-m-d H:i',strtotime($payment_info->created_at)));
            $now_time = strtotime(date('Y-m-d H:i'));
            $payment_hours = round(($now_time - $payment_time) / (60*60),2);

            $selected_linerun = ReservationLeg::select('id','i_run_id')->with('LineRune')->where('i_reservation_id',$id)->first();
            
            if($selected_linerun) {
                $selected_linerun = $selected_linerun->toArray();
                $refund_compare_date_time = strtotime($selected_linerun['line_rune']['d_run_date']." ".$selected_linerun['line_rune']['t_scheduled_arr_time']);
            } else {
                $refund_compare_date_time = strtotime($reservation_record['d_travel_date']);
            }
            $total_hours = round(($refund_compare_date_time - $now_time) / (60*60),2);
            
            if($total_hours >= 48 && $payment_hours <= 48) {
                $return = 1; // Refund Process
            } else if($total_hours >= 48) {
                $return = 2; // Add to Wallet
            } else if($total_hours < 48 && $total_hours > 0) {
                $return = 3; // Refund Request and admin will decide the future of refund
            }
        }
        return $return;
    }

    public function getTravelTypeText($pick_type_id,$drop_type_id) {
        $pick_type = SystemResCategory::find($pick_type_id);
        $drop_type = SystemResCategory::find($drop_type_id);
        $switch_text = "";
        $switch_type = "";
        $direction = "";
        
        if($pick_type && $drop_type) {
            $switch_type = "Pick";
            $travels = ['Airport','Greyhound','Amtrak','Cruise Pier','Cruise Hotel'];
    
            $pick_type_text = $pick_type->v_label;
            $drop_type_text = $drop_type->v_label;
            
    
            if(in_array($pick_type_text,$travels) && in_array($drop_type_text,$travels)) {
                $switch_text = $pick_type_text;
            } else if(!in_array($pick_type_text,$travels) && in_array($drop_type_text,$travels)) {
                $switch_text = $drop_type_text;
                $switch_type = 'Drop';
            }
    
            if($pick_type_text == 'Airport' && $drop_type_text != 'Airport') {
                $switch_text = $pick_type_text;
                $switch_type = 'Pick';
            } else if($pick_type_text != 'Airport' && $drop_type_text == 'Airport') {
                $switch_text = $drop_type_text;
                $switch_type = 'Drop';
            }

            if($switch_type == 'Pick') {
                $direction = 'Arrival';
            } else {
                $direction = 'Departure';
            }
        }
        return ['switch_text' => $switch_text,'switch_type' => $switch_type,'direction' => $direction];
    }

    public function getPaymentMode($id) {
        $status = false;
        $paymentStatus = Transactions::where(['i_reservation_id' => $id, 'e_status' => 'Success'])->whereIn('e_type', array('Booked', 'Cash-On-Board','Booked-Wallet'))->first();
        
        if($paymentStatus){
            if($paymentStatus['e_type']=='Booked-Wallet') {
                $total_trans = Transactions::where(['i_reservation_id' => $id, 'e_status' => 'Success'])->whereIn('e_type', array('Booked','Booked-Wallet'))->count();
                if($total_trans > 1) {
                    $status = 'Card + Wallet';
                } else{
                    $status =  'Wallet Balance';
                }
            } else if($paymentStatus['e_type']=='Cash-On-Board'){
                $status =  'Cash On Board';
            } else {
                $status =  'Online Card Payment';
            }
        }
        return $status;
    }
}


