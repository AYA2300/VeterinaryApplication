<?php
namespace App\Services\Applications;

use App\Models\Veterinarian;

 class App_VeterinarianServices{


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
 }










?>
