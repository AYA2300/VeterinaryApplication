<?php
    namespace App\Services\Dashboard\Diseases;

use App\Http\Traits\FileStorageTrait;
use App\Models\Diseases;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Throwable;

    class Dash_DiseasesServices{
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
                    'image'=>$this->storeFile($inputdata['image'],'Diseases')

                ]);

                if (isset($inputdata['medicines']) && is_array($inputdata['medicines'])) {
                    $disease->medicines()->attach($inputdata['medicines']);
                }

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


    public function update_Diseas(array $inputdata, $id)
{
    $data = [];
    $status_code = 400;
    $msg = '';
    $result = [];

    try {
        DB::beginTransaction();
        $newData = [];

        // التحقق من وجود الحقول في البيانات المدخلة
        if (isset($inputdata['name'])) {
            $newData['name'] = $inputdata['name'];
        }
        if (isset($inputdata['treatment'])) {
            $newData['treatment'] = $inputdata['treatment'];
        }
        if (isset($inputdata['causes'])) {
            $newData['causes'] = $inputdata['causes'];
        }
        if (isset($inputdata['symptoms'])) {
            $newData['symptoms'] = $inputdata['symptoms'];
        }
        if (isset($inputdata['image'])) {
            $newData['image'] = $this->storeFile($inputdata['image'], 'Diseases');
        }


        $disease = Diseases::find($id);
        if ($disease) {
            $disease->update($newData);
            $data= $newData;
            $status_code = 200;
            $msg = 'Record updated successfully';
        } else {
            $msg = 'Disease not found';
            $status_code = 404;
        }

        DB::commit();
    } catch (Throwable $th) {
        DB::rollBack();
        Log::error($th);
        $status_code = 500;
        $msg = 'Error: ' . $th->getMessage();
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

        $Diseases=Diseases::all();
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








