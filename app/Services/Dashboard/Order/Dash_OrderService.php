<?php
namespace App\Services\Dashboard\Order;

use App\Models\Order;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Throwable;

class Dash_OrderService{





    public function get_orders()
    {
        $data=[];
        $result=[];
        $status_code = 400;
        $msg = '';
        $orders=Order::all();
        $data['orders'] = $orders;
        $status_code=200;
        $msg='تم استرداد الطلبات';
        $result = [
            'data' => $data,
            'status_code' => $status_code,
            'msg' => $msg,
        ];

        return $result;

    }
///edit status

   public function edit_status_orders(array $input_data, Order $order)
   {
    $data=[];
        $result=[];
        $status_code = 400;
        $msg = '';
try{

    $newData=[];
    if(isset($input_data['status'])){
        $newData['status']= $input_data['status'];
    }
    $order->update($newData);
    $msg='تم تعديل حالة الطلب';
    $data['order'] = $order;
    $status_code=200;

}
catch(Throwable $th){
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


}





?>
