<?php 

namespace App\Exports;


use App\Models\Reservations;
use Carbon\Carbon;

use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\RegistersEventListeners;
use Maatwebsite\Excel\Events\BeforeSheet;
use Maatwebsite\Excel\Events\AfterSheet;
use Request;
use App\Http\Requests;
use Hash, Session, Redirect, Auth, Validator, Excel, Cookie, DB, Config;

class ReservationListExport implements FromQuery, WithMapping, WithHeadings, ShouldAutoSize, WithEvents
{
    use Exportable, RegistersEventListeners;

    private $fileName = 'participant-list.xlsx';

    /**
     * @return Builder
     */
    public function query() {
        $data = Request::all();
        
        $sortColumn = array('','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','');

        $query  = Reservations::with(['PickupCity','DropOffCity','Customers', 'SystemResCategory','ReservationTravellerInfo', 'ReservAtionLeg'=> function($q) {
            $q->with(['LineRune' => function($q1) {
                $q1->with(['Driver','VehicleFleet','DriverExtension'])->select('*');  
            }])->select('*');    
        }, 'ReservationLuggageInfo'])->select('*');    
        if(Auth::guard('admin')->user()->i_role_id == 6){
            $query = $query->where('added_by_id', '=',Auth::guard('admin')->user()->id);
        }
        // pr($query->get()->toArray()); exit;

        if (isset($data['v_reservation_number']) && $data['v_reservation_number'] != '') {
            $query = $query->where('v_reservation_number', 'LIKE', '%' . $data['v_reservation_number'] . '%');
        }
        if (isset($data['i_customer_id']) && $data['i_customer_id'] != '') {
            /* $query = $query->WhereHas('Customers', function($q) use($data){
                $q->where('customers.id', '=', $data['i_customer_id']);
            }); */
            $query = $query->WhereHas('Customers', function($q) use($data){
                $q->where(function($q1) use($data) {
                    $q1->where('customers.v_firstname', 'LIKE', '%' . trim($data['i_customer_id']) . '%')
                    ->orWhere('customers.v_lastname', 'LIKE', '%' . trim($data['i_customer_id']) . '%')
                    ->orWhere(DB::raw("CONCAT(customers.v_firstname, ' ',customers.v_lastname)"), 'LIKE', '%' . trim($data['i_customer_id']) . '%');
                }); 
            }); 
        }
        if (isset($data['i_reservation_category_id']) && $data['i_reservation_category_id'] != '') {
            $query = $query->WhereHas('SystemResCategory', function($q) use($data){
                $q->where('sys_res_categories.id', 'LIKE', '%' . $data['i_reservation_category_id'] . '%');
            });
        }
        if (isset($data['i_origin_point_id']) && $data['i_origin_point_id'] != '') {
            $query = $query->orWhereHas('PickupCity', function($qa) use($data){
                $qa->where(DB::raw("CONCAT(reservations.v_pickup_address, COALESCE(CONCAT(', ',geo_cities.v_city), ''), COALESCE(CONCAT(', ',geo_cities.v_county),''))"), 'LIKE', '%' . trim($data['i_origin_point_id']) . '%');
          });
        }
        if (isset($data['i_destination_point_id']) && $data['i_destination_point_id'] != '') {
            $query = $query->orWhereHas('DropOffCity', function($qa) use($data){
                $qa->where(DB::raw("CONCAT(reservations.v_dropoff_address, COALESCE(CONCAT(', ',geo_cities.v_city), ''), COALESCE(CONCAT(', ',geo_cities.v_county),''))"), 'LIKE', '%' . trim($data['i_destination_point_id']) . '%');

          });
        }
        if (isset($data['e_class_type']) && $data['e_class_type'] != '') {
            $query = $query->where('e_class_type', '=', $data['e_class_type'] );
        }
        if (isset($data['e_shuttle_type']) && $data['e_shuttle_type'] != '') {
            $query = $query->where('e_shuttle_type', '=', $data['e_shuttle_type'] );
        }
        if (isset($data['departureStartDate']) && trim($data['departureStartDate']) != '') {
            $query = $query->where(DB::raw('DATE(d_travel_date)'), '>=', trim(date('Y-m-d', strtotime(trim($data['departureStartDate'])))));
        }
        if (isset($data['departureEndDate']) && trim($data['departureEndDate']) != '') {
            $query = $query->where(DB::raw('DATE(d_travel_date)'), '<=', trim(date('Y-m-d', strtotime(trim($data['departureEndDate'])))));
        }
        
        if (isset($data['i_total_num_passengers']) && $data['i_total_num_passengers'] != '') {
            $query = $query->where('i_total_num_passengers', '=', $data['i_total_num_passengers']);
        }
        if (isset($data['e_reservation_status']) && $data['e_reservation_status'] != '') {
            $query = $query->where('e_reservation_status', 'LIKE', '%' . $data['e_reservation_status'] . '%');
        }

        $rec_per_page = REC_PER_PAGE;
        if (isset($data['length'])) {
            if ($data['length'] == '-1') {
                $rec_per_page = '';
            } else {
                $rec_per_page = $data['length'];
            }
        }

        $query = $query->orderBy('reservations.updated_at','desc');
        
        $records = $query->take($query->count());
       
        return $records;
    }
     /**
     * @param mixed $row
     *
     * @return array
     */
     
