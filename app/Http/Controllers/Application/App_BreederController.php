<?php

namespace App\Http\Controllers\Application;

use App\Http\Controllers\Controller;
use App\Http\Resources\Breeder\Auth_BreederResource;
use App\Http\Traits\ApiResponseTrait;
use App\Models\Breeder;
use App\Services\Applications\App_BreederServices;
use Illuminate\Http\Request;

class App_BreederController extends Controller
{
    use ApiResponseTrait;

     public function __construct(protected App_BreederServices $app_Breeder_service)
    {



     }
     //get all Breeders
     public function get_Breeders()
     {
         $result= $this->app_Breeder_service->get_Breeders();
         $output = [];
         if ($result['status_code'] == 200) {
             $result_data = $result['data'];
             // response data preparation:
             $paginated = $this->paginate($result_data['Breeders']);
             $output['Breeders'] = Auth_BreederResource::collection($paginated['data']);
             $output['meta'] = $paginated['meta'];

         }
       return $this->send_response($output, $result['msg'], $result['status_code']);

     }
     //-------------------------------------------
     //get single Breeder
     public function get_Breeder(Breeder $Breeder)
     {
         $result= $this->app_Breeder_service->get_Breeder($Breeder);
         $output = [];
         if ($result['status_code'] == 200) {
             $result_data = $result['data'];
             // response data preparation:
             $output['Breeder'] = new Auth_BreederResource($result_data['Breeder']);


         }
       return $this->send_response($output, $result['msg'], $result['status_code']);

     }

}


