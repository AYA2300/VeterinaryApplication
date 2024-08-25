<?php
namespace App\Services\Applications;

use App\Models\Medicine;

 class App_MedicineService{
    //get all
    public function get_medicines()
    {
        $data=[];
        $result=[];
        $status_code = 400;
        $msg = '';
       $medicines=Medicine::all();
       $status_code=200;
       $msg='عرض كل الادوية ';
       $data['medicines']=$medicines;

        $result = [
            'data' => $data,
            'status_code' => $status_code,
            'msg' => $msg,
        ];

        return $result;
    }

//--------------------------
//show
public function get_medicine(Medicine $medicine)
{
    $data=[];
    $result=[];
    $status_code = 400;
    $msg = '';
   $status_code=200;
   $msg='عرض الدواء المطلوب';
   $data['medicine']=$medicine;

    $result = [
        'data' => $data,
        'status_code' => $status_code,
        'msg' => $msg,
    ];

    return $result;
}

 }
?>
