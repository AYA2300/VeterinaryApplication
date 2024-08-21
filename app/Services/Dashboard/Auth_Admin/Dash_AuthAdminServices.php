<?php
namespace App\Services\Dashboard\Auth_Admin;

use Illuminate\Support\Facades\Auth;

class Dash_AuthAdminServices{
       //login
       public function login_admin(array $input_data)
       {
        $data = [];
        $status_code = 400;
        $msg = '';
        $result = [];

        $credentials = [
            'email' => $input_data['email'],
            'password' => $input_data['password'],
        ];


        if (!$auth_token = Auth::guard('admin')->attempt($credentials)) {

            $status_code = 404;
            $msg = 'Please Check your email and Password';
        } else {

            $admin = Auth::guard('admin')->user();

            $data = [
                'admin' => $admin,
                'auth_token' => $auth_token,
            ];
            $status_code = 200;
            $msg = 'logged In';
        }
        $result = [
            'data' => $data,
            'status_code' => $status_code,
            'msg' => $msg,
        ];

        return $result;


    }
    //-------------------------------------

    // Refresh auth Token
    public function refresh()
    {
        $data = [];
        $status_code = 400;
        $msg = '';
        $result = [];


        $auth_token = JWTAuth::refresh(JWTAuth::getToken());

        $data = [
            'auth_token' => $auth_token,
        ];
        $status_code = 200;
        $msg = 'Auth Token Refreshed';


        $result = [
            'data' => $data,
            'status_code' => $status_code,
            'msg' => $msg,
        ];

        return $result;

           }
           //logout
           public function logout_admin()
           {
               $data = [];
               $status_code = 400;
               $msg = '';
               $result = [];

               $user = Auth::guard('admin')->user();
               $user->tokens()->delete(); // Or mark tokens as invalid

               // Log out the veterinarian
               Auth::guard('admin')->logout();
               $msg = 'تم تسجيل الخروج بنجاح';
               $status_code = 200;
               $result = [
                   'data' => $data,
                   'status_code' => $status_code,
                   'msg' => $msg,
               ];

               return $result;

           }

}
?>
