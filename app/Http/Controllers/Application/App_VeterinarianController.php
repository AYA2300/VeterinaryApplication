<?php

namespace App\Http\Controllers\Application;

use App\Models\Veterinarian;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Traits\ApiResponseTrait;
use App\Services\Applications\App_VeterinarianServices;
use App\Http\Resources\Veterinarian\Auth_VeterinarianResource;

class App_VeterinarianController extends Controller
{
    //
    use ApiResponseTrait;

     public function __construct(protected App_VeterinarianServices $app_veterinarian_service)
    {



     }
     //get all Veterinarians
     public function get_veterinarians()
     {
         $result= $this->app_veterinarian_service->get_veterinarians();
         $output = [];
         if ($result['status_code'] == 200) {
             $result_data = $result['data'];
             // response data preparation:
             $paginated = $this->paginate($result_data['Veterinarians']);
             $output['Veterinarians'] = Auth_VeterinarianResource::collection($paginated['data']);
             $output['meta'] = $paginated['meta'];

         }
       return $this->send_response($output, $result['msg'], $result['status_code']);

     }
     //-------------------------------------------
     //get single Veterinarian
     public function get_veterinarian(Veterinarian $veterinarian)
     {
         $result= $this->app_veterinarian_service->get_veterinarian($veterinarian);
         $output = [];
         if ($result['status_code'] == 200) {
             $result_data = $result['data'];
             // response data preparation:
             $output['veterinarian'] = new Auth_VeterinarianResource($result_data['veterinarian']);


         }
       return $this->send_response($output, $result['msg'], $result['status_code']);

     }

}
