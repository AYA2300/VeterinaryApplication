<?php
namespace App\Services\Dashboard\Community;

use App\Http\Traits\FileStorageTrait;
use App\Models\AnimalCategorie;
use App\Models\Community;
use Illuminate\Support\Facades\Auth;

class CommunityServices{
   use FileStorageTrait;



public function createCommunity(array $input_data)
    {

        $data=[];
        $status_code=400;
        $msg='';
        $result=[];
        // التحقق من أن المستخدم هو Admin
        if (Auth::guard('admin')->check()) {
            $admin = Auth::guard('admin')->user();
        }

        $animalType = AnimalCategorie::find($input_data['animal_categorie_id']);


        // إنشاء المجتمع
        $community = Community::create([
            'name' => $input_data['name'],
            'image' => isset($input_data['image'])?$this->storeFile($input_data['image'],'CommunityImage'):'null',
            'animal_categorie_id'=>$animalType->id

        ]);

        // إضافة Admin إلى المجتمع
        $community->admins()->attach($admin->id);

        $data['community']=$community;
        $status_code = 200;
        $msg = 'Community created with Admin as supervisor ';


        $result =[
            'data' => $data,
            'status_code' => $status_code,
            'msg' => $msg,

        ];
        return $result;
    }

}

?>
