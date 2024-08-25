<?php

namespace App\Http\Controllers\Dashboard\Pharmacy;

use App\Models\Medicine;
use App\Models\Pharmacy;
use Illuminate\Http\Request;
use App\Models\PharmacyMedicine;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use App\Http\Traits\ApiResponseTrait;
use App\Http\Resources\Pharmacy\PharmacyResource;
use App\Http\Requests\Pharmacy\CreatePharmacyRequest;
use App\Http\Requests\Pharmacy\UpdatePharmacyRequest;
use App\Services\Dashboard\Pharmacy\Dash_PharmacyService;
use App\Http\Requests\Pharmacy\AddMedicinePharmacyRequest;
use App\Http\Resources\Pharmacy\PharmacyMedicinesResource;

class PharmacyController extends Controller
{
    use ApiResponseTrait;

    //


    public function __construct(protected Dash_PharmacyService $dash_pharmacy_service)

    {

    }

    //get all
    public function get_pharmacies()
    {
        $result=$this->dash_pharmacy_service->get_pharmacies();
        $output = [];
        if ($result['status_code'] == 200) {
            $result_data = $result['data'];
            // response data preparation:
            $paginated = $this->paginate($result_data['pharmacies']);
            $output['pharmacies'] = PharmacyResource::collection($paginated['data']);
            $output['meta'] = $paginated['meta'];

        }
      return $this->send_response($output, $result['msg'], $result['status_code']);

    }

    //show
    public function get_pharmacy(Pharmacy $pharmacy)
    {
        $result=$this->dash_pharmacy_service->get_pharmacy($pharmacy);
        $output = [];
        if ($result['status_code'] == 200) {
            $result_data = $result['data'];
            // response data preparation:
            $output['pharmacy'] = new PharmacyResource($result_data['pharmacy']);

        }
      return $this->send_response($output, $result['msg'], $result['status_code']);

    }

    //add

    public function add_pharmacy(CreatePharmacyRequest $request)

    {

        $input_data=$request->validated();
        $result=$this->dash_pharmacy_service->add_pharmacy($input_data);
        $output=[];
        if($result['status_code']==200)
        {
                $result_data = $result['data'];
                // response data preparation:
                $output['pharmacy'] = new PharmacyResource($result_data['pharmacy']);

   }
   return $this->send_response($output, $result['msg'], $result['status_code']);

}

//update pharmacy
public function update_pharmacy(UpdatePharmacyRequest $request, Pharmacy $pharmacy)
{
    $input_data=$request->validated();
    $result=$this->dash_pharmacy_service->update_pharmacy($input_data,$pharmacy);
    $output=[];
    if($result['status_code']==200)
    {
            $result_data = $result['data'];
            // response data preparation:
            $output['pharmacy'] = new PharmacyResource($result_data['pharmacy']);


}
return $this->send_response($output, $result['msg'], $result['status_code']);

}

//delete

    public function delete_pharmacy(Pharmacy $pharmacy)
{

   $result=$this->dash_pharmacy_service->delete_pharmacy($pharmacy);
    $output=[];
    if($result['status_code']==200)
    {
            $result_data = $result['data'];
            // response data preparation:

}
return $this->send_response($output, $result['msg'], $result['status_code']);

}
//add medicin to pharmacy and price
public function addPriceMedicinToPharmacy(AddMedicinePharmacyRequest $request,Pharmacy $pharmacy,Medicine $medicine)
{
    $input_data=$request->validated();
    $result=$this->dash_pharmacy_service->addPriceMedicinToPharmacy($input_data,$pharmacy,$medicine);
    $output=[];
    if($result['status_code']==200)
    {
            $result_data = $result['data'];
            // response data preparation:
            $output['pharmacy_medicines'] = new PharmacyMedicinesResource($result_data['pharmacy_medicines']);
    }
    return $this->send_response($output, $result['msg'], $result['status_code']);

}

}
