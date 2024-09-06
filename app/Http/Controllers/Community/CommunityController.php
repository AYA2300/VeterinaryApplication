<?php

namespace App\Http\Controllers\Community;

use App\Http\Controllers\Controller;
use App\Http\Requests\Community\Add_Community;
use App\Http\Resources\Community\CommunityResource;
use App\Http\Traits\ApiResponseTrait;
use App\Services\Dashboard\Community\CommunityServices;
use Illuminate\Http\Request;

class CommunityController extends Controller
{
    use ApiResponseTrait;
    //

    public function __construct(protected CommunityServices $community_Service)
    {

    }

    public function add_community(Add_Community $request){

        $input_data=$request->validated();
        $result=$this->community_Service->createCommunity($input_data);
        $output=[];
        if ($result['status_code'] == 200) {
            $result_data = $result['data'];
            // response data preparation:

          $output['community']= new CommunityResource($result_data['community']);


    }

   return $this->send_response($result, $result['msg'], $result['status_code']);

    }
}
