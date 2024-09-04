<?php

namespace App\Http\Controllers\Application\Cart;

use Throwable;
use App\Models\Cart;
use App\Models\Feed;
use App\Models\Itemable;
use App\Models\Medicine;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Http\Traits\ApiResponseTrait;
use App\Http\Resources\Cart\AddToCartResource;
use App\Http\Requests\Cart\App_AddToCartRequest;
use App\Http\Resources\Medicine\MedicineResource;
use  App\Services\Applications\Cart\App_CartServices;

class App_AddToCartController extends Controller
{
    //
    use ApiResponseTrait;

    public function __construct(protected App_CartServices $app_cart_services)
    {

    }




    public function addToCart(App_AddToCartRequest $request,$item_id)
    {
        $input_data=$request->validated();
        $result=$this->app_cart_services->Add_to_cart($input_data,$item_id);
        $output=[];

        if ($result['status_code'] == 200) {
            $result_data = $result['data'];
            // response data preparation:
            $output['carts'] = new  AddToCartResource($result_data['carts']);
            $output['sumPrice'] = $result_data['sumPrice'];

        }
      return $this->send_response($output, $result['msg'], $result['status_code']);


    }


    ///get Items \cart

    public function get_items_cart()

    {
        $result=$this->app_cart_services->get_items();
        $output=[];

        if ($result['status_code'] == 200) {
            $result_data = $result['data'];
            // response data preparation:
            $output['carts'] = new  AddToCartResource($result_data['carts']);
            $output['SumPrice'] = $result_data['SumPrice'];

        }
      return $this->send_response($output, $result['msg'], $result['status_code']);

    }


//------------------------------------------------------

    public function delete_item( Itemable $item_id)
    {
        $result=$this->app_cart_services->delete_item($item_id);


 $output=[];
 if($result['status_code']==200)
 {
         $result_data = $result['data'];

}
return $this->send_response($output, $result['msg'], $result['status_code']);
     }

public function clear()
{

    }
}

