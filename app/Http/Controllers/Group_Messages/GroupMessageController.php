<?php

namespace App\Http\Controllers\Group_Messages;

use App\Http\Controllers\Controller;
use App\Http\Requests\group\MesaageRequest;
use App\Http\Traits\ApiResponseTrait;
use App\Services\Applications\ChatPusher\App_GroupService;
use Illuminate\Http\Request;

class GroupMessageController extends Controller
{
    use ApiResponseTrait;

    public function __construct(protected App_GroupService $group_message_service){

    }



    public function send_message(MesaageRequest $request,$community_id)
    {
          $input_data=$request->validated();
          $result=$this->group_message_service->sendMessage($input_data,$community_id);
          $output=[];
          if($result['status_code'] == 200){

          $result_data = $result['data'];
          // response data preparation:
         // $output['message'] = new MessageResource ($result_data['message']);

}
          return $this->send_response($result, $result['msg'], $result['status_code']);

                }


    public function show_message($community_id)
                {
                    $result=$this->group_message_service->show_message($community_id);
                    $output=[];
                    if($result['status_code'] == 200){

                   $result_data = $result['data'] ;
                    // response data preparation:
                //   $output['messages'] =  MessageResource::collection ($result_data['messages']);

          }
                    return $this->send_response($result_data, $result['msg'], $result['status_code']);

                }
}
