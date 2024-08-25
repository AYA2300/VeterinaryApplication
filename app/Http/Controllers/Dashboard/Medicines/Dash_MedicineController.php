<?php

namespace App\Http\Controllers\Dashboard\Medicines;

use App\Models\Medicine;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Traits\ApiResponseTrait;
use App\Http\Resources\Medicine\MedicineResource;
use App\Http\Requests\Medicines\CreateMedicineRequest;
use App\Http\Requests\Medicines\UpdateMedicineRequest;
use App\Services\Dashboard\Medicines\Dash_MedicineService;

class Dash_MedicineController extends Controller
{
    //
    use ApiResponseTrait;

    public function __construct(protected Dash_MedicineService $dash_medicine_service)

    {


    }
    //get all medicines

     public function get_medicines()
     {
        $result=$this->dash_medicine_service->get_medicines();
        if ($result['status_code'] == 200) {
            $result_data = $result['data'];
            // response data preparation:
            $paginated = $this->paginate($result_data['medicines']);
            $output['medicines'] = MedicineResource::collection($paginated['data']);
            $output['meta'] = $paginated['meta'];

        }
      return $this->send_response($output, $result['msg'], $result['status_code']);

     }
     //---------------------------
     //get medicine
     public function get_medicine(Medicine $medicine){
        $result=$this->dash_medicine_service->get_medicine($medicine);
        if ($result['status_code'] == 200) {
            $result_data = $result['data'];
            // response data preparation:
            $output['medicine'] = new MedicineResource($result_data['medicine']);

        }
      return $this->send_response($output, $result['msg'], $result['status_code']);

     }
     //----------------------------------------------
    //add medicine
    public function add_medicine(CreateMedicineRequest $request)
    {
        $input_data=$request->validated();
        $result=$this->dash_medicine_service->add_medicine($input_data);
        $output=[];
        if($result['status_code']==200)
        {
                $result_data = $result['data'];
                // response data preparation:
                $output['medicine'] = new MedicineResource($result_data['medicine']);

   }
   return $this->send_response($output, $result['msg'], $result['status_code']);

}
//----------------------------------------------------
  public function update_medicine(UpdateMedicineRequest $request,Medicine $medicine)
  {
    $input_data=$request->validated();
     $result=$this->dash_medicine_service->update_medicine($input_data,$medicine);
     $output=[];
     if($result['status_code']==200)
     {
             $result_data = $result['data'];
             // response data preparation:
             $output['medicine'] = new MedicineResource($result_data['medicine']);

}
return $this->send_response($output, $result['msg'], $result['status_code']);

  }
  //delete

   public function delete_medicine(Medicine $medicine)
   {
    $result=$this->dash_medicine_service->delete_medicine($medicine);

 $output=[];
     if($result['status_code']==200)
     {
             $result_data = $result['data'];

}
return $this->send_response($output, $result['msg'], $result['status_code']);

   }
}
