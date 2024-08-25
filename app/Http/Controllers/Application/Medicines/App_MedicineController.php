<?php

namespace App\Http\Controllers\Application\Medicines;

use App\Models\Medicine;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Traits\ApiResponseTrait;
use App\Http\Resources\Medicine\MedicineResource;
use App\Services\Applications\App_MedicineService;

class App_MedicineController extends Controller
{
    //
    use ApiResponseTrait;


      public function __construct(protected App_MedicineService $app_medicine_service)
      {

      }
      public function get_medicines()
      {
          $result=$this->app_medicine_service->get_medicines();
          $output = [];
          if ($result['status_code'] == 200) {
              $result_data = $result['data'];
              // response data preparation:
              $paginated = $this->paginate($result_data['medicines']);
              $output['medicines'] = MedicineResource::collection($paginated['data']);
              $output['meta'] = $paginated['meta'];

          }
        return $this->send_response($output, $result['msg'], $result['status_code']);

      }

      //show pharmacy
      public function get_medicine(Medicine $medicine)
      {
          $result=$this->app_medicine_service->get_medicine($medicine);
          $output = [];
          if ($result['status_code'] == 200) {
              $result_data = $result['data'];
              // response data preparation:
              $output['medicine'] = new MedicineResource($result_data['medicine']);

          }
        return $this->send_response($output, $result['msg'], $result['status_code']);

      }

}
