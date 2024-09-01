<?php
namespace App\Http\Controllers\Application\Mesaages;
use App\Models\Conversation;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Http\Traits\ApiResponseTrait;
use App\Http\Resources\Breeder\Auth_BreederResource;
use App\Http\Resources\MessagesChat\MessageResource;
use App\Http\Requests\MessagesChat\App_CreateMesaageRequest;
use App\Services\Applications\ChatPusher\App_MessageService;

class App_MessageController extends Controller
{
    //
    use ApiResponseTrait;

    public function __construct(protected App_MessageService $app_message_service){

    }



    //send Message btewen breeder and doctor

    public function send_message(App_CreateMesaageRequest $request,$receiver_id)
    {
          $input_data=$request->validated();
          $result=$this->app_message_service->send_message($input_data,$receiver_id);
          $output=[];
          if($result['status_code'] == 200){

          $result_data = $result['data'];
          // response data preparation:
         // $output['message'] = new MessageResource ($result_data['message']);

}
          return $this->send_response($result_data, $result['msg'], $result['status_code']);

                }

                public function show_messages(Conversation $conversation)
                {
                    $result=$this->app_message_service->show_messages($conversation);
                    $output=[];
                    if($result['status_code'] == 200){

                    $result_data = $result['data'];
                    // response data preparation:
                  //  $output['messages'] =  MessageResource::collection ($result_data['messages'],$result_data['sender_id']);

          }
                    return $this->send_response($result_data, $result['msg'], $result['status_code']);

                }
}
