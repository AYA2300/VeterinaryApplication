<?php

namespace App\Http\Controllers\Application\Order;

use App\Models\Cart;
use App\Models\Feed;
use App\Models\Order;
use App\Models\location;
use App\Models\Medicine;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Http\Traits\ApiResponseTrait;
use App\Http\Requests\Order\OrderRequest;
use App\Http\Resources\Feed\FeedResource;
use App\Http\Resources\Order\OrderResource;
use App\Http\Resources\Medicine\MedicineResource;
use App\Services\Applications\Order\App_OrderService;

class OrderController extends Controller
{
    //
    use ApiResponseTrait;

    public function __construct(protected App_OrderService $app_order_service)
    {

    }

    public function confirmOrder(OrderRequest $request)
    {
        $input_data=$request->validated();
        $result=$this->app_order_service->confirmOrder($input_data);
        $output=[];


        if ($result['status_code'] == 200) {
            $result_data = $result['data'];
            // response data preparation:
            $output['order'] = new  OrderResource($result_data['order']);



        }
      return $this->send_response($output, $result['msg'], $result['status_code']);

    }

    ///-------------------------------------------------------------------
    //get myorder
    public function getmyorder(Request $request)
    {
        $order=$request->order;
        $result=$this->app_order_service->getmyorder($order);
        $output=[];


        if ($result['status_code'] == 200) {
            $result_data = $result['data'];
            // response data preparation:
                     $output['orders'] =   OrderResource::collection($result_data['orders']);


        }
      return $this->send_response($output, $result['msg'], $result['status_code']);

    }
}
