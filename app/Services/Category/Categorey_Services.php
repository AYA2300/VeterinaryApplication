<?php
    namespace App\Services\Category;

use App\Models\AnimalCategorie;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Throwable;

    class Categorey_Services{

        public function add_categorey(array $input_data){


            $data = [];
            $status_code = 400;
            $msg = '';
            $result = [];

            try{
                DB::beginTransaction();
                $Category=AnimalCategorie::create([
                 'name' => $input_data['name']]);


             DB::commit();

             $data['Animal_Categorey'] = $Category;

             $status_code = 200;;
             $msg = ' Animal_Categorey Added';
            }
            catch(Throwable $th){
                DB::rollBack();
                Log::debug($th);

                $status_code = 500;
                $data = $th;
                $msg = 'error ' . $th->getMessage();

            }

            $result = [
                'data' => $data,
                'status_code' => $status_code,
                'msg' => $msg,
            ];

            return $result;

            }



        }



?>
