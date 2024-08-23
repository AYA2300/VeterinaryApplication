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
}


?>
