<?php

namespace App\Http\Controllers\Dashboard\Diseases;

use App\Http\Controllers\Controller;
use App\Http\Requests\Diseases\Add_DiseasesRequest;
use App\Http\Requests\Diseases\Edit_DiseasesRequest;
use App\Http\Resources\Diseases\DiseasesResource;
use App\Http\Traits\ApiResponseTrait;
use App\Http\Traits\FileStorageTrait;
use App\Models\Diseases;
use App\Services\Dashboard\Diseases\Dash_DiseasesServices;


class Dash_DiseasesController extends Controller
{
    use ApiResponseTrait,FileStorageTrait;

    public function __construct(protected Dash_DiseasesServices  $dash_diseases_service){

    }
    public function add_disease(Add_DiseasesRequest $request){
        $input_data=$request->validated();
        $result=$this->dash_diseases_service->add_Diseas($input_data);
        $output=[];

        if($result['status_code']==200)
        {
                $result_data = $result['data'];
                // response data preparation:
                $output['Diseases'] = new DiseasesResource($result_data['Diseases']);

   }
   return $this->send_response($output, $result['msg'], $result['status_code']);

    }

    public function update_disease(Edit_DiseasesRequest $request,$id){
        $input_data=$request->validated();
        $result=$this->dash_diseases_service->update_Diseas($input_data ,$id);
        $output=[];

            if($result['status_code']==200)
            {
                    $result_data = $result['data'];
                  //  dd($result_data);
                    // response data preparation:
                //    $output['Disease'] = $result_data;
                    $output['Diseases'] = new DiseasesResource($result_data['Diseases']);


        }


   return $this->send_response($output, $result['msg'], $result['status_code']);


        }


    public function get_diseases(){
        $result=$this->dash_diseases_service->get_Diseases();
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
        $result=$this->dash_diseases_service->get_Disease($disease);
        if($result['status_code']==200)
        {
                $result_data = $result['data'];
                // response data preparation:
                $output['Diseases'] = new DiseasesResource($result_data['Diseases']);

   }
   return $this->send_response($output, $result['msg'], $result['status_code']);




    }

    public function delete_disease(Diseases $disease){
        $result=$this->dash_diseases_service->delete_Disease($disease);

   return $this->send_response('', $result['msg'], $result['status_code']);


    }






}
