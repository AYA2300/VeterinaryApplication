<?php
namespace App\Services\Applications\Feed;

use App\Models\Feed;

class App_FeedService{

    public function get_feed(Feed $feed)
        {
          $data=[];
          $result=[];
          $status_code = 400;
          $msg = '';
           $data['feed']=$feed;
           $status_code=200;
           $msg=' عرض العلف المطلوب';
          $result = [
              'data' => $data,
              'status_code' => $status_code,
              'msg' => $msg,
          ];

          return $result;
        }


public function get_feeds()
    {
        $data=[];
        $result=[];
        $status_code = 400;
        $msg = '';

           $feeds=Feed::all();
           $msg='عرض جميع الاعلاف';
           $status_code=200;
           $data['feeds']=$feeds;

        $result = [
            'data' => $data,
            'status_code' => $status_code,
            'msg' => $msg,
        ];

        return $result;
    }


}

?>
