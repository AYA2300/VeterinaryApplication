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

            // التحقق من المستخدم المسجل كمربي
            if (Auth::guard('breeder')->check()) {
                $user = Auth::guard('breeder')->user();
            } elseif (Auth::guard('veterinarian')->check()) {
                $user = Auth::guard('veterinarian')->user();
            }
                // إنشاء أو تحديث السلة بناءً على معرف المستخدم
            $cart = $user->cart()->updateOrCreate(
                [
                    'userable_id' => $user->id,  // معرف المستخدم
                    'userable_type' => get_class($user),  // نوع المستخدم (Breeder أو Veterinarian)
                ],
                [
                    // الحقول الأخرى التي ترغب في تعيينها أو تحديثها
                ]
            );




            // الحصول على السلة أو إنشائها للمربي

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
         $result = [];
         $data = [];
         $status_code = 400;
         $msg = '';

         // الحصول على المستخدم الحالي بناءً على نوع الحارس
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
             $msg = 'لا تملك صلاحيات للوصول إلى هذه السلة';
             $status_code = 403;
         } else {
             // حساب السعر الإجمالي
             $sumPrice = $cart->medicines->sum(function($medicine) {
                 return $medicine->pivot->quantity * $medicine->price;
             });

             $sumPrice += $cart->feeds->sum(function($feed) {
                 return $feed->pivot->quantity * $feed->price;
             });

             // إعداد البيانات للاستجابة
             $data['carts'] = $cart;
             $data['SumPrice'] = $sumPrice;
             $status_code = 200;
             $msg = 'تم استرجاع العناصر بنجاح';
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

        $user = null;
        $user_type = null;
try{
        if (Auth::guard('breeder')->check()) {
            $user = Auth::guard('breeder')->user();
            $user_type = 'App\Models\Breeder'; // افتراض أن هذا هو الـ namespace للنموذج
        } elseif (Auth::guard('veterinarian')->check()) {
            $user = Auth::guard('veterinarian')->user();
            $user_type = 'App\Models\Veterinarian'; // افتراض أن هذا هو الـ namespace للنموذج
        }

        if ($user) {
            // البحث عن السلة المرتبطة بالمستخدم بناءً على النوع
            $cart = Cart::where('userable_id', $user->id)
                        ->where('userable_type', $user_type)
                        ->first();

            if (!$cart) {
                // إذا لم يتم العثور على سلة
                $msg = 'لم يتم العثور على سلة لهذا المستخدم';
                $status_code = 404;
            } else {
                // التحقق من أن العنصر موجود وحذفه
                if (isset($item_id)) {
                    $item_id->delete();
                    $status_code = 200;
                    $msg = 'تم الحذف بنجاح';
                } else {
                    $msg = 'العنصر غير موجود';
                    $status_code = 404;
                }
            }
        } else {
            // إذا لم يتم العثور على مستخدم مصادق عليه
            $msg = 'يجب تسجيل الدخول للوصول إلى هذه الصفحة';
            $status_code = 401;
        }
    }

    catch (\Exception $e) {
        // في حالة حدوث خطأ غير متوقع
        $msg = 'حدث خطأ غير متوقع';
        $status_code = 500;
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
