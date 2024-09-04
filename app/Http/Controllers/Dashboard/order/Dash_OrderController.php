<?php

namespace App\Http\Controllers\Dashboard\order;

use App\Models\Order;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Traits\ApiResponseTrait;
use App\Http\Resources\Order\OrderResource;
use App\Http\Requests\Order\EditStatusOrderRequest;
use App\Services\Dashboard\Order\Dash_OrderService;

class Dash_OrderController extends Controller
{
    use ApiResponseTrait;


    public function __construct(protected Dash_OrderService $dash_order_services){

    }
    //
    public function get_orders(){
        $result=$this->dash_order_services->get_orders();
        $output=[];
        if($result['status_code']==200)
        {
                $result_data = $result['data'];
                // response data preparation:
                $output['orders'] =  OrderResource::collection($result_data['orders']);

   }
   return $this->send_response($output, $result['msg'], $result['status_code']);

    }

    //edit status

    public function edit_status_orders(EditStatusOrderRequest $request,Order $order)
    {
        $input_data=$request->validated();
        $result=$this->dash_order_services->edit_status_orders($input_data,$order);
        $output=[];
        if($result['status_code']==200)
        {
                $result_data = $result['data'];
                // response data preparation:
                $output['order'] =  new OrderResource($result_data['order']);

   }
   return $this->send_response($output, $result['msg'], $result['status_code']);

    }
}
