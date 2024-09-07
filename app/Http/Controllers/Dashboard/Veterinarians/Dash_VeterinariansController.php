<?php

namespace App\Http\Controllers\Dashboard\Veterinarians;

use App\Models\Veterinarian;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Traits\ApiResponseTrait;
use App\Http\Resources\Veterinarian\Auth_VeterinarianResource;
use App\Services\Dashboard\Veterinarians\Dash_VeterinariansService;

class Dash_VeterinariansController extends Controller
{
    //
    use ApiResponseTrait;

    public function __construct(protected Dash_VeterinariansService $dash_veterinarians_services)

    {
        $this->middleware(['auth:admin', 'role:admin']);

    }

    //get all
    public function get_veterinarians()
    {
        $result= $this->dash_veterinarians_services->get_veterinarians();
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
    //----------------------------------------
    /* get single veterinarian*/
    public function get_veterinarian(Veterinarian $veterinarian)
    {
        $result= $this->dash_veterinarians_services->get_veterinarian($veterinarian);
        $output = [];
        if ($result['status_code'] == 200) {
            $result_data = $result['data'];
            // response data preparation:
            $output['veterinarian'] = new Auth_VeterinarianResource($result_data['veterinarian']);


        }
      return $this->send_response($output, $result['msg'], $result['status_code']);

    }
//-----------------------------------------
  public function delete_veterinarian(Veterinarian $veterinarian ){
    $result=$this->dash_veterinarians_services->delete_veterinarian($veterinarian);

    $output=[];
    if($result['status_code']==200){
        $result_data = $result['data'];
    }

    return $this->send_response($output, $result['msg'], $result['status_code']);

  }

  public function edit_approved(Veterinarian $veterinarian){


    $result=$this->dash_veterinarians_services->edit_approved($veterinarian);

    $output=[];
    if($result['status_code']==200){

    }

    return $this->send_response($output, $result['msg'], $result['status_code']);



  }




}
