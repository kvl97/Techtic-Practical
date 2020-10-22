<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use DB,Auth,View, DateTime, DateTimeZone;

use Illuminate\Support\Str;
use App\Models\Permissions;
use App\Models\Transactions;
use App\Models\SystemResCategory;

use Twilio;


class BaseController extends Controller {

    protected $auth;
    protected $authRole;
    public $permissions = [];
    public function __construct() {
        // $user_data = Auth::guard('admin')->user();
        // pr($user_data);exit;
        $this->middleware(function ($request, $next) {
            $this->auth = Auth::guard('admin')->user();
            if(isset($this->auth->i_role_id)) {
                $this->permission = $permission = Permissions::where('i_role_id', $this->auth->i_role_id)->get()->keyBy('i_module_id')->toArray();
                View::share('permission', $permission);
            }
            return $next($request);
        });
    }
    /* public function saveImage($base64img, $path, $fileName) {
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
        $file = $path . $fileName;
        file_put_contents($file, $data);

        return $fileName;
    } */

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
                        $place_id = $arr['result']['place_id'];										
                    } else {						
                        $latitude = $arr['result']['geometry']['location']['lat'];					
                        $longitude = $arr['result']['geometry']['location']['lng'];	
                        $place_id = $arr['result']['place_id'];								
                    }
                    
                    $return_array['latitude'] = $latitude;
                    $return_array['longitude'] = $longitude;
                    $return_array['place_id'] = $place_id;
                }
          			
        } else {
            $return_array['latitude'] = '';
            $return_array['longitude'] = '';
            $return_array['place_id'] = '';
        }
        		
        // pr($return_array); 	
		return $return_array;
    }

    public static function getTravelTypeText($pick_type_id,$drop_type_id) {
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


