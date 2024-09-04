<?php

namespace App\Http\Controllers\Dashboard\Location;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Traits\ApiResponseTrait;
use App\Http\Resources\Order\OrderResource;
use App\Http\Resources\Location\LocationResource;
use App\Http\Requests\Location\AddLocationRequest;
use App\Services\Dashboard\Location\Dash_LocationService;

class LocationController extends Controller
{
    //
    use ApiResponseTrait;

    public function __construct(protected Dash_LocationService $dash_location_service )
    {

    }
     public function add_location(AddLocationRequest $request)

     {

        $name=$request->name;
        $delivery_time=$request->delivery_time;
        $price=$request->delivery_price;
        $result=$this->dash_location_service->add_location($delivery_time,$name,$price);
        $output=[];
        if($result['status_code']==200)
        {
                $result_data = $result['data'];
                // response data preparation:
                $output['location'] = new LocationResource($result_data['location']);

   }
   return $this->send_response($output, $result['msg'], $result['status_code']);

     }

     public function get_locations( )

     {
        $result=$this->dash_location_service->get_locations();
        $output=[];
        if($result['status_code']==200)
        {
                $result_data = $result['data'];
                // response data preparation:
                $output['locations'] =  LocationResource::collection($result_data['locations']);

   }
   return $this->send_response($output, $result['msg'], $result['status_code']);

     }

}
