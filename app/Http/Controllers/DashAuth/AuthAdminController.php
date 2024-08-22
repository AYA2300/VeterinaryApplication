<?php

namespace App\Http\Controllers\DashAuth;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Traits\ApiResponseTrait;
use App\Http\Requests\Auth_Admin\Dash_LoginRequest;
use App\Http\Resources\Admin\Auth_Admin\LoginResource;
use App\Services\Dashboard\Auth_Admin\Dash_AuthAdminServices;

class AuthAdminController extends Controller
{
    //
    use ApiResponseTrait;

    public function __construct(protected Dash_AuthAdminServices $auth_admin_service)

    {

    }


    public function login_admin(Dash_LoginRequest $request)
    {
     $input_data=$request->validated();
     $result=$this->auth_admin_service->login_admin($input_data);
     $output = [];
     if ($result['status_code'] == 200) {
         $result_data = $result['data'];
         // response data preparation:
         $output['auth_token']   = $result_data['auth_token'];
         $output['admin'] = new LoginResource($result_data['admin']);
     }

     return $this->send_response($output, $result['msg'], $result['status_code']);

    }
    //logout admin

      public function logout_admin()
      {
        $result=$this->auth_admin_service->logout_admin();
        $output=[];
        if($result['status_code'] == 200){
            $result_data = $result['data'];
            // response data preparation:
        }

        return $this->send_response($output, $result['msg'], $result['status_code']);
      }
///refresh

     public function refresh_admin(Request $request){
        $result = $this->auth_admin_service->refresh_admin();

        $output = [];
        if ($result['status_code'] == 200) {
            $result_data = $result['data'];
            $output['auth_token'] = $result_data['auth_token'];
        }

        return $this->send_response($output, $result['msg'], $result['status_code']);
     }
}
