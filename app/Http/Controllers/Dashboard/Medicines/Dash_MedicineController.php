<?php

namespace App\Http\Controllers\Dashboard\Medicines;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Traits\ApiResponseTrait;
use App\Http\Resources\Medicine\MedicineResource;
use App\Http\Requests\Medicines\CreateMedicineRequest;
use App\Services\Dashboard\Medicines\Dash_MedicineService;

class Dash_MedicineController extends Controller
{
    //
    use ApiResponseTrait;

    public function __construct(protected Dash_MedicineService $dash_medicine_service)

    {

    }
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
}
