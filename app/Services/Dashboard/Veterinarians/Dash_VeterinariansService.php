<?php
namespace App\Services\Dashboard\Veterinarians;

use App\Models\Veterinarian;

    class Dash_VeterinariansService
    {
//get all
  public function get_veterinarians()
       {
        $data=[];
        $result=[];
        $status_code = 400;
        $msg = '';

        $veterinarians=Veterinarian::all();
         $data['Veterinarians'] =$veterinarians;
         $msg='Get all veterinarians';
         $status_code=200;
        $result = [
            'data' => $data,
            'status_code' => $status_code,
            'msg' => $msg,
        ];

        return $result;

       }
       //get single
       public function get_veterinarian(Veterinarian $veterinarian)
       {
        $data=[];
        $result=[];
        $status_code = 400;
        $msg = '';

         $data['veterinarian'] =$veterinarian;
         $msg='Get  veterinarian';
         $status_code=200;
        $result = [
            'data' => $data,
            'status_code' => $status_code,
            'msg' => $msg,
        ];

        return $result;

       }
       //delete
       public function delete_veterinarian(Veterinarian $veterinarian)
       {
        $data=[];
        $result=[];
        $status_code = 400;
        $msg = '';
         if(!$veterinarian){
            $msg='الطبيب غير موجود';
            $status_code=404;
         }
         $veterinarian->tokens()->delete();
         $veterinarian->delete();
         $msg='تم حذف الطبيب';
         $status_code=200;
        $result = [
            'data' => $data,
            'status_code' => $status_code,
            'msg' => $msg,
        ];

        return $result;

       }

    }




?>
