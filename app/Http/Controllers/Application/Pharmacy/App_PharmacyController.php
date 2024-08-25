<?php

namespace App\Http\Controllers\Application\Pharmacy;

use App\Models\Pharmacy;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Traits\ApiResponseTrait;
use App\Http\Resources\Pharmacy\PharmacyResource;
use App\Services\Applications\App_PharmacyService;
class App_PharmacyController extends Controller
{
    //
    use ApiResponseTrait;


     public function __construct(protected App_PharmacyService $app_pharmacy_service)
     {

     }

      //get all pharmacy
    public function get_pharmacies()
    {
        $result=$this->app_pharmacy_service->get_pharmacies();
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

    //show pharmacy
    public function get_pharmacy(Pharmacy $pharmacy)
    {
        $result=$this->app_pharmacy_service->get_pharmacy($pharmacy);
        $output = [];
        if ($result['status_code'] == 200) {
            $result_data = $result['data'];
            // response data preparation:
            $output['pharmacy'] = new PharmacyResource($result_data['pharmacy']);

        }
      return $this->send_response($output, $result['msg'], $result['status_code']);

    }

}
