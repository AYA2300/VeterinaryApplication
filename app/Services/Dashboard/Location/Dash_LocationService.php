<?php
namespace App\Services\Dashboard\Location;

use Throwable;
use App\Models\location;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class Dash_LocationService
{
    public function add_location($delivery_time,$name,$price)
    {
        $data=[];
        $result=[];
        $status_code = 400;
        $msg = '';
        try{
            DB::beginTransaction();
            $location=location::create([
                'name' => $name,
                'delivery_time' =>    $delivery_time,
                'delivery_price' => $price,
            ]);
            DB::commit();
            $data['location'] = $location;
            $status_code=200;
            $msg='اضافة مكان';

        }catch(Throwable $th)
        {
            DB::rollBack();
            Log::debug($th);

            $status_code = 500;
            $data = $th;
            $msg = 'error ' . $th->getMessage();


        }
        $result = [
            'data' => $data,
            'status_code' => $status_code,
            'msg' => $msg,
        ];

        return $result;


    }
    /////////////

    public function get_locations()
    {
        $data=[];
        $result=[];
        $status_code = 400;
        $msg = '';
        $locations=location::all();
        $data['locations'] = $locations;
        $status_code=200;
        $msg='تم استرداد الاماكن';
        $result = [
            'data' => $data,
            'status_code' => $status_code,
            'msg' => $msg,
        ];

        return $result;

    }

}




?>
