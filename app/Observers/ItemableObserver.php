<?php

namespace App\Observers;

use App\Models\Cart;
use App\Models\Itemable;

class ItemableObserver
{
    /**
     * Handle the Itemable "created" event.
     */
    public function created(Itemable $itemable): void
    {
        //
       // الحصول على السلة المرتبطة بالعناصر
       $cart = Cart::find($itemable->cart_id);

       // التحقق من وجود السلة
       if ($cart) {
           // حساب السعر الإجمالي لجميع العناصر في السلة
           $totalPrice = $cart->items->sum(function ($item) {
               // الحصول على السعر من العنصر سواء كان دواء أو علف
               $itemable = $item->itemable;

               return $itemable->price * $item->pivot->quantity;
           });

           // تحديث السعر الإجمالي في السلة
           $cart->total_price = $totalPrice;
           $cart->save();
       }
    }

    /**
     * Handle the Itemable "updated" event.
     */
    public function updated(Itemable $itemable): void
    {
        //
    }

    /**
     * Handle the Itemable "deleted" event.
     */
    public function deleted(Itemable $itemable): void
    {
        //
    }

    /**
     * Handle the Itemable "restored" event.
     */
    public function restored(Itemable $itemable): void
    {
        //
    }

    /**
     * Handle the Itemable "force deleted" event.
     */
    public function forceDeleted(Itemable $itemable): void
    {
        //
    }
}
