<?php
    namespace App\Services\Category;

use App\Http\Resources\Categorie\Categorie_Resource;
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

            public function update_categorey(array $input_data, $id)
            {
                $data = [];
                $status_code = 400;
                $msg = '';
                $result = [];

                try {
                    DB::beginTransaction();

                    // العثور على الفئة باستخدام المعرف
                    $Category = AnimalCategorie::find($id);
                   if($Category){
                    if(isset($input_data['name'])){
                        $Category->name=$input_data['name']??$Category->name;
                        }
                        $Category->save();

                           DB::commit();
                           $data['Animal_Categorey'] = $Category;
                           $status_code = 200;
                           $msg = 'Animal_Categorey Updated';
                   }

                                      else {
                        // إذا لم يتم العثور على الفئة
                        $status_code = 404;
                        $msg = 'Animal_Categorey not found';
                    }
                } catch (Throwable $th) {
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

            public function get_all_categories()
    {
        $data = [];
        $status_code = 400;
        $msg = '';

        try {
            DB::beginTransaction();

            // استرجاع جميع الفئات
            $categories = AnimalCategorie::all();

            DB::commit();

            $data['Animal_Categories'] =$categories;
            $status_code = 200;
            $msg = 'Animal Categories Retrieved Successfully';

        } catch (Throwable $th) {
            DB::rollBack();
            Log::debug($th);

            $status_code = 500;
            $data = $th;
            $msg = 'Error: ' . $th->getMessage();
        }

        return [
            'data' => $data,
            'status_code' => $status_code,
            'msg' => $msg,
        ];
    }

    public function delete_categories($id){

        $id->delete();
        $status_code = 200;
        $msg = 'Animal Categories Deleted Successfully';
        return [

            'status_code' => $status_code,
            'msg' => $msg,
        ];



    }
}























?>
