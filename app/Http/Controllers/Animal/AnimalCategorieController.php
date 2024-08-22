<?php

namespace App\Http\Controllers\Animal;

use App\Http\Controllers\Controller;
use App\Http\Requests\Animal\Add_CategoreyRequest;
use App\Http\Requests\Animal\Update_CategoreyRequest;
use App\Http\Resources\Categorie\Categorie_Resource;
use App\Http\Traits\ApiResponseTrait;
use App\Models\AnimalCategorie;
use App\Services\Category\Categorey_Services;
use Illuminate\Http\Request;

class AnimalCategorieController extends Controller


{
    use ApiResponseTrait;
    public function __construct(protected Categorey_Services $category_service)
    {}

    public function add_categorey(Add_CategoreyRequest $request)
    {

        $input_data=$request->validated();


        $result=$this->category_service->add_categorey($input_data);
        $output=[];
        if ($result['status_code'] == 200) {
            $result_data = $result['data'];
            // response data preparation:
            $output['Animal_Categorey']= new Categorie_Resource($result_data['Animal_Categorey']);


    }
    return $this->send_response($output, $result['msg'], $result['status_code']);

    }


    public function update_categorey(Update_CategoreyRequest $request,$id){
        $input_data=$request->validated();


        $result=$this->category_service->update_categorey($input_data ,$id);
        $output=[];
        if ($result['status_code'] == 200) {
            $result_data = $result['data'];
            // response data preparation:
            $output['Animal_Categorey']= new Categorie_Resource($result_data['Animal_Categorey']);


    }
    return $this->send_response($output, $result['msg'], $result['status_code']);










    }

    public function get_categories(){
        $result = $this->category_service->get_all_categories();

        // تحضير المخرجات للاستجابة
        $output = [];
        if ($result['status_code'] == 200) {
            $output['Animal_Categories'] = $result['data']['Animal_Categories'];
        }

        // إعادة الاستجابة مع المخرجات والرسالة وحالة الاستجابة
        return $this->send_response($output, $result['msg'], $result['status_code']);




    }

    public function delete_categories(AnimalCategorie $id){
        $result = $this->category_service->delete_categories($id);

        return $this->send_response('Done', $result['msg'], $result['status_code']);




    }

}
