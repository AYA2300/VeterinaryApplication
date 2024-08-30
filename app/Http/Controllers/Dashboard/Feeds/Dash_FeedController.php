<?php

namespace App\Http\Controllers\Dashboard\Feeds;

use App\Http\Controllers\Controller;
use App\Http\Requests\Feed\Add_FeedRequest;
use App\Http\Requests\Feed\Edit_FeedRequest;
use App\Http\Resources\Feed\FeedResource;
use App\Http\Traits\ApiResponseTrait;
use App\Models\Feed;
use App\Services\Dashboard\Feed\Dash_FeedService;
use Illuminate\Http\Request;

class Dash_FeedController extends Controller
{
    use ApiResponseTrait;
    public function __construct(protected Dash_FeedService $dash_feed_service)

    {


    }

    public function get_feeds()
     {
        $result=$this->dash_feed_service->get_feeds();
        if ($result['status_code'] == 200) {
            $result_data = $result['data'];
            // response data preparation:
            $paginated = $this->paginate($result_data['feeds']);
            $output['feeds'] = FeedResource::collection($paginated['data']);
            $output['meta'] = $paginated['meta'];

        }
      return $this->send_response($output, $result['msg'], $result['status_code']);

     }


     public function get_feed(Feed $feed){
        $result=$this->dash_feed_service->get_feed($feed);
        if ($result['status_code'] == 200) {
            $result_data = $result['data'];
            // response data preparation:
            $output['feed'] = new FeedResource($result_data['feed']);

        }
      return $this->send_response($output, $result['msg'], $result['status_code']);

     }



     public function add_feed(Add_FeedRequest $request)
    {
        $input_data=$request->validated();
        $result=$this->dash_feed_service->add_feed($input_data);
        $output=[];
        if($result['status_code']==200)
        {
                $result_data = $result['data'];
                // response data preparation:
                $output['feed'] = new FeedResource($result_data['Feed']);

   }
   return $this->send_response($output, $result['msg'], $result['status_code']);

}


public function update_feed(Edit_FeedRequest $request,Feed $feed)
  {
    $inputdata=$request->validated();
     $result=$this->dash_feed_service->update_feed($inputdata,$feed);
     $output=[];
     if($result['status_code']==200)
     {
             $result_data = $result['data'];
             // response data preparation:
             $output['feed'] = new FeedResource($result_data['Feeds']);

}
return $this->send_response($output, $result['msg'], $result['status_code']);

  }
  //delete

   public function delete_feed(Feed $feed)
   {
    $result=$this->dash_feed_service->delete_feed($feed);

 $output=[];
     if($result['status_code']==200)
     {
             $result_data = $result['data'];

}
return $this->send_response($output, $result['msg'], $result['status_code']);

   }
}










