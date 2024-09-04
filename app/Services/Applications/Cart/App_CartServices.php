<?php
namespace App\Services\Applications\Cart;

use Exception;
use App\Models\Cart;
use App\Models\Feed;
use App\Models\Itemable;
use App\Models\Medicine;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

class App_CartServices{
     //add to cart

     public function Add_to_cart(array $input_data,$item_id)
     {
        $result=[];
        $data=[];
        $status_code=400;
        $msg='';

        try {
            DB::beginTransaction();
            $breeder = Auth::guard('breeder')->user();

            if (!$breeder) {
                return response()->json(['status' => 'المربي غير مصرح له'], 403);
            }

            // الحصول على السلة أو إنشائها للمربي
            $cart = Cart::firstOrCreate(['breeder_id' => $breeder->id]);

            // المتغيرات المبدئية
            $sumPrice = 0;
            $data = [];

            // التعامل مع إضافة الأدوية
            if ($input_data['type'] == 'medicine') {
                $medicine = Medicine::find($item_id);

                if (!$medicine) {
                    $status_code=404;
                    $msg='نوع العنصر غير موجود';
                              }

                // التحقق إذا كان العنصر موجودًا في السلة
                $exists_medicine = $cart->medicines()->where('itemable_id', $medicine->id)->first();

                if ($exists_medicine) {
                    // تحديث الكمية إذا كانت موجودة بالفعل
                    $cartItem=   $cart->medicines()->updateExistingPivot($medicine->id, [
                        'quantity' => $exists_medicine->pivot->quantity +  $input_data['quantity']
                    ]);
                    // $sumPrice += $medicine->price * ($exists_medicine->pivot->quantity + 1);
                } else {
                    // إضافة عنصر جديد للسلة
                    $cart->medicines()->attach($medicine->id, ['quantity' =>  $input_data['quantity']]);
                    // $sumPrice += $medicine->price;
                }


            // التعامل مع إضافة العلف
            } elseif ($input_data['type'] == 'feed') {
                $feed = Feed::find($item_id);

                if (!$feed) {
                    $status_code=404;
                    $msg='نوع العنصر غير موجود';                }

                // التحقق إذا كان العنصر موجودًا في السلة
                $exists_feed = $cart->feeds()->where('itemable_id', $feed->id)->first();

                if ($exists_feed) {
                    // تحديث الكمية إذا كانت موجودة بالفعل
                    $cartItem=    $cart->feeds()->updateExistingPivot($feed->id, [
                        'quantity' => $exists_feed->pivot->quantity + $input_data['quantity']
                    ]);
                    // $sumPrice += $feed->price * ($exists_feed->pivot->quantity + 1);
                } else {
                    // إضافة عنصر جديد للسلة
                    $cartItem=   $cart->feeds()->attach($feed->id, ['quantity' => $input_data['quantity']]);
                    // $sumPrice += $feed->price;
                }

                // $data['sumPrice'] = $sumPrice;

            } else {
                $status_code=404;
                $msg='نوع العنصر غير موجود';
            }
            $sumPrice = $cart->medicines->sum(function($medicine) {
                return $medicine->pivot->quantity * $medicine->price;
            });

            $sumPrice += $cart->feeds->sum(function($feed) {
                return $feed->pivot->quantity * $feed->price;
            });

            DB::commit();
             $data['carts'] = $cart;
             $data['sumPrice'] = $sumPrice;
             $msg='تم الاضافة الى السلة';
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
     /////-------------------------------------------------------
     //getItems

      public function get_items()
      {
        $result=[];
        $data=[];
        $status_code=400;
        $msg='';
$breeder = Auth::guard('breeder')->user();
if ($breeder) {
    $cart = Cart::where('breeder_id', $breeder->id)->first();

    if ($cart) {
        if ($cart->breeder_id !== $breeder->id) {
            $msg = 'غير مصرح لك بالوصول إلى هذه السلة';
            $status_code = 403;
        } else {
            $sumPrice = 0;

            $sumPrice += $cart->medicines->sum(function($medicine) {
                return $medicine->pivot->quantity * $medicine->price;
            });

            $sumPrice += $cart->feeds->sum(function($feed) {
                return $feed->pivot->quantity * $feed->price;
            });

            $data['carts'] = $cart;
            $data['SumPrice'] = $sumPrice ;
            $status_code = 200;
            $msg = 'تم استرجاع العناصر بنجاح';
        }
    } else {
        $msg = 'السلة غير موجودة';
        $status_code = 404;
    }
} else {
    // إذا لم يتم العثور على مربي
    $msg = 'المربي غير مصرح له';
    $status_code = 403;
}

// إعداد النتيجة النهائية للإرجاع
$result = [
    'data' => $data,
    'status_code' => $status_code,
    'msg' => $msg,
];

return $result;

      }

      //--------------------------------------------------------------
      //delete

      public function delete_item(Itemable $item_id){
        $result = [];
        $data = [];
        $status_code = 400;
        $msg = '';

        try {
            // الحصول على المستخدم المصادق عليه
            $breeder = Auth::guard('breeder')->user();
            $cart = Cart::where('breeder_id', '!=', $breeder->id)->first();
            if($cart){
                        $status_code=403;
                        $msg='لا تملك صلاحيات بالحذف';
                     }else{
                            $item_id->delete();
                            $status_code=200;
                            $msg='تم الحذف';
                     }


                }

         catch (Exception $th) {
            // في حالة حدوث خطأ غير متوقع
            Log::debug($th);
            $status_code = 500;
            $msg = 'حدث خطا';
        }

        // إرجاع النتيجة كاستجابة JSON
        $result = [
            'data' => $data,

            'status_code' => $status_code,
            'msg' => $msg,
        ];
        return $result;

    }
}

?>
