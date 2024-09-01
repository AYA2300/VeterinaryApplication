<?php


namespace App\Services\Dashboard\Feed;

use App\Http\Traits\FileStorageTrait;
use App\Models\Feed;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Throwable;

class  Dash_FeedService{
    use FileStorageTrait;

    public function add_feed(array $inputdata){
        $data = [];
        $status_code = 400;
        $msg = '';
        $result = [];

       try{
            DB::beginTransaction();
           $image=isset($inputdata['image'])?$this->storeFile($inputdata['image'],'Feed'):'null';

            $feeds=Feed::create([
                'name'=>$inputdata['name'],
                'type'=>$inputdata['type'],
                'Detailes'=>$inputdata['Detailes'],
                'Base_price' => $input_data['Base_price']??'null',

                'price'=>$inputdata['price'],
                'image'=>$image

            ]);

        DB::commit();
            $data['Feed']=$feeds;
            $status_code=200;
            $msg='Feedes Added ';

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

    public function update_feed(array $inputdata, Feed $feed){
            {
                $data = [];
                $status_code = 400;
                $msg = '';
                $result = [];

                try {
                    DB::beginTransaction();
                    $newData = [];

                    if (isset($inputdata['name'])) {
                        $newData['name'] = $inputdata['name'];
                    }
                    if (isset($inputdata['type'])) {
                        $newData['type'] = $inputdata['type'];
                    }
                    if (isset($inputdata['Detailes'])) {
                        $newData['Detailes'] = $inputdata['Detailes'];
                    }
                    if (isset($inputdata['Base_price'])) {
                        $newData['Base_price'] = $inputdata['Base_price'];
                    }
                    if (isset($inputdata['price'])) {
                        $newData['price'] = $inputdata['price'];
                    }
                    if (isset($inputdata['image'])) {
                        $newData['image']=$this->storeFile($inputdata['image'],'Feed')??$feed->image;
                    }



                    $feed->update($newData);
                    DB::commit();



                   $data['Feeds']= $feed;
                   $status_code = 200;
                   $msg = 'Record updated successfully';



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

        }


        public function delete_feed(Feed $feed){

            $data=[];
            $result=[];
            $status_code = 400;
            $msg = '';

            $feed->delete();
            $msg='feed deleted ';
            $status_code=200;

            $result = [
                'data' => $data,
                'status_code' => $status_code,
                'msg' => $msg,
            ];

            return $result;






        }

        public function get_feed(Feed $feed)
        {
          $data=[];
          $result=[];
          $status_code = 400;
          $msg = '';
           $data['feed']=$feed;
           $status_code=200;
           $msg='عرض العلف
            المطلوب';

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
           $msg='عرض جميع الادوية';
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


















