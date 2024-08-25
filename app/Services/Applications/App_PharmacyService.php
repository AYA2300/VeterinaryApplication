<?php
namespace App\Services\Applications;

use App\Models\Pharmacy;

  class App_PharmacyService
  {
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


  }












?>
