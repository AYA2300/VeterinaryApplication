<?php

namespace App\Http\Controllers\Application;

use App\Http\Controllers\Controller;
use App\Http\Resources\Categorie\Categorie_Resource;
use App\Http\Traits\ApiResponseTrait;
use App\Services\Category\Categorey_Services;
use Illuminate\Http\Request;

class App_AnimalCategorieController extends Controller
{
    use ApiResponseTrait;
    public function __construct(protected Categorey_Services $category_service)
    {
    }

        public function get_categories(){
            $result = $this->category_service->get_all_categories();

            // تحضير المخرجات للاستجابة
            $output = [];
            if ($result['status_code'] == 200) {
                $result_data = $result['data'];

                $paginated = $this->paginate($result_data['Animal_Categories']);
                $output['Animal_Categories'] =  Categorie_Resource::collection($paginated['data']);
                $output['meta'] = $paginated['meta'];
            }

            // إعادة الاستجابة مع المخرجات والرسالة وحالة الاستجابة
            return $this->send_response($output, $result['msg'], $result['status_code']);




        }


    }
