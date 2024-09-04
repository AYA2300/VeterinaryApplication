<?php
    namespace App\Services\Breeder;

use App\Models\Breeder;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Laravel\Sanctum\HasApiTokens;
use PHPOpenSourceSaver\JWTAuth\Facades\JWTAuth;
use Spatie\Permission\Models\Role;
use Throwable;

    class Auth_BreederService{
        use HasApiTokens;

        public function register_breeder(array $input_data)
        {
            $data=[];
            $status_code=400;
            $msg='';
            $result=[];

            // try{
            //     DB::beginTransaction();
                $breeder=Breeder::create([
                    'name'=>$input_data['name'],
                    'password'=>Hash::make($input_data['password']),
                    'confirm_password' => Hash::make($input_data['confirm_password']),
                    'phone_number'=>$input_data['phone_number'],
                    'role'=>'breeder',
                    'region'=>$input_data['region'],


                ]);

          if (isset($input_data['animal_categorie_id']) || is_array(isset($input_data['animal_categorie_id']))) {

                   $breeder->animalCategories()->attach($input_data['animal_categorie_id']);
                 }




                $breeder->assignRole(Role::where('name','breeder')->first());
                $auth_token=JWTAuth::fromUser($breeder);

                DB::commit();

                $data['Breeder']=$breeder;
                $data['auth_token']=$auth_token;
                $status_code = 200;;
                $msg = 'Register Breeder ';


            // }catch(Throwable $th){
            //     DB::roleBack();
            //     Log::debug($th);
            //     $status_code = 500;;
            //     $data = $th;
            //     $msg = 'error ' . $th->getMessage();

            // }
            $result =[
                'data' => $data,
                'status_code' => $status_code,
                'msg' => $msg,

            ];
            return $result;

        }

        public function login_breeder(array $input_data){

            $data = [];
            $status_code = 400;
            $msg = '';
            $result = [];

            $credentials=[
                'phone_number'=>$input_data['phone_number'],
                'password'=>$input_data['password']
            ];

            if(!$auth_token =Auth::guard('breeder')->attempt($credentials)){
                $status_code=404;
                $msg ='Please Check your number and password';

            }
            else{
                $breeder=Auth::guard('breeder')->user();

                $data=[
                    'breeder'=>$breeder,
                    'auth_token'=>$auth_token
                ];

                $status_code=200;
                $msg="logged in";

                $result=[
                    'data'=>$data,
                    'status_code'=>$status_code,
                    'msg'=>$msg
                ];
                return $result;

            }


        }

        public function logout_breeder(){


                $status_code = 400;
                $msg = '';
                $result = [];

                $user=Auth::guard('breeder')->user();
                $user->tokens()->delete();
                Auth::guard('breeder')->logout();

                $msg='logged out';
                $status_code=200;
                $result=[
                    'status_code'=>$status_code,
                    'msg'=>$msg




                ];

                return $result;



        }

        public function refresh_token()
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







    }





?>
