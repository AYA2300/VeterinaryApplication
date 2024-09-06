<?php
namespace App\Services\Applications\Order;

use Log;
use Throwable;
use App\Models\Cart;
use App\Models\Feed;
use App\Models\Order;
use App\Models\location;
use App\Models\Medicine;
use App\Models\OrderItem;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Http\Resources\Feed\FeedResource;
use App\Http\Resources\Medicine\MedicineResource;


class App_OrderService{

    //confirm order

    public function confirmOrder(array $input_data)
    {
        $result=[];
        $data=[];
        $status_code=400;
        $msg='';
        try {
            DB::beginTransaction(); // بدء المعاملة


                $user_type = null;
                $user = null;

                if (Auth::guard('breeder')->check()) {
                    $user = Auth::guard('breeder')->user();
                    $user_type = 'App\Models\Breeder';
                } elseif (Auth::guard('veterinarian')->check()) {
                    $user = Auth::guard('veterinarian')->user();
                    $user_type = 'App\Models\Veterinarian';
                }

                if (!$user) {
                    $msg = 'ليس لديك طلبات';
                    $status_code = 404;
                }

                $msg = 'يرجى استلام طلبك من المركز خلال مدة 24 ساعة'; // رسالة افتراضية
                $location = null;

                if (array_key_exists('delivery_type', $input_data) && $input_data['delivery_type'] == 'delivery') {
                    $location = $input_data['location_id'];
                    $msg = 'سيتم التوصيل إلى المكان الذي اخترته';
                } else {
                    $msg = 'يرجى استلام طلبك من المركز خلال مدة 24 ساعة';
                }
                $order = $user->orders()->create([
                    // 'userable_id' => $user->id,
                    // 'userable_type' => $user_type,
                    'order_number' => Order::generateOrderNumber(),
                    'total_price' => $input_data['total_price']??'null',
                    'delivery_type' => $input_data['delivery_type'],
                    'location_id' => $location,
                ]);
                $medicines=[];

                if (!empty($input_data['medicines'])) {
                    foreach ($input_data['medicines'] as $medicine) {
                        OrderItem::create([
                            'order_id' => $order->id,
                            'itemable_id' => $medicine['id'],
                            'itemable_type' => 'App\Models\Medicine',
                            'quantity' => $medicine['quantity'] ?? 1,


                        ]);
                    }
                }
                $feeds=[];

                if (!empty($input_data['feeds'])) {
                    foreach ($input_data['feeds'] as $feed) {
                        OrderItem::create([
                            'order_id' => $order->id,
                            'itemable_id' => $feed['id'],
                            'itemable_type' => 'App\Models\Feed',
                            'quantity' => $feed['quantity']??1,
                            // 'price' => $feed['price'],
                        ]);
                    }
                }

                DB::commit();

            // إرسال الرد الناجح
            $data['order'] = $order;


           $msg=$msg;
           $status_code=200;





       } catch (\Exception $th) {
           DB::rollBack();
           Log::debug($th);
      $status_code=500;
      $msg='حدث خطا';

       }
       $result = [
           'data' => $data,
           'status_code' => $status_code,
           'msg' => $msg,
       ];

       return $result;
    }
/////////////////////
    //get
    public function getMyOrder($order)
    {
        $result = [];
        $data = [];
        $status_code = 400;
        $msg = '';
        $user = null;
        $user_type = null;

        if (Auth::guard('breeder')->check()) {
            $user = Auth::guard('breeder')->user();
            $user_type = 'App\Models\Breeder';
        } elseif (Auth::guard('veterinarian')->check()) {
            $user = Auth::guard('veterinarian')->user();
            $user_type = 'App\Models\Veterinarian';
        }

        if (!$user) {
            $msg = 'يجب تسجيل الدخول للوصول إلى هذه الصفحة';
            $status_code = 401;
        } else {
            // Query the orders based on the authenticated user's ID and type
            $ordersQuery = Order::where('userable_id', $user->id)
                ->where('userable_type', $user_type);

            // Filter orders based on the status of the order
            if ($order === 'current') {
                $ordersQuery->where('status', 'pending');
            } elseif ($order === 'previous') {
                $ordersQuery->where('status', 'completed');
            } else {
                $msg = 'حالة الطلب غير صالحة';
                $status_code = 400;
            }

            // Get the filtered orders
            $orders = $ordersQuery->get();

            if ($orders->isEmpty()) {
                $msg = 'لا يوجد لديك طلبات للعرض';
                $status_code = 404;
            } else {
                $data['orders'] = $orders;
                $msg = 'تم عرض الطلبات بنجاح';
                $status_code = 200;
            }
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
