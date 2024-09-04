<?php

namespace App\Http\Controllers\Application\Feed;

use App\Models\Feed;
use App\Models\Itemable;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Traits\ApiResponseTrait;
use App\Http\Resources\Feed\FeedResource;
use App\Services\Applications\Feed\App_FeedService;

class App_FeedController extends Controller
{
    //
    use ApiResponseTrait;

    public function __construct(protected App_FeedService $app_feed_service)

    {


    }

    public function get_feeds()
     {
        $result=$this->app_feed_service->get_feeds();
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
        $result=$this->app_feed_service->get_feed($feed);
        if ($result['status_code'] == 200) {
            $result_data = $result['data'];
            // response data preparation:
            $output['feed'] = new FeedResource($result_data['feed']);

        }
      return $this->send_response($output, $result['msg'], $result['status_code']);

     }

}
