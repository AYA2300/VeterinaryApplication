<?php

namespace App\Http\Controllers\Application\Diseases;

use App\Http\Controllers\Controller;
use App\Http\Resources\Diseases\DiseasesResource;
use App\Http\Traits\ApiResponseTrait;
use App\Models\Diseases;
use App\Services\Applications\Diseases\App_Diseases_service;

class App_DiseasesController extends Controller

{
    use ApiResponseTrait;

    public function __construct(protected App_Diseases_service  $app_diseases_service){

    }



    public function get_diseases(){
        $result=$this->app_diseases_service->get_Diseases();
        $output=[];
        if($result['status_code']==200)
        {
            $result_data = $result['data'];
            $paginated = $this->paginate($result_data['Diseases']);
            $output['Diseases'] = DiseasesResource::collection($paginated['data']);
            $output['meta'] = $paginated['meta'];


   }
   return $this->send_response($output, $result['msg'], $result['status_code']);



    }

    public function get_disease(Diseases $disease){
        $result=$this->app_diseases_service->get_Disease($disease);
        if($result['status_code']==200)
        {
                $result_data = $result['data'];
                // response data preparation:
                $output['Diseases'] = new DiseasesResource($result_data['Diseases']);

   }
   return $this->send_response($output, $result['msg'], $result['status_code']);



}
}
