<?php

namespace App\Http\Controllers\Breeder;

use App\Http\Controllers\Controller;
use App\Http\Requests\Login_BreederRequest;
use App\Http\Requests\Register_BreederRequest;
use App\Http\Resources\Auth_BreederResource;
use App\Http\Traits\ApiResponseTrait;
use App\Services\Breeder\Auth_BreederService;
use Illuminate\Http\Request;

class Auth_BreederController extends Controller
{

    use ApiResponseTrait;

    public function __construct(protected Auth_BreederService $auth_BreederService)
    {

    }

    public function register_breeder(Register_BreederRequest $request){
        $input_data=$request->validated();
        $result=$this->auth_BreederService->register_breeder($input_data);
        $output=[];
        if ($result['status_code'] == 200) {
            $result_data = $result['data'];
            // response data preparation:
            $output['auth_token']   = $result_data['auth_token'];
            $output['Breeder']= new Auth_BreederResource($result_data['Breeder']);


    }
    return $this->send_response($output, $result['msg'], $result['status_code']);

}

public function login_breeder(Login_BreederRequest $request)
{
 $input_data=$request->validated();
 $result=$this->auth_BreederService->login_breeder($input_data);
 $output = [];
 if ($result['status_code'] == 200) {
     $result_data = $result['data'];
     // response data preparation:
     $output['auth_token']   = $result_data['auth_token'];
     $output['Breeder']= new Auth_BreederResource($result_data['Breeder']);
    }

 return $this->send_response($output, $result['msg'], $result['status_code']);


}

public function logout_breeder()
{
    $result = $this->auth_BreederService->logout_breeder();

    $output = [];
    if ($result['status_code'] == 200) {
        $result_data = $result['data'];
        // response data preparation:
    }

    return $this->send_response($output, $result['msg'], $result['status_code']);

}

public function refresh(Request $request)
    {
        $result = $this->auth_BreederService->refresh_token();

        $output = [];
        if ($result['status_code'] == 200) {
            $result_data = $result['data'];
            $output['auth_token'] = $result_data['auth_token'];
        }

        return $this->send_response($output, $result['msg'], $result['status_code']);
    }





}
