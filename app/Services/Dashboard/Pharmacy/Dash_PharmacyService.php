<?php
namespace App\Services\Dashboard\Pharmacy;

use App\Models\Medicine;
use App\Models\Pharmacy;
use App\Models\PharmacyMedicine;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Http\Resources\Pharmacy\PharmacyResource;

 class Dash_PharmacyService{

    //get all
    public function get_pharmacies()
    {
        $data=[];
        $result=[];
        $status_code = 400;
        $msg = '';
       $pharmacies=Pharmacy::all();
       $status_code=200;
       $msg='عرض كل الصيدليات';
       $data['pharmacies']=$pharmacies;

        $result = [
            'data' => $data,
            'status_code' => $status_code,
            'msg' => $msg,
        ];

        return $result;
    }

//--------------------------
//show
public function get_pharmacy(Pharmacy $pharmacy)
{
    $data=[];
    $result=[];
    $status_code = 400;
    $msg = '';
   $status_code=200;
   $msg='عرض الصيدلية المطلوبة';
   $data['pharmacy']=$pharmacy;

    $result = [
        'data' => $data,
        'status_code' => $status_code,
        'msg' => $msg,
    ];

    return $result;
}

    //add pharmacy

    public function add_pharmacy(array $input_data)
    {
        $data=[];
        $result=[];
        $status_code = 400;
        $msg = '';
    try{
            DB::beginTransaction();
            $pharmacy=Pharmacy::create([
               'name'=>$input_data['name'],
               'owner' =>$input_data['owner']??'owner',
               'open_time' => $input_data['open_time'],
               'close_time' => $input_data['close_time'],
               'address' => $input_data['address']
            ]);

            DB::commit();
            $status_code=200;
            $msg="add pharmacy";
            $data['pharmacy']=$pharmacy;

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
    //update

    public function update_pharmacy(array $input_data,Pharmacy $pharmacy)
    {
        $data=[];
        $result=[];
        $status_code = 400;
        $msg = '';
        try{
            DB::beginTransaction();
            $newData=[];
            if(isset($input_data['name'])){
                $pharmacy->name=$input_data['name'];
            }
            if(isset($input_data['owner'])){
                $pharmacy->owner=$input_data['owner'];
            }
            if(isset($input_data['open_time'])){
                $pharmacy->open_time=$input_data['open_time'];
            }
            if(isset($input_data['close_time'])){
                $pharmacy->close_time=$input_data['close_time'];
            }
            if(isset($input_data['address'])){
                $pharmacy->address=$input_data['address'];
            }
            $pharmacy->update($newData);
            if (isset($input_data['medicines']) && is_array($input_data['medicines'])) {
                $medicines = [];

                foreach ($input_data['medicines'] as $medicine) {
                    if (isset($medicine['medicine_id']) && isset($medicine['price'])) {
                        $medicines[$medicine['medicine_id']] = ['price' => $medicine['price']];
                    }
                }

                // مزامنة الأدوية مع الصيدلية بدون حذف العلاقات السابقة
                $pharmacy->medicines()->syncWithoutDetaching($medicines);
            }


            DB::commit();

            $status_code=200;
            $msg="تم تعديل الصيدلية";
            $data['pharmacy']=$pharmacy;

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
    ///////////
    //delete
    public function delete_pharmacy(Pharmacy $pharmacy)
    {
        $data=[];
        $result=[];
        $status_code = 400;
        $msg = '';
        if($pharmacy){
            if($pharmacy->medicines){
              $pharmacy->medicines()->detach();
            }
            $pharmacy->delete();
            $status_code=200;
            $msg='تم الحذف';

        }
        else{
            $status_code=404;
            $msg='العنصر غير موجود';

        }
        $result = [
            'data' => $data,
            'status_code' => $status_code,
            'msg' => $msg,
        ];

        return $result;
    }
    //-------------
    //addMedicinePharmacyPrice by id

       public function addPriceMedicinToPharmacy(array $input_data,Pharmacy $pharmacy,Medicine $medicine)
       {
        $data=[];
        $result=[];
        $status_code = 400;
        $msg = '';

         try{
             Db::beginTransaction();

             $add_price=PharmacyMedicine::updateOrCreate( [
                'medicine_id' => $medicine->id,
                'pharmacy_id' => $pharmacy->id,
            ],
            [
                'medicine_id' => $medicine->id,
                'pharmacy_id' => $pharmacy->id,
                'price' => $input_data['price'],
            ]);
            DB::commit();
            $msg='اضافة الادوية للصيدلية';
            $status_code=200;
            $data['pharmacy_medicines'] =$add_price;

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
