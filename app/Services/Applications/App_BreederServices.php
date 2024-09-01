<?php
namespace App\Services\Applications;

use App\Models\Breeder;

 class App_BreederServices{


      //get all
       public function get_Breeders()
       {
        $data=[];
        $result=[];
        $status_code = 400;
        $msg = '';

        $Breeders=Breeder::all();
         $data['Breeders'] =$Breeders;
         $msg='Get all Breeders';
         $status_code=200;
        $result = [
            'data' => $data,
            'status_code' => $status_code,
            'msg' => $msg,
        ];

        return $result;

       }
       public function get_Breeder(Breeder $Breeder)
       {
        $data=[];
        $result=[];
        $status_code = 400;
        $msg = '';

         $data['Breeder'] =$Breeder;
         $msg='Get  Breeder';
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
