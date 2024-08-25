<?php
namespace App\Services\Dashboard\Medicines;

use Throwable;
use App\Models\Medicine;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Http\Traits\FileStorageTrait;

class Dash_MedicineService
{
    use FileStorageTrait;


    //get all medicines
    public function get_medicines()
    {
        $data=[];
        $result=[];
        $status_code = 400;
        $msg = '';

           $medicines=Medicine::all();
           $msg='عرض جميع الادوية';
           $status_code=200;
           $data['medicines']=$medicines;

        $result = [
            'data' => $data,
            'status_code' => $status_code,
            'msg' => $msg,
        ];

        return $result;
    }
    //---------------------------------------
    //get single medicine
      public function get_medicine(Medicine $medicine)
      {
        $data=[];
        $result=[];
        $status_code = 400;
        $msg = '';
         $data['medicine']=$medicine;
         $status_code=200;
         $msg='عرض الدواء المطلوب';

        $result = [
            'data' => $data,
            'status_code' => $status_code,
            'msg' => $msg,
        ];

        return $result;

      }
//-----------------------------------------------------------------------------
//add medicine
     public function add_medicine(array $input_data)
     {
        $data=[];
        $result=[];
        $status_code = 400;
        $msg = '';

          try{
            DB::beginTransaction();
            $image=isset($input_data['image'])?$this->storeFile($input_data['image'],'medicines'):'null';
            $medicine=Medicine::create([
             'name' =>$input_data['name'],
             'image'=>$image,
               'expiration_date'=>$input_data['expiration_date']
            ]);
            DB::commit();
            $msg='تم اضافة دواء';
            $status_code=200;
            $data['medicine']=$medicine;
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
     //--------------------------------------
     //update medicine
     public function update_medicine(array $input_data ,Medicine $medicine)
     {
        $data=[];
        $result=[];
        $status_code = 400;
        $msg = '';
        try{
             DB::beginTransaction();
             $newData=[];
             if(isset($input_data['name'])){
                $newData['name']=$input_data['name'];
             }
             if(isset($input_data['image'])){
                $newData['image']=$this->storeFile($input_data['image'],'medicines')??$medicine->image;
             }
             if(isset($input_data['expiration_date'])){
                $newData['expiration_date']=$input_data['expiration_date'];
             }
             $medicine->update($newData);
             DB::commit();
             $msg='تم تعديل الدواء بنجاح';
             $status_code=200;
             $data['medicine']=$medicine;

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
     ////////------------------------------
     //delete

     public function delete_medicine(Medicine $medicine)
     {
        $data=[];
        $result=[];
        $status_code = 400;
        $msg = '';

        if($medicine){
             if($medicine->pharmacies){
                $medicine->pharmacies()->detach();
             }
             $medicine->delete();
             $msg='تم حذف الدواء';
             $status_code=200;
        }
        else{
            $msg='العنصر غير موجود';
            $status_code=404;
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
