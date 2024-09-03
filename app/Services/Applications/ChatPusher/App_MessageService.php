<?php
namespace App\Services\Applications\ChatPusher;

use App\Models\Conversation;
use App\Models\Veterinarian;
use App\Events\SendMessageEvent;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use App\Http\Traits\FileStorageTrait;
use App\Models\Breeder;
use Illuminate\Support\Facades\Storage;
use Throwable;

 class App_MessageService
 {
    use FileStorageTrait;

    public function send_message(array $input_data,$receiver_id)
    {
         $result=[];
         $data=[];
         $status_code=400;
         $msg='';


        try{
            DB::beginTransaction();

            $sender = null;
            $receiver = null;
            $sendType = null;
                 if(Auth::guard('breeder')->check()){
                   $sender=Auth::guard('breeder')->user();
                   $receiver=Veterinarian::Where('id',$receiver_id)->first();
                   $sendType='breeder';

                  }elseif (Auth::guard('veterinarian')->check()) {
                      $sender = Auth::guard('veterinarian')->user();
                      $receiver = Breeder::find($receiver_id);
                      $sendType = 'veterinary';
           }
            // التحقق من وجود المستخدم والمستقبل
                      if (!$sender || !$receiver) {
                        $status_code=400;
                        $msg='Invalid sender or receiver';
                   }
                 $conversation=Conversation::UpdateOrCreate([
                 "{$sendType}_id" => $sender->id,
                  ($sendType=='breeder'?'veterinary_id':'breeder_id') => $receiver->id,
                    ]);
                    if ($input_data['type'] == 'text') {
                      $messageContent = $input_data['message'];
                  } elseif ($input_data['type'] == 'audio') {
                      $path = $input_data['audio']->store('public/audios')??null;
                      $messageContent = Storage::url($path);
                  } elseif ($input_data['type'] == 'image') {
                      $messageContent =$this->storeFile($input_data['image'],'chats')??null;

                  }

                    $message=$sender->messages()->create([
                     'conversation_id' =>$conversation->id,
                     'type' => $input_data['type'],
                     'message'=>$messageContent
                    ]);


                    $conversation_id=$conversation->id;
                    \broadcast(new SendMessageEvent($message, $conversation_id))->toOthers();
                    DB::commit();
                    $data['message']=$message;
                    $status_code=200;
                    $msg='تم ارسال رسالتك بنجاح';

          }
           catch(Throwable $th){
              DB::rollBack();
              Log::debug($th);
              $msg = 'error ' . $th->getMessage();
              $status_code = 500;
             $data = $th;

        }
        $result = [
            'data' =>[ $data,
            'sender_id'=>$sender->id,
            'sender_name'=>$sender->name,
             'receiver_id'=>$receiver->id,
             'receiver_name'=>$receiver->name],

            'status_code' => $status_code,
            'msg' => $msg,
        ];

        return $result;


    }
    ///------------------------------------------------
    //show messages in conversation
    public function show_messages(Conversation $conversation)
    {

        $result=[];
        $data=[];
        $status_code=400;
        $msg='';

        $user = null;

        if (Auth::guard('breeder')->check()) {
            $user = Auth::guard('breeder')->user();
            $isAuthorized = $conversation->breeder_id === $user->id;
            $receiver_id = $conversation->veterinary_id;
            $receiver_name = $conversation->Veterinarian->name;

        } elseif (Auth::guard('veterinarian')->check()) {
            $user = Auth::guard('veterinarian')->user();
            $isAuthorized = $conversation->veterinary_id === $user->id;
            $receiver_id = $conversation->breeder_id;
            $receiver_name = $conversation->breeder->name;


        } else {
            $status_code=403;
           $msg='Unauthorized';
        }

        // إذا لم يكن المستخدم جزءًا من المحادثة
        if (!$isAuthorized) {
           $status_code=403;
           $msg='Unauthorized';
        }else{
            $messages = $conversation->messages;
        $data['messages']=$messages;
        $status_code=200;
        $msg='عرض الرسائل الخاصة بهذه المحادثة';

        }
        // جلب الرسائل من المحادثة
        ;


        $result = [
            'data' =>[ $data,
            'sender_id'=>$user->id,
            'sender_name'=>$user->name,
             'receiver_id'=>$receiver_id,
             'receiver_name'=>$receiver_name,

            ],

            'status_code' => $status_code,
            'msg' => $msg,
        ];

        return $result;

    }

    public function show_message($id)
    {
        $result = [];
        $data = [];
        $status_code = 400;
        $msg = '';
        $user = null;

        if (Auth::guard('breeder')->check()) {
            $user = Auth::guard('breeder')->user();
            $vet = Veterinarian::find($id);

            // البحث عن المحادثة بين المربي والطبيب البيطري
            $conversation = Conversation::where('breeder_id', $user->id)
                                        ->where('veterinary_id', $vet->id)
                                        ->first();
            if (!$conversation) {
                $status_code = 404;
                $msg = 'Conversation not found';
                return response()->json(['status_code' => $status_code, 'msg' => $msg], $status_code);
            }

            $isAuthorized = $conversation->breeder_id === $user->id;
            $receiver_id = $conversation->veterinary_id;
            $receiver_name = $vet->name;

        } elseif (Auth::guard('veterinarian')->check()) {
            $user = Auth::guard('veterinarian')->user();
            $breeder = Breeder::find($id);

            // البحث عن المحادثة بين الطبيب البيطري والمربي
            $conversation = Conversation::where('veterinary_id', $user->id)
                                        ->where('breeder_id', $breeder->id)
                                        ->first();
            if (!$conversation) {
                $status_code = 404;
                $msg = 'Conversation not found';
                return response()->json(['status_code' => $status_code, 'msg' => $msg], $status_code);
            }

            $isAuthorized = $conversation->veterinary_id === $user->id;
            $receiver_id = $conversation->breeder_id;
            $receiver_name = $breeder->name;
        } else {
            $status_code = 403;
            $msg = 'Unauthorized';
            return response()->json(['status_code' => $status_code, 'msg' => $msg], $status_code);
        }

        // إذا لم يكن المستخدم جزءًا من المحادثة
        if (!$isAuthorized) {
            $status_code = 403;
            $msg = 'Unauthorized';
        } else {
            // جلب الرسائل من المحادثة
            $messages = $conversation->messages()->with('sender')->get();
            $data['messages'] = $messages;
            $status_code = 200;
            $msg = 'عرض الرسائل الخاصة بهذه المحادثة';
        }

        $result = [
            'data' => [
                $data,
                'sender_id' => $user->id,
                'sender_name' => $user->name,
                'receiver_id' => $receiver_id,
                'receiver_name' => $receiver_name,
            ],
            'status_code' => $status_code,
            'msg' => $msg,
        ];

        return $result;
    }






 }

?>