    public function map($row): array {
        // pr($row->ReservationTravellerInfo); exit;
        $total_adult = $total_senior = $total_military = $total_child = $total_infant = 0;
        foreach ($row->ReservationTravellerInfo as $key => $value) {
            $total = ($value['e_type'] == 'Adult') ? $total_adult++ : (($value['e_type'] == 'Senior') ? $total_senior++ : (($value['e_type'] == 'Military') ? $total_military++ : (($value['e_type'] == 'Child') ? $total_child++ : (($value['e_type'] == 'Infant') ? $total_infant++ : 0)))) ;
        }
        return [
           
            $row->v_reservation_number,
            $row->e_shuttle_type,
            date('m/d/Y',strtotime($row->d_travel_date)),
            $row->t_flight_time,
            isset($row->PickupCity) ? $row->PickupCity['v_city'].', '.$row->PickupCity['v_county'] : '',
            isset($row->PickupCity) ? $row->v_pickup_address.', '.$row->PickupCity['v_city'].', '.$row->PickupCity['v_county'] : '',
            isset($row->DropOffCity) ? $row->DropOffCity['v_city'].', '.$row->DropOffCity['v_county'] : '',
            isset($row->DropOffCity) ? $row->v_dropoff_address.', '.$row->DropOffCity['v_city'].', '.$row->DropOffCity['v_county'] : '',
            $row->t_special_instruction,
            $row->ReservAtionLeg['LineRune']['Driver']['v_firstname'].' '.$row->ReservAtionLeg['LineRune']['Driver']['v_lastname'].' '.$row->ReservAtionLeg['LineRune']['DriverExtension']['v_extension'],
            $row->ReservAtionLeg['LineRune']['id'],
            $row->d_total_fare,
            $total_adult,
            $total_senior,
            $total_military,
            $total_child,
            $total_infant,
            $row->v_flight_name,
            $row->v_flight_number,
            $row->e_flight_type,
            date('m/d/Y',strtotime($row->d_travel_date)).' '.date('h:i A',strtotime($row->t_flight_time)),
            $row->v_contact_phone_number,
            $row->v_contact_email,
            count($row->ReservationLuggageInfo),
            $row->e_reservation_status,
            date('m/d/Y h:i A',strtotime($row->created_at)),
            date('m/d/Y h:i A',strtotime($row->updated_at)),
        ];
    }

    /**
     * @return array
     */
    public function headings(): array {
        return [
            'Reservation Number',
            'Shuttle Type',
            'Pickup',
            'Flight',
            'Pickup Location',
            'PickupAddr',
            'DropOff Location',
            'DropOffAddr',
            'Special Instr',
            'Drv',
            'TripID',
            'Fare',
            'Passengers - Adult',
            'Passengers - Senior',
            'Passengers - Military',
            'Passengers - Child',
            'Passengers - Infant',
            'Airline',
            'FlightNumber',
            'FlightType',
            'FlightInfoTOD',
            'Telephone',
            'EmailAddr',
            'BagCount',
            'Reservation Status',
            'CreatedAt',
            'UpdatedAt',
        ];
    }
    
    public static function afterSheet(AfterSheet $event) {
        $event->sheet->getStyle('A1:AB1')->applyFromArray([
            'font' => [
                'name'      =>  'Calibri',
                'size'      =>  12,
                'bold'      =>  true
            ]
        ]);
    }
    
}