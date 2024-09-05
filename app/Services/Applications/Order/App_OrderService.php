<?php
namespace App\Services\Applications\Order;

use Log;
use Throwable;
use App\Models\Cart;
use App\Models\Feed;
use App\Models\Order;
use App\Models\location;
use App\Models\Medicine;
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

            // الحصول على المستخدم الحالي من الحارس "breeder"
            $user_type = null;

            if (Auth::guard('breeder')->check()) {
                $user = Auth::guard('breeder')->user();
                $user_type = 'App\Models\Breeder'; // افتراض أن هذا هو الـ namespace لنموذج Breeder
            } elseif (Auth::guard('veterinarian')->check()) {
                $user = Auth::guard('veterinarian')->user();
                $user_type = 'App\Models\Veterinarian'; // افتراض أن هذا هو الـ namespace لنموذج Veterinarian
            }
            // التحقق من وجود سلة للمستخدم
            if (!$user->cart) {
                    $msg='ليس لديك سلة';
                    $status_code=404;
            }

            // البحث عن سلة تابعة للمستخدم الحالي
            $cart = Cart::where('userable_id', $user->id)
            ->where('userable_type', $user_type)
            ->first();

if (!$cart) {
    $msg = 'لا تملك صلاحيات';
    $status_code = 403;
}
            if (!$cart) {
                $msg='لا تملك صلاحيات';
                   $status_code=403;

            } elseif (!$cart->medicines()->exists() && !$cart->feeds()->exists()) { // التحقق من وجود عناصر في السلة
               $msg='لا تحوي السلة عناصر للطلب !';
               $status_code=404;
            }else{
                  // تحديد نوع الطلب وموقع التوصيل إن وجد
                $msg = 'يرجى استلام طلبك من المركز خلال مدة 24 ساعة'; // رسالة افتراضية

                    $location = null;
                    $price_location=0;


            if ($input_data['delivery_type'] == 'delivery') {
                $location = $input_data['location_id'];
               $priceselect=location::where('id',$input_data['location_id'])->select(['delivery_price'])
                ->first();
$price_location=$priceselect->delivery_price;
                $msg = 'سيتم التوصيل إلى المكان الذي اخترته';
            }

            // إنشاء الطلب
            $order = Order::create([
                'cart_id' => $cart->id,
                'order_number' => Order::generateOrderNumber(),
                'delivery_type' => $input_data['delivery_type'],
                'location_id' => $location,
            ]);
            $medicines=[];
            // التعامل مع الأدوية الموجودة في السلة
            if ($cart->medicines()->exists()) {
              $medicines=  $cart->medicines->each(function ($medicine) use ($order) {
                   $medicine->orders()->create([
                        'order_id' => $order->id,
                        // 'itemable_id' => $medicine->id, // تعيين itemable_id
                        // 'itemable_type' => Medicine::class, // تعيين itemable_type
                        'quantity' => $medicine->pivot->quantity,
                    ]);
                    $price= $medicine->pivot->quantity * $medicine->price;

                });

            }
$feeds=[];
            // التعامل مع الأعلاف الموجودة في السلة
            if ($cart->feeds()->exists()) {
               $feeds= $cart->feeds->each(function ($feed) use ($order) {
                      $feed->orders()->create([
                        'order_id' => $order->id,
                        // 'itemable_id' => $feed->id, // تعيين itemable_id
                        // 'itemable_type' => Feed::class, // تعيين itemable_type
                        'quantity' => $feed->pivot->quantity,
                        // 'price' => $feed->pivot->quantity * $feed->price,
                    ]);
                    $price= $feed->pivot->quantity * $feed->price;
                });
            }
            $sumPrice = $cart->medicines->sum(function($medicine) {
                return $medicine->pivot->quantity * $medicine->price;
            });

            $sumPrice += $cart->feeds->sum(function($feed) {
                return $feed->pivot->quantity * $feed->price;
            });
            $total=$sumPrice + $price_location;
            // إفراغ السلة بعد تأكيد الطلب
            $cart->medicines()->detach();
            $cart->feeds()->detach();

            // تثبيت التغييرات في قاعدة البيانات
            DB::commit();

            // إرسال الرد الناجح
            $data['order'] = $order;
            $data['medicines'] = $medicines;
            $data['feeds'] = $feeds;
            $data['sumPrice'] = $sumPrice;
            $data['sumPrice'] = $sumPrice;
            $data['delivery_price'] = $price_location;
            $data['total_Price'] = $total;
            $msg=$msg;
           $status_code=200;

            }



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

    public function getmyorder($order){
        $result=[];
        $data=[];
        $status_code=400;
        $msg='';
        $user = null;
        $user_type = null;

        if (Auth::guard('breeder')->check()) {
            $user = Auth::guard('breeder')->user();
            $user_type = 'App\Models\Breeder'; // افتراض أن هذا هو الـ namespace لنموذج Breeder
        } elseif (Auth::guard('veterinarian')->check()) {
            $user = Auth::guard('veterinarian')->user();
            $user_type = 'App\Models\Veterinarian'; // افتراض أن هذا هو الـ namespace لنموذج Veterinarian
        }

        // التحقق من وجود المستخدم
        if (!$user) {
            $msg = 'يجب تسجيل الدخول للوصول إلى هذه الصفحة';
            $status_code = 401;
            return response()->json(['message' => $msg], $status_code);
        }

        // البحث عن سلة تابعة للمستخدم الحالي بناءً على النوع
        $cart = Cart::where('userable_id', $user->id)
            ->where('userable_type', $user_type)
            ->first();

        // التحقق من وجود السلة
        if (!$cart) {
            $msg = 'لا تملك صلاحيات';
            $status_code = 403;
        } else {
            // التحقق من وجود طلبات في السلة
            if ($cart->orders()->exists()) {
                // الحصول على الطلبات بناءً على الحالة (current أو previous)
                if ($order === 'current') {
                    $orders = Order::where('cart_id', $cart->id)
                                   ->where('status', 'pending')
                                   ->get();
                } elseif ($order === 'previous') {
                    $orders = Order::where('cart_id', $cart->id)
                                   ->where('status', 'completed')
                                   ->get();
                }

                if ($orders->isEmpty()) {
                    $msg = 'لا توجد طلبات متاحة';
                    $status_code = 404;
                } else {
                    // جلب عناصر الطلب لكل طلب
                    $items = $orders->flatMap(function ($order) {
                        return $order->orderItems->map(function ($item) {
                            // جلب بيانات itemable (العلاقة المتعددة الأشكال)
                            $itemable = $item->itemable;

                            // تحديد نوع العنصر بناءً على نوع العلاقة المتعددة الأشكال
                            $itemType = class_basename($itemable); // هذا يعيد اسم الكلاس (مثلاً: "Medicine" أو "Feed")

                            // تخصيص البيانات بناءً على نوع العنصر
                            $itemDetails = [];
                            if ($itemType === 'Medicine') {
                                $itemDetails = [
                                    'type' => 'Medicine',
                                    'details' => new MedicineResource($itemable),
                                ];
                            } elseif ($itemType === 'Feed') {
                                $itemDetails = [
                                    'type' => 'Feed',
                                    'details' => new FeedResource($itemable),
                                ];
                            }

                            // تجميع تفاصيل العنصر في استجابة JSON
                            return [
                                'item' => [
                                    'id' => $item->id,
                                    'quantity' => $item->quantity,
                                    'item_details' => $itemDetails,
                                    'created_at' => $item->created_at->format('Y-m-d H:i:s A'),
                                ],
                                'order' => [
                                    'id' => $item->order->id,
                                    'order_number' => $item->order->order_number,
                                    'status' => $item->order->status,
                                ],
                                'cart' => [
                                    'id' => $item->order->cart->id,
                                ],
                            ];
                        });
                    });

                    $data['items'] = $items;
                    $msg = 'تم عرض الطلبات بنجاح';
                    $status_code = 200;
                }
            } else {
                $msg = 'لا توجد طلبات متاحة';
                $status_code = 404;
            }
        }

        // إعداد الاستجابة
        $result = [
            'data' => $data,
            'status_code' => $status_code,
            'msg' => $msg,
        ];

       return $result;
    }}



?>
