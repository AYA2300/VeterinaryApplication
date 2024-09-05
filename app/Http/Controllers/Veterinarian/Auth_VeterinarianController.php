<?php

namespace App\Http\Controllers\Veterinarian;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Traits\ApiResponseTrait;
use App\Services\Veterinarian\Auth_VeterinarianService;
use  App\Http\Resources\Veterinarian\Auth_VeterinarianResource;
use App\Http\Requests\Auth_VeterinarianRequest\Login_VeterinarianRequest;
use App\Http\Requests\Auth_VeterinarianRequest\Register_VeterinarRequest;

class Auth_VeterinarianController extends Controller
{
    use ApiResponseTrait;

    //

    public function __construct(protected Auth_VeterinarianService $auth_veterian_service){

    }
    public function register_veterinarian(Register_VeterinarRequest $request)
    {
     $input_data=$request->validated();
     $result=$this->auth_veterian_service->register_veterinarian($input_data);
     $output = [];
     if ($result['status_code'] == 200) {
        $result_data = $result['data'];
          // response data preparation:
         $output['auth_token']   = $result_data['auth_token'];
         $output['Veterinarian'] = new Auth_VeterinarianResource($result_data['Veterinarian']);
     }

     return $this->send_response($output, $result['msg'], $result['status_code']);


    }

    //login
    public function login_veterinarian(Login_VeterinarianRequest $request)
    {
     $input_data=$request->validated();
     $result=$this->auth_veterian_service->login_veterinarian($input_data);
     $output = [];
     if ($result['status_code'] == 200) {
         $result_data = $result['data'];
         // response data preparation:
         $output['auth_token']   = $result_data['auth_token'];
         $output['veterinarian'] = new Auth_VeterinarianResource($result_data['veterinarian']);
     }

     return $this->send_response($output, $result['msg'], $result['status_code']);


    }


    public function login(Login_VeterinarianRequest $request)
    {
     $input_data=$request->validated();
     $result=$this->auth_veterian_service->login($input_data);
     $output = [];
     if ($result['status_code'] == 200) {
         $result_data = $result['data'];
         // response data preparation:
        // $output['auth_token']   = $result_data['auth_token'];
         //$output['data'] = new Auth_VeterinarianResource($result_data['data']);
     }

     return $this->send_response($result, $result['msg'], $result['status_code']);


    }






    public function logout_veterinarian()
    {
        $result = $this->auth_veterian_service->logout_veterinarian();

        $output = [];
        if ($result['status_code'] == 200) {
            $result_data = $result['data'];
            // response data preparation:
        }

        return $this->send_response($output, $result['msg'], $result['status_code']);

    }


    public function refresh(Request $request)
    {
        $result = $this->auth_veterian_service->refresh_token();

        $output = [];
        if ($result['status_code'] == 200) {
            $result_data = $result['data'];
            $output['auth_token'] = $result_data['auth_token'];
        }

        return $this->send_response($output, $result['msg'], $result['status_code']);
    }


}
