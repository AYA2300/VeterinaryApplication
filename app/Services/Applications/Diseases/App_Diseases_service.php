<?php
    namespace App\Services\Applications\Diseases;

use App\Http\Traits\FileStorageTrait;
use App\Models\Diseases;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Throwable;

    class App_Diseases_service{
        use FileStorageTrait;


        public function add_Diseas(array $inputdata){
            $data = [];
            $status_code = 400;
            $msg = '';
            $result = [];

            try{
                DB::beginTransaction();
                $disease=Diseases::create([
                    'name'=>$inputdata['name'],
                    'treatment'=>$inputdata['treatment'],
                    'causes'=>$inputdata['causes'],
                    'symptoms'=>$inputdata['symptoms'],
                    'prevention_methods'=>$inputdata['prevention_methods'],

                    'image'=>$this->storeFile($inputdata['image'],'Diseases')

                ]);
                DB::commit();
                $data['Diseases']=$disease;
                $status_code=200;
                $msg='Diseases Added ';
            }catch(Throwable $th){
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

        public function update_Diseas(array $inputdata,$id){

            $data = [];
                $status_code = 400;
                $msg = '';
                $result = [];

                try{
                    DB::beginTransaction();
                    $Disease=Diseases::find($id);
                    if($Disease){
                        if(isset($inputdata['name'])){
                            $Disease->name=$input_data['name']??$Disease->name;

                        }
                        if(isset($inputdata['treatment'])){
                            $Disease->treatment=$input_data['treatment']??$Disease->treatment;

                        }
                        if(isset($inputdata['causes'])){
                            $Disease->causes=$input_data['causes']??$Disease->causes;

                        }
                        if(isset($inputdata['symptoms'])){
                            $Disease->symptoms=$input_data['symptoms']??$Disease->symptoms;

                        }
                        if(isset($inputdata['image'])){
                            $Disease->image=$this->storeFile($inputdata['image'],'Diseases');

                        }
                        if (isset($inputdata['prevention_methods'])) {
                            $newData['prevention_methods'] = $this->storeFile($inputdata['prevention_methods'], 'Diseases');
                        }

                    }


        }catch(Throwable $th){
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

    public function get_Diseases(){
        $data = [];
        $status_code = 400;
        $msg = '';
        $result = [];

        $Diseases = Diseases::with('medicines')->get();

        $data['Diseases']=$Diseases;
        $msg='Get all Diseases';
        $status_code=200;
        $result = [
            'data' => $data,
            'status_code' => $status_code,
            'msg' => $msg,
        ];

        return $result;

       }

       public function get_Disease(Diseases $disease){

        $data=[];
        $result=[];
        $status_code = 400;
        $msg = '';

        $data['Diseases']=$disease;
        $msg='Get  disease';
        $status_code=200;
       $result = [
           'data' => $data,
           'status_code' => $status_code,
           'msg' => $msg,
       ];

       return $result;



    }

    public function delete_Disease($disease){

        $disease->delete();
        $status_code = 200;
        $msg = 'Animal Diseases Deleted Successfully';
        return [

            'status_code' => $status_code,
            'msg' => $msg,
        ];



    }









}
?>
